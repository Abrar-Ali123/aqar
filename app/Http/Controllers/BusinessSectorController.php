<?php

namespace App\Http\Controllers;

use App\Models\BusinessSector;
use Illuminate\View\View;

class BusinessSectorController extends Controller
{
    /**
     * Display a listing of the business sectors.
     */
    public function index(): View
    {
        $sectors = BusinessSector::where('is_active', true)
            ->withCount(['categories' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->paginate(12);

        return view('sectors.index', compact('sectors'));
    }

    /**
     * Display the specified business sector.
     */
    public function show(BusinessSector $sector): View
    {
        $sector->load(['categories' => function($query) {
            $query->where('is_active', true)
                ->withCount('facilities');
        }]);

        return view('sectors.show', compact('sector'));
    }
}
