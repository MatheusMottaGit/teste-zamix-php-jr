<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCompose extends Model
{
    protected $fillable = ['simple_product_id', 'compound_product_id', 'simple_product_quantity'];
}
