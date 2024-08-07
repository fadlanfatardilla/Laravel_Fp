<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)->with('product')->get();
        return response()->json($wishlists);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        // Cek apakah produk sudah ada di wishlist user
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingWishlist) {
            return response()->json(['message' => 'Product already in wishlist']);
        }

        // Tambahkan produk ke wishlist
        $wishlist = new Wishlist();
        $wishlist->user_id = $user->id;
        $wishlist->product_id = $request->product_id;
        $wishlist->save();

        return response()->json($wishlist, 201);
    }

    public function delete($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist item not found'], 404);
        }

        $wishlist->delete();

        return response()->json(['message' => 'Wishlist item deleted successfully']);
    }
}