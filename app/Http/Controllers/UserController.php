<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;


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
    public function updateAdminProfile(Request $request)
    {

        $user = getSimpleUser();
        // Validate request data
        $validated = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string', // Corrected 'fitst_name' to 'first_name'
            'last_name' => 'required|string',  // Corrected 'require' to 'required'
            'phone' => 'required|string'
        ]);


        CleanInputs($validated);


        // Update user information
        $user->update([
            'name' => $validated['first_name'] . " " . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone']
        ]);

        $nameParts = explode(' ', $user->name);
        $user->first_name = $nameParts[0];
        $user->last_name = isset($nameParts[1]) ? $nameParts[1] : '';

        return  response(['user' => $user, 'message' => 'Profile Updated Successfully'], 200);
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

    public function changeUserStatus(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $user->status = $request->status;
            $user->save();
            Redis::del('users');
            return response()->json(['message' => 'User has been ' . ($request->status == 'active' ? 'activated' : 'deactivated') . ' successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'oops, something went wrong'], 500);
        }
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

    public function setAsAdmin(Request $request)
    {
        $id = $request->validate(['user_id' => 'numeric|required'])['user_id'];
        $user = User::find($id);
        $user->is_admin = 1;
        $user->save();
        Redis::del('users');
        return response()->json(['message' => 'this account now is admin'], 200);
    }

 



    public function moveUserToBlackList($id)
    {
        $user = User::whereId($id)->get();
        Redis::del('users');
        return response()->json(['message' => $user->name . ' moved To blacklist !!']);
    }
    public function getLoggedUser()
    {
        $user = getSimpleUser();
        $nameParts = explode(' ', $user->name);
        $user->first_name = $nameParts[0];
        $user->last_name = isset($nameParts[1]) ? $nameParts[1] : '';
        return response()->json($user);
    }

    public function getLastCustomers()
    {
        $customers = User::with(['orders' => function ($query) {
            $query->where('status', 'completed')
                ->latest();
        }])
            ->whereHas('orders', function ($query) {
                $query->where('status', 'completed');
            })
            ->latest()
            ->limit(7)
            ->get();

        return response()->json($customers);
    }
}
