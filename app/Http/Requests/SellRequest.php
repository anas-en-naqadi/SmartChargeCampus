<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellRequest extends FormRequest
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
            'client_id' => 'nullable|integer|exists:clients,id',
            'client.name' => 'nullable|string|max:255',
            'client.phone' => 'nullable|string|max:20',
            'client.cni' => 'nullable|string|max:20',
            'client.total_credit' => 'nullable|numeric|min:0',
            'change' => 'nullable|numeric|max:0',
            'products' => 'required|array',
            'total_price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'remaining_amount' => 'required|numeric',
            'payment_method' => 'required|string|max:255',
            'check_date' => 'nullable|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.integer' => 'يجب أن يكون معرف العميل رقمًا صحيحًا.',
            'client_id.exists' => 'معرف العميل غير موجود.',

            'client.name.string' => 'يجب أن يكون اسم العميل نصاً صالحاً.',
            'client.name.max' => 'يجب ألا يزيد اسم العميل عن 255 حرفًا.',
            'client.phone.string' => 'يجب أن يكون رقم هاتف العميل نصاً صالحاً.',
            'client.phone.max' => 'يجب ألا يزيد رقم هاتف العميل عن 20 حرفًا.',
            'client.cni.string' => 'يجب أن يكون الرقم الوطني للعميل نصاً صالحاً.',
            'client.cni.max' => 'يجب ألا يزيد الرقم الوطني للعميل عن 20 حرفًا.',
            'client.total_credit.numeric' => 'يجب أن يكون إجمالي الرصيد رقمًا.',
            'client.total_credit.min' => 'يجب ألا يكون إجمالي الرصيد سالباً.',

            'change.numeric' => 'يجب أن يكون تغيير المبلغ رقمًا.',
            'change.max' => 'يجب ألا يتجاوز تغيير المبلغ 0.',

            'products.required' => 'يجب تحديد المنتجات.',
            'products.array' => 'يجب أن تكون المنتجات في شكل قائمة.',

            'total_price.required' => 'إجمالي السعر مطلوب.',
            'total_price.numeric' => 'يجب أن يكون إجمالي السعر رقمًا.',
            'total_price.min' => 'يجب أن يكون إجمالي السعر أكبر من أو يساوي 0.',

            'paid_amount.numeric' => 'يجب أن يكون المبلغ المدفوع رقمًا.',
            'paid_amount.min' => 'يجب أن يكون المبلغ المدفوع أكبر من أو يساوي 0.',

            'remaining_amount.required' => 'المبلغ المتبقي مطلوب.',
            'remaining_amount.numeric' => 'يجب أن يكون المبلغ المتبقي رقمًا.',

            'payment_method.required' => 'طريقة الدفع مطلوبة.',
            'payment_method.string' => 'يجب أن تكون طريقة الدفع نصًا.',
            'payment_method.max' => 'يجب ألا تتجاوز طريقة الدفع 255 حرفًا.',

            'check_date.date' => 'يجب أن يكون تاريخ الشيك تاريخًا صالحًا.',
            'check_date.after_or_equal' => 'يجب أن يكون تاريخ الشيك اليوم أو بعده.',
        ];
    }
}
