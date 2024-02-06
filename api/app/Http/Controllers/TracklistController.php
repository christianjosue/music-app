<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tracklist;
use App\Models\User;

class TracklistController extends Controller
{
    public function tracklist (int $id) {
        try {
            $tracklist = Tracklist::where('id', $id)
                ->with(['tracks.artists', 'owners', 'followers'])
                ->first();
            
            return response()->json($tracklist);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
