<?php

namespace App\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest {

    public function authorize()
    {
        return true; // Cho phép mọi request
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:App\Models\Role,name,' . $this->route('role'),
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '',
            'description.required' => ''
        ];
    }
}