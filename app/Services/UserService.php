<?php

namespace App\Services;

use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService
{

    public function __construct() {}




    public function updateUserProfile(UpdateStudentRequest $request)
    {
        $validatedData = $request->validated();

        $user = getSimpleUser();

        // Update the user information
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if (isset($validatedData['student']))
            $user->student->update($validatedData['student']);
        $user->save();
        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            saveActivity($user, $user->name .' Informations utilisateur récupérées.', 'get-logged-user');
        }
        return $user;
    }

    public function updatePass(Request $request)
    {
        $user = getSimpleUser(); // If $user is null, use getSimpleUser()

            $validatedData = $request->validate([
                'currentPassword' => 'required|string',
                'newPassword' => 'required|string|min:8|confirmed',
                'newPassword_confirmation' => 'required|string|min:8',
            ], [
                'currentPassword.required' => 'Veuillez entrer le mot de passe actuel.',
                'currentPassword.string' => 'Le mot de passe actuel doit être une chaîne de caractères.',
                'newPassword.required' => 'Veuillez entrer le nouveau mot de passe.',
                'newPassword.string' => 'Le nouveau mot de passe doit être une chaîne de caractères.',
                'newPassword.min' => 'Le nouveau mot de passe doit comporter au moins 8 caractères.',
                'newPassword.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas. Veuillez vérifier le mot de passe saisi.',
                'newPassword_confirmation.required' => 'Veuillez confirmer le nouveau mot de passe.',
                'newPassword_confirmation.string' => 'Le mot de passe de confirmation doit être une chaîne de caractères.',
                'newPassword_confirmation.min' => 'Le mot de passe de confirmation doit comporter au moins 8 caractères.'
            ]);


        cleanInputs($validatedData);
        // Check if the current password is correct
        if (!Hash::check($validatedData['currentPassword'], $user->password)) {
            return response()->json(['errors' => ['Le mot de passe actuel est incorrect']], 422);
        }

        // Update the user's password
        $user->password = Hash::make($validatedData['newPassword']);
        $user->save();

        if ($user instanceof \Illuminate\Database\Eloquent\Model) {
            saveActivity($user, $user->name . ' Mot de passe mis à jour.', 'update-password');
        }
    }

}
