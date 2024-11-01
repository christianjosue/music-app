<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ArtistTrack;

class ArtistTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ArtistTrack::create([
            'idArtist' => 1,
            'idTrack' => 1
        ]);
        ArtistTrack::create([
            'idArtist' => 2,
            'idTrack' => 2
        ]);
        ArtistTrack::create([
            'idArtist' => 3,
            'idTrack' => 3
        ]);
    }
}
