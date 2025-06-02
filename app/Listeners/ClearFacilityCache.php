<?php

namespace App\Listeners;

use App\Events\FacilityUpdated;
use Illuminate\Support\Facades\Cache;

class ClearFacilityCache
{
    /**
     * معالجة الحدث
     *
     * @param FacilityUpdated $event
     * @return void
     */
    public function handle(FacilityUpdated $event)
    {
        // حذف الكاش الخاص بالمنشأة
        Cache::tags(['facility', "facility_{$event->facility->id}"])->flush();

        // حذف الكاش الخاص بالترجمات إذا تم تحديثها
        if (isset($event->changes['translations'])) {
            foreach ($event->facility->supported_locales as $locale) {
                Cache::tags(['translations', "facility_{$event->facility->id}_{$locale}"])->flush();
            }
        }

        // حذف الكاش الخاص بالصور إذا تم تحديثها
        if (isset($event->changes['images'])) {
            Cache::tags(['images', "facility_{$event->facility->id}"])->flush();
        }
    }
}
