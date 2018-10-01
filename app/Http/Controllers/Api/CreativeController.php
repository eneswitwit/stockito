<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Models\BrandCreative;
use App\Transformers\CreativeBrandsTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LukeVear\LaravelTransformer\TransformerEngine;
use App\Http\Controllers\Controller;

/**
 * Class CreativeController
 *
 * @package App\Http\Controllers\Api
 */
class CreativeController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getCreativeBrands(Request $request) : JsonResponse
    {
        $user = $request->user();
        return new JsonResponse(new TransformerEngine($user, new CreativeBrandsTransformer()));
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function removeCreativeBrand(Request $request, $id): JsonResponse
    {
        $creativeBrand = BrandCreative::where('brand_id', '=', $id)->first();
        if ($creativeBrand) {
            $creativeBrand->delete();
        }

	    $user = $request->user();
	    return new JsonResponse(new TransformerEngine($user, new CreativeBrandsTransformer()));
    }

}