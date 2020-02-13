<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    
    protected $fillable = [
    	'StockLevel',
    	'StockAmount',
    	'product_id',

    ];
    
    public function Product() {
        return $this->belongsTo('App\Product');
    }
}
