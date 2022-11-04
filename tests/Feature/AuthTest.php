<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_the_registration_endpoint_returns_validation_errors()
    {
        $response = $this->postJson('/api/v1/registration');

        $response
            ->assertStatus(422)
            ->assertJson([
                "message" => "Validation failed!"
            ]);
    }

    // public function test_the_registration_endpoint_returns_registration_successful_response()
    // {
    //     $response = $this->postJson('/api/v1/registration',  [
    //         "name" => "Yasha",
    //         "email" => "yasha@gmail.com",
    //         "phone" => "01810241412",
    //         "currency" => "EUR",
    //         "password" => "12345678",
    //         "password_confirmation" => "12345678"
    //      ]);

    //     $response
    //         ->assertOk()
    //         ->assertJson([
    //             "message" => "Registration successfull!"
    //         ]);
    // }
    
    public function test_the_login_endpoint_returns_email_and_password_validation_errors()
    {
        $response = $this->postJson('/api/v1/login');

        $response
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The email field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ]
                ]
            ]);
    }

    public function test_the_login_endpoint_returns_an_incorrect_credentials_errors()
    {
        $response = $this->postJson('/api/v1/login', ['email' => 'xyz@gmail.com', 'password' => 12345678]);

        $response
            ->assertStatus(422)
            ->assertJson([
                "errors" => [
                    "email" => [
                        "The provided credentials are incorrect."
                    ]
                ]
            ]);
    }

    public function test_the_login_endpoint_returns_login_successful_response()
    {
        $response = $this->postJson('/api/v1/login', ['email' => 'eng.asifrahman@gmail.com', 'password' => 12345678]);

        $response
            ->assertOk()
            ->assertJson([
                "message" => "Login successfull!"
            ]);
    }
}
