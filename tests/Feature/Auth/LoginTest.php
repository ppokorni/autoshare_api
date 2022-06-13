<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase {

    /**
     * Test login functionality for an existing user with valid credentials.
     *
     * @return void
     */
    public function test_user_can_login_with_valid_credentials() {

        $password = '666666';

        $user = User::factory()->create(
            [
                'password' => Hash::make($password)
            ]
        );

        $response = $this->post(
            url('api/auth/login'),
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

    /**
     * Test login functionality for an existing user with invalid credentials.
     *
     * @return void
     */
    public function test_user_cannot_login_with_invalid_credentials() {
        $password = '666666';

        $user = User::factory()->create(
            [
                'password' => Hash::make($password)
            ]
        );

        $response = $this->post(
            url('api/auth/login'),
            [
                'email' => $user->email,
                'password' => Str::random(),
            ]
        );

        $response->assertStatus(401);

        $response->assertJsonStructure(
            [
                "error"
            ]
        );
    }

    /**
     * Test login with non-existent credentials.
     *
     * @return void
     */
    public function test_non_existent_user_cannot_login() {
        //fake email
        $email = Str::random() . '@email.com';

        $response = $this->post(
            url('api/auth/login'),
            [
                'email' => $email,
                'password' => Str::random(),
            ]
        );

        $response->assertStatus(401);

        $response->assertJsonStructure(
            [
                "error"
            ]
        );
    }
}
