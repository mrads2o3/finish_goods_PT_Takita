<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'cust_id',
        'request_date',
        'send_date',
        'cancel_at',
        'complete_at',
        'send_at',
        'status'
    ];
}
