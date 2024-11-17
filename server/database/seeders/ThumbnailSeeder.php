<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Thumbnail;

class ThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Thumbnail::create(['src' => 'https://d1csarkz8obe9u.cloudfront.net/posterpreviews/monkey-cover-music-album-art-trap-mixtape-rap-design-template-846c8db07c9d3875354428496f4bce2c_screen.jpg?ts=1663005840']);
        Thumbnail::create(['src' => 'https://easy-peasy.ai/cdn-cgi/image/quality=80,format=auto,width=700/https://fdczvxmwwjwpwbeeqcth.supabase.co/storage/v1/object/public/images/78ccd2c6-89ac-46ac-aa9e-7c0a0902500d/eaae091a-550a-4d49-92e2-d61e368fd7fd.png']);
        Thumbnail::create(['src' => 'https://mir-s3-cdn-cf.behance.net/project_modules/hd/0e08df133126563.61b7435276650.jpg']);
        Thumbnail::create(['src' => 'https://www.premadepixels.com/wp-content/uploads/2022/01/Cross-Roads-Album-Cover-PP1.jpg']);
    }
}
