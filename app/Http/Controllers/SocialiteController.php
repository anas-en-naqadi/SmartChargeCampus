<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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

            $findUser = User::where('social_id', $user->id)->first();
            if ($findUser) {
                Auth::login($findUser);
                $token = $findUser->createToken('main')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $findUser]);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'phone' => null,
                    'social_type' => 'google',
                    'password' => Hash::make($user->password)
                ]);
                Auth::login($newUser);
                $token = $newUser->createToken('main')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $newUser]);
            }

    }
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $findUser = User::where('social_id', $user->id)->first();
            if ($findUser) {
                Auth::login($findUser);
                $token = $findUser->createToken('main')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $findUser]);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'social_type' => 'facebook',
                    'password' => Hash::make($user->password)
                ]);
                Auth::login($newUser);
                $token = $newUser->createToken('main')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $newUser]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'oops, something went wrong pls try again later']);
        }
    }
}
