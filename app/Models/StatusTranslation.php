<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['status_id', 'locale', 'name'];

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
