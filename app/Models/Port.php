<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Port extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['charging_station_id','status','port_number'];

    public function reservations(){
        return $this->hasMany(Reservation::class);
    }
    public function charge_station(){
        return $this->belongsTo(ChargingStation::class, 'charging_station_id');
    }
}
