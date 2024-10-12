<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sell extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = ['client_id','user_id', 'total_price', 'paid_amount', 'remaining_amount', 'status',"check","check_date"];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'sell_products', 'sell_id', 'product_id')
                    ->withPivot('quantity') // Include quantity from the pivot table
                    ->withTimestamps(); // Optionally include timestamps if needed
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }

}
