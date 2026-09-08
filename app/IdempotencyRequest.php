<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdempotencyRequest extends Model
{
    protected $fillable = [
        'user_id',
        'scope',
        'idempotency_key',
        'request_hash',
        'status',
        'status_code',
        'response_body',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];
}
