<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = ['name', 'phone', 'password', 'email'];

    public function transporters(){
        return $this->hasMany(Client::class,'user_id')->where("role","transporter");
    }
    public function clients()
    {
        return $this->hasMany(Client::class, 'user_id')->where("role",  "client");
    }
    public function purchases() {
        return $this->hasMany(Purchase::class);
    }

    public function sells(){
        return $this->hasMany(Sell::class, 'user_id');
    }
    public function expenses(){
        return $this->hasMany(Expense::class, 'user_id');
    }
    public function categories(){
        return $this->hasMany(Category::class,'user_id');
    }
    public function products(){
        return $this->hasMany(Product::class,'user_id');
    }
}

