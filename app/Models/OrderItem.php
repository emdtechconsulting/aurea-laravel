<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'product_name', 'quantity', 'unit_price_cents', 'subtotal_cents'];
}
