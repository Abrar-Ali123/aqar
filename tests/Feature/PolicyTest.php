<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class PolicyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_with_permission_can_create_product()
    {
        $user = User::factory()->create();
        $user->permissions()->create(['name' => 'create_product']);
        $this->actingAs($user);
        $product = Product::factory()->make();
        $this->assertTrue($user->can('create', $product));
    }
}
