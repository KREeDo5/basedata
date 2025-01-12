<?php

namespace App\Traits;

trait Functions
{
     public function getResponse($httpCode, $errorMessage = '', $data = null)
     {
        $response = [
            'meta' => ['success' => $httpCode == 200 ? true : false, 'error' => $errorMessage],
            'data' => $data == null ? (object) [] : $data
        ];
        return response()->json($response, $httpCode, [], JSON_UNESCAPED_UNICODE);
     }
}