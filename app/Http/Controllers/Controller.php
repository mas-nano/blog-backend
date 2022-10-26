<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseValidation($errors)
    {
        return response()->json(
            [
                "status" => false,
                "message" => $errors,
                "data" => null,
            ],
            422
        );
    }

    public function responseError($msg, $code, $data = null)
    {
        return response()->json(
            [
                "status" => false,
                "message" => $msg,
                "data" => $data,
            ],
            $code
        );
    }

    public function responseSuccess($msg = null)
    {
        return response()->json(
            [
                "status" => true,
                "message" => $msg,
            ],
            200
        );
    }

    public function responseSuccessWithData($msg = null, $data = null)
    {
        return response()->json(
            [
                "status" => true,
                "message" => $msg,
                "data" => $data,
            ],
            200
        );
    }

    public function responseCreated($msg = null, $data = null)
    {
        return response()->json(
            [
                "status" => true,
                "message" => $msg,
                "data" => $data,
            ],
            201
        );
    }
}
