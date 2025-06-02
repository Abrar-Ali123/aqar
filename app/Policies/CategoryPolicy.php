<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;

class CategoryPolicy
{
    public function view(User $user, Category $category)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->hasPermission('create_category');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasPermission('update_category');
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasPermission('delete_category');
    }
}
