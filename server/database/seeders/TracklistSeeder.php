<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tracklist;

class TracklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tracklist::create([
            'name' => 'Custom Playlist',
            'thumbnail' => 3
        ]);
    }
}
