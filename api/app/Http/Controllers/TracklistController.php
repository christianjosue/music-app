<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function tracklists(int $id) {
        try {
            $tracklists = User::where('id', $id)
                ->with(['tracklists.owners'])
                ->first();

            return response()->json($tracklists);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
