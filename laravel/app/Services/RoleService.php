<?php

namespace App\Services;
use App\Repositories\RoleRepository;

class RoleService {

    protected RoleRepository $_roleRepository;

    public function __construct(RoleRepository $roleRepository) {
        $this->_roleRepository = $roleRepository;
    }

    public function getAll() {
        return $this->_roleRepository->getAll();
    }

    public function createRole(array $data) {
        return $this->_roleRepository->createRole($data);
    }

    public function findRoleById($id) {
        return $this->_roleRepository->findRoleById($id);
    }

    public function updateRole(array $data, $id) {
        return $this->_roleRepository->updateRole($data, $id);
    }

    public function searchRoles(array $filters) {
        return $this->_roleRepository->searchRoles($filters);
    }

    public function deleteRole($role_id, $auth_id) {
        return $this->_roleRepository->deleteRole($role_id, $auth_id);
    }
}