<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FacilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * اختبار إنشاء منشأة جديدة
     *
     * @return void
     */
    public function test_can_create_facility()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->postJson('/api/facilities', [
            'name' => 'Test Facility',
            'type_id' => 1,
            'description' => 'Test Description'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'id',
                         'name',
                         'type_id',
                         'description',
                         'created_at'
                     ]
                 ]);

        $this->assertDatabaseHas('facilities', [
            'name' => 'Test Facility'
        ]);
    }

    /**
     * اختبار تحديث منشأة
     *
     * @return void
     */
    public function test_can_update_facility()
    {
        $user = User::factory()->create();
        $facility = Facility::factory()->create();

        $response = $this->actingAs($user)->putJson("/api/facilities/{$facility->id}", [
            'name' => 'Updated Facility'
        ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'name' => 'Updated Facility'
        ]);
    }

    /**
     * اختبار حذف منشأة
     *
     * @return void
     */
    public function test_can_delete_facility()
    {
        $user = User::factory()->create();
        $facility = Facility::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/api/facilities/{$facility->id}");

        $response->assertStatus(200);
        
        $this->assertDatabaseMissing('facilities', [
            'id' => $facility->id
        ]);
    }
}
