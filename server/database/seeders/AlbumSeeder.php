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
            'cover' => 'https://is1-ssl.mzstatic.com/image/thumb/Music116/v4/13/21/22/132122a1-2ef2-381b-94b6-7b9449dcaa4a/197190137897.jpg/1200x1200bf-60.jpg'
        ]);
        Album::create([
            'idArtist' => 3,
            'title' => 'Curtain Call: The Hits',
            'cover' => 'https://i.scdn.co/image/ab67616d0000b273eab40fc794b88b9d1e012578'
        ]);
    }
}
