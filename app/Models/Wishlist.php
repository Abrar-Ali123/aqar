<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function addItem($item, ?string $notes = null): WishlistItem
    {
        return $this->items()->create([
            'notes' => $notes,
            'wishlistable_type' => get_class($item),
            'wishlistable_id' => $item->id
        ]);
    }

    public function removeItem($item): bool
    {
        return $this->items()
            ->where('wishlistable_type', get_class($item))
            ->where('wishlistable_id', $item->id)
            ->delete();
    }

    public function hasItem($item): bool
    {
        return $this->items()
            ->where('wishlistable_type', get_class($item))
            ->where('wishlistable_id', $item->id)
            ->exists();
    }
}
