<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

// base class takes care of serializing result into meaningful JSON
class BaseApiController extends Controller
{
    /**
     * Serialize and send data as sucess response
     *
     * @param  $result result object of http request
     * @param  string  $messsage additional user frendly message
     * @return \Illuminate\Http\Response json response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * Serialize and send data as error response
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}