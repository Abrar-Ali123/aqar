<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\FacilityTranslation;
use App\Models\User;
use App\Models\BusinessCategory;
use App\Models\BusinessSector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run()
    {
        // جلب البيانات الأساسية للاستخدام في الربط
        $user = User::first(); // يفترض وجود مستخدم واحد على الأقل
        $categories = BusinessCategory::all()->keyBy('slug');
        $sectors = BusinessSector::all()->keyBy('slug');

        // التأكد من وجود مستخدم لتجنب الأخطاء
        if (!$user) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        // بيانات المنشآت مع صور حقيقية
        $facilities = [
            [
                'business_sector' => 'retail',
                'business_category' => 'fashion',
                'name' => [
                    'ar' => 'بوتيك الأناقة',
                    'en' => 'Elegance Boutique'
                ],
                'description' => [
                    'ar' => 'بوتيك فاخر متخصص في الأزياء العصرية والإكسسوارات الراقية للسيدات والرجال',
                    'en' => 'Luxury boutique specializing in modern fashion and elegant accessories for women and men'
                ],
                'logo' => 'https://images.unsplash.com/photo-1583744946564-b52ac1c389c8?w=200&q=80',
                'is_active' => true,
                'is_featured' => true,
                'is_verified' => true,
                'phone' => '+966500000000',
                'email' => 'contact@elegance-boutique.com',
                'address' => 'الرياض - حي الورود - شارع العليا',
                'working_hours' => [
                    'sun_thu' => '10:00 - 22:00',
                    'fri_sat' => '16:00 - 23:00'
                ],
                'social_media' => [
                    'instagram' => '@elegance_boutique',
                    'twitter' => '@elegance_sa'
                ]
            ],
            [
                'business_sector' => 'food',
                'business_category' => 'fine_dining',
                'name' => [
                    'ar' => 'مطعم الذواقة العالمي',
                    'en' => 'Le Gourmet International'
                ],
                'description' => [
                    'ar' => 'مطعم فاخر يقدم أشهى المأكولات العالمية بلمسة شرقية مميزة. تجربة طعام فريدة مع إطلالة ساحرة على المدينة',
                    'en' => 'A luxury restaurant offering the finest international cuisine with a distinctive oriental touch. A unique dining experience with a magical city view'
                ],
                'logo' => 'https://images.unsplash.com/photo-1577106263724-2c8e03bfe9cf?w=200&q=80',
                'is_active' => true,
                'is_featured' => true,
                'is_verified' => true,
                'phone' => '+966500000001',
                'email' => 'reservations@legourmet.sa',
                'address' => 'الرياض - أبراج المملكة - الطابق 77',
                'working_hours' => [
                    'sun_thu' => '12:00 - 00:00',
                    'fri_sat' => '13:00 - 01:00'
                ],
                'social_media' => [
                    'instagram' => '@legourmet_sa',
                    'twitter' => '@legourmet_sa'
                ]
            ],
            [
                'business_sector' => 'services',
                'business_category' => 'wellness_spa',
                'name' => [
                    'ar' => 'سبا الصفاء والعافية',
                    'en' => 'Serenity Spa & Wellness'
                ],
                'description' => [
                    'ar' => 'مركز فاخر للعناية بالجسم والروح. نقدم تجربة استرخاء فريدة مع مجموعة متنوعة من العلاجات والخدمات العلاجية',
                    'en' => 'A luxury wellness center for body and soul care. We offer a unique relaxation experience with a diverse range of therapeutic treatments and services'
                ],
                'logo' => 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?w=200&q=80',
                'is_active' => true,
                'is_featured' => true,
                'is_verified' => true,
                'phone' => '+966500000002',
                'email' => 'bookings@serenityspa.sa',
                'address' => 'الرياض - حي الملقا - شارع التخصصي',
                'working_hours' => [
                    'sun_thu' => '10:00 - 22:00',
                    'fri_sat' => '14:00 - 23:00'
                ],
                'social_media' => [
                    'instagram' => '@serenityspa_sa',
                    'twitter' => '@serenityspa_sa'
                ]
            ]
        ];

        foreach ($facilities as $facilityData) {
            $name = $facilityData['name'];
            $description = $facilityData['description'];

            // استخراج معرفات الفئة والقطاع
            $categorySlug = $facilityData['business_category'];
            $sectorSlug = $facilityData['business_sector'];

            $categoryId = $categories->get($categorySlug)->id ?? null;
            $sectorId = $sectors->get($sectorSlug)->id ?? null;

            // تجميع تفاصيل العمل في حقل واحد
            $businessDetails = [
                'phone' => $facilityData['phone'],
                'email' => $facilityData['email'],
                'address' => $facilityData['address'],
                'working_hours' => $facilityData['working_hours'],
                'social_media' => $facilityData['social_media'],
            ];

            // إزالة البيانات التي تم تجميعها والبيانات الوصفية
            unset(
                $facilityData['name'], $facilityData['description'], $facilityData['business_category'], $facilityData['business_sector'],
                $facilityData['phone'], $facilityData['email'], $facilityData['address'], $facilityData['working_hours'], $facilityData['social_media']
            );

            // إضافة البيانات الجديدة للمنشأة
            $facilityData['slug'] = Str::slug($name['en']);
            $facilityData['user_id'] = $user->id;
            $facilityData['business_category_id'] = $categoryId;
            $facilityData['business_sector_id'] = $sectorId;
            $facilityData['business_details'] = json_encode($businessDetails);

            $newFacility = Facility::create($facilityData);

            // إضافة الترجمات
            foreach (['ar', 'en'] as $locale) {
                FacilityTranslation::create([
                    'facility_id' => $newFacility->id,
                    'locale' => $locale,
                    'name' => $name[$locale],
                    'description' => $description[$locale]
                ]);
            }
        }
    }
}
