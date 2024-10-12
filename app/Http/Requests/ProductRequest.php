<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'selling_price' => 'numeric|required',
            'purchase_price' => 'numeric|required',
            'stock_quantity' => 'numeric|required',
            'image' => 'required|string', // Fixed image validation rules
            'category_id' => 'numeric|required',
            'expiration_date' => 'nullable|date'
        ];
    }

    /**
     * Get custom messages for validator errors in Arabic.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'selling_price.required' => 'سعر البيع مطلوب',
            'purchase_price.required' => 'سعر الشراء مطلوب',
            'stock_quantity.required' => 'كمية المخزون مطلوبة',
            'image.required' => 'الصورة مطلوبة',
 'name.unique' => 'الاسم موجود بالفعل في قاعدة البيانات.',
            'category_id.required' => ' الفئة مطلوبة',
        ];
    }
}
