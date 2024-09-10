<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Aws\S3\S3Client;

class TrackController extends Controller
{
    /**
     * Gets audio and lyrics of the specified track
     * @param Request $request
     * @return json
     */
    public function getTrack(Request $request)
    {
        // Sets the S3 client
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
            // Specifies track's filename to get and the bucket where it is
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $request->input('src')
            ]);
            // Creates the presigned request with a limited time
            $response = $s3->createPresignedRequest($cmd, '+20 minutes');
            // Gets the presigned url of the audio track
            $presignedUrl = (string) $response->getUri();

            // Make the request to python container to get the lyrics of the track
            $lyricsResponse = Http::post('http://python:5000/get-lyrics', [
                'artist' => $request->input('artist'),
                'song' => $request->input('song'),
            ]);
            // Gets the response lyrics in LRC format
            $lyrics = $lyricsResponse->json();

            return response()->json(['url' => $presignedUrl, 'lyrics' => $lyrics['lyrics']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}