<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_name' => $this->client?->name,
            'total_price' => $this->total_price,
            'paid_amount' => $this->paid_amount,
            'remaining_amount' => $this->remaining_amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'change' => $this->change,
            'check_date' => $this->check_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
