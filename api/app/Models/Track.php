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
    protected $table = 'track';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idTrack';

    protected $fillable = [
        'idAlbum',
        'title',
        'src',
        'thumbnail',
        'duration'
    ];

    public function tracklists() {
        return $this->belongsToMany(Tracklist::class, 'tracklist_track', 'idTrack', 'idTracklist');
    }

    public function artists() {
        return $this->belongsToMany(Artist::class, 'artist_track', 'idTrack', 'idArtist');
    }

    public function album() {
        return $this->belongsTo(Album::class, 'idAlbum');
    }
}
