<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    public function returnData($data, $message, $statusCode = 200) {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function returnError($er, $statusCode = 400) {
        return response()->json([
            'message' => $er->getMessage(),
        ], $statusCode);
    }
    
    public function returnStatus($status = true, $message = "Data berhasil di ambil", $statusCode = 200) {
        return response([
            "status" => $status,
            "message" => $message,
        ], $statusCode);
    }
}