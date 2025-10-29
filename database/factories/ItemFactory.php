<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'code' => $this->faker->unique()->regexify('[A-Z]{4}[0-9]{4}'),
            'category_id' => Category::factory(),
            'cost_price' => $this->faker->numberBetween(1000, 10000),
            'selling_price' => $this->faker->numberBetween(1000, 10000),
            'stock' => $this->faker->numberBetween(1, 100)
        ];
    }
}
