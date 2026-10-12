<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $properties = [
            [
                'title' => ['en' => 'Seaside Serenity Villa', 'ar' => 'فيلا سيرينيتي الساحلية'],
                'description' => [
                    'en' => 'A stunning 4-bedroom, 3-bathroom villa in a peaceful suburban neighborhood with ocean views and modern finishes.',
                    'ar' => 'فيلا فاخرة مكونة من 4 غرف نوم و3 حمامات في حي سكني هادئ مع إطلالات على المحيط وتشطيبات عصرية.',
                ],
                'price' => 500000,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'type' => 'Villa',
                'location' => ['en' => 'Malibu, CA', 'ar' => 'ماليبو، كاليفورنيا'],
                'features' => ['Swimming Pool', 'Sea View', 'Private Garden', '24/7 Security'],
                'images' => [
                    ['path' => 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80', 'is_primary' => true],
                    ['path' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80', 'is_primary' => false],
                ],
            ],
            [
                'title' => ['en' => 'Metropolitan Haven', 'ar' => 'ملاذ العاصمة'],
                'description' => [
                    'en' => 'A chic and fully-furnished 2-bedroom apartment with panoramic city views and premium amenities.',
                    'ar' => 'شقة أنيقة ومفروشة بالكامل من غرفتي نوم مع إطلالات بانورامية على المدينة ومرافق فاخرة.',
                ],
                'price' => 600000,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'type' => 'Apartment',
                'location' => ['en' => 'New York, NY', 'ar' => 'نيويورك'],
                'features' => ['Gym', '24/7 Security', 'Balcony', 'Central Air Conditioning'],
                'images' => [
                    ['path' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&q=80', 'is_primary' => true],
                ],
            ],
            [
                'title' => ['en' => 'Rustic Retreat Cottage', 'ar' => 'كوخ الريفي الهادئ'],
                'description' => [
                    'en' => 'An elegant 3-bedroom, 3-bathroom townhouse in a gated community surrounded by nature.',
                    'ar' => 'منزل أنيق مكون من 3 غرف نوم و3 حمامات في مجمع سكني مسور تحيط به الطبيعة.',
                ],
                'price' => 500000,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'type' => 'Villa',
                'location' => ['en' => 'Aspen, CO', 'ar' => 'أسبن، كولورادو'],
                'features' => ['Private Garden', 'Parking', 'Central Air Conditioning'],
                'images' => [
                    ['path' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80', 'is_primary' => true],
                ],
            ],
            [
                'title' => ['en' => 'Urban Loft Studio', 'ar' => 'استوديو لوفت حضري'],
                'description' => [
                    'en' => 'Open-plan loft with industrial details and floor-to-ceiling windows.',
                    'ar' => 'لوفت مفتوح التصميم مع تفاصيل صناعية ونوافذ ممتدة من الأرض إلى السقف.',
                ],
                'price' => 420000,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'type' => 'Apartment',
                'location' => ['en' => 'Chicago, IL', 'ar' => 'شيكاغو'],
                'features' => ['Balcony', 'Gym', 'Parking'],
                'images' => [
                    ['path' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80', 'is_primary' => true],
                ],
            ],
            [
                'title' => ['en' => 'Lakeview Family Home', 'ar' => 'منزل عائلي بإطلالة على البحيرة'],
                'description' => [
                    'en' => 'Spacious family home with private dock and landscaped gardens.',
                    'ar' => 'منزل عائلي واسع مع مرسى خاص وحدائق منسقة.',
                ],
                'price' => 780000,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'type' => 'House',
                'location' => ['en' => 'Seattle, WA', 'ar' => 'سياتل'],
                'features' => ['Private Dock', 'Private Garden', 'Parking', 'Swimming Pool'],
                'images' => [
                    ['path' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&q=80', 'is_primary' => true],
                ],
            ],
            [
                'title' => ['en' => 'Downtown Penthouse', 'ar' => 'بنتهاوس وسط المدينة'],
                'description' => [
                    'en' => 'Luxury penthouse with rooftop terrace and skyline views.',
                    'ar' => 'بنتهاوس فاخر مع شرفة سطح وإطلالات على أفق المدينة.',
                ],
                'price' => 1250000,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'type' => 'Penthouse',
                'location' => ['en' => 'Miami, FL', 'ar' => 'ميامي'],
                'features' => ['Sea View', 'Smart Home System', 'Gym', '24/7 Security', 'Central Air Conditioning'],
                'images' => [
                    ['path' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80', 'is_primary' => true],
                ],
            ],
        ];

        foreach ($properties as $index => $data) {
            $property = Property::updateOrCreate(
                ['title->en' => $data['title']['en']],
                [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'bedrooms' => $data['bedrooms'],
                    'bathrooms' => $data['bathrooms'],
                    'type' => $data['type'],
                    'location' => $data['location'],
                    'is_published' => true,
                    'sort_order' => $index,
                ]
            );

            // Images
            $property->images()->delete();
            foreach ($data['images'] as $imgIndex => $image) {
                $property->images()->create([
                    'path' => $image['path'],
                    'is_primary' => $image['is_primary'],
                    'sort_order' => $imgIndex,
                ]);
            }

            // Features (pivot with sort_order)
            $featureIds = Feature::whereIn('name->en', $data['features'])->pluck('id');
            $sync = [];
            foreach ($featureIds as $i => $featureId) {
                $sync[$featureId] = ['sort_order' => $i];
            }
            $property->features()->sync($sync);
        }
    }
}