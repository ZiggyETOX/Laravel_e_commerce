<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    protected $fillable = [
        // 'date',
    ];

    public function Product() {
        return $this->belongsToMany(Product::class);
    }
    
    public function User() {
        return $this->belongsTo('App\User');
    }
}
