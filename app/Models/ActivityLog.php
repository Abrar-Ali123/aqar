<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'action',
        'payload',
    ];
}
