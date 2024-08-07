<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;

class ShippingAddressController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $shippingAddress = new ShippingAddress();
        $shippingAddress->user_id = $user->id;
        $shippingAddress->receiver_name = $request->receiver_name;
        $shippingAddress->receiver_phone = $request->receiver_phone;
        $shippingAddress->address = $request->address;
        $shippingAddress->city = $request->city;
        $shippingAddress->province = $request->province;
        $shippingAddress->street_name = $request->street_name;

        $shippingAddress->save();

        return response()->json($shippingAddress, 201);
    }

    public function delete($id)
    {
        $shippingAddress = ShippingAddress::find($id);

        if (!$shippingAddress) {
            return response()->json(['message' => 'Shipping address not found'], 404);
        }

        // Hanya izinkan pengguna untuk menghapus alamat mereka sendiri
        if ($shippingAddress->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $shippingAddress->delete();

        return response()->json(['message' => 'Shipping address deleted']);
    }
}