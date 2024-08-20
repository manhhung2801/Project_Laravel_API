<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function getPosts(Request $request) {
        $query = $request->get('query');

        $data = DB::table('posts');
        if(!is_null($query)) {
            $posts  = $data->where('title', 'like', '%'.$query.'%');
            return response($posts->paginate(10), 200);
        }

        return response($data->paginate(10), 200);
    }
    public function store(Request $request) {
        $fields = $request->all();

        $errors = Validator::make($fields, [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        
        if($errors->fails()) {
            return response()->json([
                'errors' => $errors->errors()->all()
            ], 422);
        }

        $post = Post::create([
            'title' => $fields['title'],
            'content' => $fields['content']
        ]);

        return response()->json([
            'post' => $post
        ], 201);
    }
    public function update(Request $request, $id) {

        Post::where('id', $id)->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);
        
        return response([
            'message' => 'Post updated!'
        ], 200);
    }
    public function destroy(Request $request, $id) {

        Post::where('id', $id)->delete();
        
        return response([
            'message' => 'Post deleted!'
        ], 200);
    }

    public function addImage(Request $request) {
        $fields = $request->all();
    
        $errors = Validator::make($fields, [
            'postId' => 'required',
            'image' => 'required|image|max:2000'
        ]);
    
        if($errors->fails()) {
            return response()->json([
                'errors' => $errors->errors()->all()
            ], 422);
        }
        
        if($request->hasFile('image')) {
            $image = $request->file('image');
    
            $input['file'] = time().'.'.$image->extension();
    
            Storage::disk('public')
                ->put('images/' . $input['file'], file_get_contents($image));
    
            $imageURL = url('/').'/storage/images/'.$input['file'];
    
            Post::where('id', $request->postId)->update([
                'image' => $imageURL
            ]);

            return response()->json([
                'message' => 'Post image uploaded',
                'imageURL' => $imageURL
            ], 200);
        }
    
        return response()->json([
            'message' => 'Image not uploaded'
        ], 500);
    }
    
}
