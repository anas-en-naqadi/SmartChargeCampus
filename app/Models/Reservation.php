<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['student_id', 'port_id', 'reservation_code'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            $reservation->reservation_code = $reservation->generateUniqueReservationCode();
        });
    }

    private function generateRandomCode(int $length = 10): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return collect(range(1, $length))
            ->map(fn() => $characters[random_int(0, strlen($characters) - 1)])
            ->implode('');
    }

    private function generateUniqueReservationCode(): string
    {
        $code = $this->generateRandomCode(10);
        while (self::where('reservation_code', $code)->exists()) {
            $code = $this->generateRandomCode(10);
        }
        return $code;
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function port()
    {
        return $this->belongsTo(Port::class);
    }

}
