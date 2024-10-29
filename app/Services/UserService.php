<?php

namespace App\Services;

use App\Http\Requests\companyRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;

class UserService
{

    public function __construct() {}


    public function updateCompanyInfo(companyRequest $request)
    {
        // Validate and clean request data
        $validatedData = $request->validated();
        CleanInputs($validatedData);

        // Get the current user
        $user = getSimpleUser();
        $validatedData['user_id'] = $user?->id;

        // Check if the user has an existing company
        if (!$user->company) {
            // Create a new company if it doesn't exist
            if (isset($validatedData['logo'])) {
                $validatedData['logo'] = storeImage($validatedData['logo']);
            }
            $user->company()->create($validatedData);
        } else {
            // Handle logo update if provided
            if (isset($validatedData['logo']) && preg_match('/^data:image\/(\w+);base64,/', $validatedData['logo'], $type)) {
                $validatedData['logo'] = storeImage($validatedData['logo']);

                // Delete the old logo if it exists
                if ($user->company->logo) {
                    File::delete(public_path($user->company->logo));
                }
            } else {
                // If no new logo, retain the old logo
                $validatedData['logo'] = $user->company->logo;
            }

            // Update the existing company with validated data
            $user->company->update($validatedData);
        }

        $this->makeLogoAsUrlAndClearCache($user);
info($user->load('company'));
        return $user;
    }

    public function updateUserProfile(Request $request)
    {

        $request->validate([
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
        ]);

        $user = getSimpleUser(); // or getSimpleUser() if that's your method

        // Update the user information
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');

        $user->save(); // Save the updated user information


        $this->makeLogoAsUrlAndClearCache($user);

        return $user;
    }

    public function updatePass(Request $request)
    {
        $user = getSimpleUser(); // If $user is null, use getSimpleUser()

        $validatedData = $request->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8|confirmed',
            'confirmation_password' => 'required|string|min:8',
        ], [
            'currentPassword.required' => 'يرجى إدخال كلمة المرور الحالية.',
            'currentPassword.string' => 'يجب أن تكون كلمة المرور الحالية نصًا.',
            'newPassword.required' => 'يرجى إدخال كلمة المرور الجديدة.',
            'newPassword.string' => 'يجب أن تكون كلمة المرور الجديدة نصًا.',
            'newPassword.min' => 'يجب أن تتكون كلمة المرور الجديدة من 8 أحرف على الأقل.',
            'newPassword.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق. يرجى التحقق من كلمة المرور المدخلة.',
            'confirmation_password.required' => 'يرجى تأكيد كلمة المرور الجديدة.',
            'confirmation_password.string' => 'يجب أن تكون كلمة المرور المؤكدة نصًا.',
            'confirmation_password.min' => 'يجب أن تتكون كلمة المرور المؤكدة من 8 أحرف على الأقل.',
        ]);

        cleanInputs($validatedData);
        // Check if the current password is correct
        if (!Hash::check($validatedData['currentPassword'], $user->password)) {
            return response()->json(['errors' => ['كلمة المرور الحالية غير صحيحة']], 422);
        }

        // Update the user's password
        $user->password = Hash::make($validatedData['newPassword']);
        $user->save();
        $this->makeLogoAsUrlAndClearCache($user);
    }
    private function makeLogoAsUrlAndClearCache(User &$user){
        Redis::del('user');

        if($user->company && $user->company->logo){
            $user->company->logo = URL::to($user->company->logo);

            // Save the updated logo path in the company model
            $user->company->save();
        }
    }
}
