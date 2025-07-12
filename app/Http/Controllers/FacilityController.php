<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Models\BusinessCategory;
use App\Models\BusinessSector;
use App\Models\Facility;
use App\Services\FacilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function __construct(protected FacilityService $facilityService)
    {
        // Authorization is now handled by the AuthorizeUser middleware in routes
        // or within the Form Requests for store/update actions.
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $facilities = Auth::user()->facilities()->latest()->paginate(15);
        return view('dashboard.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $businessCategories = BusinessCategory::all();
        $businessSectors = BusinessSector::all();

        return view('dashboard.facilities.create', compact('businessCategories', 'businessSectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacilityRequest $request): RedirectResponse
    {
        $facility = $this->facilityService->createFacility($request->validated());

        return redirect()->route('facilities.edit', ['locale' => app()->getLocale(), 'facility' => $facility->id])
                         ->with('success', __('messages.facility_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility): RedirectResponse
    {
        return redirect()->route('facilities.edit', ['locale' => app()->getLocale(), 'facility' => $facility->id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $facility = Facility::findOrFail($id);
        $businessCategories = BusinessCategory::all();
        $businessSectors = BusinessSector::all();

        return view('dashboard.facilities.edit', compact('facility', 'businessCategories', 'businessSectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacilityRequest $request, string $id): RedirectResponse
    {
        $facility = Facility::findOrFail($id);
        $this->facilityService->updateFacility($facility, $request->validated());

        return redirect()->route('facilities.index', ['locale' => app()->getLocale()])
                         ->with('success', __('messages.facility_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility): RedirectResponse
    {
        // We assume authorization is handled by middleware or a policy check can be added here if needed.
        $this->facilityService->deleteFacility($facility);

        return redirect()->route('facilities.index', ['locale' => app()->getLocale()])
                         ->with('success', __('messages.facility_deleted_successfully'));
    }
}

