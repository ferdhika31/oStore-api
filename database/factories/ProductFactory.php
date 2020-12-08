<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = $this->faker->numberBetween(1000, 15000);
        return [
            'name' => "Product ".$this->faker->name,
            'price' => $price,
            'stock' => $this->faker->numberBetween(1, 5),
            'discount' => $price-99,
        ];
    }
}