<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory,SoftDeletes;


    protected $fillable = ['name','user_id','purchase_price','category_id','stock_quantity','expiration_date','transporter_id','updated_product'];


    public function transporter()
    {
        return $this->belongsTo(Client::class);
    }

    public function getTransporterNameAttribute()
    {
        return $this->transporter ? $this->transporter->name : null; // Return null if no transporter is assigned
    }
    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->category_name : null; // Return null if no transporter is assigned
    }

        public function category(){
            return $this->belongsTo(Category::class);
        }

        public function product(){
            return $this->belongsTo(Product::class,'updated_product');
        }



}
