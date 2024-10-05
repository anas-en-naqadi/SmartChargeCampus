<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = ['name','selling_price','user_id','purchase_price','category_id','stock_quantity','expiration_date','image'];

    public function category(){
        return $this->belongsTo(Category::class);
    }


}
