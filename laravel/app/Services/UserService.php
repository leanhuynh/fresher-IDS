<?php
namespace App\Services;
use App\Http\Request\UserRequest;
use App\Repositories\UserRepository;

class UserService {

    protected UserRepository $_userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->_userRepository = $userRepository;
    }

    public function getAll() {
        return $this->_userRepository->getAll();
    }

    public function createUser(array $data, $auth_id) {
        return $this->_userRepository->createUser($data, $auth_id);
    }

    public function findUserById($id) {
        return $this->_userRepository->findUserById($id);
    }

    public function updateUser(array $data, $id, $auth_id) {
        return $this->_userRepository->updateUser($data, $id, $auth_id);
    }

    public function deleteUser($id) {
        return $this->_userRepository->deleteUser($id);
    }

    public function searchUsers($filters) {
        return $this->_userRepository->searchUsers($filters);
    }

    public function getUserProfileById($id) {
        // if id is null or empty
        if (is_null($id) || $id === '')
            throw new Exception(__('exception.search.id'));
        
        return $this->_userRepository->getUserProfileById($id);
    }
}