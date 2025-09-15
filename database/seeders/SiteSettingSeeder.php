<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (SiteSetting::count() === 0) {
            SiteSetting::create([
                'email' => 'contact@offitrade.com',
                'phone' => '+212 6 12 34 56 78',
                'address' => '7 rue des Fleurs, 37000 Tours, France',
                'facebook_url' => 'https://www.facebook.com/',
                'linkedin_url' => 'https://www.linkedin.com/',
                'twitter_url' => 'https://twitter.com/',
                'instagram_url' => 'https://www.instagram.com/',
                'youtube_url' => 'https://youtube.com/@offitrade',
                'video_id' => '55en7wd-y38',
            ]);
        }
    }
}
