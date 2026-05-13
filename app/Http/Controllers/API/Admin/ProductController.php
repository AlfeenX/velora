<?php

namespace App\Http\Controllers\API\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;

use App\Services\Product\ProductService;

use App\Http\Resources\Product\ProductResource;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

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
        ->paginate(20);

        return ProductResource::collection($products);
    }

    public function store(
        StoreProductRequest $request,
        ProductService $service
    ) {

        $product = $service->store(
            $request->validated()
        );

        return new ProductResource($product);
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

    public function update(
        UpdateProductRequest $request,
        Product $product
    ) {

        $product->update(
            $request->validated()
        );

        return new ProductResource($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted'
        ]);
    }
}