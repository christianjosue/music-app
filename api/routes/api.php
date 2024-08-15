<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TracklistController;
use App\Http\Controllers\TrackController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/tracklist/{id}', [TracklistController::class, 'tracklist']);
Route::get('/tracklists/{id}', [TracklistController::class, 'tracklists']);
Route::get('/audio/{audio}', [TrackController::class, 'getAudio']);