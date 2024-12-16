<?php

namespace App\Services;



use App\Http\Requests\ReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Payment;
use App\Models\Port;
use App\Models\Reservation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
class ReservationService
{

    public function __construct() {}

    public function store($validatedData)
    {
        try {
            DB::beginTransaction();

            // Clean and prepare the input data
            cleanInputs($validatedData);
            $validatedData['student_id'] = getSimpleUser()?->student?->id;

            // Update the port status
            $port = Port::findOrFail($validatedData['port_id']);
            $port->update(['status' => 'indisponible']);

            // Create the reservation
            $reservation = Reservation::create($validatedData);

            // Create the associated payment
            Payment::create([
                'reservation_id' => $reservation->id,
                'payment_method' => 'espèce',
                'payment_status' => 'en_attente',
                'amount' => 5
            ]);

            DB::commit();

            // Clear cache and dashboard data
            Redis::del('reservations');
            clearDashboards();

            // Save the activity log
            saveActivity($reservation, 'Nouvelle Réservation créée par ' . getSimpleUser()->name, 'create-reservation');

            return [
                'message' => 'Votre réservation a été créée avec succès.',
                'port' => $port
            ];
        } catch (Exception $e) {
            DB::rollBack(); // Rollback in case of error

            // You can log the error message here if needed

            return ['error' => 'An error occurred while creating the reservation.'];
        }
    }
    public function filterReservationsByDates(Request $request){

        $data = $request->validate([
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'start_date.required' => 'La date de début est requise.',
            'start_date.date' => 'La date de début doit être une date valide.',
            'start_date.before_or_equal' => 'La date de début doit être antérieure ou égale à la date de fin.',
            'end_date.required' => 'La date de fin est requise.',
            'end_date.date' => 'La date de fin doit être une date valide.',
            'end_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

        $reservations = Reservation::whereBetween('created_at', [$data['start_date'], $data['end_date']])->get();

        return ReservationResource::collection($reservations);

    }

    public function cancelReservation(Request $request){
        $reservation =  $this->makeAction("annulé", $request);
        $this->logActivity($reservation, 'Réservation annulée', 'cancel-reservation');
        return $reservation ;
    }
    public function markReservationAsPayed(Request $request)
    {
        $reservation = $this->makeAction("payé", $request);
        $this->logActivity($reservation, 'Réservation marquée comme payée', 'mark-as-payed');
        return $reservation;
    }
    private function makeAction($status, $request)
    {
        $reservation_id = $request->validate(['reservation_id' => 'required|exists:reservations,id'])['reservation_id'];
        $reservation = Reservation::with('payment', 'port')->findOrFail($reservation_id);
        $reservation->payment->update(['payment_status' => $status]);

        if ($status === "annulé") {
            $reservation->port->update(['status' => 'disponible']);
        }
        Redis::del('reservations');
        clearDashboards();
        return $reservation;
    }
    private function logActivity(Reservation $reservation, string $actionDescription, string $logName)
    {
        $admin = getSimpleUser();

        if ($admin && $admin->isAdmin()) {
            saveActivity(
                $reservation,
                "{$actionDescription} par l'administrateur {$admin->name}.",
                $logName
            );
        }
    }
}
