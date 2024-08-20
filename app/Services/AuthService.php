<?php 

namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService {
    protected $userRepository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }   

    public function register(Request $request) {

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'code_verify_email' => Str::upper(Str::random(6)),
        ];
        return $this->userRepository->create($data);
    }

    public function forgotPassword(Request $request) {

        $user = $this->userRepository->findByEmail($request->email);
        
        if(!empty($user)) {
            $user->code_verify_email = Str::upper(Str::random(6));
            $user->save();

            return $user;
        }
        return null;
    }
    public function confirmChangePassword($email, $code_verify, $newPassword) {

        $user = $this->userRepository->where('email', $email)->where('code_verify_email', $code_verify)->first();
    
        if(!empty($user)) 
        {
                $user->code_verify_email = Str::upper(Str::random(6));
                $user->password = Hash::make($newPassword);
                $user->email_verified_at = Carbon::now();
                $user->save();

                return $user; 
        }
        return null;    
    }

    public function changePassword() {
        
    }
    public function verifyEmail($email, $code_verify) {

        $user = $this->userRepository->where('email', $email)->where('code_verify_email', $code_verify)->first();
        
        if(!empty($user)) {
            $user->email_verified_at = Carbon::now();
            $user->code_verify_email = Str::upper(Str::random(6));
            $user->save();
            
            return true;
        }else {
            return false;
        }
    }
}