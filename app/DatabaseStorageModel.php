<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseStorageModel extends Model
{
    protected $table = 'cart_storage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cart_data',
    ];

    public function setCartDataAttribute($value, $id)
    {
        $this->attributes['cart_data'] = serialize($value);
        $this->attributes['id'] = $id;
    }

    public function getCartDataAttribute($value)
    {
        return unserialize($value);
    }

    /**
     * Get all of the Products for the Order.
     */
    // public function Product() {
    //     return $this->hasMany('App\Product');
    // }
}
