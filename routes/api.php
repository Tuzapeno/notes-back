<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\NoteController;
use App\TokenAbility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// GET
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum', 'ability:'.TokenAbility::ISSUE_ACCESS_TOKEN->value)->group(function () {
    Route::get('/refresh_token', [AuthController::class, 'refreshToken']);
});

Route::get('/hello', [HelloController::class, 'index']);

// POST
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);

Route::post('/createNote', [NoteController::class, 'createNote'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);

Route::post('/destroyNote', [NoteController::class, 'destroyNote'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);

Route::post('/updateNote', [NoteController::class, 'updateNote'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);

Route::post('/getNotes', [NoteController::class, 'getNotes'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);

Route::post('/saveNotes', [NoteController::class, 'saveNotes'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);
