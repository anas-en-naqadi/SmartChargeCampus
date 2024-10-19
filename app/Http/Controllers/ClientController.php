<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;


class ClientController extends Controller
{
    public function index(){


        $cacheKey = 'clients';
        $cachedData = getCachedData($cacheKey, function () {
            $clients = getSimpleUser()->clients()
            ->with(['sells']) // Eager load the 'sells' relationship
            ->latest() // Sort by the latest creation date
            ->get();
            return ClientResource::collection($clients); // Return the processed clients
        });

        return response()->json($cachedData);
    }



   

}
