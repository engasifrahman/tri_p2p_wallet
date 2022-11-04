<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendMoneyTest extends TestCase
{
    public function test_the_initiate_send_money_endpoint_with_auth_returns_validation_errors()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money');

        $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "Validation failed!"
        ]);
    }

    public function test_the_initiate_send_money_endpoint_with_auth_returns_wallet_not_found_errors()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => "01310223344",
            "amount" => "2"
         ]);

        $response
        ->assertStatus(404)
        ->assertJson([
            "message" => "Receiver's wallet not found!"
        ]);
    }

    public function test_the_initiate_send_money_endpoint_with_auth_returns_you_can_not_send_money_to_your_own_wallet_errors()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => $user->phone,
            "amount" => "2"
         ]);

        $response
        ->assertStatus(400)
        ->assertJson([
            "message" => "You can not send money to your own wallet!"
        ]);
    }

    public function test_the_initiate_send_money_endpoint_with_auth_returns_receivers_wallet_currency_can_not_be_the_same_as_yours_errors()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01310520252',
            "amount" => "2"
         ]);

        $response
        ->assertStatus(400)
        ->assertJson([
            "message" => "Receiver's wallet currency can not be the same as yours!"
        ]);
    }

    public function test_the_initiate_send_money_endpoint_with_auth_returns_insufficient_balance_errors()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "1000.01"
         ]);

        $response
        ->assertStatus(400)
        ->assertJson([
            "message" => "Insufficient balance!"
        ]);
    }

    public function test_the_initiate_send_money_endpoint_with_auth_returns_transaction_initiated_successfully_response()
    {
        $user = User::find(1);

        $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "5"
         ]);

        $response
        ->assertOk()
        ->assertJson([
            "message" => "Transaction initiated successfully!"
        ]);
    }

    public function test_the_complete_send_money_endpoint_with_auth_returns_validation_errors()
    {
        $user = User::find(1);

        $response1 = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "5"
        ]);

        // dd($response1->original['data']['transaction_id']);

        $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/complete-send-money/'.$response1->original['data']['transaction_id']);

        $response
        ->assertStatus(422)
        ->assertJson([
            "message" => "Validation failed!"
        ]);
    }

    public function test_the_complete_send_money_endpoint_with_auth_returns_you_do_not_own_this_transaction_response()
    {
        $user1 = User::find(1);

        $response1 = $this->actingAs($user1, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "5"
        ]);

        // dd($response1->original['data']['transaction_id']);

        $user = User::find(2);

        $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/complete-send-money/'.$response1->original['data']['transaction_id'], [
            "status" => "cancel"
        ]);

        $response
        ->assertStatus(403)
        ->assertJson([
            "message" => "You do not own this transaction!"
        ]);
    }

    public function test_the_complete_send_money_endpoint_with_auth_returns_transaction_cancelled_response()
    {
        $user = User::find(1);

        $response1 = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "5"
        ]);

        // dd($response1->original['data']['transaction_id']);

        $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/complete-send-money/'.$response1->original['data']['transaction_id'], [
            "status" => "cancel"
        ]);

        $response
        ->assertStatus(200)
        ->assertJson([
            "message" => "Transaction cancelled!"
        ]);
    }

    public function test_the_complete_send_money_endpoint_with_auth_returns_transaction_successfull_response()
    {
        $user = User::find(1);

        $response1 = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "5"
        ]);

        // dd($response1->original['data']['transaction_id']);

        $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/complete-send-money/'.$response1->original['data']['transaction_id'], [
            "status" => "success"
        ]);

        $response
        ->assertStatus(200)
        ->assertJson([
            "message" => "Transaction successful!"
        ]);
    }

    public function test_the_complete_send_money_endpoint_with_auth_returns_transaction_already_completed_errors()
    {
        $user = User::find(1);

        $response1 = $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/initiate-send-money',  [
            "receiver_identity" => '01710445385',
            "amount" => "5"
        ]);

        // dd($response1->original['data']['transaction_id']);

        $response2 = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/complete-send-money/'.$response1->original['data']['transaction_id'], [
            "status" => "success"
        ]);

        $response = $this->actingAs($user, 'sanctum')
        ->putJson('/api/v1/complete-send-money/'.$response1->original['data']['transaction_id'], [
            "status" => "success"
        ]);

        $response
        ->assertStatus(400)
        ->assertJson([
            "message" => "This transaction already completed!"
        ]);
    }
}
