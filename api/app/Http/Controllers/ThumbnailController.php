<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thumbnail;

class ThumbnailController extends Controller
{
    /**
     * Retrieves all thumbnails
     * @return json $thumbnails
     */
    public function getThumbnails()
    {
        $thumbnails = Thumbnail::all();
        return $thumbnails;
    }
}
