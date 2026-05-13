<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $indonesianColors = ['Hitam', 'Putih', 'Merah', 'Biru', 'Cokelat', 'Abu-abu', 'Hijau Navy', 'Kuning'];
        return [
            'product_id' => \App\Models\Product::factory(),
            'sku' => strtoupper($this->faker->unique()->bothify('SH-####')),
            'color' => $this->faker->randomElement($indonesianColors),
            'size' => $this->faker->numberBetween(38, 45),
            'price' => $this->faker->numberBetween(150, 2500) * 1000, // Indonesian Rupiah format (150k - 2.5m)
            'stock' => $this->faker->numberBetween(5, 50),
            'weight' => $this->faker->numberBetween(500, 1500), // grams
        ];
    }
}
