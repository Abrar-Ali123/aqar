<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PropertyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create users with roles
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);

        $this->user = User::factory()->create();
        $this->user->assignRole($userRole);
    }

    public function test_admin_can_create_property()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.properties.store'), [
            'title' => 'Test Property',
            'price' => 1000000,
            'location' => 'Test Location',
            'type' => 'apartment',
            'status' => 'active',
            'translations' => [
                'ar' => ['title' => 'عقار تجريبي', 'description' => 'وصف تجريبي'],
                'en' => ['title' => 'Test Property', 'description' => 'Test Description']
            ],
            'images' => [
                UploadedFile::fake()->image('property1.jpg'),
                UploadedFile::fake()->image('property2.jpg')
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'title' => 'Test Property',
            'price' => 1000000
        ]);
    }

    public function test_user_cannot_access_admin_property_management()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.properties.index'));

        $response->assertForbidden();
    }

    public function test_can_update_property()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.properties.update', $property), [
            'title' => 'Updated Property',
            'price' => 2000000,
            'translations' => [
                'ar' => ['title' => 'عقار محدث', 'description' => 'وصف محدث'],
                'en' => ['title' => 'Updated Property', 'description' => 'Updated Description']
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'title' => 'Updated Property',
            'price' => 2000000
        ]);
    }

    public function test_can_delete_property()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.properties.destroy', $property));

        $response->assertRedirect();
        $this->assertSoftDeleted($property);
    }

    public function test_can_filter_properties()
    {
        Property::factory()->count(3)->create(['type' => 'apartment']);
        Property::factory()->count(2)->create(['type' => 'villa']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.properties.index', ['type' => 'apartment']));

        $response->assertOk();
        $response->assertViewHas('properties', function($properties) {
            return $properties->count() === 3;
        });
    }

    public function test_property_validation()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.properties.store'), []);

        $response->assertSessionHasErrors(['title', 'price', 'location', 'type']);
    }

    public function test_can_view_property_details()
    {
        $property = Property::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.properties.show', $property));

        $response->assertOk()
            ->assertViewIs('dashboard.properties.show')
            ->assertViewHas('property', $property);
    }

    public function test_audit_log_creation()
    {
        $property = Property::factory()->create();

        $this->actingAs($this->admin)
            ->put(route('admin.properties.update', $property), [
                'title' => 'Updated Title',
                'price' => 3000000
            ]);

        $this->assertDatabaseHas('audit_logs', [
            'model_type' => Property::class,
            'model_id' => $property->id,
            'action' => 'updated',
            'user_id' => $this->admin->id
        ]);
    }
}
