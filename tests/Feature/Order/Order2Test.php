<?php
namespace Tests\Feature\Order;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductStock;
use Faker\Factory as Faker;
use Tests\TestCase;

class Order2Test extends TestCase
{
    public function testUser2Order()
    {
        $user = User::factory(\App\Models\User::class)->create();
        $product = Product::first();

        // time create order
        // sleep(2);

        // send request to api
        $response = $this->call('POST', '/api/v1/orders', [
            "user_id" => $user->id,
            "note" => "Titip tetangga aja ntar",
            "detail" => [
                [
                    "product_id" => $product->id,
                    "quantity" => 1
                ]
            ]
        ]);
        // order success if status true
        $this->assertEquals(json_decode($response->getContent())->status, true);
    }
}
