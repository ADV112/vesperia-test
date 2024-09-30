<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'index']);
Route::get('form', [APIController::class, 'allForm']);

// Route::middleware('auth:sanctum')->group(function() {
//     Route::get('logout', [AuthController::class, 'logout']);
//     Route::get('user', fn() => auth()->user());
// });
