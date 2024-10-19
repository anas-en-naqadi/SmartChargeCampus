<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name' =>$this->name,
            'cni' => $this->cni,
            'phone' =>$this->phone,
            'total_credit' => $this->total_credit,
            'sells'=> SellResource::collection($this->sells),
            'created_at' => $this->created_at
        ];

    }
}
