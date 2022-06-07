<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Test login functionality with valid credentials.
     *
     * @return void
     */
    public function user_can_login_with_valid_credentials() {
        $password = bcrypt('666666');

        $user = User::factory()->create(
            [
                'password' => $password
            ]
        );

        $response = $this->post(
            route('api/auth/login'),
            [
                'email' => $user->email,
                'password' => $password,
            ]
        );
        $response->assertStatus(200);

        $response->assertJsonStructure(
            [
                "user" => [
                    "id",
                    "email",
                    "name",
                    "surname",
                    "date_of_birth",
                    "license_id",
                    "renter_avg_rating",
                    "rentee_avg_rating"
                ],
                "token"
            ]
        );

        $this->assertAuthenticatedAs($user);


    }
}
