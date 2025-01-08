<?php
namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'color',
        'icon',
        'table_name',
    ];

 
    public function statusables()
    {
        return $this->morphedByMany(Status::class, 'statusable');
    }
}
