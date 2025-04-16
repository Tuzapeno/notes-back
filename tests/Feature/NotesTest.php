<?php

namespace Tests\Feature;

use App\Models\User;
use App\TokenAbility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_note_can_be_created(): void
    {
        $user = User::create([
            'username' => 'joaomosca',
            'email' => 'joaomosca@gmail.com',
            'password' => 'password09101910191019',
            'cpf' => '12345678900',
        ]);

        $noteRequest = [
            'user_id' => $user->id,
            'title' => 'Test Note',
            'description' => 'This is a test note.',
            'date' => now(),
            'initialColor' => '#FFFFFF',
            'lastEditDate' => now(),
        ];

        $token = $user->createToken('auth_token', [TokenAbility::ACCESS_API])->plainTextToken;

        $response = $this->withToken($token)->postJson('api/createNote', $noteRequest);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Note created successfully',
            ]);

        $this->assertDatabaseHas('notes', [
            'user_id' => $user->id,
            'title' => 'Test Note',
            'description' => 'This is a test note.',
        ]);
    }

    public function test_note_can_be_destroyed(): void {}
}
