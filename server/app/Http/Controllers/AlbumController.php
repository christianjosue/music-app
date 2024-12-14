<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;

class AlbumController extends Controller
{
    /**
     * Retrieve the album, his artist and songs by the given id
     * @param integer $idArtist
     * @return json
     */
    public function album($idAlbum)
    {
        $success = false;
        // Retrieve the album
        $album = Album::where('idAlbum', $idAlbum)
            ->with(['songs', 'artist', 'songs.album', 'songs.artists'])
            ->first();
        // If the album exists, returns a success, otherwhise returns an error
        if ($album instanceof Album) {
            $success = true;
        }
        return response()->json(['success' => true, 'album' => $album]);
    }
}
