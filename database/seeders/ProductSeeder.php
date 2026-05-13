<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $tags = Tag::all();

        if ($categories->isEmpty()) {
            $this->command->info('Please run CategorySeeder first.');
            return;
        }

        // Create 30 products
        Product::factory()
            ->count(30)
            ->create()
            ->each(function ($product) use ($categories, $tags) {
                // Assign a random category
                $product->update([
                    'category_id' => $categories->random()->id,
                ]);

                // Assign 1-3 random tags
                $product->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Create 2-4 variants for each product
                ProductVariant::factory()
                    ->count(rand(2, 4))
                    ->create([
                        'product_id' => $product->id,
                    ]);
            });
    }
}
