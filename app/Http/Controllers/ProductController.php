<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function all()
    {
        $products = Product::all()->makeHidden(['description', 'created_at', 'updated_at']);

        return $products;
    }

    public function find(int $id)
    {
        $product = Product::find($id);

        return $product;
    }
}
