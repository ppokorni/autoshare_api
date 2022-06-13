<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * Test registration functionality with valid personal information.
     *
     * @return void
     */
    public function test_user_can_register_with_valid_information() {

        $userData = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => Str::random(10) . '@gmail.com',
            'password' => '666666',
        ];

        $response = $this->post(
            url('api/auth/register'),
            $userData
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

        //check if user exists
        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }
    /**
     * Test registration functionality with valid personal information.
     *
     * @return void
     */
    public function test_user_can_not_register_with_invalid_information() {

        $userData = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => Str::random(10) . '@gmail.com',
            'password' => '666666',
        ];

        $response = $this->post(
            url('api/auth/register'),
            $userData
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

        //check if user exists
        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }

    /**
     * Confirms that the user can not register twice with the same email (and other personal info).
     *
     * @return void
     */
    public function test_user_cannot_register_twice_with_the_same_email() {

        $userData = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => Str::random(10) . '@gmail.com',
            'password' => '666666',
        ];

        $firstResponse = $this->post(
            url('api/auth/register'),
            $userData
        );

        $secondResponse = $this->post(
            url('api/auth/register'),
            $userData
        );

        $firstResponse->assertStatus(200);

        //check if user exists
        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);

        $firstResponse->assertJsonStructure(
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

        $secondResponse->assertStatus(401);

        $secondResponse->assertJsonStructure(
            [
                "error"
            ]
        );

        $secondResponse->assertJsonFragment(
            [
                "error" => "User already exists"
            ]
        );
    }
}
