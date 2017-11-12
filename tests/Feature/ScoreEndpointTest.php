<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ScoreEndpointTest extends TestCase
{
    public function testInvalidRequest()
    {
        $response = $this->get('/match/score');
        $response->assertStatus(400);
    }
}
