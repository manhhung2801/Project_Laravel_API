<?php

namespace App\Http\Controllers\Api\V1;

use App\Mail\RegisterMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Mail\ForgotPasswordMail;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\RegisterUserRequest;


class AuthController extends Controller
{
    private $secretKey = "YfML/01OB1TcDXuSInmROcURP/EUc41c0ZIshnRiUQ0=YfML/01OB1TcDXuSInmROcURP/EUc41c0ZIshnRiUQ0=";
    protected $apiResponse;
    protected $authService;
    protected $userRepository;

    public function __construct(
        ApiResponse $apiResponse,
        AuthService $authService,
        UserRepository $userRepository
    ) {
        $this->apiResponse = $apiResponse;
        $this->authService = $authService;
        $this->userRepository = $userRepository;
        
    }

    // register account and send token to verify email
    public function register(RegisterUserRequest $request)
    {
        $request->validated();
        $user = $this->authService->register($request);
        Mail::to($user->email)->send(new RegisterMail($user));

        return $this->apiResponse->successResponse(new UserResource($user), "You have successfully registered an account, please check your email to verify your email.", 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return  $this->apiResponse->errorResponse($validator->errors()->all(), "Please enter email and password.", 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {

            $user = $this->userRepository->findByEmail($request['email']);

            if (!empty($user->email_verified_at)) {

                $token = $user->createToken($this->secretKey)->plainTextToken;

                return response()->json([
                    'status' => "success",
                    'message' => "Logged in successfully.",
                    'data' => new UserResource($user),
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ], 200);
            } else {

                $user->code_verify_email = Str::upper(Str::random(6));
                $user->save();

                Mail::to($user->email)->send(new RegisterMail($user));
            
                return response()->json([
                    'status' => "success",
                    'message' => "Please first you need verify your email address."
                ], 200);
            }
        } else {
            return response()->json([
                'status' => "error",
                'message' => "Please enter correct email and password."
            ], 404);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => "success",
            'message' => "Logged out successfully."
        ], 200);
    }

    public function forgotPassword(Request $request) {

        $user = $this->authService->forgotPassword($request);

        if(!empty($user)) {
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return response()->json([
                'status' => "success",
                'message' => "Please check your email and reset password."
            ], 200);

        }else {
            return response()->json([
                'status' => "error",
                'message' => "Email not found in the system."
            ], 400);
        }
    }

    public function ChangePassword(Request $request) {
        $user = $this->userRepository->where('code_verify_email', $request)->first();

        if(!empty($user)) {
            $data = $user;
            return $this->apiResponse->successResponse($data ,"Information to reset password.", 200);
        }else {
            return response()->json([
                "status" => "error",
                "message"=> "Not found."
            ], 400);
        }
    }

    public function confirmChangePassword(Request $request) {
        

        if($request->password !== $request->password_confirmation){
            return response()->json([
                "status" => "error",
                "message"=> "Password and Confirm Password does not match."
            ], 401);
        }

        $user = $this->authService->confirmChangePassword($request->email, $request->code_verify, $request->password);

        if(!empty($user)) 
        {
            return response()->json([
                "status" => "success",
                "message"=> "Change password successfully."
            ], 201);

        }else {
            return response()->json([
                "status" => "error",
                "message"=> "User information not found to change password."
            ], 400);
        }
    }

    // accept token to verify email 
    public function verifyEmail(Request $request)
    {
        $verify = $this->authService->verifyEmail($request->email,$request->code_verify);

        if($verify) {
            return response()->json([
                "status" => "success",
                "message"=> "Email verified success."
            ], 201);        
        }else {
            return response()->json([
                "status" => "error",
                "message"=> "Email not verified"
            ], 404); 
        }
    }
}




