<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'logo' => "",
            'header_text' => 'lorem',
            'telegram_link' => 'lorem',
            'feature_categories_is_active' => 1,
            'video_is_active' => 1,
            'author_is_active' => 1,
            'footer_text' => 'lorem',
            'category_default_image' => '',
            'article_default_image' => '',
        ]);
    }
}
