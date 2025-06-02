<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentTranslation extends Model
{
    public $timestamps = true;
    protected $fillable = ['content'];
}
