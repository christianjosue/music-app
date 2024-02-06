<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracks';

    protected $fillable = [
        'idAlbum',
        'title',
        'src',
        'thumbnail',
        'duration',
        'created_at'
    ];

    public function tracklists() {
        return $this->belongsToMany(Tracklist::class, 'tracklist_tracks', 'idTrack', 'idTracklist');
    }

    public function artists() {
        return $this->belongsToMany(User::class, 'user_tracks', 'idTrack', 'idArtist');
    }
}
