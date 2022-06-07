<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

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
}
