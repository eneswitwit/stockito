<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Http\Controllers\Controller;
use App\Models\FTPUser;
use App\Transformers\FTPUserTransformer;
use Illuminate\Http\JsonResponse;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class FtpController
 *
 * @package App\Http\Controllers\Api
 */
class FtpController extends Controller
{

    /**
     * @param int $user_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show ($user_id, $brand_id = 0): JsonResponse
    {
        if($brand_id === 0) {
            $ftpUser = FTPUser::where('user_id', $user_id)->first();
        } else {
            $ftpUser = FTPUser::where('user_id', $user_id)->where('brand_id', $brand_id)->first();
        }

        if($ftpUser) {
            return new JsonResponse(new TransformerEngine($ftpUser, new FTPUserTransformer()));
        } else {
            return new JsonResponse();
        }
    }
}