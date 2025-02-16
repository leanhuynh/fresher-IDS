<?php

namespace App\Common;

class Constant {
    const PAGINATE_DEFAULT = 2;
    const DEFAULT_USER_ROLE = 1;
    const ADMIN_ROLE_ID = 1;
    const ADMIN_ROLE_NAME = 'Admin';
    public static function getRoles() {
        $roles = collect([
            ['name' => Constant::ADMIN_ROLE_NAME],
            ['name' => 'Members']
        ]);
        return $roles;
    }
}