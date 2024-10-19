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
            'selling_price' => 'numeric|required|min:0',
            'purchase_price' => 'numeric|required|min:0',
            'stock_quantity' => 'numeric|required|min:0',
            'image' => 'required|string', // Fixed image validation rules
'category_id' => 'numeric|required|exists:categories,id',
            'expiration_date' => 'nullable|date'
        ];
    }

    /**
     * Get custom messages for validator errors in Arabic.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنتج مطلوب.',
            'selling_price.required' => 'سعر البيع مطلوب.',
            'selling_price.numeric' => 'يجب أن يكون سعر البيع رقماً صالحاً.',
            'selling_price.min' => 'يجب أن يكون سعر البيع رقماً إيجابياً.',
            'purchase_price.required' => 'سعر الشراء مطلوب.',
            'purchase_price.numeric' => 'يجب أن يكون سعر الشراء رقماً صالحاً.',
            'purchase_price.min' => 'يجب أن يكون سعر الشراء رقماً إيجابياً.',
            'stock_quantity.required' => 'كمية المخزون مطلوبة.',
            'stock_quantity.numeric' => 'يجب أن تكون كمية المخزون رقماً صالحاً.',
            'stock_quantity.min' => 'يجب أن تكون كمية المخزون رقماً إيجابياً.',
            'image.required' => 'الصورة مطلوبة.',
            'image.string' => 'يجب أن تكون الصورة في شكل نصي صحيح.',
            'category_id.required' => 'الفئة مطلوبة.',
            'category_id.numeric' => 'يجب أن تكون الفئة رقماً صالحاً.',
            'category_id.exists' => 'الفئة المحددة غير موجودة.',
            'expiration_date.date' => 'يجب أن يكون تاريخ انتهاء الصلاحية في شكل تاريخ صالح.'
        ];
    }
}
