<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // e.g., 'student', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function charge_stations()
    {
        return $this->hasMany(ChargingStation::class);
    }


    public function student(){
        return $this->hasOne(Student::class);
    }



    /**
     * Check if the user has any upcoming reservations.
     *
     * @return bool
     */
    public function hasUpcomingReservation()
    {
        return $this->reservations()->where('start_time', '>', now())->exists();
    }

    /**
     * Role-based access control for users.
     * For example, check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Add a new reservation for the user.
     */
    public function addReservation($station_id, $start_time, $end_time)
    {
        return $this->reservations()->create([
            'station_id' => $station_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => 'pending', // You can set a default status here
        ]);
    }
}
