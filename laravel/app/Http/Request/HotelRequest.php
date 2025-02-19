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
            'email' => 'required|email|max:255',
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
                'min:10',        // Đảm bảo mã số thuế có ít nhất 10 ký tự
                'max:13',        // Đảm bảo mã số thuế có không quá 13 ký tự
                'regex:/^\d+$/', // Đảm bảo chỉ chứa các chữ số
            ],
            'address_1' => 'required|string|min:5|max:255',
            'address_2' => 'nullable|string|min:5|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name_en.required' => __('validation.required'),
            'name_en.string' => __('validation.string'),
            'name_en.max' => __('validation.max'),
            'name_en.unique' => __('validation.unique'),
            'name_en.regex' => __('validation.regex'),

            'name_jp.required' => __('validation.required'),
            'name_jp.string' => __('validation.string'),
            'name_jp.max' => __('validation.max'),
            'name_jp.unique' => __('validation.unique'),
            'name_jp.regex' => __('validation.regex'),

            'city_id.required' => __('validation.required'),
            'city_id.integer' => __('validation.integer'),
            'city_id.exists' => __('validation.database.exists', ['database' => 'City']),

            'owner_id.required' => __('validation.required'),
            'owner_id.integer' => __('validation.integer'),
            'owner_id.exists' => __('validation.exists'),

            'hotel_code.required' => __('validation.required'),
            'hotel_code.string' => __('validation.string'),
            'hotel_code.size' => __('validation.size'),
            'hotel_code.alpha_num' => __('validation.alpha_num'),
            'hotel_code.unique' => __('validation.unique'),

            'company_name.required' => __('validation.required'),
            'company_name.string' => __('validation.string'),
            'company_name.max' => __('validation.max'),

            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),

            'telephone.required' => __('validation.required'),
            'telephone.string' => __('validation.string'),
            'telephone.regex' => __('validation.regex'),

            'fax.nullable' => __('validation.nullable'),
            'fax.string' => __('validation.string'),
            'fax.max' => __('validation.max'),
            'fax.regex' => __('validation.regex'),

            // 'tax_code.nullable' => __('validation.nullable'),
            'tax_code.string' => __('validation.string'),
            'tax_code.regex' => __('validation.regex'),

            'address_1.required' => __('validation.required'),
            'address_1.string' => __('validation.string'),
            'address_1.min' => __('validation.min'),
            'address_1.max' => __('validation.max'),

            'address_2.nullable' => __('validation.nullable'),
            'address_2.string' => __('validation.string'),
            'address_2.min' => __('validation.min'),
            'address_2.max' => __('validation.max'),
        ];
    }
}
