<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['user_id','total','due_date','status','to_customer'];

    public function sells()
    {
        return $this->hasMany(Sell::class);
    }
    public function customer(){
        return $this->belongsTo(User::class,'to_customer');
    }
}
