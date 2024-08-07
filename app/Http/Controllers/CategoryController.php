<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 422);
        }

        $payload = $validator->validated();
        Category::create($payload);

        return response()->json([
            'success' => true,
            'message' => 'Category Added'
        ], 200);
    }

    public function read()
    {
        $categorys = Category::all();
        return response()->json([
            'success' => true,
            'message' => 'Category List',
            'data' => $categorys
        ], 200);
    }

    public function readById($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category Found',
            'data' => $category
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error Validation',
                'data' => $validator->errors()
            ], 422);
        }

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found'
            ], 404);
        }

        $category->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category Edited'
        ], 200);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category Not Found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category Deleted'
        ], 200);
    }
}