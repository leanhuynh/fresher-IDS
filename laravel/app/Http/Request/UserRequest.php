<?php

namespace App\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {

    public function authorize()
    {
        return true; // Cho phép mọi request
    }

    public function rules()
    {
        return [
            'role_id' => 'nullable|integer',
            'first_name' => 'required|string|max:255,',
            'last_name' => 'required|string|max:255,',
            'user_name' => 'required|string|max:255|unique:App\Models\User,user_name,' . $this->route('user'),
            'email' => 'required|email|unique:App\Models\User,email,' . $this->route('user'),
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|max:16|confirmed',
            'avatar' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'avatar.image' => __('validation.avatar.image'),
            'avatar.max' => __('validation.avatar.max'),
            'name.required' => __('validation.name.required'),
            'name.unique' => __('validation.name.unique'),
            'name.max' => __('validation.name.max'),
            'email.required' => __('validation.email.required'),
            'email.unique' => __('validation.email.unique'),
            'email.email' => __('validation.email.email'),
            'password.nullable' => __('validation.password.nullable'),
            'password.min' => __('validation.password.min'),
            'password.confirmed' => __('validation.password.confirmed'),
        ];
    }
}