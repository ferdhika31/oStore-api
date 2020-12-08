<?php

namespace Database\Factories;

use App\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductStock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $price = $this->faker->numberBetween(1000, 15000);
        return [
            'stock' => 0,
            'prev_stock' => 0,
            'cur_stock' => 0,
            'type' => "IN",
            'ref_id' => "0",
            'ref_type' => "INIT_STOCK",
        ];
    }
}