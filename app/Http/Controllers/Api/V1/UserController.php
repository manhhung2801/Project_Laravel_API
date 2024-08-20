<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;


class UserController extends Controller
{
    protected $apiResponse;

    protected $userRepository;

    public function __construct(
        ApiResponse $apiResponse,
        UserRepository $userRepository
    )
    {
        $this->apiResponse = $apiResponse;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        // $data = $this->repository->all();
        // return $this->successResponse($data);
    }

    public function show($id)
    {
        // $data = $this->repository->find($id);
        // return $this->successResponse($data);
    }

    public function store(StoreUserRequest  $request) {
        $validatedData = $request->validated();
            
        $user = $this->userRepository->create($validatedData);

        return $this->apiResponse->successResponse(new UserResource($user), 'User created successfully.', 201);      
    }
    public function update(UpdateUserRequest $request, $id) {
        $validatedData = $request->validated();
        $user = $this->userRepository->update($id, $validatedData);
        return $this->successResponse(new UserResource($user), 'User updated successfully');
    }

    public function destroy($id)
    {
        // $this->repository->delete($id);
        // return $this->successResponse(null, 'Resource deleted successfully');
    }
}
