<?php

use App\Http\Controllers\AuthController;
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

// POST
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/createNote', [NoteController::class, 'createNote'])
    ->middleware('auth:sanctum', 'ability:'.TokenAbility::ACCESS_API->value);
