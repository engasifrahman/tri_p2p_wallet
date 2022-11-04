<?php

namespace Tests\Feature;

use Tests\TestCase;
use app\models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatisticsTest extends TestCase
{
    public function test_the_statistics_endpoint_with_auth_returns_ok_response()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/statistics');
        
        $response->assertOk();
    }
}
