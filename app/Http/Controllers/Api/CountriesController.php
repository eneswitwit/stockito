<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

/**
 * Class CountriesController
 *
 * @package App\Http\Controllers\Api
 */
class CountriesController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index () : JsonResponse
    {
        $countries = Country::orderBy('name')->get();
        return new JsonResponse($countries);
    }
}
