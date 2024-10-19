<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'total_credit',
        'phone',
        'user_id',
        'role',
        'cni',
    ];

    public function sells(){
        return $this->hasMany(Sell::class,'client_id');
    }

    public function purchases(){
        return $this->hasMany(Purchase::class,'transporter_id');
    }





}
