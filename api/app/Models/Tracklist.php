<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracklist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracklists';

    protected $fillable = [
        'name',
        'thumbnail'
    ];

    public function tracks() {
        return $this->belongsToMany(Track::class, 'tracklist_tracks', 'idTracklist', 'idTrack');
    }

    public function owners() {
        return $this->belongsToMany(User::class, 'user_tracklists', 'idTracklist', 'idUser')->wherePivot('owner', 1);
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'user_tracklists', 'idTracklist', 'idUser')->wherePivot('owner', 0);
    }
}
