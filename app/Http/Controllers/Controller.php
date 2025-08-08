<?php

namespace App\Http\Controllers;

use App\Arch\Domain\Response\BaseResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller {

    protected function applyRules(Request $request, array $rules) {
        $validator = Validator::make($request -> all(), $rules);

        if ($validator -> fails()) {
            throw new HttpResponseException(response() -> json([
                'message' => 'Se ha producido un error al validar la request',
                'data' => $validator -> errors()
            ], Response::HTTP_BAD_REQUEST));
        }
    }

    protected function getResponse(BaseResponse $response) {
        $statusCode = $response -> getData() == null ? Response::HTTP_ACCEPTED : Response::HTTP_OK;

        return response() -> json([
            'message' => $response -> getMessage(),
            'data' => $response -> getData(),
        ], $statusCode);
    }


}
