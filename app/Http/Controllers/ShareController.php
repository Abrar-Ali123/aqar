<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ShareController extends Controller
{
    public function share(Request $request, Product $product)
    {
        $platform = $request->input('platform', 'general');
        $url = URL::to('/products/' . $product->id);
        $title = $product->name;
        $description = $product->description;

        switch ($platform) {
            case 'facebook':
                $shareUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url);
                break;
            case 'twitter':
                $shareUrl = "https://twitter.com/intent/tweet?url=" . urlencode($url) . "&text=" . urlencode($title);
                break;
            case 'whatsapp':
                $shareUrl = "https://wa.me/?text=" . urlencode($title . "\n" . $url);
                break;
            case 'telegram':
                $shareUrl = "https://t.me/share/url?url=" . urlencode($url) . "&text=" . urlencode($title);
                break;
            default:
                return response()->json([
                    'url' => $url,
                    'title' => $title,
                    'description' => $description
                ]);
        }

        return response()->json(['share_url' => $shareUrl]);
    }
}
