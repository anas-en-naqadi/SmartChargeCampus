<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;  // Import the AuthService

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService; // Inject the AuthService
    }

    // Login method
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        cleanInputs($credentials);

        $response = $this->authService->login($credentials);

        if ($response) {
            return response()->json($response, 200);
        }

        return response()->json(['message' => 'Wrong Credentials. Please verify.'], 401);
    }

    // Register method
    public function register(RegisterRequest $request)
    {
        try {
            $formFields = $request->validated();
            cleanInputs($formFields);

            $response = $this->authService->register($formFields);

            if ($response) {
                return response()->json($response, 200);
            }

            return response()->json(['error' => 'Authentication failed'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred during registration. Please try again later.'], 500);
        }
    }

    // Logout method
    public function logout()
    {
        try {
            $this->authService->logout();
            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error occurred during logout'], 500);
        }
    }
}
