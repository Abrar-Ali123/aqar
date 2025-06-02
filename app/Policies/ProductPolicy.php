<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function view(User $user, Product $product)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_product');
    }

    public function update(User $user, Product $product)
    {
        return $user->hasPermission('update_product');
    }

    public function delete(User $user, Product $product)
    {
        return $user->hasPermission('delete_product');
    }
}
