<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Str;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Attempt to authenticate the user
        if (Auth::attempt($credentials, $credentials['remember'] ?? false)) {
            // If authentication is successful, retrieve the authenticated user
            $user = Auth::user();

            // Create a new token for the user
            $token = $user->createToken('main')->plainTextToken;

            // Return user data and token
            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            // If authentication fails, return error message
            return response()->json(['message' => 'Wrong Credentials. Please verify.'], 401);
        }
    }



    public function register(RegisterRequest $request)
    {
        $formFields = $request->validated();
        cleanInputs($formFields);

        // Hash the password
        $formFields['password'] = Hash::make($formFields['password']);

        // Create the user
        $user = User::create($formFields);

        // Attempt to authenticate the user after registration
        if (Auth::attempt(['email' => $user->email, 'password' => $request->password], true)) {
            // If authentication after registration is successful, retrieve the authenticated user
            $user = getSimpleUser();

            // Create a new token for the user
            $token = $user->createToken('main')->plainTextToken;

            // Return user data and token
            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            // If authentication after registration fails, return error message
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }


    public function logout()
    {
        $user = getSimpleUser();
            $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
