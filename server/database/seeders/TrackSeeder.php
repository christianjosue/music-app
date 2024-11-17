<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Track;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Track::create([
            'idAlbum' => 1,
            'title' => 'Dejavu',
            'src' => 'dejavu.mp3',
            'thumbnail' => 'https://is1-ssl.mzstatic.com/image/thumb/Music126/v4/98/f2/e6/98f2e60a-d2ac-c20d-4ac2-e7d2f5811ed5/190296284182.jpg/600x600bf-60.jpg',
            'duration' => '3:15'
        ]);
        Track::create([
            'idAlbum' => 2,
            'title' => 'Baby Nueva',
            'src' => 'baby-nueva.mp3',
            'thumbnail' => 'https://upload.wikimedia.org/wikipedia/en/7/74/Bad_Bunny_-_Nadie_Sabe_Lo_Que_Va_a_Pasar_Ma%C3%B1ana.png',
            'duration' => '4:00'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Shake That',
            'src' => 'shake-that.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000087591199-p4smrc-t500x500.jpg',
            'duration' => '4:34'
        ]);
    }
}
