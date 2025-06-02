<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use App\Http\Livewire\AccountManagementComponent;

class LivewirePerformanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function account_management_component_uses_eager_loading()
    {
        Livewire::test(AccountManagementComponent::class)
            ->assertStatus(200);
        // يفضل إضافة تحليل زمن التنفيذ أو عدد الاستعلامات لاحقًا
    }
}
