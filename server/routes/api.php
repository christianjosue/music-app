<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TracklistController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\ThumbnailController;
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
Route::delete('/removeTrack', [TracklistController::class, 'removeTrack']);
Route::delete('/tracklist/{id}', [TracklistController::class, 'deleteTracklist']);

Route::get('/getAudioTrack/{src}', [TrackController::class, 'getAudioTrack']);
Route::get('/search/all/{text}', [TrackController::class, 'search']);
Route::get('/search/{song}', [TrackController::class, 'searchSong']);
Route::get('/thumbnails', [ThumbnailController::class, 'getThumbnails']);
Route::get('/tracklist/{id}', [TracklistController::class, 'tracklist']);
Route::get('/tracklists', [TracklistController::class, 'tracklists']);

Route::post('/addTrack', [TracklistController::class, 'addTrack']);
Route::post('/getTrack', [TrackController::class, 'getTrack']);
Route::post('/tracklist', [TracklistController::class, 'createTracklist']);

Route::put('/tracklist', [TracklistController::class, 'editTracklist']);