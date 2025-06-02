<?php

namespace Tests\Feature;

use Tests\TestCase;

class LanguagesConfigTest extends TestCase
{
    /** @test */
    public function languages_config_is_accessible_and_editable()
    {
        $config = config('languages');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('required', $config);
        $this->assertContains('ar', $config['required']);
    }
}
