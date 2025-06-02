<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends TranslatableController
{
    protected $translatableFields = [
        'notes' => ['nullable', 'string'],
        'cancellation_reason' => ['nullable', 'string'],
    ];

    public function index()
    {
        if (!Auth::user()->can('view bookings')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $query = Booking::with(['facility.translations', 'user', 'status.translations']);

        // Filter by user if not admin
        if (!Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }

        $bookings = $query->latest()->paginate(15);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        if (!Auth::user()->can('create bookings')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $facilities = Facility::with('translations')
            ->where('is_active', true)
            ->get();
        $languages = $this->getLanguages();
        return view('admin.bookings.create', compact('facilities', 'languages'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('create bookings')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'guests_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        try {
            $facility = Facility::findOrFail($request->facility_id);
            
            // Check if facility is available for the requested dates
            if (!$facility->isAvailable($request->start_date, $request->end_date)) {
                return redirect()->back()
                    ->with('error', __('messages.facility_not_available'))
                    ->withInput();
            }

            $booking = Booking::create([
                'facility_id' => $request->facility_id,
                'user_id' => Auth::id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'guests_count' => $request->guests_count,
                'total_price' => $request->total_price,
                'status_id' => Status::where('type', 'booking')
                    ->where('is_default', true)
                    ->first()
                    ->id,
            ]);

            $this->handleTranslations($booking, $request, array_keys($this->translatableFields));

            return redirect()->route('admin.bookings.index')
                ->with('success', __('messages.booking_created_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.booking_create_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Booking $booking)
    {
        if (!Auth::user()->can('view bookings') || 
            (!Auth::user()->hasRole('admin') && $booking->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $booking->load(['facility.translations', 'user', 'status.translations']);
        $translations = $this->prepareTranslations($booking, array_keys($this->translatableFields));
        return view('admin.bookings.show', compact('booking', 'translations'));
    }

    public function edit(Booking $booking)
    {
        if (!Auth::user()->can('edit bookings') || 
            (!Auth::user()->hasRole('admin') && $booking->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $facilities = Facility::with('translations')
            ->where('is_active', true)
            ->get();
        $statuses = Status::with('translations')
            ->where('type', 'booking')
            ->where('is_active', true)
            ->get();
        $languages = $this->getLanguages();
        $translations = $this->prepareTranslations($booking, array_keys($this->translatableFields));
        
        return view('admin.bookings.edit', compact('booking', 'facilities', 'statuses', 'languages', 'translations'));
    }

    public function update(Request $request, Booking $booking)
    {
        if (!Auth::user()->can('edit bookings') || 
            (!Auth::user()->hasRole('admin') && $booking->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'guests_count' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
        ]);

        try {
            $facility = Facility::findOrFail($request->facility_id);
            
            // Check if facility is available for the requested dates (excluding current booking)
            if (!$facility->isAvailable($request->start_date, $request->end_date, $booking->id)) {
                return redirect()->back()
                    ->with('error', __('messages.facility_not_available'))
                    ->withInput();
            }

            $booking->update([
                'facility_id' => $request->facility_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'guests_count' => $request->guests_count,
                'total_price' => $request->total_price,
                'status_id' => $request->status_id,
            ]);

            $this->handleTranslations($booking, $request, array_keys($this->translatableFields));

            return redirect()->route('admin.bookings.index')
                ->with('success', __('messages.booking_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.booking_update_error') . ': ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Booking $booking)
    {
        if (!Auth::user()->can('delete bookings') || 
            (!Auth::user()->hasRole('admin') && $booking->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            $booking->delete();

            return redirect()->route('admin.bookings.index')
                ->with('success', __('messages.booking_deleted_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.booking_delete_error') . ': ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, Booking $booking)
    {
        if (!Auth::user()->can('edit bookings') || 
            (!Auth::user()->hasRole('admin') && $booking->user_id !== Auth::id())) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        try {
            $cancelStatus = Status::where('type', 'booking')
                ->where('name->en', 'Cancelled')
                ->firstOrFail();

            $booking->update([
                'status_id' => $cancelStatus->id,
            ]);

            $this->handleTranslations($booking, $request, ['cancellation_reason']);

            return redirect()->route('admin.bookings.index')
                ->with('success', __('messages.booking_cancelled_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('messages.booking_cancel_error') . ': ' . $e->getMessage());
        }
    }

    public function showBookings($facilityId)
    {
        $bookings = Booking::whereHas('product', function ($query) use ($facilityId) {
            $query->where('facility_id', $facilityId);
        })->get();

        return view('bookings.index', compact('bookings'));
    }
}
