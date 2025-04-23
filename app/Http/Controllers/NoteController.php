<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNoteRequest;
use App\Http\Requests\GetNotesRequest;
use App\Http\Requests\SaveNotesRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\Request;

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

    public function destroyNote(Request $id)
    {
        $note = Note::find($id)->first();

        if (! $note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully'], 200);
    }

    public function updateNote(UpdateNoteRequest $request)
    {
        $request->validated();

        $note = Note::find($request->input('id'));

        if (! $note) {
            return response()->json(['message' => 'Note not found'], 404);
        }

        $note->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'date' => $request->input('date'),
            'initialColor' => $request->input('initialColor'),
            'lastEditDate' => $request->input('lastEditDate'),
        ]);

        return response()->json(['message' => 'Note updated successfully'], 200);
    }

    public function getNotes(GetNotesRequest $request)
    {
        $request->validated();

        $notes = Note::where('user_id', $request['user_id'])->get();

        if ($notes->isEmpty()) {
            return response()->json(['message' => 'No notes found'], 404);
        }

        return response()->json(['notes' => $notes], 200);
    }

    public function saveNotes(SaveNotesRequest $request)
    {
        $request->validated();

        $notes = $request->input('notes');
        $userId = $request->user()->id;

        // Extract IDs from the request notes
        $noteIds = collect($notes)->pluck('id')->filter()->toArray();

        // Delete notes that are not in the request (they've been removed)
        Note::where('user_id', $userId)->whereNotIn('id', $noteIds)->delete();

        // Update existing notes and create new ones
        foreach ($notes as $noteData) {
            // Update existing note
            Note::updateOrCreate(
                ['id' => $noteData['id'], 'user_id' => $userId],
                [
                    'title' => $noteData['title'] ?? null,
                    'description' => $noteData['description'] ?? null,
                    'date' => $noteData['date'] ?? null,
                    'initialColor' => $noteData['initialColor'] ?? null,
                    'lastEditDate' => $noteData['lastEditDate'] ?? null,
                ]
            );
        }

        return response()->json(['message' => 'Notes saved successfully'], 200);
    }
}
