<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => ['en' => 'Amina Khalid', 'ar' => 'أمينة خالد'],
                'title' => ['en' => 'Homeowner', 'ar' => 'مالكة منزل'],
                'body' => [
                    'en' => 'The team made buying our first home effortless. Professional, transparent, and always available.',
                    'ar' => 'جعل الفريق شراء منزلنا الأول أمراً سهلاً. محترفون وشفافون ومتاحون دائماً.',
                ],
                'location' => ['en' => 'Dubai, UAE', 'ar' => 'دبي، الإمارات'],
                'rating' => 5,
            ],
            [
                'name' => ['en' => 'John Peterson', 'ar' => 'جون بيترسون'],
                'title' => ['en' => 'Property Investor', 'ar' => 'مستثمر عقاري'],
                'body' => [
                    'en' => 'Excellent market insights. Their valuation reports helped me close three profitable deals this year.',
                    'ar' => 'رؤى سوقية ممتازة. ساعدتني تقارير التقييم الخاصة بهم على إتمام ثلاث صفقات رابحة هذا العام.',
                ],
                'location' => ['en' => 'London, UK', 'ar' => 'لندن، المملكة المتحدة'],
                'rating' => 5,
            ],
            [
                'name' => ['en' => 'Fatima Al-Rashid', 'ar' => 'فاطمة الراشد'],
                'title' => ['en' => 'Homeowner', 'ar' => 'مالكة منزل'],
                'body' => [
                    'en' => 'From viewing to handover, everything was smooth. I highly recommend their services.',
                    'ar' => 'من المعاينة إلى الاستلام، كان كل شيء سلساً. أوصي بشدة بخدماتهم.',
                ],
                'location' => ['en' => 'Riyadh, Saudi Arabia', 'ar' => 'الرياض، السعودية'],
                'rating' => 4,
            ],
            [
                'name' => ['en' => 'David Osei', 'ar' => 'ديفيد أوسي'],
                'title' => ['en' => 'First-time Buyer', 'ar' => 'مشتري لأول مرة'],
                'body' => [
                    'en' => 'They guided me through every step of the mortgage process. Truly client-focused.',
                    'ar' => 'رشدوني خلال كل خطوة من عملية الرهن العقاري. يركزون حقاً على العميل.',
                ],
                'location' => ['en' => 'Toronto, Canada', 'ar' => 'تورونتو، كندا'],
                'rating' => 5,
            ],
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::updateOrCreate(
                ['name->en' => $testimonial['name']['en']],
                $testimonial + ['is_published' => true, 'sort_order' => $index]
            );
        }
    }
}