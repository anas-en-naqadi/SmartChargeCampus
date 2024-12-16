<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class AuthService
{
    // Handle user login
    public function login($credentials)
    {
        if (Auth::attempt($credentials, $credentials['remember'] ?? false)) {
            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            saveActivity($user, 'L\'utilisateur ' . $user->name . ' s\'est connecté avec succès.', 'connexion');
            return ['user' => $user, 'token' => $token];
        }

        return null;
    }

    // Handle user registration
    public function register($formFields)
    {
        try {
            DB::beginTransaction();
            $hashedPassword = Hash::make($formFields['password']);

            // Create the user
            $user = User::create([
                'name' => $formFields['name'],
                'email' => $formFields['email'],
                'password' => $hashedPassword,
            ]);

            if (Auth::attempt(['email' => $user->email, 'password' => $formFields['password']], true)) {
                $user = getSimpleUser();
                $formFields['student']['user_id'] = $user->id;

                // Create student record if needed
                if ($user) {
                    Student::create($formFields['student']);
                }

                $token = $user->createToken('main')->plainTextToken;
                saveActivity($user, 'L\'utilisateur '.$user->name.' a été enregistré avec succès.', 'inscription');
                DB::commit();

                return ['user' => $user, 'token' => $token];
            }
            DB::rollBack();
            return null;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('An error occurred during registration. Please try again later.');
        }
    }

    // Handle user logout
    public function logout()
    {
        $user = getSimpleUser();
        $user->currentAccessToken()->delete();
        saveActivity($user, 'L\'utilisateur '.$user->name. ' s\'est déconnecté avec succès.', 'déconnexion');
        Redis::del('user');
    }
}
