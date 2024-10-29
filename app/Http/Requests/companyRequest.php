<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class companyRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15', // Adjust max length as necessary
            'ICE' => 'nullable|string|max:50',
            'RC' => 'nullable|string|max:50',
            'IF' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
            'instagram_url' => 'nullable|url|max:150',
            'facebook_url' => 'nullable|url|max:150',
            'logo' => 'nullable|string', // Assuming it's a string path
        ];


    }
    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.unique' => 'اسم الشركة مسجل بالفعل.',
            'name.string' => 'يجب أن يكون الاسم نصاً.',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفاً.',

            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.unique' => 'رقم الهاتف مسجل بالفعل.',
            'phone.string' => 'يجب أن يكون رقم الهاتف نصاً.',
            'phone.max' => 'يجب ألا يتجاوز رقم الهاتف 15 حرفاً.',

            'ICE.unique' => 'رقم ICE مسجل بالفعل.',
            'ICE.string' => 'يجب أن يكون رقم ICE نصاً.',
            'ICE.max' => 'يجب ألا يتجاوز رقم ICE 50 حرفاً.',

            'RC.unique' => 'رقم RC مسجل بالفعل.',
            'RC.string' => 'يجب أن يكون رقم RC نصاً.',
            'RC.max' => 'يجب ألا يتجاوز رقم RC 50 حرفاً.',

            'IF.unique' => 'رقم IF مسجل بالفعل.',
            'IF.string' => 'يجب أن يكون رقم IF نصاً.',
            'IF.max' => 'يجب ألا يتجاوز رقم IF 50 حرفاً.',

            'address.required' => 'العنوان مطلوب.',
            'address.string' => 'يجب أن يكون العنوان نصاً.',
            'address.max' => 'يجب ألا يتجاوز العنوان 255 حرفاً.',

            'instagram_url.url' => 'يجب أن يكون رابط Instagram صالحاً.',
            'instagram_url.max' => 'يجب ألا يتجاوز رابط Instagram 150 حرفاً.',

            'facebook_url.url' => 'يجب أن يكون رابط Facebook صالحاً.',
            'facebook_url.max' => 'يجب ألا يتجاوز رابط Facebook 150 حرفاً.',

            'logo.string' => 'يجب أن يكون مسار الشعار نصاً.',
        ];

    }
}
