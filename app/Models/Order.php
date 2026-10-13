<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['client_request_id', 'request_hash', 'customer_name', 'phone', 'delivery_type', 'address', 'status', 'total_cents'];

    protected $hidden = ['request_hash'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
