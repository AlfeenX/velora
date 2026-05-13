<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Sneakers', 'Sepatu Formal', 'Sepatu Olahraga', 'Boots', 'Sandal & Flat Shoes'];
        $name = $this->faker->randomElement($categories) . ' ' . $this->faker->numberBetween(1, 100);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
