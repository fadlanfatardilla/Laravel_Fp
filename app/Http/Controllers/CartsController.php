<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CartsController extends Controller
{
    // Display a listing of the carts for the authenticated user.
    public function show()
    {
        // Pastikan pengguna sudah terautentikasi
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Dapatkan pengguna yang terautentikasi
        $user = auth()->user();

        // Cari keranjang belanja milik pengguna dengan produk-produk terkait
        $cart = Cart::where('user_id', $user->id)->with('cartItems.product')->first();

        // Jika keranjang tidak ditemukan, kirimkan respons bahwa keranjang kosong
        if (is_null($cart)) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        // Kirimkan data keranjang
        return response()->json($cart);
    }


    // Display the specified cart.
    public function showById($id)
    {
        $cart = Cart::with('cartItems.product')->find($id);

        if (is_null($cart)) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json($cart);
    }

    // Store a newly created cart in storage.
    public function create(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::create([
            'user_id' => $user->id,
        ]);

        return response()->json($cart, 201);
    }

    // Update the specified cart in storage.
    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);

        if (is_null($cart)) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $this->validate($request, [
            'user_id' => 'exists:users,id',
        ]);

        $cart->update($request->all());

        return response()->json($cart);
    }

    // Remove the specified cart from storage.
    public function delete($id)
    {
        $cart = Cart::find($id);

        if (is_null($cart)) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $cart->delete();

        return response()->json(['message' => 'Cart deleted successfully'], 204);
    }
}