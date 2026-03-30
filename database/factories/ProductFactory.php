<?php

namespace Database\Factories;

use App\Models\Category;
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
          return [
            'name' => $this->faker->words(3, true), // اسم المنتج عشوائي
            'description' => $this->faker->sentence(), // وصف عشوائي
            'price' => $this->faker->randomFloat(2, 10, 1000), // سعر بين 10 و 1000
            'stock' => $this->faker->numberBetween(0, 100), // كمية المخزون
            'cat_id' => Category::factory(), // ينشئ فئة جديدة عشوائية ويربطها
        ];
    }
}
