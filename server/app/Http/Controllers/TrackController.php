<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
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

    /**
     * Search songs, artists and albums by the given text
     * @param String $text
     * @return json
     */
    public function search($text)
    {
        // Retrieve the first 5 songs that match with the written text
        $songs = Track::where('title', 'LIKE', '%' . $text . '%')
            ->with([
                'artists', 
                'tracklists', 
                'album',
                'artists.songs',
                'album.songs',
                'album.artist'
            ])
            ->take(5)
            ->get();

        // Retrieve the first 4 artists that match with the written text
        $artists = Artist::where('name', 'LIKE', '%' . $text . '%')
            ->with([
                'songs', 
                'songs.artists', 
                'songs.album', 
                'albums',
                'albums.artist',
                'albums.songs'
            ])
            ->take(4)
            ->get();
                
        // Retrieve the first 4 albums that match with the written text
        $albums = Album::where('title', 'LIKE', '%' . $text . '%')
            ->with([
                'artist',
                'artist.songs',
                'songs',
                'songs.artists'
            ])
            ->take(4)
            ->get();
        
        // Given the text searched, pick up the most relevant album, artist and song
        $album = $albums->first();
        $artist = $artists->first();
        $song = $songs->first();

        // Songs related to the most relevant artist or album
        $relatedAlbumSongs = $album?->songs;
        $relatedArtistSongs = $artist?->songs;

        // Artists related to the most relevant song or album
        $relatedSongArtists = $song?->artists;
        $relatedAlbumArtist = $album?->artist;
        // Albums related to the most relevant artist or song
        $relatedArtistAlbums = $artist?->albums;
        $relatedSongAlbum = $song?->album;
        
        // Merge songs
        $songs = $relatedAlbumSongs != null ? $songs->merge($relatedAlbumSongs)->unique('idTrack') : $songs;
        $songs = $relatedArtistSongs != null ? $songs->merge($relatedArtistSongs)->unique('idTrack') : $songs;
        // Merge artists
        $artists = $relatedSongArtists != null ? $artists->merge($relatedSongArtists)->unique('idArtist') : $artists;
        $artists = $relatedAlbumArtist != null ? $artists->merge(collect([$relatedAlbumArtist]))->unique('idArtist') : $artists;
        // Merge albums
        $albums = $relatedArtistAlbums != null ? $albums->merge($relatedArtistAlbums)->unique('idAlbum') : $albums;
        $albums = $relatedSongAlbum != null ? $albums->merge(collect([$relatedSongAlbum]))->unique('idAlbum') : $albums;

        return response()->json([
            'songs' => $songs,
            'albums' => $albums,
            'artists' => $artists
        ]);
    }

    /**
     * Retrieves songs, artists and albums to show in the Search view when no search has been made
     * @return json 
     */
    public function getInitialDataSearch()
    {
        // Retrieve the first 5 songs that match with the written text
        $songs = Track::with([
                'artists', 
                'tracklists', 
                'album',
                'artists.songs',
                'album.songs',
                'album.artist'
            ])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Retrieve the first 4 artists that match with the written text
        $artists = Artist::with([
                'songs', 
                'songs.artists', 
                'songs.album', 
                'albums',
                'albums.artist',
                'albums.songs'
            ])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
                
        // Retrieve the first 4 albums that match with the written text
        $albums = Album::with([
                'artist',
                'artist.songs',
                'songs',
                'songs.artists'
            ])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return response()->json([
            'songs' => $songs,
            'albums' => $albums,
            'artists' => $artists
        ]);
    }
}