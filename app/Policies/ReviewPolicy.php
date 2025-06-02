<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * تحديد ما إذا كان المستخدم يمكنه حذف التقييم
     */
    public function delete(User $user, Review $review): bool
    {
        // يمكن للمستخدم حذف تقييمه الخاص أو إذا كان مشرفاً
        return $user->id === $review->user_id || $user->is_admin;
    }

    /**
     * تحديد ما إذا كان المستخدم يمكنه الموافقة على التقييم
     */
    public function approve(User $user, Review $review): bool
    {
        // فقط المشرفون يمكنهم الموافقة على التقييمات
        return $user->is_admin;
    }
}
