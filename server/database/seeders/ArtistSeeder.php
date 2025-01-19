<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Artist;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artist::create([
            'name' => 'RYYZN',
            'thumbnail' => 'https://yt3.googleusercontent.com/x6zf6dteUg3bYn0FL_hAn7LJjEbnkFGzm8e5ws0V3sM6uMkw_ZCDRx1KTim3YLu3NVcTEUUtT84=s900-c-k-c0x00ffffff-no-rj',
            'cover' => 'https://cdn-images.dzcdn.net/images/artist/5fca5b41ff89a36ce5339ab4257be7de/500x500.jpg',
            'monthlyListeners' => 140000
        ]);
        Artist::create([
            'name' => 'NEFFEX',
            'thumbnail' => 'https://ih1.redbubble.net/image.5173605952.8417/flat,750x,075,f-pad,750x1000,f8f8f8.jpg',
            'cover' => 'https://imageio.forbes.com/specials-images/imageserve/5da6105ddb402600062023c0/NEFFEX--Left-to-right--Bryce-Savage-and-Cameron-Wales--Photo-by-Jonathan-Weiner-/960x0.jpg?format=jpg&width=960',
            'monthlyListeners' => 4610000
        ]);
        Artist::create([
            'name' => 'NCS',
            'thumbnail' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/NoCopyrightSounds_logo_black-white.svg/1200px-NoCopyrightSounds_logo_black-white.svg.png',
            'cover' => 'https://i.ytimg.com/vi/5z_SPHwspo8/maxresdefault.jpg',
            'monthlyListeners' => 10580000
        ]);
    }
}
