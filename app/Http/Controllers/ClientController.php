<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(){


        $cacheKey = 'clients';
        $cachedData = getCachedData($cacheKey, function () {
            $clients = getSimpleUser()->clients()
                ->with(['sells' => function ($query) {
                    $query->with('client');
                }])->get();
            return $clients; // Return the processed clients
        });

        return response()->json($cachedData);
    }

    // public function update(ClientRequest $request){
    //     try {
    //         $validatedData = $request->validated();
    //         $validatedData['user_id']=getSimpleUser()?->id;
    //         $client = Client::create($validatedData);

    //         return response()->json(['message'=>''], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

}
