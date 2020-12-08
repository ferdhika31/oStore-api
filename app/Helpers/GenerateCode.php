<?php
namespace App\Helpers;

use App\Models\Order;

class GenerateCode {

    /**
     * @return string
     */
    public static function generateOrderCode() {
        $code = "OD-".date("ymdhis") . rand(10, 99);

        // call the same function if the barcode exists already
        if (Order::where('code',$code)->exists()) {
            return GenerateCode::generateOrderCode();
        }

        // otherwise, it's valid and can be used
        return $code;
    }
}
