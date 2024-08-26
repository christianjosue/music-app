<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class TrackController extends Controller
{
    public function getAudio($audio)
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
                'Key'    => $audio
            ]);

            $request = $s3->createPresignedRequest($cmd, '+20 minutes');
            $presignedUrl = (string) $request->getUri();

            $lyrics = file_get_contents('http://python:5000/get-lyrics');

            return response()->json(['url' => $presignedUrl, 'lyrics' => $lyrics]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}