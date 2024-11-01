<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

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

    public function translations()
    {
        return $this->hasMany(LoanTranslation::class);
    }
}
