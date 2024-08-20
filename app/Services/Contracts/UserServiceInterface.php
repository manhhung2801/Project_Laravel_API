<?php 

// 7. Service Contract
namespace App\Services\Contracts;

interface UserServiceInterface
{
    public function createUser(array $data);
    public function updateUser($id, array $data);
}
