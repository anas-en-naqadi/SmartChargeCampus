<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargingStationRequest;
use App\Models\ChargingStation;
use App\Services\ChargingStationService;


class ChargingStationController extends Controller
{
    private ChargingStationService $chargeStationService;
    public function __construct(ChargingStationService $chargeStationService)
    {
        $this->chargeStationService = $chargeStationService;
    }

    public function index()
    {
        $cacheKey = 'charge_stations';
        $cacheData = getCachedData($cacheKey, function () {
            $charging_stations = ChargingStation::latest()->get();
            return $charging_stations;
        });
        saveActivity(new ChargingStation(), 'L\'administrateur ' . getSimpleUser()->name . ' a Consultée les stations de recharges', 'consulte-station-recharges');

        return response()->json($cacheData, 200);
    }

    public function show($id)
    {
        $cs = ChargingStation::findOrfail($id);
        return response()->json($cs->load('ports'), 200);
    }
    public function edit($id)
    {
        $cs = ChargingStation::findOrfail($id);
        return response()->json($cs, 200);
    }


    public function store(ChargingStationRequest $request)
    {
        $chargingStation = $this->chargeStationService->storeChargeStation($request);
        return response()->json([
            'message' => 'La station de charge a été créée avec succès.',
            'charge_station' => $chargingStation,
        ], 201);
    }

    public function update(ChargingStationRequest $request, $id)
    {
        $chargingStation = $this->chargeStationService->updateChargeStation($request, $id);

        return response()->json([
            'message' => 'La station de charge a été mise à jour avec succès.',
            'charge_station' => $chargingStation,
        ], 200);
    }

    public function destroy($id)
    {

        $this->chargeStationService->destroyChargeStation($id);
        return response()->json([
            'message' => 'La station de charge a été supprimée avec succès.'
        ], 200);
    }
}
