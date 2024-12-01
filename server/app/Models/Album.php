<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idAlbum';

    protected $fillable = [
        'idArtist',
        'title',
        'cover'
    ];

    public $timestamps = true;

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'idArtist');
    }

    public function songs()
    {
        return $this->hasMany(Track::class, 'idAlbum', 'idAlbum');
    }
}
