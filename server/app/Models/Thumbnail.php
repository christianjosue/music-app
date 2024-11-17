<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'thumbnail';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'idThumbnail';

    protected $fillable = [
        'src'
    ];

    public $timestamps = false;

}
