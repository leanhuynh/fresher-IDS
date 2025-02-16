<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi request
    }

    public function rules()
    {
        return [
            'role_id' => 'nullable|integer|exists:roles,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_name' => [
                'required', 'string', 'max:50',
                'regex:/^[a-zA-Z0-9._]+$/',
                'unique:App\Models\User,user_name,' . $this->route('user')
            ],
            'email' => 'required|email|unique:App\Models\User,email,' . $this->route('user'),
            'address' => 'nullable|string|max:255',
            'password' => [
                'nullable', 'string', 'min:8', 'max:16', 'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/'
            ],
            'avatar' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'role_id.exists' => __('validation.role_id.exists'),

            'first_name.required' => __('validation.first_name.required'),
            'first_name.max' => __('validation.first_name.max'),

            'last_name.required' => __('validation.last_name.required'),
            'last_name.max' => __('validation.last_name.max'),

            'user_name.required' => __('validation.user_name.required'),
            'user_name.regex' => __('validation.user_name.regex'),
            'user_name.unique' => __('validation.user_name.unique'),
            'user_name.max' => __('validation.user_name.max'),

            'email.required' => __('validation.email.required'),
            'email.unique' => __('validation.email.unique'),
            'email.email' => __('validation.email.email'),

            'password.nullable' => __('validation.password.nullable'),
            'password.min' => __('validation.password.min'),
            'password.max' => __('validation.password.max'),
            'password.confirmed' => __('validation.password.confirmed'),
            'password.regex' => __('validation.password.regex'),

            'avatar.image' => __('validation.avatar.image'),
            'avatar.max' => __('validation.avatar.max'),
        ];
    }
}
