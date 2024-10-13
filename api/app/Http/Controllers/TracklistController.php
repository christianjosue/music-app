<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Tracklist;
use App\Models\TracklistTrack;
use App\Models\TracklistUser;
use App\Models\Thumbnail;
use App\Models\User;

class TracklistController extends Controller
{
    /**
     * Retrieves the tracklist by his id
     * @param int $id
     * @return json
     */
    public function tracklist(int $id) 
    {
        try {
            $tracklist = [];
            // Check if there is an id to look for
            if ($id != 0) {
                $tracklist = Tracklist::where('id', $id)
                    ->with(['tracks.artists', 'owners', 'followers', 'thumbnailImage'])
                    ->first();
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
                ->with(['tracklists.owners', 'tracklists.thumbnailImage'])
                ->first();

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
            // Creates a tracklist
            $tracklist = new Tracklist();
            $tracklist->name = $request->input('name');
            $tracklist->thumbnail = $request->input('thumbnail');
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

    /**
     * Edits the specified tracklist
     * @param $id
     * @return json
     */
    public function editTracklist(Request $request)
    {
        $success = false;
        if ($request->exists('id')) {
            $tracklist = Tracklist::where('id', $request->input('id'))->first();
            if ($tracklist instanceof Tracklist) {
                $tracklist->name = $request->input('name');
                $tracklist->thumbnail = $request->input('thumbnail');
                if ($tracklist->save()) {
                    $success = true;
                }
            }
        }

        return response()->json(['success' => $success]);
    }

    /**
     * Deletes the specified tracklist
     * @param $id
     * @return json
     */
    public function deleteTracklist($id)
    {
        $success = false;
        // Delete tracks of the tracklist
        $result = TracklistTrack::where('idTracklist', $id)->delete();
        // Delete the relationship between tracklist and user
        $result = TracklistUser::where('idTracklist', $id)->delete();
        if ($result > 0) {
            $result = Tracklist::where('id', $id)->delete();
            if ($result > 0) $success = true;
        }

        return response()->json(['success' => $success]);
    }
}
