<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class CountriesController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index () : JsonResponse
    {
        $countries = Country::all();
        return new JsonResponse($countries);
    }
}
