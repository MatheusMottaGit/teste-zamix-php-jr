<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['user_id', 'request_date'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function stockMovements() {
        return $this->hasMany(StockMovement::class);
    }
}
