<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => ['en' => 'How do I schedule a property viewing?', 'ar' => 'كيف أجدول موعداً لمعاينة عقار؟'],
                'answer' => [
                    'en' => 'You can schedule a viewing by contacting our sales team through the website form or by phone. Viewings are available 7 days a week.',
                    'ar' => 'يمكنك جدولة معاينة من خلال التواصل مع فريق المبيعات عبر نموذج الموقع أو الهاتف. المعاينات متاحة طوال أيام الأسبوع.',
                ],
            ],
            [
                'question' => ['en' => 'What fees are involved when buying a property?', 'ar' => 'ما هي الرسوم المتضمنة عند شراء عقار؟'],
                'answer' => [
                    'en' => 'Fees typically include agency commission, transfer/registration fees, and legal fees. We provide a full cost breakdown before any commitment.',
                    'ar' => 'تشمل الرسوم عادةً عمولة الوكالة ورسوم النقل والتسجيل والرسوم القانونية. نقدم تفصيلاً كاملاً للتكاليف قبل أي التزام.',
                ],
            ],
            [
                'question' => ['en' => 'Do you help with mortgage financing?', 'ar' => 'هل تساعدون في التمويل العقاري؟'],
                'answer' => [
                    'en' => 'Yes, we work with major banks and can connect you with mortgage advisors to find the best financing options for your budget.',
                    'ar' => 'نعم، نتعاون مع كبرى البنوك ويمكننا ربطك بمستشاري رهن عقاري للعثور على أفضل خيارات التمويل المناسبة لميزانيتك.',
                ],
            ],
            [
                'question' => ['en' => 'Can foreigners buy property?', 'ar' => 'هل يمكن للأجانب شراء عقار؟'],
                'answer' => [
                    'en' => 'In most areas, yes. Regulations vary by location, so our legal team will guide you through the requirements for your specific case.',
                    'ar' => 'في معظم المناطق، نعم. تختلف الأنظمة حسب الموقع، لذا سيرشدك فريقنا القانوني خلال المتطلبات الخاصة بحالتك.',
                ],
            ],
            [
                'question' => ['en' => 'How long does the buying process take?', 'ar' => 'كم تستغرق عملية الشراء؟'],
                'answer' => [
                    'en' => 'A typical purchase takes 4–8 weeks from offer acceptance to handover, depending on financing and documentation.',
                    'ar' => 'تستغرق عملية الشراء النموذجية من 4 إلى 8 أسابيع من قبول العرض حتى الاستلام، حسب التمويل والمستندات.',
                ],
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::updateOrCreate(
                ['question->en' => $faq['question']['en']],
                $faq + ['is_published' => true, 'sort_order' => $index]
            );
        }
    }
}