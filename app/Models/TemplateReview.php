<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateReview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'template_id',
        'user_id',
        'rating',
        'title',
        'content',
        'pros',
        'cons',
        'is_verified',
        'is_featured'
    ];

    protected $casts = [
        'rating' => 'float',
        'pros' => 'array',
        'cons' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean'
    ];

    public function template()
    {
        return $this->belongsTo(PageTemplate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(TemplateReviewReply::class);
    }

    public function votes()
    {
        return $this->hasMany(TemplateReviewVote::class);
    }

    public function getHelpfulVotesCount()
    {
        return $this->votes()->where('is_helpful', true)->count();
    }

    public function getUnhelpfulVotesCount()
    {
        return $this->votes()->where('is_helpful', false)->count();
    }
}
