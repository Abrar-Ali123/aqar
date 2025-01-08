<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['route_name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_page');
    }
}
