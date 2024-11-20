<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class IconSelector extends Component
{
    public $search = '';

    public $selectedIcon = '';

    public $icons = [];

    public function mount()
    {
        // جلب الأيقونات عند تحميل المكون لأول مرة
        $this->fetchIconsFromAPI();
    }

    public function updatedSearch()
    {
        // عند تحديث مصطلح البحث، يتم جلب الأيقونات الجديدة
        $this->fetchIconsFromAPI();
    }

    public function fetchIconsFromAPI()
    {
        $query = $this->search ? $this->search : 'default';

        // البحث في الكاش أولاً لتحسين الأداء
        $cachedIcons = Cache::get("icons_search_{$query}");
        if ($cachedIcons) {
            $this->icons = $cachedIcons;

            return;
        }

        // إذا لم توجد نتائج في الكاش، جلبها من API
        $client = new Client();
        $response = $client->get('https://api.iconify.design/search?query='.urlencode($query));
        $iconsData = json_decode($response->getBody()->getContents(), true);

        // ترجمة الأيقونات للعربية وإعداد البيانات
        $this->icons = array_map(function ($icon) {
            return [
                'name' => $icon['name'],
                'arabic_name' => $this->translateToArabic($icon['name']), // ترجمة الأيقونة إلى العربية
            ];
        }, $iconsData['icons']);

        // تخزين النتائج في الكاش لمدة ساعة
        Cache::put("icons_search_{$query}", $this->icons, now()->addHour());
    }

    private function translateToArabic($iconName)
    {
        // قاموس بسيط لترجمة بعض الأيقونات الشائعة
        $translations = [
            'heart' => 'قلب',
            'camera' => 'كاميرا',
            'home' => 'منزل',
            'star' => 'نجمة',
            'user' => 'مستخدم',
            'search' => 'بحث',
            'settings' => 'إعدادات',
            'phone' => 'هاتف',
            'message' => 'رسالة',
            // أضف المزيد حسب الحاجة
        ];

        foreach ($translations as $english => $arabic) {
            if (stripos($iconName, $english) !== false) {
                return $arabic;
            }
        }

        return $iconName; // إذا لم توجد ترجمة مناسبة
    }

    public function selectIcon($iconName)
    {
        // تعيين الأيقونة المختارة
        $this->selectedIcon = $iconName;

        // إرسال حدث مخصص للأيقونة المختارة
        $this->dispatch('icon-selected', selectedIcon: $this->selectedIcon);
    }

    public function render()
    {
        // عرض الأيقونات في مكون العرض
        return view('livewire.icon-selector', ['showedIcons' => $this->icons]);
    }
}
