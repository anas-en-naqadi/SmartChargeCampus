<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name' => 'required|string',
            'phone' => 'required|string',
            'cni' => 'required|string',
            'total_credit' => 'required|numeric|between:0,999999999', // Ensure proper validation for decimal
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصًا.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصًا.',
            'cni.required' => 'رقم البطاقة الوطنية مطلوب.',
            'cni.string' => 'يجب أن يكون رقم البطاقة الوطنية نصًا.',
            'total_credit.required' => 'إجمالي الائتمان مطلوب.',
            'total_credit.numeric' => 'يجب أن يكون إجمالي الائتمان رقماً.',
        ];
    }
}
