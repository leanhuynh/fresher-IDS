<?php

namespace App\Exceptions;

use Exception;

class RoleInUseException extends Exception
{
    // Constructor để truyền thông điệp lỗi vào exception
    public function __construct($message = 'There are users with this role, you cannot delete this role!', $code = 400)
    {
        parent::__construct($message, $code); // Truyền thông điệp và mã lỗi vào constructor của Exception
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage() // Sử dụng getMessage() để lấy thông điệp lỗi
        ], $this->getCode()); // Sử dụng getCode() để lấy mã lỗi
    }
}