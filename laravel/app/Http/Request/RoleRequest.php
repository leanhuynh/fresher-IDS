<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                'unique:\App\Models\Role,name,' . $this->route('role')
            ],
            'description' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'name.string' => __('validation.string'),
            'name.max' => __('validation.max'),
            'name.unique' => __('validation.unique'),

            'description.string' => __('validation.string'),
            'description.max' => __('validation.max')
        ];
    }
}