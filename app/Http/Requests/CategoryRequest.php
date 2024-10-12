<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'category_name' => 'string|required|unique:categories'
        ];

    }

    public function messages():array{
   return [
     'category_name.string' => 'يجب أن يكون اسم الفئة نصًا.',
     'category_name.required' => 'حقل اسم الفئة مطلوب.',
     'category_name.unique' => 'اسم الفئة موجود بالفعل. يرجى اختيار اسم آخر.'];
}
}
