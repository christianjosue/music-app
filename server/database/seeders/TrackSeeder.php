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
        /* RYYZN album tracks */
        Track::create([
            'idAlbum' => 1,
            'title' => 'Secrets',
            'src' => 'secrets.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-28imvbhLxyTwo1MW-odAlfQ-t500x500.jpg',
            'duration' => '3:00'
        ]);
        Track::create([
            'idAlbum' => 1,
            'title' => 'The Clock',
            'src' => 'the-clock.mp3',
            'thumbnail' => 'https://s.mxmcdn.net/images-storage/albums4/9/7/9/9/7/6/49679979_500_500.jpg',
            'duration' => '2:27'
        ]);
        Track::create([
            'idAlbum' => 1,
            'title' => 'Waited',
            'src' => 'waited.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-AJgpxa668LITzzF0-Giz6pg-t500x500.jpg',
            'duration' => '2:59'
        ]);
        Track::create([
            'idAlbum' => 1,
            'title' => 'You Were Right',
            'src' => 'you-were-right.mp3',
            'thumbnail' => 'https://s.mxmcdn.net/images-storage/albums5/7/8/4/1/9/0/50091487_500_500.jpg',
            'duration' => '2:31'
        ]);
        Track::create([
            'idAlbum' => 1,
            'title' => 'Out in the city',
            'src' => 'out-in-the-city.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-6Ta9bvNzbJ3nlk4D-Gg2U9A-t500x500.jpg',
            'duration' => '2:34'
        ]);
        /* NEFFEX album tracks */
        Track::create([
            'idAlbum' => 2,
            'title' => 'Destiny',
            'src' => 'destiny.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000215191318-e7edw2-t500x500.jpg',
            'duration' => '3:27'
        ]);
        Track::create([
            'idAlbum' => 2,
            'title' => 'Life',
            'src' => 'life.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-mCiGYzS6F6CAWi1u-XWtoAg-t1080x1080.jpg',
            'duration' => '2:09'
        ]);
        Track::create([
            'idAlbum' => 2,
            'title' => 'Failure',
            'src' => 'failure.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000218437885-35iafv-t500x500.jpg',
            'duration' => '5:18'
        ]);
        Track::create([
            'idAlbum' => 2,
            'title' => 'Numb',
            'src' => 'numb.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-HUjaxJwvHzkO0fHn-8x9FQQ-t500x500.jpg',
            'duration' => '2:24'
        ]);
        Track::create([
            'idAlbum' => 2,
            'title' => 'Chance',
            'src' => 'chance.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000349034643-kux4om-t500x500.jpg',
            'duration' => '3:38'
        ]);
        Track::create([
            'idAlbum' => 2,
            'title' => 'Graveyard',
            'src' => 'graveyard.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000316647642-y94mkv-t500x500.jpg',
            'duration' => '2:54'
        ]);
        /* NCS album tracks */
        Track::create([
            'idAlbum' => 3,
            'title' => 'Shine',
            'src' => 'shine.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000564569483-1rclom-t500x500.jpg',
            'duration' => '5:15'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Heroes Tonight',
            'src' => 'heroes-tonight.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/001/750/1000x0/heroes-tonight-x-dreams-pt-ii-mashup-1725969651-F2Ho14TMDA.jpg',
            'duration' => '3:29'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'On & On',
            'src' => 'on-&-on.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/000/152/1000x0/1705340894_JZ2NifV4gB_2024---CARTOON-JEYJA---On--On-ft.-Daniel-Levi.jpg',
            'duration' => '3:28'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Mortals',
            'src' => 'mortals.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/000/287/1000x0/mortals-feat-laura-brehm-1586948734-yFnA6l5Geq.jpg',
            'duration' => '3:50'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Superhero',
            'src' => 'superhero.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/000/278/1000x0/superhero-feat-chris-linton-1586948507-HRAmXcSlA5.jpg',
            'duration' => '3:02'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Where We Started',
            'src' => 'where-we-started.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/000/671/1000x0/where-we-started-feat-jex-1586959339-BqrS2PV0TI.jpg',
            'duration' => '3:42'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Control',
            'src' => 'control.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000424126146-m10eu5-t500x500.jpg',
            'duration' => '2:47'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Flutter',
            'src' => 'flutter.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/000/544/1000x0/flutter-1586956102-nU3BcEU8K6.jpg',
            'duration' => '4:00'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Say Goodbye',
            'src' => 'say-goodbye.mp3',
            'thumbnail' => 'https://ncsmusic.s3.eu-west-1.amazonaws.com/tracks/000/000/685/1000x0/say-goodbye-1586959775-mSn0OJaHY3.jpg',
            'duration' => '3:50'
        ]);
        Track::create([
            'idAlbum' => 3,
            'title' => 'Earth',
            'src' => 'earth.mp3',
            'thumbnail' => 'https://i1.sndcdn.com/artworks-000145205114-lm98qe-t500x500.jpg',
            'duration' => '3:54'
        ]);
    }
}
