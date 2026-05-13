<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $shoeNames = [
            'Sepatu Sneaker', 'Sepatu Pantofel', 'Sepatu Lari', 'Sepatu Boots', 'Sandal Gunung',
            'Sepatu Casual', 'Sepatu Olahraga', 'Sepatu Basket', 'Sepatu Kulit', 'Sepatu Kanvas'
        ];
        $brands = ['Velora', 'Aero', 'Stride', 'Luna', 'Peak', 'Sky', 'Terra'];
        
        $name = $this->faker->randomElement($shoeNames) . ' ' . $this->faker->randomElement($brands) . ' ' . $this->faker->unique()->numberBetween(100, 999);
        
        return [
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory(),
            'collection_id' => null,
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => 'Sepatu berkualitas tinggi dengan desain modern. ' . $this->faker->sentence(10),
            'gender' => $this->faker->randomElement(['male', 'female', 'unisex']),
            'release_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
