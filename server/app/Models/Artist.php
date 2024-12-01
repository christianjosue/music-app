<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'artist';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idArtist';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'thumbnail',
        'monthlyListeners'
    ];

    public function songs()
    {
        return $this->belongsToMany(Track::class, 'artist_track', 'idArtist', 'idTrack');
    }

    public function albums()
    {
        return $this->hasMany(Album::class, 'idArtist', 'idArtist');
    }
}
