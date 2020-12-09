<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use DB;
use Auth;
use Validator;

class OrderController extends BaseApiController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Order::search($request)->get();

        return $this->returnData($data, "Data retrieved.");
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        $user = User::find($request->user_id);

        if(empty($user)){
            return $this->returnStatus(false, "User not found.", 404);
        }

        // validation product
        foreach($request->detail as $detail){
            $validator = Validator::make($detail, [
                'product_id' => 'required'
            ]);
            if($validator->fails()){
                return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
            }

            $detail = (object) $detail;

            $qty = !empty($detail->qty) ? $detail->qty : 1;
            // product id not found
            $product = Product::find($detail->product_id);
            if(empty($product)){
                return $this->returnStatus(false, "Product not found.", 404);
            }
            // user can only buy one product qty
            if($qty > 1){
                return $this->returnStatus(false, "You can only buy 1", 422);
            }
            // user can only buy one product during this period
            $soldProductByUser = Order::where('user_id', $user->id)->whereHas('orderDetail', function($query) use ($detail){
                $query->where('product_id', $detail->product_id);
            })->first();
            if(!empty($soldProductByUser)){
                return $this->returnStatus(false, "Sorry, you already bought product {$product->name}. User can only buy one product during this period.", 422);
            }
        }

        try {
            DB::beginTransaction();

            // create order temp
            $instance = $request->only(['note']);
            $instance['total'] = 0; // init total
            $order = new Order($instance);
            $order->user()->associate($user);
            // save order
            $order->save();

            // loop products
            $details = []; // init details
            foreach($request->detail as $detail){
                $detail = (object) $detail; //convert array to object
                $productId = $detail->product_id;
                $product = Product::find($productId);
                $qty = !empty($detail->qty) ? $detail->qty : 1;

                $unSoldProduct = DB::table('products')
                    ->where('stock','!=', 0)
                    ->lockForUpdate()
                    ->find($productId);

                if(empty($unSoldProduct)){
                    return $this->returnStatus(false, "Sorry, product {$product->name} sold out.", 200);
                }

                // order detail temp
                $details[] = new OrderDetail([
                    'product_id' => $productId,
                    'price' => $product->price,
                    'quantity' => $qty,
                    'sub_total' => $product->selling_price * $qty,
                    'discount' => $product->discount
                ]);
                // calculate total
                $instance['total'] += $product->selling_price;

                // reduce stock history
                $product->reduceStock($qty, $order->id, "ORDER", TRUE);
            }

            $paymentTime = $request->input('payment_time', 0);

            //simulate to complete payment .
            sleep($paymentTime);

            // save detail
            $order->orderDetail()->saveMany($details);
            // update total order
            $order->total = $instance['total'];
            $order->save();

            // if everything that's fine, the user will success buy the product and order data inserted
            DB::commit();

            return $this->returnData($order, "Order Completed.");
        } catch (\Throwable $th) {
            // rollback order product data
            DB::rollback();
            return $this->returnError($th);
        }
    }
}
