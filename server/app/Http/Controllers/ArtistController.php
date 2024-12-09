<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;

class ArtistController extends Controller
{
    /**
     * Retrieve the artist, his songs and albums by the given id
     * @param integer $idArtist
     * @return json
     */
    public function artist($idArtist)
    {
        $success = false;
        // Retrieve the artist
        $artist = Artist::where('idArtist', $idArtist)
            ->with(['songs', 'albums'])    
            ->first();
        // If the artist exists, returns a success, otherwhise returns an error
        if ($artist instanceof Artist) {
            $success = true;
        }
        return response()->json(['success' => true, 'artist' => $artist]);
    }
}
