<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChargingStation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'user_id', 'latitude', 'longitude', 'total_ports'];

    public function ports()
    {
        return $this->hasMany(Port::class, 'charging_station_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
