<?php

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép mọi request
    }

    public function rules()
    {
        return [
            'name_en' => [
                'required', 'string', 'max:255',
                'unique:\App\Models\Hotel,name_en,' . $this->route('hotel'),
                'regex:/^[\pL0-9\s]+$/u'  // Cho phép chữ cái, số và khoảng trắng
            ],
            'name_jp' => [
                'required', 'string', 'max:255',
                'unique:\App\Models\Hotel,name_jp,' . $this->route('hotel'),
                'regex:/^[\pL0-9\s]+$/u'  // Cho phép chữ cái, số và khoảng trắng
            ],
            'city_id' => 'required|integer|exists:\App\Models\City,id',
            'owner_id' => 'required|integer|exists:\App\Models\User,id',
            'hotel_code' => [
                'required', 'string', 'size:6', 'alpha_num',
                'unique:\App\Models\Hotel,hotel_code,' . $this->route('hotel')
            ],
            'company_name' => 'required|string|max:255',
            'email' => 'required|email',
            'telephone' => [
                'required', 'string',
                'regex:/^\+?[0-9\-\s]+$/' // Chỉ chấp nhận số, dấu "-" và khoảng trắng
            ],
            'fax' => [
                'nullable', 'string', 'max:255',
                'regex:/^\+?[0-9\-\s]+$/' // Chỉ chấp nhận số, dấu "-" và khoảng trắng
            ],
            'tax_code' => [
                'nullable', 'string',
                'regex:/^\d{10,13}$/' // Mã số thuế chỉ chứa số và có độ dài từ 10 đến 13 ký tự
            ],
            'address_1' => 'required|string|min:5|max:255',
            'address_2' => 'nullable|string|min:5|max:255',
        ];
    }

    public function messages()
    {
        return [
            'role_id.exists' => __('validation.exists'),

            'first_name.required' => __('validation.required'),
            'first_name.max' => __('validation.max'),

            'last_name.required' => __('validation.required'),
            'last_name.max' => __('validation.max'),

            'user_name.required' => __('validation.required'),
            'user_name.regex' => __('validation.regex'),
            'user_name.unique' => __('validation.unique'),
            'user_name.max' => __('validation.max'),

            'email.required' => __('validation.required'),
            'email.unique' => __('validation.unique'),
            'email.email' => __('validation.email'),

            'password.nullable' => __('validation.nullable'),
            'password.min' => __('validation.min'),
            'password.max' => __('validation.max'),
            'password.confirmed' => __('validation.confirmed'),
            'password.regex' => __('validation.regex'),

            'avatar.image' => __('validation.image'),
            'avatar.max' => __('validation.max'),
        ];
    }
}
