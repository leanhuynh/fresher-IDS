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
                'unique:App\Models\Hotel,name_en,' . $this->route('hotel'),
                'regex:/^[\pL\s]+$/u' // Chỉ cho phép ký tự chữ và khoảng trắng
            ],
            'name_jp' => [
                'required', 'string', 'max:255',
                'unique:App\Models\Hotel,name_jp,' . $this->route('hotel'),
                'regex:/^[\pL\s]+$/u' // Chỉ cho phép ký tự chữ và khoảng trắng
            ],
            'city_id' => 'required|integer|exists:App\Models\City,id',
            'owner_id' => 'required|integer|exists:App\Models\User,id',
            'hotel_code' => [
                'nullable', 'string', 'size:6', 'alpha_num',
                'unique:App\Models\Hotel,hotel_code,' . $this->route('hotel')
            ],
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:App\Models\Hotel,email,' . $this->route('hotel'),
            'telephone' => [
                'required', 'string', 'unique:App\Models\Hotel,telephone,' . $this->route('hotel'),
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
            'name_en.required' => 'Tên khách sạn (English) là bắt buộc.',
            'name_en.unique' => 'Tên khách sạn (English) đã tồn tại.',
            'name_en.regex' => 'Tên khách sạn (English) chỉ được chứa chữ và khoảng trắng.',

            'name_jp.required' => 'Tên khách sạn (Japanese) là bắt buộc.',
            'name_jp.unique' => 'Tên khách sạn (Japanese) đã tồn tại.',
            'name_jp.regex' => 'Tên khách sạn (Japanese) chỉ được chứa chữ và khoảng trắng.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',

            'telephone.required' => 'Số điện thoại là bắt buộc.',
            'telephone.regex' => 'Số điện thoại không hợp lệ. Chỉ chấp nhận số, dấu "-" hoặc khoảng trắng.',
            'telephone.unique' => 'Số điện thoại này đã được sử dụng.',

            'fax.regex' => 'Fax không hợp lệ. Chỉ chấp nhận số, dấu "-" hoặc khoảng trắng.',

            'tax_code.regex' => 'Mã số thuế phải có từ 10 đến 13 số.',

            'hotel_code.size' => 'Mã khách sạn phải có đúng 6 ký tự.',
            'hotel_code.alpha_num' => 'Mã khách sạn chỉ được chứa chữ cái và số.',
            'hotel_code.unique' => 'Mã khách sạn đã tồn tại.',

            'city_id.required' => 'Thành phố là bắt buộc.',
            'city_id.exists' => 'Thành phố không hợp lệ.',

            'address_1.required' => 'Địa chỉ 1 là bắt buộc.',
            'address_1.min' => 'Địa chỉ 1 phải có ít nhất 5 ký tự.',

            'address_2.min' => 'Địa chỉ 2 phải có ít nhất 5 ký tự.',
        ];
    }
}
