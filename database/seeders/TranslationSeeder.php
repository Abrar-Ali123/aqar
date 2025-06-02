<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        $translations = [
            // الترجمات العامة
            [
                'key' => 'language_changed',
                'group' => 'messages',
                'translations' => [
                    'ar' => 'تم تغيير اللغة إلى :language',
                    'en' => 'Language changed to :language',
                ],
            ],
            // القائمة الرئيسية
            [
                'key' => 'nav.home',
                'group' => 'messages',
                'translations' => [
                    'ar' => 'الرئيسية',
                    'en' => 'Home',
                ],
            ],
            [
                'key' => 'nav.products',
                'group' => 'messages',
                'translations' => [
                    'ar' => 'المنتجات',
                    'en' => 'Products',
                ],
            ],
            [
                'key' => 'nav.facilities',
                'group' => 'messages',
                'translations' => [
                    'ar' => 'المنشآت',
                    'en' => 'Facilities',
                ],
            ],
            [
                'key' => 'nav.about',
                'group' => 'messages',
                'translations' => [
                    'ar' => 'من نحن',
                    'en' => 'About Us',
                ],
            ],
            [
                'key' => 'nav.contact',
                'group' => 'messages',
                'translations' => [
                    'ar' => 'اتصل بنا',
                    'en' => 'Contact Us',
                ],
            ],

            // ترجمات الصفحة الرئيسية
            [
                'key' => 'welcome',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'مرحباً بك في عقار',
                    'en' => 'Welcome to Aqar',
                ],
            ],
            [
                'key' => 'welcome_subtitle',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'اكتشف أفضل العقارات والخدمات العقارية في مكان واحد',
                    'en' => 'Discover the best properties and real estate services in one place',
                ],
            ],
            [
                'key' => 'featured_properties',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'عقارات مميزة',
                    'en' => 'Featured Properties',
                ],
            ],
            [
                'key' => 'property',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'عقار',
                    'en' => 'Property',
                ],
            ],
            [
                'key' => 'property_description',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'عقار مميز بموقع استراتيجي وخدمات متكاملة',
                    'en' => 'Premium property with strategic location and integrated services',
                ],
            ],
            [
                'key' => 'view_details',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'عرض التفاصيل',
                    'en' => 'View Details',
                ],
            ],
            [
                'key' => 'our_facilities',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'مرافقنا',
                    'en' => 'Our Facilities',
                ],
            ],
            [
                'key' => 'facility',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'مرفق',
                    'en' => 'Facility',
                ],
            ],
            [
                'key' => 'facility_description',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'مرفق متكامل الخدمات',
                    'en' => 'Full-service facility',
                ],
            ],
            [
                'key' => 'contact_us',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'تواصل معنا',
                    'en' => 'Contact Us',
                ],
            ],
            [
                'key' => 'contact_description',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'هل لديك استفسار؟ نحن هنا لمساعدتك',
                    'en' => 'Have a question? We\'re here to help',
                ],
            ],

            // ترجمات صفحة من نحن
            [
                'key' => 'about_intro',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'نحن شركة رائدة في مجال العقارات، نقدم خدمات متكاملة لعملائنا منذ أكثر من 10 سنوات.',
                    'en' => 'We are a leading real estate company, providing comprehensive services to our clients for over 10 years.',
                ],
            ],
            [
                'key' => 'our_mission',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'مهمتنا',
                    'en' => 'Our Mission',
                ],
            ],
            [
                'key' => 'mission_text',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'نسعى لتقديم أفضل الخدمات العقارية وتسهيل عملية البيع والشراء والإيجار لعملائنا.',
                    'en' => 'We strive to provide the best real estate services and facilitate the process of buying, selling, and renting for our clients.',
                ],
            ],
            [
                'key' => 'our_vision',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'رؤيتنا',
                    'en' => 'Our Vision',
                ],
            ],
            [
                'key' => 'vision_text',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'أن نكون الخيار الأول في سوق العقارات وأن نقدم حلولاً مبتكرة تلبي احتياجات عملائنا.',
                    'en' => 'To be the first choice in the real estate market and provide innovative solutions that meet our clients\' needs.',
                ],
            ],

            // ترجمات صفحة اتصل بنا
            [
                'key' => 'contact_info',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'معلومات الاتصال',
                    'en' => 'Contact Information',
                ],
            ],
            [
                'key' => 'address',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'العنوان',
                    'en' => 'Address',
                ],
            ],
            [
                'key' => 'company_address',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'المملكة العربية السعودية - الرياض',
                    'en' => 'Riyadh, Saudi Arabia',
                ],
            ],
            [
                'key' => 'email',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'البريد الإلكتروني',
                    'en' => 'Email',
                ],
            ],
            [
                'key' => 'phone',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'رقم الهاتف',
                    'en' => 'Phone',
                ],
            ],
            [
                'key' => 'send_message',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'أرسل رسالة',
                    'en' => 'Send Message',
                ],
            ],
            [
                'key' => 'name',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'الاسم',
                    'en' => 'Name',
                ],
            ],
            [
                'key' => 'subject',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'الموضوع',
                    'en' => 'Subject',
                ],
            ],
            [
                'key' => 'message',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'الرسالة',
                    'en' => 'Message',
                ],
            ],
            [
                'key' => 'send',
                'group' => 'pages',
                'translations' => [
                    'ar' => 'إرسال',
                    'en' => 'Send',
                ],
            ],
        ];

        foreach ($translations as $item) {
            foreach ($item['translations'] as $locale => $text) {
                Translation::updateOrCreate(
                    [
                        'key' => $item['key'],
                        'group' => $item['group'],
                        'locale' => $locale,
                    ],
                    ['text' => $text]
                );
            }
        }
    }
}
