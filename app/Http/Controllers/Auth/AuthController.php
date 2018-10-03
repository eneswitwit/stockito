<?php

// namespace
namespace App\Http\Controllers\Auth;

// use
use App\Transformers\BrandTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function user (Request $request)
    {
        return new JsonResponse(new TransformerEngine($request->user(), new UserTransformer()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function brand (Request $request)
    {
        return new JsonResponse(new TransformerEngine($request->user()->brand, new BrandTransformer()));
    }
}
