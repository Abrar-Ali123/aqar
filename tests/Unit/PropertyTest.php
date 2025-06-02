<?php

namespace Tests\Unit;

use App\Models\Property;
use App\Models\User;
use App\Models\Attribute;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_property()
    {
        $user = User::factory()->create();
        $attributes = Attribute::factory()->count(3)->create();

        $property = Property::factory()->create([
            'user_id' => $user->id
        ]);

        $property->attributes()->attach($attributes->pluck('id'));

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'user_id' => $user->id
        ]);

        $this->assertEquals(3, $property->attributes()->count());
    }

    public function test_property_belongs_to_user()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($property->owner->is($user));
    }

    public function test_can_update_property()
    {
        $property = Property::factory()->create();
        $newTitle = 'Updated Title';

        $property->update(['title' => $newTitle]);

        $this->assertEquals($newTitle, $property->fresh()->title);
    }

    public function test_can_delete_property()
    {
        $property = Property::factory()->create();
        $propertyId = $property->id;

        $property->delete();

        $this->assertSoftDeleted('properties', ['id' => $propertyId]);
    }

    public function test_can_filter_properties()
    {
        Property::factory()->count(3)->create(['status' => 'active']);
        Property::factory()->count(2)->create(['status' => 'inactive']);

        $activeProperties = Property::where('status', 'active')->get();
        $inactiveProperties = Property::where('status', 'inactive')->get();

        $this->assertEquals(3, $activeProperties->count());
        $this->assertEquals(2, $inactiveProperties->count());
    }

    public function test_property_has_translations()
    {
        $property = Property::factory()->create([
            'translations' => [
                'ar' => ['title' => 'عنوان عربي', 'description' => 'وصف عربي'],
                'en' => ['title' => 'English Title', 'description' => 'English Description']
            ]
        ]);

        $this->assertEquals('عنوان عربي', $property->translations['ar']['title']);
        $this->assertEquals('English Title', $property->translations['en']['title']);
    }
}
