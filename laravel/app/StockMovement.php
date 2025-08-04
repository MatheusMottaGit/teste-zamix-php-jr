<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['product_id', 'request_id', 'type', 'cost_price', 'movement_date', 'quantity'];
}
