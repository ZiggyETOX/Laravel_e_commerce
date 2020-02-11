<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    protected $fillable = [
        // 'date',
    ];

    public function Stock() {
        return $this->hasMany('App\Stock');
    }
    
    public function Order() {
        return $this->belongsToMany(Order::class);
    }
}
