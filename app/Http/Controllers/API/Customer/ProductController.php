<?php

namespace App\Http\Controllers\API\Customer;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category',
            'collection',
            'tags',
            'variants',
            'images'
        ])
        ->latest()
        ->paginate(12);

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'collection',
            'tags',
            'variants',
            'images'
        ]);

        return new ProductResource($product);
    }
}