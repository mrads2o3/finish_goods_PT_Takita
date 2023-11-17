<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders_items extends Model
{
    use HasFactory;

    protected $fillable = [
        'orders_id',
        'items_id',
        'price',
        'qty'
    ];
}
