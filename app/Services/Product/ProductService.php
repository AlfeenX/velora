<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function store(array $data): Product
    {
        return DB::transaction(function () use ($data) {

            $product = Product::create([
                'category_id' => $data['category_id'],
                'collection_id' => $data['collection_id'] ?? null,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'gender' => $data['gender'],
                'release_date' => $data['release_date'] ?? null,
            ]);

            // Attach tags
            if (!empty($data['tags'])) {
                $product->tags()->attach($data['tags']);
            }

            // Create variants
            foreach ($data['variants'] as $variant) {

                $product->variants()->create([
                    'sku' => $variant['sku'],
                    'color' => $variant['color'],
                    'size' => $variant['size'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'weight' => $variant['weight'] ?? null,
                ]);
            }

            return $product->load([
                'category',
                'collection',
                'tags',
                'variants',
                'images'
            ]);
        });
    }
}