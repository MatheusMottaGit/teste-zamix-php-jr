<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sale_price',
        'cost_price',
        'type',
    ];

    public function productCompose() {
        return $this->belongsTo(ProductCompose::class);
    }

    public function simpleProducts() {
        return $this->hasMany(ProductCompose::class, 'compound_product_id');
    }

    public function requestItems() {
        return $this->belongsToMany(RequestItem::class);
    }

    public function stock() {
        return $this->belongsTo(Stock::class);
    }   

    public function stockMovements() {
        return $this->hasMany(StockMovement::class);
    }
}
