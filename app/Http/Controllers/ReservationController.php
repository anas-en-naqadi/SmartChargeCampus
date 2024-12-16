<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Payment;
use App\Models\Port;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ReservationController extends Controller
{
    private $reservationService ;
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        Redis::del('reservations');
        $caheKey = 'reservations';
        $cacheData = getCachedData($caheKey, function () {
            $user = getSimpleUser();
            return $user->isAdmin() ? Reservation::all() : $user->student->reservations;
        });
        saveActivity(new Reservation(), getSimpleUser()->name . ' a Consultée les Réservations', 'consulte-reservations');

        return response()->json(ReservationResource::collection($cacheData));
    }

    public function markReservationAsPayed(Request $request)
    {
        $reservation =  $this->reservationService->markReservationAsPayed($request);

        return response()->json(['message' => 'Réservation payé avec succès.', 'reservation' => ReservationResource::make($reservation)], 200);
    }

    public function store(ReservationRequest $request)
    {
        $validatedData = $request->validated();

        $response = $this->reservationService->store($validatedData);

        if (isset($response['error'])) {
            return response()->json($response, 500);
        }

        return response()->json($response, 200);
    }

    public function cancelReservation(Request $request)
    {
      $reservation =  $this->reservationService->cancelReservation($request);
        return response()->json(['message' => 'Réservation annulée avec succès.', 'reservation' => ReservationResource::make($reservation)], 200);
    }
    public function filterReservationsByDates(Request $request)
    {
        $reservations =  $this->reservationService->filterReservationsByDates($request);
        return response()->json($reservations);
    }


}
