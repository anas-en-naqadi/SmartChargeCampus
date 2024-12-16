<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'cne' => $this->cne,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'sector' => $this->sector,
            'year_of_study' => $this->year_of_study,
            'university' => $this->university,
            'phone_number' => $this->phone_number,
            'created_at' => Carbon::parse($this?->created_at),
        ];
    }
}
