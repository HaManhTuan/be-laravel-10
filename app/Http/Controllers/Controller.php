<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function resultResponse($data): \Illuminate\Http\JsonResponse
    {
        return $data ? $this->successResponse() : $this->errorResponse(['error' => 'System Error']);
    }


    protected function successResponse(array $responseData = ['message' => 'OK']): \Illuminate\Http\JsonResponse
    {
        return response()->json($responseData);
    }

    protected function errorResponse(array $errorData): \Illuminate\Http\JsonResponse
    {
        return response()->json($errorData, 500);
    }

}
