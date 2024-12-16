<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            // Top-level fields
            'name' => 'nullable|string',
            'email' => 'nullable|string|email', // Add email validation

            // Nested fields inside 'student'
            'student.sector' => 'nullable|string|max:100',
            'student.year_of_study' => 'nullable|integer|between:1,5',
            'student.university' => 'nullable|string|in:FPSB,FLSHJ,ESEF|max:150',
            'student.phone_number' => 'nullable|string|regex:/^(\+?\d{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{7,15}$/',
            // 'student.profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }


    public function messages()
    {
        return [
            // Top-level fields
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'email.string' => 'L\'email doit être une chaîne de caractères.',
            'email.email' => 'L\'email n\'est pas valide.',

            // Nested fields inside 'student'
            'student.sector.string' => 'Le secteur doit être une chaîne de caractères.',
            'student.sector.max' => 'Le secteur ne peut pas dépasser 100 caractères.',
            'student.year_of_study.integer' => 'L\'année d\'études doit être un entier.',
            'student.year_of_study.between' => 'L\'année d\'études doit être entre 1 et 5.',
            'student.university.string' => 'L\'université doit être une chaîne de caractères.',
            'student.university.max' => 'L\'université ne peut pas dépasser 150 caractères.',
            'student.university.in' => 'L\'université doit être l\'une des suivantes : FPSB, FLSHJ, ESEF.',
            'student.phone_number.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
            'student.phone_number.regex' => 'Le numéro de téléphone est invalide.',
            // 'student.profile_picture.image' => 'Le fichier doit être une image.',
            // 'student.profile_picture.mimes' => 'L\'image doit être un fichier de type : jpeg, png, jpg, gif, svg.',
        ];
    }

}
