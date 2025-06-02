<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasTranslations, SoftDeletes;

    protected $fillable = [
        'bank_id',
        'interest_rate',
        'min_amount',
        'max_amount',
        'min_duration',
        'max_duration',
        'is_active',
        'translations'
    ];

    protected $casts = [
        'interest_rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'min_duration' => 'integer',
        'max_duration' => 'integer',
        'is_active' => 'boolean',
        'translations' => 'array'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function applications()
    {
        return $this->hasMany(LoanApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForBank($query, $bankId)
    {
        return $query->where('bank_id', $bankId);
    }

    public function scopeInAmountRange($query, $amount)
    {
        return $query->where('min_amount', '<=', $amount)
            ->where('max_amount', '>=', $amount);
    }

    public function scopeInDurationRange($query, $duration)
    {
        return $query->where('min_duration', '<=', $duration)
            ->where('max_duration', '>=', $duration);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isAmountInRange($amount)
    {
        return $amount >= $this->min_amount && $amount <= $this->max_amount;
    }

    public function isDurationInRange($duration)
    {
        return $duration >= $this->min_duration && $duration <= $this->max_duration;
    }

    public function calculateMonthlyPayment($amount, $duration)
    {
        $monthlyInterestRate = $this->interest_rate / 12 / 100;
        return $amount * $monthlyInterestRate * pow(1 + $monthlyInterestRate, $duration)
            / (pow(1 + $monthlyInterestRate, $duration) - 1);
    }
}
