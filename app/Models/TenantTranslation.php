<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantTranslation extends Model
{
    public $timestamps = true;
    protected $fillable = ['name', 'description', 'notes', 'address'];
}
