<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Aws\S3\S3Client;

class TrackController extends Controller
{
    public function getTrack(Request $request)
    {
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        try {
            $cmd = $s3->getCommand('GetObject', [
                'Bucket' => env('AWS_BUCKET'),
                'Key'    => $request->input('src')
            ]);

            $response = $s3->createPresignedRequest($cmd, '+20 minutes');
            $presignedUrl = (string) $response->getUri();

            $lyricsResponse = Http::post('http://python:5000/get-lyrics', [
                'artist' => $request->input('artist'),
                'song' => $request->input('song'),
            ]);
            
            $lyrics = $lyricsResponse->json();

            return response()->json(['url' => $presignedUrl, 'lyrics' => $lyrics['lyrics']]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}