<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\companyRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;

use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    private  $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updateCompanyInfo(CompanyRequest $request)
    {
        $user = $this->userService->updateCompanyInfo($request);

        return response()->json([
            'message' => 'تم تحديث معلومات المحل بنجاح',
            'user' => $user->load('company'),
        ]);
    }



    public function updateUserProfile(Request $request)
    {

        $user = $this->userService->updateUserProfile($request);

        // Return a success response with the updated user data
        return response()->json([
            'message' => 'تم تحديث معلومات المستخدم بنجاح',
            'user' => $user->load('company') // Eager load the company relationship
        ]);
    }


    public function destroy(User $user)
    {


        $user->delete();
        return response('successfful');
    }



    public function updatePass(Request $request)
    {
        $this->userService->updatePass($request);
        // Return success message
        return response()->json(['message' => 'تم تحديث كلمة المرور بنجاح']);
    }



    public function oneUser($id)
    {
        $user = User::whereId($id)->get();

        return response()->json($user);
    }








    public function getLoggedUser()
    {
        $cacheKey = 'user';
        $cachedData = getCachedData($cacheKey, function () {
            $user = getSimpleUser()->load('company');
            if($user->company){
                $user->company->logo = URL::to($user->company?->logo);
                $user->company->save();
            }

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
