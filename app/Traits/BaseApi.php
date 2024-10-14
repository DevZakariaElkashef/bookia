<?php


namespace App\Traits;

trait BaseApi
{
    public function sendResponse($data, $message, $code = 200)
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}
