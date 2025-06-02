<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTranslation extends Model
{
    public $timestamps = true;
    protected $fillable = ['notes', 'description', 'cancellation_reason'];
}
