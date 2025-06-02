<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentTranslation extends Model
{
    public $timestamps = true;
    protected $fillable = ['description'];
}
