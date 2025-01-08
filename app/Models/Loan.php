<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['agency'];

    protected $fillable = [
        'applicant',
        'manager',
        'bank_emp',
        'bank_id',
        'birth',
        'salary',
        'commitments',
        'military',
        'rank',
        'employment',
        'supported',
    ];

    public function applicantUser()
    {
        return $this->belongsTo(User::class, 'applicant');
    }

    public function managerUser()
    {
        return $this->belongsTo(User::class, 'manager');
    }

    public function bankEmployee()
    {
        return $this->belongsTo(User::class, 'bank_emp');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
