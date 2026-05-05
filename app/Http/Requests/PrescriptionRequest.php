<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
            'title'  => 'required|string',
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
            'title.required' => 'Tên đơn thuốc là bắt buộc.',
            'title.string' => 'Tên đơn thuốc phải là một chuỗi.',
        ];
    }
}
