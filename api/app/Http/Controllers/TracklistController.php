<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Models\Tracklist;
use App\Models\User;
use App\Models\TracklistUser;

class TracklistController extends Controller
{
    /**
     * Retrieves the tracklist by his id
     * @param int $id
     * @return json
     */
    public function tracklist (int $id) 
    {
        try {
            $tracklist = [];
            // Check if there is an id to look for
            if ($id != 0) {
                $tracklist = Tracklist::where('id', $id)
                    ->with(['tracks.artists', 'owners', 'followers'])
                    ->first();
                // Once we got the tracklist, obtain thumbnail's url to display it on frontend
                $tracklistUrl = url(Storage::url($tracklist->thumbnail));
                $tracklist->thumbnail = $tracklistUrl;
            }
            
            return response()->json($tracklist);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieves all user's tracklists
     * @param int $id User's id
     * @return json
     */
    public function tracklists(int $id) 
    {
        try {
            $tracklists = User::where('id', $id)
                ->with(['tracklists.owners'])
                ->first();
            // Once we got the tracklists, obtain every thumbnail's url to display them on frontend
            foreach ($tracklists->tracklists as $tracklist) {
                $thumbnailUrl = url(Storage::url($tracklist->thumbnail));
                $tracklist->thumbnail = $thumbnailUrl;
            }

            return response()->json($tracklists);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Creates a new tracklist
     * @param Request $request
     * @return json
     */
    public function createTracklist(Request $request)
    {
        try {
            // Creates the new tracklist with the given request
            $request->validate([
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Puedes ajustar las reglas de validación según tus necesidades
            ]);
            // Store the thumbnail
            $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails');
            // Creates a tracklist
            $tracklist = new Tracklist();
            $tracklist->name = $request->get('name');
            $tracklist->thumbnail = $thumbnailPath;
            $tracklist->privacy = 0;
            $tracklist->created_by = 1;
            if ($tracklist->save()) {
                // If all goes on properly, we associated this tracklist to his user
                $tracklistUser = new TracklistUser();
                $tracklistUser->idUser = 1;
                $tracklistUser->idTracklist = $tracklist->id;
                $tracklistUser->owner = 1;
                if (!$tracklistUser->save()) {
                    $tracklist = null;
                }
            } else {
                $tracklist = null;
            }
            return response()->json(['tracklist' => $tracklist]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
