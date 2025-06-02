<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\PriceAlert;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->withCount('items')
            ->with(['items' => function($query) {
                $query->with('wishlistable')->latest()->take(4);
            }])
            ->get();

        $priceAlerts = PriceAlert::where('user_id', auth()->id())
            ->with('alertable')
            ->get();

        return view('wishlists.index', compact('wishlists', 'priceAlerts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $wishlist = Wishlist::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->is_public ?? false
        ]);

        return response()->json([
            'success' => true,
            'wishlist' => $wishlist
        ]);
    }

    public function update(Request $request, Wishlist $wishlist)
    {
        $this->authorize('update', $wishlist);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $wishlist->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_public' => $request->is_public ?? false
        ]);

        return response()->json([
            'success' => true,
            'wishlist' => $wishlist
        ]);
    }

    public function destroy(Wishlist $wishlist)
    {
        $this->authorize('delete', $wishlist);
        $wishlist->delete();

        return response()->json(['success' => true]);
    }

    public function addItem(Request $request, Wishlist $wishlist)
    {
        $this->authorize('update', $wishlist);

        $request->validate([
            'item_type' => 'required|string',
            'item_id' => 'required|integer',
            'notes' => 'nullable|string'
        ]);

        $itemType = "App\\Models\\" . ucfirst($request->item_type);
        $item = $itemType::findOrFail($request->item_id);

        $wishlistItem = $wishlist->addItem($item, $request->notes);

        return response()->json([
            'success' => true,
            'item' => $wishlistItem
        ]);
    }

    public function removeItem(Request $request, Wishlist $wishlist)
    {
        $this->authorize('update', $wishlist);

        $request->validate([
            'item_type' => 'required|string',
            'item_id' => 'required|integer'
        ]);

        $itemType = "App\\Models\\" . ucfirst($request->item_type);
        $item = $itemType::findOrFail($request->item_id);

        $wishlist->removeItem($item);

        return response()->json(['success' => true]);
    }

    public function createPriceAlert(Request $request)
    {
        $request->validate([
            'alertable_type' => 'required|string',
            'alertable_id' => 'required|integer',
            'target_price' => 'required|numeric|min:0'
        ]);

        $alertableType = "App\\Models\\" . ucfirst($request->alertable_type);
        $alertable = $alertableType::findOrFail($request->alertable_id);

        $alert = PriceAlert::create([
            'user_id' => auth()->id(),
            'alertable_type' => get_class($alertable),
            'alertable_id' => $alertable->id,
            'target_price' => $request->target_price,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'alert' => $alert
        ]);
    }

    public function deletePriceAlert(PriceAlert $alert)
    {
        $this->authorize('delete', $alert);
        $alert->delete();

        return response()->json(['success' => true]);
    }
}
