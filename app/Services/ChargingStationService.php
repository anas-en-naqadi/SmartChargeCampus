<?php

namespace App\Services;

use App\Http\Requests\ChargingStationRequest;
use App\Models\ChargingStation;

use Illuminate\Support\Facades\Redis;

class ChargingStationService
{

    public function __construct() {}


    public function storeChargeStation(ChargingStationRequest $request)
    {
        $validatedData = $request->validated();

        cleanInputs($validatedData);

        $chargingStation = ChargingStation::create([
            ...$validatedData,
            'user_id' => getSimpleUser()->id,
        ]);
        Redis::del('charge_stations');
        clearDashboards();
        $this->logActivity('create', $chargingStation);
        return $chargingStation;
    }

    public function updateChargeStation(ChargingStationRequest $request, int $id)
    {
        $validatedData = $request->validated();
        cleanInputs($validatedData);
        $chargingStation = ChargingStation::findOrfail($id);
        $chargingStation->update($validatedData);
        Redis::del('charge_stations');
        clearDashboards();
        $this->logActivity('update', $chargingStation);
        return $chargingStation;
    }

    public function destroyChargeStation( $id)
    {
        $chargingStation = ChargingStation::findOrfail($id);
        $chargingStation->delete();
        Redis::del('charge_stations');
        clearDashboards();
        $this->logActivity('delete', $chargingStation);
    }

    private function logActivity(string $action, ChargingStation $chargingStation)
    {
        $admin = getSimpleUser();

        if ($admin && $admin->isAdmin()) {
            saveActivity(
                $chargingStation,
                "Station de charge {$action} par l'administrateur {$admin->name}.",
                "{$action}-charge-station"
            );
        }
    }
}
