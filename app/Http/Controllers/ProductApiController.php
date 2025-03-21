<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function all(Request $request): JsonResponse
    {
        $request->validate([
            'instock' => 'nullable|integer|min:0|max:1',
        ]);

        $products = Product::query();

        if ($request->has('instock')) {
            $comparisonSymbol = $request->instock ? '>' : '=';

            $products = $products->where('stock', $comparisonSymbol, 0);
        }

        if ($request->has('search')) {
            $products = $products->where('name', 'LIKE', "%$request->search%");
        }

        // makeHidden only allows me to hide fields that belong to the product
        // If we want to hide fields that are coming from a relationship, we cannot use makeHidden to do that
        // For relationships, instead of makeHidden we specify which fields we want from that relationship: category:id,name
        // But this will not hide the pivot data for many-to-many relationships
        // To hide the pivot data, go the model of the relation you're getting and add
        // a hidden property protected $hidden = ['pivot'];
        $products = $products
            ->with(['category:id,name', 'colours:id,name'])
            ->get()
            ->makeHidden(['category_id', 'description', 'created_at', 'updated_at']);

        return response()->json([
            'message' => 'Products retrieved successfully',
            'data' => $products,
        ]);
    }

    public function find(Product $product): JsonResponse
    {
        return response()->json([
            'message' => 'Product found',
            'data' => $product,
        ]);
    }

    public function create(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required|string|max:70',
            'description' => 'nullable|string',
            'price' => 'required|decimal:2',
            'stock' => 'nullable|integer',
            'category_id' => 'required|integer|exists:categories,id',
            'colour_ids' => 'required|array',
            // Validating the contents of the colour_ids array
            // These rules apply to each colour id we send
            'colour_ids.*' => 'integer|exists:colours,id',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        // Adding the category id to the product - one to many
        $product->category_id = $request->category_id;

        $product->save();
        // Accessing the colours relationship of the product and attaching to it
        // the colour ids we sent from postman
        // many-to-many
        // You MUST save before you use attach
        $product->colours()->attach($request->colour_ids);

        return response()->json([
            'message' => 'Product created',
        ], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:70',
            'description' => 'nullable|string',
            'price' => 'nullable|decimal:2',
            'stock' => 'nullable|integer',
        ]);

        $product->name = $request->name ?? $product->name;
        $product->description = $request->description ?? $product->description;
        $product->price = $request->price ?? $product->price;
        $product->stock = $request->stock ?? $product->stock;

        $product->save();

        return response()->json([
            'message' => 'Product updated',
        ]);
    }

    public function delete(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
        ]);
    }
}
