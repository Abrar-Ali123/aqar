<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements TranslatableContract
{
    use HasFactory , Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'is_active', 'price', 'room', 'Space', 'bathroom', 'facility_id', 'category_id', 'employee_id', 'owner_id', 'product_type',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(City::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'product_feature', 'product_id', 'feature_id');
    }
}
