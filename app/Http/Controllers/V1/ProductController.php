<?php

namespace App\Http\Controllers\V1;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Response;
use Validator;

class ProductController extends BaseApiController
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = Product::search($request)->get();

        return $this->returnData($data, "Data retrieved.");
    }

    /**
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function show($id)
    {
        $data = Product::find($id);

        if(empty($data)){
            return $this->returnStatus(false, "Product not found.", 404);
        }

        return $this->returnData($data, "Data retrieved.");
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'price' => 'required',
            'stock' => 'required|digits_between:1,200',
        ]);

        if($validator->fails()){
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        try {
            $param = $request->only(['name', 'price']);
            $param['stock'] = 0; // di init 0 heula
            $param['discount'] = $request->price-99; // biar jadi rp.99
            $product = Product::create($param);

            // create stock history
            $product->updateStock($request->stock, "0", "INIT_STOCK");

            return $this->returnData($product, "Product created.");
        } catch (\Throwable $th) {
            return $this->returnError($th);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'price' => 'required',
            'stock' => 'required|digits_between:1,200',
        ]);

        if($validator->fails()){
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        try {
            $product = Product::find($id);
            if(empty($product)){
                return $this->returnStatus(false, "Product not found.", 404);
            }
            $param = $request->only(['name', 'price']);
            $param['discount'] = $request->price-99; // biar jadi rp.99
            $product->update($param);

            // create stock history
            $product->updateStock($request->stock, "0", "UPDATE_STOCK");

            return $this->returnData($product, "Product updated.");
        } catch (\Throwable $th) {
            return $this->returnError($th);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function historyStock(Request $request, $id)
    {
        $data['product'] = Product::find($id);
        $data['stock_histories'] = ProductStock::search($request)->where('product_id', $id)->get();

        return $this->returnData($data, "Data retrieved.");
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function addStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        try {
            $product = Product::find($id);
            if(empty($product)){
                return $this->returnStatus(false, "Product not found.", 404);
            }
            // create stock history
            $product->addStock($request->quantity, "0", "ADD_STOCK");

            return $this->returnData($product, "Product stock added.");
        } catch (\Throwable $th) {
            return $this->returnError($th);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function reduceStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        try {
            $product = Product::find($id);
            if(empty($product)){
                return $this->returnStatus(false, "Product not found.", 404);
            }
            // create stock history
            $product->reduceStock($request->quantity, "0", "REDUCE_STOCK");

            return $this->returnData($product, "Product stock reduced.");
        } catch (\Throwable $th) {
            return $this->returnError($th);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function updateStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
        }

        try {
            $product = Product::find($id);
            if(empty($product)){
                return $this->returnStatus(false, "Product not found.", 404);
            }
            // create stock history
            $product->reduceStock($request->quantity, "0", "UPDATE_STOCK");

            return $this->returnData($product, "Product stock updated.");
        } catch (\Throwable $th) {
            return $this->returnError($th);
        }
    }
}
