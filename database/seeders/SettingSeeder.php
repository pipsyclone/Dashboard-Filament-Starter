<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'app_name' => 'Unit Layanan Terpadu',
            'app_name_short' => 'ULT LLDIKTI XIV',
            'app_color' => '#00ff91',
            'app_logo' => null,
            'app_favicon' => null,
            'app_stempel' => null,
            'app_background_login_image' => null,
            'youtube_link' => null,
            'instagram_link' => null,
            'tiktok_link' => null,
            'facebook_link' => null,
            'x_twitter_link' => null,
        ]);
    }
}
