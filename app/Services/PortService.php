<?php

namespace App\Services;

use App\Http\Requests\PortRequest;
use App\Models\ChargingStation;
use App\Models\Port;
use Illuminate\Http\Request;

class PortService
{

    public function __construct() {}

    public function storePort(PortRequest $request)
    {
        $validatedData = $request->validated();
        $charging_station = ChargingStation::whereId($validatedData['charging_station_id'])->first();
        if ($charging_station->ports->count() >= $charging_station->total_ports) {
            return [
                'message' => 'Le nombre maximal de ports pour cette station de charge a été atteint.',
                'errors' => [
                    'port_number' => ['Le nombre maximal de ports pour cette station de charge a été atteint.'],
                ]
            ];
        }
      
            $port = Port::create($validatedData);

        clearDashboards();
        $this->logActivity($port, 'Port créé', 'create-port');
        return $port;
    }
    public function updatePort(PortRequest $request, int $id)
    {
        $validatedData = $request->validated();

        $port = Port::findOrFail($id);


        $port->update($validatedData);
        clearDashboards();
        $this->logActivity($port, 'Port mis à jour', 'update-port');
        return $port;
    }
    public function markPortAsNotReserved(Request $request)
    {
        $validatedData = $request->validate([
            'port_id' => 'required|exists:ports,id'
        ]);

        $port = Port::findOrFail($validatedData['port_id']);


        $port->update(['status' => 'disponible']);

        clearDashboards();
        $this->logActivity($port, 'Port ouvert pour réservation', 'open-port');
        return $port;
    }
    public function destroyPort(int $id)
    {
        $port = Port::findOrFail($id);
        $port->delete();
        clearDashboards();
        $this->logActivity($port, 'Port supprimé', 'delete-port');
    }

    private function logActivity(Port $port, string $actionDescription, string $logName)
    {
        $admin = getSimpleUser();

        if ($admin && $admin->isAdmin()) {
            saveActivity(
                $port,
                "{$actionDescription} par l'administrateur {$admin->name}.",
                $logName
            );
        }
    }
}
