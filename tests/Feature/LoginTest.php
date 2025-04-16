<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_credentials_doesnt_exist(): void
    {
        $fields = [
            'email' => 'lararilarara@gmail.com',
            'password' => 'password09101910191019',
        ];

        $response = $this->postJson('api/login', $fields);

        $response->assertStatus(422)->assertJson([
            'errors' => [
                'email' => ['The selected email is invalid.'],
            ],
        ]);
    }

    public function test_credentials_exists(): void
    {
        $user = User::factory()->createOne();
        $structure = array_keys(User::factory()->definition());
        $structure = array_diff($structure, ['password', 'remember_token', 'created_at', 'updated_at']);

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => $structure,
                'token',
            ])
            ->assertCookie('refresh_token');
    }

    public function test_refresh_user_token(): void
    {
        $user = User::factory()->createOne();

        $loginResponse = $this->postJson('api/login', ['email' => $user->email, 'password' => 'password'])
            ->assertStatus(200)
            ->assertCookie('refresh_token');

        $cookie_value = $loginResponse->getCookie('refresh_token', decrypt: false)->getValue();

        $this->withToken($cookie_value)->getJson('api/refresh_token')
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'refresh_token',
            ]);
    }
}
