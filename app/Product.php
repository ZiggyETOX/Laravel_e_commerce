<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    protected $fillable = [
        	'ProductName',
			'ProductShortDescription',
			'CSProductCategory',
			'CSProductSubCategory',
			'TopLevel',
			'SKU',
			'NewDate',
			'Promotion',
			'SAPrice',
			'BotswanaPrice',
			'NamibiaPrice',
    ];

    public function Stock() {
        return $this->hasMany('App\Stock');
    }
    
    public function OrderItem() {
        return $this->hasMany('App\OrderItem');
    }
}
