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
            'title' => 'Here For The Night',
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
            'cover' => 'https://i.pinimg.com/1200x/7a/53/be/7a53bead35df9a0225b00798f7d68a49.jpg'
        ]);
    }
}
