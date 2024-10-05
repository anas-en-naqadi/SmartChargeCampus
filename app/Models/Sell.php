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
        return $this->hasManyThrough(Product::class, SellProduct::class, 'sell_id', 'id', 'id', 'product_id');
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }

}
