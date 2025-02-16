<?php

namespace App\Repositories\Interface;

interface UserRepositoryInterface 
{
    public function getAll();

    public function findUserById($id);

    public function createUser(array $data);

    public function updateUser(array $data, $id, $auth_id);

    public function deleteUser($id);

    public function searchUsers(array $filters);

    public function getUserProfileById($id);
}