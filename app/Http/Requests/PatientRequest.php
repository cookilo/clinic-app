<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name'          => 'required|string|max:255|regex:/^[\p{L} ]+$/u',
            'parent_name'        => 'nullable|string|max:255|regex:/^[\p{L} ]+$/u',
            'date_of_birth'      => 'required|date|date_format:d-m-Y|before:today',
            'gender'             => 'required|string|in:male,female,other|max:10',
            'phone'              => 'required|string|regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/|min:10|max:20',
            'address'            => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|unique:patients,email',
            'allergies'          => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'medical_history'    => 'nullable|string',
        ];
    }

    /**
     * Get the message validate.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Tên bênh nhân là bắt buộc.',
            'full_name.string'   => 'Tên bênh nhân phải là một chuỗi.',
            'full_name.max'      => 'Tên bênh nhân không được vượt quá 255 ký tự.',
            'full_name.regex'    => 'Tên bênh nhân chỉ được phép chứa chữ và khoảng trắng.',

            'parent_name.string' => 'Tên bố mẹ phải là một chuỗi.',
            'parent_name.max'    => 'Tên bố mẹ không được vượt quá 255 ký tự.',
            'parent_name.regex'  => 'Tên bố mẹ chỉ được phép chứa chữ và khoảng trắng.',

            'date_of_birth.required'    => 'Ngày tháng năm sinh là bắt buộc.',
            'date_of_birth.date'        => 'Ngày tháng năm sinh phải là một ngày hợp lệ.',
            'date_of_birth.date_format' => 'Ngày tháng năm sinh phải có định dạng DD-MM-YYYY.',
            'date_of_birth.before'      => 'Ngày tháng năm sinh phải trước ngày hôm nay.',

            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.string'   => 'Giới tính phải là một chuỗi.',
            'gender.in'       => 'Giới tính phải là male, female, hoặc other.',
            'gender.max'      => 'Giới tính không được vượt quá 10 ký tự.',

            'phone.required' => 'Số điện thoại là bắt buộc.',
            'phone.string'   => 'Số điện thoại phải là một chuỗi.',
            'phone.regex'    => 'Số điện thoại không hợp lệ. Vui lòng sử dụng định dạng đúng.',
            'phone.min'      => 'Số điện thoại phải có ít nhất 10 chữ số.',
            'phone.max'      => 'Số điện thoại không được vượt quá 20 ký tự.',

            'address.required' => 'Địa chỉ là bắt buộc.',
            'address.string'   => 'Địa chỉ phải là một chuỗi.',
            'address.max'      => 'Địa chỉ không được vượt quá 255 ký tự.',

            'allergies.string'          => 'Thông tin về dị ứng phải là một chuỗi.',
            'chronic_conditions.string' => 'Thông tin về bệnh mãn tính phải là một chuỗi.',
            'medical_history.string'    => 'Thông tin về tiền sử bệnh phải là một chuỗi.',
        ];
    }
}
