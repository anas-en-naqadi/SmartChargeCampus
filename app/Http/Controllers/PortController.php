<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortRequest;
use App\Models\Port;
use App\Services\PortService;
use Illuminate\Http\Request;

class PortController extends Controller
{
    private $portService ;

    public function __construct(PortService $portService)
    {
        $this->portService = $portService;
    }

    public function store(PortRequest $request)
    {
        $port = $this->portService->storePort($request);
        if(isset($port['errors'])){
            return response()->json($port, 422);
        }

        return response()->json([
            'message' => 'Le port a été créé avec succès.',
            'port' => $port,
        ], 201);
    }
    public function update(PortRequest $request, $id)
    {

        $port = $this->portService->updatePort($request,$id);

        return response()->json([
            'message' => 'Le port a été mis à jour avec succès.',
            'port' => $port,
        ], 200);
    }

    public function markPortAsNotReserved(Request $request)
    {
        $port = $this->portService->markPortAsNotReserved($request);

        return response()->json([
            'message' => 'Le port a été ouvert avec succès pour la réservation.',
            'port' => $port,
        ], 200);
    }




    public function edit($id)
    {
        $port = Port::findOrFail($id);
        return response()->json($port);
    }


    public function destroy($id)
    {
       $this->portService->destroyPort($id);
        return response()->json([
            'message' => 'Le port a été supprimé avec succès.',
        ]);
    }

}
