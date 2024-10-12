<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator)
    {
        $validator->sometimes('updated_product', 'required|numeric|exists:products,id', function ($input) {
            return isset($input->updated_product);
        });
        $validator->sometimes('name', 'required|string', function ($input) {
            return isset($input->name);
        });
        $validator->sometimes('category_id', 'required|numeric|exists:categories,id', function ($input) { // Specify the primary key
            return isset($input->category_id);
        });
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'purchase_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:1',
            'expiration_date' => 'nullable|date|after:today', // Expiration date can be null, but must be a valid future date if provided
            'transporter_id' => 'required|integer|exists:clients,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفًا.',

            'name.unique' => 'الاسم موجود بالفعل في قاعدة البيانات.',


            'purchase_price.required' => 'سعر الشراء مطلوب.',
            'purchase_price.numeric' => 'سعر الشراء يجب أن يكون رقمًا.',
            'purchase_price.min' => 'سعر الشراء يجب أن يكون أكبر من أو يساوي 0.',

            'category_id.required' => 'الفئة مطلوبة.',
            'category_id.integer' => 'معرف الفئة يجب أن يكون رقماً صحيحاً.',
            'category_id.exists' => 'معرف الفئة غير موجود.',

            'stock_quantity.required' => 'الكمية المطلوبة مطلوبة.',
            'stock_quantity.integer' => 'الكمية المطلوبة يجب أن تكون رقماً صحيحاً.',
            'stock_quantity.min' => 'الكمية المطلوبة يجب أن تكون أكبر من أو تساوي 1.',

            'expiration_date.date' => 'يجب أن يكون تاريخ انتهاء الصلاحية تاريخًا صالحًا.',
            'expiration_date.after' => 'يجب أن يكون تاريخ انتهاء الصلاحية في المستقبل.',

            'transporter_id.required' => 'معرف الناقل مطلوب.',
            'transporter_id.integer' => 'معرف الناقل يجب أن يكون رقماً صحيحاً.',
            'transporter_id.exists' => 'معرف الناقل غير موجود.',
        ];
    }

}
