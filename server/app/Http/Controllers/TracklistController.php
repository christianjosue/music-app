<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Models\Tracklist;
use App\Models\TracklistTrack;
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
                $tracklist = Tracklist::where('idTracklist', $id)
                    ->with(['tracks.artists', 'tracks.album', 'tracks.tracklistTrack', 'thumbnailImage'])
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
    public function tracklists() 
    {
        try {
            $tracklists = Tracklist::with(['thumbnailImage', 'tracks'])->get();

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
            // Retrieves number of created tracklists
            $tracklistsCreated = Tracklist::count();
            // Only creates the new tracklist if there are not six or more tracklists already in the db
            if ($tracklistsCreated < 6) {
                // Creates a tracklist
                $tracklist = new Tracklist();
                $tracklist->name = $request->input('name');
                $tracklist->thumbnail = $request->input('thumbnail');
                if (!$tracklist->save()) {
                    $tracklist = null;
                }
            } else {
                return response()->json(['limitError' => true]);
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
        if ($request->exists('idTracklist')) {
            $tracklist = Tracklist::where('idTracklist', $request->input('idTracklist'))->first();
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
        TracklistTrack::where('idTracklist', $id)->delete();
        // Delete tracklist
        $result = Tracklist::where('idTracklist', $id)->delete();
        if ($result > 0) $success = true;

        return response()->json(['success' => $success]);
    }

    /**
     * Adds a track to a tracklist
     * @param Request $request
     * @return json
     */
    public function addTrack(Request $request)
    {
        $success = false;
        if ($request->exists('idTrack') && $request->exists('idTracklist')) {
            $trackToAdd = TracklistTrack::firstOrCreate([
                'idTrack' => $request->input('idTrack'),
                'idTracklist' => $request->input('idTracklist')
            ]);
            if ($trackToAdd->save()) {
                $success = true;
            }
        }
        return response()->json(['success' => $success]);
    }

    /**
     * Removes a track from the tracklist
     * @param Request $request
     * @return json
     */
    public function removeTrack(Request $request)
    {
        $success = false;
        if ($request->exists('idTrack') && $request->exists('idTracklist')) {
            $result = TracklistTrack::where('idTrack', $request->input('idTrack'))
                ->where('idTracklist', $request->input('idTracklist'))
                ->delete();

            if ($result > 0) {
                $success = true;
            }
        }

        return response()->json(['success' => $success]);
    }
}
