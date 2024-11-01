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

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idTracklist';

    protected $fillable = [
        'name',
        'thumbnail'
    ];

    public function tracks() {
        return $this->belongsToMany(Track::class, 'tracklist_track', 'idTracklist', 'idTrack');
    }

    public function owners() {
        return $this->belongsToMany(User::class, 'user_tracklists', 'idTracklist', 'idUser')->wherePivot('owner', 1);
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'user_tracklists', 'idTracklist', 'idUser')->wherePivot('owner', 0);
    }

    public function thumbnailImage() {
        return $this->hasOne(Thumbnail::class, 'idThumbnail', 'thumbnail');
    }
}
