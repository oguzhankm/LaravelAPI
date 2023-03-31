<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
/**
 *
 *
 * @OA\Info(
 *      version="2.0.0",
 *      title="Laravel API Documentation",
 *      description="This is a sample API Documentation.",
 *      termsOfService="http://laravelapi.test/api/terms",
 *      @OA\Contact(
 *          email="oguzhankm@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * *  @OA\Schema(
 *     title = "ApiResponse",
 *     description = "ApiResponse model",
 *     type="object",
 *     schema="ApiResponse",
 *     @OA\Property ( property="success",type="bool"),
 *     @OA\Property ( property="data",type="object"),
 *     @OA\Property (property="message",type="string"),
 *     @OA\Property (property="errors", type="object")
 * )
 *
 *
 */
class ApiController extends Controller
{
    public function apiResponse($resultType, $data, $message = null, $code = 200)
    {
        $response = [];
        $response['success'] = $resultType == ResultType::Success ? true : false;

        if (isset($data)){
            if ($resultType != ResultType::Error){
                $response['data'] = $data;
            }
            if ($resultType == ResultType::Error){
                $response['errors'] = $data;
            }
        }
        if (isset($message)){
            $response['message'] = $message;
        }

        return response()->json($response, $code);
    }
}

class ResultType{
    const Success = 1;
    const Information = 2;
    const Warning = 3;
    const Error = 4;
}
