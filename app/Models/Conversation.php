<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'user_one',
        'user_two',
        'last_message_at'
    ];

    protected $casts = [
        'last_message_at' => 'datetime'
    ];

    /**
     * العلاقة مع المستخدم الأول
     */
    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    /**
     * العلاقة مع المستخدم الثاني
     */
    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    /**
     * العلاقة مع الرسائل
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * الحصول على آخر رسالة
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * الحصول على عدد الرسائل غير المقروءة
     */
    public function unreadCount($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * الحصول على المستخدم الآخر في المحادثة
     */
    public function otherUser($userId)
    {
        return $userId == $this->user_one ? $this->userTwo : $this->userOne;
    }
}
