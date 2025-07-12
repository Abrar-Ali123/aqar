<?php

namespace App\View\Composers;

use Illuminate\View\View;

class FacilityViewComposer
{
    public function compose(View $view)
    {
        $facility = $view->getData()['facility'];
        
        $view->with([
            'templateSlug' => $facility->template ? $facility->template->slug : 'default',
            'styles' => $facility->template ? $facility->template->styles : [],
            'components' => $facility->template ? $facility->template->components : [],
            'themeConfig' => [
                'primary_color' => $facility->template->styles['primary_color'] ?? '#3B82F6',
                'accent_color' => $facility->template->styles['accent_color'] ?? '#F59E0B',
                'heading_font' => $facility->template->styles['heading_font'] ?? 'inherit',
                'body_font' => $facility->template->styles['body_font'] ?? 'inherit',
            ],
        ]);
    }
}
