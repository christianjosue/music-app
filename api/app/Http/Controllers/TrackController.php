<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Services\TrackService;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    protected $trackService;

    public function __construct(TrackService $trackService)
    {
        $this->trackService = $trackService;
    }
    /**
     * Gets audio and lyrics of the specified track
     * @param Request $request
     * @return json
     */
    public function getTrack(Request $request)
    {
        try {
            // Get the presigned url of the audio track
            $presignedUrl = $this->trackService->getAudioUrl($request->input('src'));

            // If we don't have song's lyrics, we request it
            if ($request->input('lyrics') == "") {
                $lyrics = $this->trackService->getLyrics($request->input('artist'), $request->input('song'));
            } else {
                $lyrics['lyrics'] = $request->input('lyrics');
            }

            return response()->json(['url' => $presignedUrl, 'lyrics' => $lyrics['lyrics']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get only the song's audio 
     * @param string $src
     * @return json $response
     */
    public function getAudioTrack($src)
    {
        return response()->json(['audioUrl' => $this->trackService->getAudioUrl($src)]);
    }

    /**
     * Search songs for the given string and returns songs may coincide with it
     * @param String $song
     * @return $songs
     */
    public function searchSong($song)
    {
        $songs = Track::where('title', 'LIKE', '%' . $song . '%')
            ->with(['artists', 'tracklists'])
            ->orderBy('title', 'ASC')
            ->take(10)
            ->get();
        
        return $songs;
    }
}