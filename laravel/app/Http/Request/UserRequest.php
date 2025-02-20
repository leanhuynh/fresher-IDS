<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'auth_id' => 'nullable|integer|exists:\App\Models\User,id',
            'role_id' => 'nullable|integer|exists:\App\Models\Role,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => [
                'required', 'string', 'max:50',
                'regex:/^[a-zA-Z0-9._]+$/',
                'unique:\App\Models\User,user_name,' . $this->route('user')
            ],
            'email' => 'required|email|max:255|unique:\App\Models\User,email,' . $this->route('user'),
            'address' => 'nullable|string|max:255',
            'password' => [
                'string', 'min:8', 'max:16', 'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/'
            ],
            'avatar' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'role_id.exists' => __('validation.database.exists', ['database' => 'Role']),
            'role_id.integer' => __('validation.integer'),

            'first_name.required' => __('validation.required'),
            'first_name.max' => __('validation.max'),

            'last_name.required' => __('validation.required'),
            'last_name.max' => __('validation.max'),

            'user_name.required' => __('validation.required'),
            'user_name.regex' => __('validation.regex'),
            'user_name.unique' => __('validation.unique'),
            'user_name.max' => __('validation.max'),

            'email.required' => __('validation.required'),
            'email.max' => __('validation.max'),
            'email.unique' => __('validation.unique'),
            'email.email' => __('validation.email'),

            // 'password.nullable' => __('validation.nullable'),
            'password.min' => __('validation.min'),
            'password.max' => __('validation.max'),
            'password.confirmed' => __('validation.confirmed'),
            'password.regex' => __('validation.regex'),

            'avatar.image' => __('validation.image'),
            'avatar.max' => __('validation.max'),
        ];
    }
}
