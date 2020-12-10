<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseApiController extends Controller
{
    /**
     * @param $data
     * @param $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function returnData($data, $message, $statusCode = 200) {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * @param $er
     * @param int $statusCode
     * @return JsonResponse
     */
    public function returnError($er, $statusCode = 400) {
        return response()->json([
            'status' => false,
            'message' => $er->getMessage(),
        ], $statusCode);
    }

    /**
     * @param bool $status
     * @param string $message
     * @param int $statusCode
     * @return Application|ResponseFactory|Response
     */
    public function returnStatus($status = true, $message = "Data berhasil di ambil", $statusCode = 200) {
        return response([
            "status" => $status,
            "message" => $message,
        ], $statusCode);
    }
}
