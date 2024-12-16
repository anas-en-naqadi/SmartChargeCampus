<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {

        $user = Socialite::driver('google')->stateless()->user();

        $findUser = User::where('email', $user->getEmail())->first();
        if ($findUser) {
            Auth::login($findUser);
        } else {
            $findUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'google_id' => $user->getId(),
                'password' => Hash::make(Str::random(16)),
            ]);
            Auth::login($findUser);
        }
        $token = $findUser->createToken('main')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $findUser]);

    }
}
