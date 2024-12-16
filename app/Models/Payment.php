<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['reservation_id','amount','status', 'payment_status','payment_method'];

    public function reservation(){
       return $this->belongsTo(Reservation::class);
    }
}
