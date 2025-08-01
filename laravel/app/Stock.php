<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['product_id', 'simple_product_quantity', 'product_quantity'];

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
