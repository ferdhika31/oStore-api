<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductStock;
use Faker\Factory as Faker;
use Tests\TestCase;

class Order3Test extends TestCase
{
    public function testUser3Order()
    {
        $user = User::factory(\App\Models\User::class)->create();
        $product = Product::where('stock','!=',0)->first();

        // time create order
        // sleep(1);

        // send request to api
        $response = $this->call('POST', '/api/v1/orders', [
            "user_id" => $user->id,
            "note" => "Taro depan pintu aja",
            "payment_time" => 2,// in second
            "detail" => [
                [
                    "product_id" => $product->id,
                    "quantity" => 1
                ]
            ]
        ]);

        // check product id stock 0
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 0
        ]);
    }
}
