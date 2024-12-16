<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;


class UserController extends Controller
{
    private  $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }





    public function updateUserProfile(UpdateStudentRequest $request)
    {

        $user = $this->userService->updateUserProfile($request);

        return response()->json([
            'message' => 'Votre profil a été mis à jour.',
            'user' => $user->load('student') // Eager load the company relationship
        ]);
    }


    public function destroy(User $user)
    {
        $user->delete();
        saveActivity($user, $user->name . ' Utilisateur supprimé.', 'delete-user');
        return response('successfful');
    }



    public function updatePass(Request $request)
    {
        $this->userService->updatePass($request);
        // Return success message
        return response()->json(['message' => 'Le mot de passe a été mis à jour avec succès']);
    }











    public function getLoggedUser()
    {
        $cacheKey = 'user';
        $cachedData = getCachedData($cacheKey, function () {
            $user = getSimpleUser();


            return  $user->isAdmin() ? $user :  $user->load('student');
        });

        return response()->json($cachedData);
    }
}
