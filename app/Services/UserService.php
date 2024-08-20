<?php 

// 8. Concrete Service
namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data)
    {
        // Additional business logic here
        return $this->userRepository->create($data);
    }

    public function updateUser($id, array $data)
    {
        // Additional business logic here
        return $this->userRepository->update($id, $data);
    }
}