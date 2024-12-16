<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargingStationRequest extends FormRequest
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
            'description' => 'nullable|string|max:1000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'total_ports' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le champ nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser :max caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description ne peut pas dépasser :max caractères.',
            'user_id.required' => 'L\'utilisateur associé est obligatoire.',
            'user_id.integer' => 'L\'ID de l\'utilisateur doit être un entier.',
            'user_id.exists' => 'L\'utilisateur spécifié n\'existe pas.',
            'latitude.required' => 'La latitude est obligatoire.',
            'latitude.numeric' => 'La latitude doit être un nombre.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.required' => 'La longitude est obligatoire.',
            'longitude.numeric' => 'La longitude doit être un nombre.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
            'total_ports.required' => 'Le nombre total de ports est obligatoire.',
            'total_ports.integer' => 'Le nombre total de ports doit être un entier.',
            'total_ports.min' => 'Le nombre total de ports doit être au moins :min.',
        ];
    }
}
