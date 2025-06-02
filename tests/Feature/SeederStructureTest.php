<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederStructureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function seeders_do_not_depend_on_removed_columns()
    {
        $this->seed();
        $this->assertDatabaseMissing('users', ['name' => 'Test User']);
        $this->assertDatabaseMissing('facilities', ['name' => 'Test Facility']);
        $this->assertDatabaseMissing('categories', ['name' => 'Test Category']);
        $this->assertDatabaseMissing('units', ['name' => 'Test Unit']);
        $this->assertDatabaseMissing('permissions', ['name' => 'Test Permission']);
    }
}
