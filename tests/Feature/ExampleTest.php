<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testHomePageWithDevServer()
    {

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
