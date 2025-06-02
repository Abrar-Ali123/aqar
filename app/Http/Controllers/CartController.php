<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with(['items.product', 'items.product.facility'])
            ->first();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $cart->items()->create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'options' => $request->options ?? [],
            'price' => $product->price
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تمت إضافة المنتج إلى السلة'),
            'cart_count' => $cart->items()->count()
        ]);
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        $item = $cart->items()->findOrFail($itemId);
        
        $item->update([
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تم تحديث الكمية'),
            'cart_total' => $cart->total
        ]);
    }

    public function remove($itemId)
    {
        $cart = Cart::where('user_id', Auth::id())->firstOrFail();
        $cart->items()->findOrFail($itemId)->delete();

        return response()->json([
            'success' => true,
            'message' => __('تم حذف المنتج من السلة'),
            'cart_count' => $cart->items()->count(),
            'cart_total' => $cart->total
        ]);
    }
}
