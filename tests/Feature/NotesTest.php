<?php

namespace Tests\Feature;

use App\Models\Note;
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

    public function test_note_can_be_destroyed(): void
    {
        $user = User::factory()->createOne();
        $note = Note::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('auth_token', [TokenAbility::ACCESS_API])->plainTextToken;
        $request = [
            'id' => $note->id,
        ];

        $response = $this->withToken($token)->postJson('api/destroyNote/', $request);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Note deleted successfully',
            ]);
    }

    public function test_note_can_be_updated(): void
    {
        $user = User::factory()->createOne();
        $note = Note::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('auth_token', [TokenAbility::ACCESS_API])->plainTextToken;
        $request = [
            'id' => $note->id,
            'title' => 'Updated Note',
            'description' => 'This is an updated note.',
            'date' => now(),
            'initialColor' => '#FFFFFF',
            'lastEditDate' => now(),
        ];

        $response = $this->withToken($token)->postJson('api/updateNote/', $request);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Note updated successfully',
            ]);
    }

    public function test_notes_can_be_retrieved(): void
    {
        $user = User::factory()->createOne();
        $note = Note::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('auth_token', [TokenAbility::ACCESS_API])->plainTextToken;

        $response = $this->withToken($token)->postJson('api/getNotes/', ['user_id' => $user->id]);

        $response->assertStatus(200)
            ->assertJson([
                'notes' => [
                    [
                        'id' => $note->id,
                        'user_id' => $user->id,
                        'title' => $note->title,
                        'description' => $note->description,
                        'date' => $note->date,
                        'initialColor' => $note->initialColor,
                        'lastEditDate' => $note->lastEditDate,
                    ],
                ],
            ]);
    }

    public function test_notes_can_be_saved(): void
    {
        $user = User::factory()->createOne();
        $note = Note::factory()->createOne([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('auth_token', [TokenAbility::ACCESS_API])->plainTextToken;

        $request = [
            'user_id' => $user->id,
            'notes' => [
                [
                    'id' => $note->id,
                    'title' => 'Updated Note',
                    'description' => 'This is an updated note.',
                    'date' => now(),
                    'initialColor' => '#FFFFFF',
                    'lastEditDate' => now(),
                ],
            ],
        ];

        $response = $this->withToken($token)->postJson('api/saveNotes/', $request);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Notes saved successfully',
            ]);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Note',
            'description' => 'This is an updated note.',
        ]);
    }
}
