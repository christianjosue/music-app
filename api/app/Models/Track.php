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

    protected $fillable = [
        'title',
        'src',
        'thumbnail'
    ];

    public function tracklists() {
        return $this->belongsToMany(Tracklist::class, 'track_tracklist', 'idTrack', 'idTracklist');
    }
}
