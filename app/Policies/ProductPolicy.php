<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Product $product): bool
    {
        // Anyone can view an active product. Inactive products only visible to owner/admin.
        if ($product->is_active) {
            return true;
        }

        return $user && (($user->id === $product->owner_user_id) || $user->hasRole(['admin', 'moderator']));
    }

    public function create(User $user): bool
    {
        return $user->can('create_product');
    }

    public function update(User $user, Product $product): bool
    {
        // Allow if the user is the owner and has permission, or is an admin/moderator.
        return ($user->id === $product->owner_user_id && $user->can('update_product')) || $user->hasRole(['admin', 'moderator']);
    }

    public function delete(User $user, Product $product): bool
    {
        // Allow if the user is the owner and has permission, or is an admin/moderator.
        return ($user->id === $product->owner_user_id && $user->can('delete_product')) || $user->hasRole(['admin', 'moderator']);
    }
}
