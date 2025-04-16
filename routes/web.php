<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;


// GET
Route::get('/api/hello', [HelloController::class, 'index']);


