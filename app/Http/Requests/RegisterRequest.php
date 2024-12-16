<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'email|required|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'student.sector' => 'required|string|max:100',
            'student.year_of_study' => 'required|integer|between:1,5',
            'student.university' => 'required|string|max:150',
            'student.cne' => 'required|string|max:150',
            'student.phone_number' => 'nullable|string|regex:/^(\+?\d{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{7,15}$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est requis.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',

            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail doit être valide.',
            'email.unique' => 'L\'adresse e-mail est déjà utilisée.',

            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',

            'student.sector.required' => 'Le secteur est requis.',
            'student.sector.string' => 'Le secteur doit être une chaîne de caractères.',
            'student.sector.max' => 'Le secteur ne peut pas dépasser 100 caractères.',

            'student.year_of_study.required' => 'L\'année d\'étude est requise.',
            'student.year_of_study.integer' => 'L\'année d\'étude doit être un nombre entier.',
            'student.year_of_study.between' => 'L\'année d\'étude doit être entre 1 et 5.',

            'student.university.required' => 'L\'université est requise.',
            'student.university.string' => 'L\'université doit être une chaîne de caractères.',
            'student.university.max' => 'Le nom de l\'université ne peut pas dépasser 150 caractères.',

            'student.cne.required' => 'Le CNE est requis.',
            'student.cne.string' => 'Le CNE doit être une chaîne de caractères.',
            'student.cne.max' => 'Le CNE ne peut pas dépasser 150 caractères.',

            'student.phone_number.nullable' => 'Le numéro de téléphone est optionell.',
            'student.phone_number.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'student.phone_number.regex' => 'Le numéro de téléphone n\'est pas valide.',
        ];

    }
}
