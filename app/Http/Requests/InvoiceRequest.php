<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'weight'     => 'required|numeric',
            'patient_id' => 'required|exists:patients,id',
            'symptoms'   => 'required|string',
            'diagnosis'  => 'required|string',
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
            'weight.required' => 'Vui lòng nhập cân nặng.',
            'weight.numeric'  => 'Cân nặng của bệnh nhân là số.',

            'patient_id.required' => 'Vui lòng cung cấp ID của bệnh nhân.',
            'patient_id.exists'   => 'ID của bệnh nhân không tồn tại trong hệ thống.',

            'symptoms.required' => 'Triệu chứng là bắt buộc.',
            'symptoms.string'   => 'Triệu chứng phải là một chuỗi.',

            'diagnosis.required' => 'Chẩn đoán là bắt buộc.',
            'diagnosis.string'   => 'Chẩn đoán phải là một chuỗi.',
        ];
    }
}
