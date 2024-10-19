<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class UserController extends Controller
{
    public function updateUserInfo(Request $request, User $user)
    {
        // Validate request data
        $validated = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string', // Corrected 'fitst_name' to 'first_name'
            'last_name' => 'required|string',  // Corrected 'require' to 'required'
            'default_shipping_address.address' => 'required|string',
            'default_shipping_address.city' => 'required|string',
            'default_shipping_address.state' => 'required|string',
            'default_shipping_address.country' => 'required|string',
            'default_shipping_address.postal_code' => 'required|string',
        ]);

        // Clean inputs if CleanInputs function is defined
        if (function_exists('CleanInputs')) {
            CleanInputs($validated);
        }

        // Update user information
        $user->update([
            'name' => $validated['first_name'] . " " . $validated['last_name'],
            'email' => $validated['email']
        ]);

        // Update default shipping address
        if ($user->defaultShippingAddress) {
            $user->defaultShippingAddress->update($validated['default_shipping_address']);
        } else {
            $user->defaultShippingAddress()->create($validated['default_shipping_address']);
        }

        return response($user);
    }


    public function destroy(User $user)
    {
        // Delete user's relations
        $user->cart()->delete();
        $user->wishList()->delete();
        // $user->orders()->delete();
        $user->reviews()->delete();

        // // Finally, delete the user
        $user->delete();
        return response('successfful');
    }


    public function storeUser(Request $request)
    {
        $validatedData = $request->validate(['email' => 'required|email|unique:users,email', 'phone' => 'required|string|min:10', 'name' => 'string|required', 'is_admin' => 'required|boolean']);
        $password = Str::random(16);

        $validatedData['password'] = Hash::make($password);
        $validatedData['is_admin'] = $validatedData['is_admin'] == '1' ? true : false;
        $user =   User::create($validatedData);
        if ($user)
            Mail::to($validatedData['email'])->send(new SendPasswordMail(['password' => $password, 'name' => $validatedData['name'], 'email' => $validatedData['email'], 'role' => $validatedData['is_admin']]));
        Redis::del('users');
        return response()->json(['message' => 'User added successfully'], 200);
    }
    public function updatePass(Request $request)
    {
        $user =  getSimpleUser(); // If $user is null, use getSimpleUser()

        // Array to store validation errors
        $errors = [];

        // Check if the current password is correct
        if (!Hash::check($request->currentPassword, $user->password)) {
            array_push($errors, 'Current password is incorrect');
        }

        // Check if the new password meets the minimum length requirement
        if (strlen($request->newPassword) < 8) {
            array_push($errors, 'The password must be at least 8 characters long');
        }

        // Check if the new password and confirmation match
        if ($request->newPassword !== $request->confirmation_password) {
            array_push($errors, 'The password confirmation does not match');
        }

        // If there are validation errors, return them
        if (!empty($errors)) {
            return response(['errors' => $errors], 422);
        }

        // Update the user's password
        $user->password = Hash::make($request->newPassword);
        $user->save();

        // Return success message
        return response()->json(['message' => 'Password updated successfully']);
    }


    public function oneUser($id)
    {
        $user = User::whereId($id)->get();

        return response()->json($user);
    }








    public function getLoggedUser()
    {
      $cacheKey = 'user';
      $cachedData = getCachedData($cacheKey,function(){
        $user = getSimpleUser();
        return $user;
      });

        return response()->json($cachedData);
    }

    public function getLastCustomers()
    {
        $clients = Client::latest()->limit(7)->get();


        return response()->json($clients);
    }
}
