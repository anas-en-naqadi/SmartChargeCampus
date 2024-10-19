<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'selling_price' => $this->selling_price,
            'purchase_price' => $this->purchase_price,
            'stock_quantity' => $this->stock_quantity,
            'image' => URL::to($this->image), // Transform the image field to a full URL
            'category_name' => $this->category?->category_name,
            'expiration_date' => $this->expiration_date,
            'category_id' =>$this->category?->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
