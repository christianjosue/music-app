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
            'name' => 'JC Reyes',
            'thumbnail' => 'https://s1.abcstatics.com/lavozdigital/www/multimedia/provincia/2023/11/13/jcreyes-R2eYa2e2dK2YDUKl5mYdU5M-1200x840@abc.jpg',
            'monthlyListeners' => 240000
        ]);
        Artist::create([
            'name' => 'Bad Bunny',
            'thumbnail' => 'https://e00-marca.uecdn.es/assets/multimedia/imagenes/2023/10/13/16971904238942.jpg',
            'monthlyListeners' => 1250000
        ]);
        Artist::create([
            'name' => 'Eminem',
            'thumbnail' => 'https://dynamicmedia.livenationinternational.com/Media/g/r/s/fd60e188-86ab-437b-b4e1-d33b5ac52b66.jpg',
            'monthlyListeners' => 995000
        ]);
    }
}
