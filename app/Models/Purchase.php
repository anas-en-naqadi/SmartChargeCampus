<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'transporter_id'
    ];

    public function transporter(){
        return $this->belongsTo(Client::class);
    }


  
}
