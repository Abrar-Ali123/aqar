<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Facility;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FavoriteController extends TranslatableController
{
    protected $translatableFields = [
        'note' => ['nullable', 'string'],
        'tags' => ['nullable', 'string'],
    ];

    /**
     * عرض قائمة المفضلة
     */
    public function index(): View
    {
        $favorites = auth()->user()->favorites()->latest()->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * إضافة/إزالة من المفضلة
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'favorable_type' => 'required|string|in:product,facility',
            'favorable_id' => 'required|integer',
        ]);

        try {
            $favorite = Favorite::where([
                'user_id' => auth()->id(),
                'favorable_type' => $request->favorable_type,
                'favorable_id' => $request->favorable_id,
            ])->first();

            if ($favorite) {
                $favorite->delete();
                $message = __('messages.favorite_removed_successfully');
            } else {
                $favorite = Favorite::create([
                    'user_id' => auth()->id(),
                    'favorable_type' => $request->favorable_type,
                    'favorable_id' => $request->favorable_id,
                ]);
                $this->handleTranslations($favorite, $request, array_keys($this->translatableFields));
                $message = __('messages.favorite_added_successfully');
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorite' => !is_null($favorite)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.favorite_toggle_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إزالة من المفضلة
     */
    public function destroy(Favorite $favorite): JsonResponse
    {
        try {
            $favorite->delete();

            return response()->json([
                'success' => true,
                'message' => __('messages.favorite_removed_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.favorite_remove_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
