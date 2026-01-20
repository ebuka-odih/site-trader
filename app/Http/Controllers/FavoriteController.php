<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asset;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for an asset
     */
    public function toggle(Request $request, $assetId)
    {
        $user = Auth::user();
        $asset = Asset::findOrFail($assetId);

        $isFavorited = $user->hasFavorited($assetId);

        if ($isFavorited) {
            $user->favorites()->detach($assetId);
            $message = 'Removed from favorites';
            $isFavorited = false;
        } else {
            $user->favorites()->attach($assetId);
            $message = 'Added to favorites';
            $isFavorited = true;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorited' => $isFavorited,
        ]);
    }

    /**
     * Get user's favorite assets
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $favorites = $user->favorites()
            ->get()
            ->pluck('id')
            ->toArray();

        return response()->json([
            'success' => true,
            'favorites' => $favorites,
        ]);
    }
}
