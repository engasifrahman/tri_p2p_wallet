<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
{
    public function test_the_transactions_endpoint_with_auth_returns_ok_response()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/transactions');

        $response->assertOk();
    }

    public function test_the_sent_money_transactions_endpoint_with_auth_returns_ok_response()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/sent-money-transactions');

        $response->assertOk();
    }

    public function test_the_received_money_transactions_endpoint_with_auth_returns_ok_response()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/received-money-transactions');
        
        $response->assertOk();
    }
}
