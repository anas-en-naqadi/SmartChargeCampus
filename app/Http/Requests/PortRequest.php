<?php

namespace App\Http\Requests;

use App\Models\Port;
use Illuminate\Foundation\Http\FormRequest;

class PortRequest extends FormRequest
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
        // Récupérer l'ID du port actuel (lors de la mise à jour)
        $portId = $this->route('port');  // Cette ligne récupère l'ID du port de l'URL de la requête.

        // Validation par défaut
        $rules = [
            'charging_station_id' => 'required|exists:charging_stations,id',
            'status' => 'required|in:disponible,indisponible,sous maintenance',
            'port_number' => 'required|integer',
        ];

        // Validation pour les mises à jour
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // Récupérer le port actuel depuis la base de données
            $currentPort = Port::find($portId);

            if ($currentPort) {
                $chargingStationId = $currentPort->charging_station_id;  // Récupère l'ID de la station de charge actuelle

                // Vérifier si le port_number dans la requête est différent de l'actuel
                if ($currentPort->port_number != $this->input('port_number')) {
                    // Appliquer la règle de validation manuelle pour vérifier l'unicité du port_number
                    $rules['port_number'] = [
                        'required',
                        'integer',
                        function ($attribute, $value, $fail) use ($chargingStationId) {
                            // Vérifier si un autre port avec le même numéro existe dans la même station, en excluant les enregistrements supprimés
                            $existingPort = Port::where('charging_station_id', $chargingStationId)
                                ->where('port_number', $value)
                                ->whereNull('deleted_at') // Exclure les enregistrements supprimés
                                ->first();

                            if ($existingPort) {
                                $fail('Le numéro du port existe déjà pour cette station de charge.');
                            }
                        },
                    ];
                }
            }
        } else {
            // Pour les créations, appliquer la validation manuelle pour vérifier l'unicité du port_number
            $rules['port_number'] = [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    // Vérifier si un autre port avec le même numéro existe dans la même station, en excluant les enregistrements supprimés
                    $existingPort = Port::where('charging_station_id', $this->input('charging_station_id'))
                    ->where('port_number', $value)
                        ->whereNull('deleted_at') // Exclure les enregistrements supprimés
                        ->first();

                    if ($existingPort) {
                        $fail('Le numéro du port existe déjà pour cette station de charge.');
                    }
                },
            ];
        }

        return $rules;
    }



    public function messages()
    {
        return [
            'charging_station_id.required' => 'La station de charge est requise.',
            'charging_station_id.exists' => 'La station de charge spécifiée n\'existe pas.',
            'status.required' => 'Le statut est requis.',
            'status.in' => 'Le statut doit être soit "disponible", "indisponible" ou "sous maintenance".',
            'port_number.required' => 'Le numéro du port est requis.',
            'port_number.integer' => 'Le numéro du port doit être un entier.',
            'port_number.unique' => 'Le numéro du port existe déjà.',
        ];
    }
}
