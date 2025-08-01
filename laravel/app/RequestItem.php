<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable = ['request_id', 'product_id', 'items_quantity'];

    public function request() {
        return $this->belongsTo(Request::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
