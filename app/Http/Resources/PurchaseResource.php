<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
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
            'name'=>$this->name,
            'purchase_id' => $this->id,
            'transporter_name' => $this->transporter_name,
            'category_name' => $this->category?->category_name, // Get product category name
            'created_at' => $this->created_at,
            'stock_quantity' =>$this->stock_quantity,
            'purchase_price' => $this->purchase_price,
            'expiration_date' => $this->expiration_date,
            'updated_product' => $this->updated_product,
        ];
    }
}
