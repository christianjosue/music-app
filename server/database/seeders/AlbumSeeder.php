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
            'title' => 'The Night',
            'cover' => 'https://cdn-images.dzcdn.net/images/cover/3271af485d2be55f2749bab7005dda28/1900x1900-000000-80-0-0.jpg'
        ]);
        Album::create([
            'idArtist' => 2,
            'title' => 'Space',
            'cover' => 'https://cdn-images.dzcdn.net/images/cover/ee7e22a7a5b42b0c60dc980b1b513b1c/0x1900-000000-80-0-0.jpg'
        ]);
        Album::create([
            'idArtist' => 3,
            'title' => 'Elevate',
            'cover' => 'https://i1.sndcdn.com/artworks-000102769935-nkultb-t500x500.jpg'
        ]);
    }
}
