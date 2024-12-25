<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracklistTrack extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tracklist_track';

    protected $fillable = [
        'idTracklist',
        'idTrack'
    ];

    public $timestamps = true;

    public function track()
    {
        return $this->belongsTo(Track::class, 'idTrack', 'idTrack');
    }

    public function tracklist()
    {
        return $this->belongsTo(Tracklist::class, 'idTracklist', 'idTracklist');
    }
}
