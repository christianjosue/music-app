<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Http;

class TrackService
{
    protected $s3;

    /**
     * Initialize s3 client
     */
    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Retrieve song's audio
     * @param string $src
     * @return string $presignedUrl
     */
    public function getAudioUrl($src)
    {
        // Get the audio object from AWS S3 Bucket
        $cmd = $this->s3->getCommand('GetObject', [
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => $src
        ]);

        // Create a presigned url from the audio object
        $response = $this->s3->createPresignedRequest($cmd, '+20 minutes');
        return (string) $response->getUri();
    }

    /**
     * Get song's lyrics
     * @param string $artist
     * @param string $song
     * @return json
     */
    public function getLyrics($artist, $song)
    {
        // Request the song lyrics to python server
        $lyricsResponse = Http::post('http://python:5000/get-lyrics', [
            'artist' => $artist,
            'song' => $song,
        ]);

        return $lyricsResponse->json();
    }
}