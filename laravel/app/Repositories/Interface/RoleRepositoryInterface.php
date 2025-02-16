<?php

namespace App\Repositories\Interface;

interface RoleRepositoryInterface 
{
    public function getAll();

    public function findRoleById($id);

    public function createRole(array $data);

    public function updateRole(array $data, $id);

    public function searchRoles(array $filters);

    public function deleteRole($role_id, $auth_id);
}