<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statusable extends Model
{
    use HasFactory;

    protected $fillable = ['status_id', 'statusable_id', 'statusable_type'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function statusable()
    {
        return $this->morphTo();
    }
}
