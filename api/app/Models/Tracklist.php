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
    protected $table = 'tracklist';

    protected $fillable = [
        'name',
        'thumbnail'
    ];

    public function tracks() {
        return $this->belongsToMany(Track::class, 'track_tracklist', 'idTracklist', 'idTrack');
    }
}
