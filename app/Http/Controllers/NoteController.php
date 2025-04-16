<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNoteRequest;
use App\Models\Note;

class NoteController extends Controller
{
    public function createNote(CreateNoteRequest $request)
    {
        $request->validated();

        Note::create([
            'user_id' => $request->input('user_id'),
            'id' => $request->input('id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'initialColor' => $request->input('initialColor'),
            'lastEditDate' => $request->input('lastEditDate'),
        ]);

        return response()->json(['message' => 'Note created successfully'], 201);
    }
}
