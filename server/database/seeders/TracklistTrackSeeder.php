<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TracklistTrack;

class TracklistTrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 5
        ]);
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 8
        ]);
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 15
        ]);
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 10
        ]);
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 12
        ]);
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 2
        ]);
        TracklistTrack::create([
            'idTracklist' => 1,
            'idTrack' => 20
        ]);
    }
}
