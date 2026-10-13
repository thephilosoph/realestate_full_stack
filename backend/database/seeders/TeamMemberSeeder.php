<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => ['en' => 'Sarah Mitchell', 'ar' => 'سارة ميتشل'],
                'role' => ['en' => 'Chief Executive Officer', 'ar' => 'الرئيسة التنفيذية'],
                'bio' => [
                    'en' => 'Sarah has over 15 years of experience leading real estate firms across the region.',
                    'ar' => 'تمتلك سارة أكثر من 15 عاماً من الخبرة في قيادة شركات العقارات في المنطقة.',
                ],
                'photo_path' => 'team/sarah-mitchell.jpg',
            ],
            [
                'name' => ['en' => 'Omar Hassan', 'ar' => 'عمر حسن'],
                'role' => ['en' => 'Senior Real Estate Consultant', 'ar' => 'مستشار عقاري أول'],
                'bio' => [
                    'en' => 'Omar specializes in luxury villas and off-plan investments.',
                    'ar' => 'يتخصص عمر في الفلل الفاخرة والاستثمارات على المخطط.',
                ],
                'photo_path' => 'team/omar-hassan.jpg',
            ],
            [
                'name' => ['en' => 'Layla Ahmed', 'ar' => 'ليلى أحمد'],
                'role' => ['en' => 'Sales Manager', 'ar' => 'مديرة المبيعات'],
                'bio' => [
                    'en' => 'Layla leads the sales team with a passion for matching clients with their dream homes.',
                    'ar' => 'تقود ليلى فريق المبيعات بشغف لربط العملاء بمنازل أحلامهم.',
                ],
                'photo_path' => 'team/layla-ahmed.jpg',
            ],
            [
                'name' => ['en' => 'Michael Chen', 'ar' => 'مايكل تشين'],
                'role' => ['en' => 'Property Valuation Expert', 'ar' => 'خبير تقييم العقارات'],
                'bio' => [
                    'en' => 'Michael provides accurate market valuations and investment analysis.',
                    'ar' => 'يقدم مايكل تقييمات دقيقة للسوق وتحليلات استثمارية.',
                ],
                'photo_path' => null,
            ],
        ];

        foreach ($members as $index => $member) {
            TeamMember::updateOrCreate(
                ['name->en' => $member['name']['en']],
                $member + ['is_published' => true, 'sort_order' => $index]
            );
        }
    }
}