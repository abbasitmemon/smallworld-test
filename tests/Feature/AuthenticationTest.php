<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    public function test_successful_user_registration()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'user@123',
            'password_confirmation' => 'user@123'
        ];
        $response = $this->postJson('/api/signup', $userData);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user',
                    'token'
                ],
            ]);
    }

    public function test_invalid_user_registration()
    {
        $userData = [
            'name' => 'John Doe',
            // Missing email ,password and password_confirmation
        ];
        $response = $this->postJson('/api/signup', $userData);
        $response->assertStatus(422);
    }

    public function test_successful_user_login()
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => Carbon::now(),
            'status' => User::ACTIVE,

        ]);
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'secret123',
        ];
        $response = $this->postJson('/api/login', $loginData);
        $response->assertStatus(200);
    }

    public function test_invalid_user_login()
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,

        ]);
        $loginData = [
            'email' => 'johndoe@example.com',
            'password' => 'incorrectPassword',
        ];
        $response = $this->postJson('/api/login', $loginData);
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid Credentials',
                'data' => [],
            ]);
    }

    public function test_logout()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret123'),
            'email_verified_at' => "2023-07-07T15:09:51.000000Z",
            'status' => User::ACTIVE,

        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);

        // Additional assertions to ensure the token is invalidated or removed from storage
    }
}
