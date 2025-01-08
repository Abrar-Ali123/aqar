<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTranslation extends Model
{
    use HasFactory;
    protected $table = 'user_translations';
    protected $fillable = [
        'user_id', 'locale', 'name',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
