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
                'unique:\App\Models\Role,name,' . $this->route('role'),
                'regex:/^[a-zA-Z0-9]+$/'
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
            // 'name.regex' => __('validation.regex'),
            'name.regex' => __('validation.name.regex'),

            'description.string' => __('validation.string'),
            'description.max' => __('validation.max')
            
            // 'name.required' => 'The name field is required.',
            // 'name.string' => __('validation.string'),
            // 'name.max' => 'The name must not be greater than :max characters.',
            // 'name.unique' => 'The name has already been taken.',
            // 'name.regex' => 'The name must only contain letters and numbers.',

            // 'description.string' => __('validation.string'),
            // 'description.max' => 'The description must not be greater than 255 characters.'
        ];
    }
}