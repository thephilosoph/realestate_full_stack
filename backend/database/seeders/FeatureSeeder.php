<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            ['name' => ['en' => 'Swimming Pool', 'ar' => 'مسبح']],
            ['name' => ['en' => 'Private Garden', 'ar' => 'حديقة خاصة']],
            ['name' => ['en' => 'Parking', 'ar' => 'موقف سيارات']],
            ['name' => ['en' => '24/7 Security', 'ar' => 'أمن على مدار الساعة']],
            ['name' => ['en' => 'Central Air Conditioning', 'ar' => 'تكييف مركزي']],
            ['name' => ['en' => 'Balcony', 'ar' => 'شرفة']],
            ['name' => ['en' => 'Gym', 'ar' => 'صالة رياضية']],
            ['name' => ['en' => 'Sea View', 'ar' => 'إطلالة على البحر']],
            ['name' => ['en' => 'Smart Home System', 'ar' => 'نظام منزل ذكي']],
            ['name' => ['en' => 'Private Dock', 'ar' => 'مرسى خاص']],
        ];

        foreach ($features as $feature) {
            Feature::updateOrCreate(['name->en' => $feature['name']['en']], $feature);
        }
    }
}