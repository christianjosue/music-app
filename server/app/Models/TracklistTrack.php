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
}
