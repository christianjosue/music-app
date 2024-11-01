<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Album;

class AlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Album::create([
            'idArtist' => 1,
            'title' => 'Vicios',
            'cover' => 'https://m.media-amazon.com/images/I/61Wi4aAGpYL._UXNaN_FMjpg_QL85_.jpg'
        ]);
        Album::create([
            'idArtist' => 2,
            'title' => 'Nadie sabe',
            'cover' => 'https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2023/10/12/16971477116771.jpg'
        ]);
        Album::create([
            'idArtist' => 3,
            'title' => 'Curtain Call: The Hits',
            'cover' => 'https://i.scdn.co/image/ab67616d0000b273eab40fc794b88b9d1e012578'
        ]);
    }
}
