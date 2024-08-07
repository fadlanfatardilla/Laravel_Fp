<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'string|nullable',
            'price' => 'required|numeric|min:0',
            'image' => 'required',
            'stock' => 'required|integer|min:0',
            'size' => 'string|nullable',
            'category_id' => 'required|exists:categories,id',
            'expired_at' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 422);
        }

        $payload = $validator->validated();

        Product::create($payload);

        return response()->json([
            'success' => true,
            'message' => 'Product Added'
        ], 200);
    }

    public function read()
    {
        $products = Product::all();
        return response()->json([
            'success' => true,
            'message' => 'Product List',
            'data' => $products
        ], 200);
    }

    public function readById($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product Found',
            'data' => $product
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'string|nullable',
            'price' => 'required|numeric|min:0',
            'image' => 'required',
            'stock' => 'required|integer|min:0',
            'size' => 'string|nullable',
            'category_id' => 'required|exists:categories,id',
            'modified_by' => 'required',
            'expired_at' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 422);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found'
            ], 404);
        }

        $product->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product Edited'
        ], 200);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product Deleted'
        ], 200);
    }
}