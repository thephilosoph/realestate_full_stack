<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            ['name' => ['en' => 'Skyline Developers', 'ar' => 'سكاي لاين للتطوير'], 'website_url' => 'https://example.com'],
            ['name' => ['en' => 'Oceanview Properties', 'ar' => 'عقارات أوشن فيو'], 'website_url' => 'https://example.com'],
            ['name' => ['en' => 'Golden Gate Realty', 'ar' => 'جولدن جيت العقارية'], 'website_url' => 'https://example.com'],
            ['name' => ['en' => 'Palm Estates', 'ar' => 'نخيل العقارات'], 'website_url' => 'https://example.com'],
            ['name' => ['en' => 'Horizon Homes', 'ar' => 'هورايزون هومز'], 'website_url' => 'https://example.com'],
            ['name' => ['en' => 'BlueRock Investments', 'ar' => 'بلو روك للاستثمار'], 'website_url' => 'https://example.com'],
        ];

        foreach ($clients as $index => $client) {
            Client::updateOrCreate(
                ['name->en' => $client['name']['en']],
                $client + ['logo_path' => null, 'is_published' => true, 'sort_order' => $index]
            );
        }
    }
}