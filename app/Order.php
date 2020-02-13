<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    protected $fillable = [
        // 'date',
    ];

    public function OrderItem() {
        return $this->hasMany('App\OrderItem');
    }
    
    public function User() {
        return $this->belongsTo('App\User');
    }


    /**
     * Get all of the Products for the Order.
     */
    public function Product() {
        return $this->hasManyThrough('App\Product', 'App\OrderItem');
    }
}
