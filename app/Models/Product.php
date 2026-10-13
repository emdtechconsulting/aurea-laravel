<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price_cents', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean', 'price_cents' => 'integer'];
    }
}
