<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load(['student.user', 'port.charge_station', 'payment']);
        return [
            'id' => $this->id,
            'student_name' => $this->student?->user->name,
            'cne' => $this->student?->cne,
            'reservation_code' => $this->reservation_code,

            'reserved_port' => $this->port->port_number,
            'charging_station' => $this->port?->charge_station->name,
            'payment_status' => $this->payment?->payment_status,
            'payment_method' => $this->payment?->payment_method,
            'created_at' => $this?->created_at?->toDateTimeString(),

        ];
    }
}
