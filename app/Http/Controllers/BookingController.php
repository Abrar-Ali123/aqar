<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Product;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request, Product $product)
    {

        Booking::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'payment_method' => $request->payment_method, 
            'expires_at' => now()->addHours(24),
            'is_confirmed' => true,
            'status' => 'confirmed',
        ]);

        return redirect()->back()->with('success', 'تم حجز المنتج بنجاح');
    }

    public function showBookings($facilityId)
    {
        $bookings = Booking::whereHas('product', function ($query) use ($facilityId) {
            $query->where('facility_id', $facilityId);
        })->get();

        return view('bookings.index', compact('bookings'));
    }
}
