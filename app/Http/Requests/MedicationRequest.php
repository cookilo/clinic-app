<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicationRequest extends FormRequest
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
            'name'                => 'required|string|max:255',
            'description'         => 'nullable|string',
            'unit'                => 'required|string|max:50',
            'purchase_price'      => 'required|numeric|min:0|lte:sale_price',
            'sale_price'          => 'required|numeric|min:0',
            'stock'               => 'required|integer|min:0',
            'manufacturer'        => 'nullable|string|max:255',
            'expiry_date'         => 'nullable|date',
            'dosage_instructions' => 'nullable|string',
            'side_effects'        => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên thuốc là bắt buộc.',
            'name.string'   => 'Tên thuốc phải là một chuỗi ký tự.',
            'name.max'      => 'Tên thuốc không được vượt quá 255 ký tự.',

            'unit.required' => 'Đơn vị tính là bắt buộc.',
            'unit.string'   => 'Đơn vị tính phải là một chuỗi ký tự.',
            'unit.max'      => 'Đơn vị tính không được vượt quá 50 ký tự.',

            'purchase_price.required' => 'Giá nhập là bắt buộc.',
            'purchase_price.numeric'  => 'Giá nhập phải là số.',
            'purchase_price.min'      => 'Giá nhập không được âm.',
            'purchase_price.lte'      => 'Giá bán phải lớn hơn hoặc bằng giá nhập.',

            'sale_price.required' => 'Giá bán là bắt buộc.',
            'sale_price.numeric'  => 'Giá bán phải là số.',
            'sale_price.min'      => 'Giá bán không được âm.',

            'stock.required' => 'Số lượng tồn kho là bắt buộc.',
            'stock.integer'  => 'Số lượng tồn kho phải là số nguyên.',
            'stock.min'      => 'Số lượng tồn kho không được âm.',

            'manufacturer.string' => 'Nhà sản xuất phải là một chuỗi ký tự.',
            'manufacturer.max'    => 'Nhà sản xuất không được vượt quá 255 ký tự.',

            'expiry_date.date'  => 'Ngày hết hạn phải là một ngày hợp lệ.',
            'expiry_date.after' => 'Ngày hết hạn phải sau ngày sản xuất.',

            'dosage_instructions.string' => 'Hướng dẫn sử dụng phải là một chuỗi ký tự.',
            'side_effects.string'        => 'Tác dụng phụ phải là một chuỗi ký tự.',
        ];
    }
}
