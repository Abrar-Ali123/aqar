<?php

namespace App\View\Components\Facilities\Pages\Components;

use App\Models\Facility;
use Illuminate\View\Component;

class Products extends Component
{
    public Facility $facility;
    public array $settings;

    public function __construct(Facility $facility, array $settings)
    {
        $this->facility = $facility;
        $this->settings = $settings;
    }

    public function render()
    {
        $limit = $this->settings['limit'] ?? 6;
        $products = $this->facility->products()
            ->where('is_active', true)
            ->latest()
            ->take($limit)
            ->get();

        return view('components.facilities.pages.components.products', [
            'products' => $products
        ]);
    }
}
