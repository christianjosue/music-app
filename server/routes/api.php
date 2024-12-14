<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ThumbnailController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TracklistController;
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

Route::delete('/removeTrack', [TracklistController::class, 'removeTrack']);
Route::delete('/tracklist/{id}', [TracklistController::class, 'deleteTracklist']);

Route::get('/getAudioTrack/{src}', [TrackController::class, 'getAudioTrack']);
Route::get('/search/all/{text}', [TrackController::class, 'search']);
Route::get('/search/initial', [TrackController::class, 'getInitialDataSearch']);
Route::get('/search/{song}', [TrackController::class, 'searchSong']);
Route::get('/thumbnails', [ThumbnailController::class, 'getThumbnails']);
Route::get('/tracklist/{id}', [TracklistController::class, 'tracklist']);
Route::get('/tracklists', [TracklistController::class, 'tracklists']);
Route::get('/artist/{id}', [ArtistController::class, 'artist']);
Route::get('/album/{id}', [AlbumController::class, 'album']);

Route::post('/addTrack', [TracklistController::class, 'addTrack']);
Route::post('/getTrack', [TrackController::class, 'getTrack']);
Route::post('/tracklist', [TracklistController::class, 'createTracklist']);

Route::put('/tracklist', [TracklistController::class, 'editTracklist']);