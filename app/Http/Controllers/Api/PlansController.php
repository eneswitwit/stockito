<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use App\Transformers\PlanTransformer;
use App\Transformers\SimplePlanTransformer;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use LukeVear\LaravelTransformer\TransformerEngine;

class PlansController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index () : JsonResponse
    {
        $plans = Plan::all();
        return new JsonResponse(new TransformerEngine($plans, new PlanTransformer()));
    }

    /**
     * @return JsonResponse
     */
    public function monthly () : JsonResponse
    {
        $plans = Plan::where('interval', Plan::MONTH_INTERVAL)->get();
        return new JsonResponse(new TransformerEngine($plans, new SimplePlanTransformer()));
    }

    /**
     * @param Plan $plan
     * @return JsonResponse
     */
    public function show (Plan $plan) : JsonResponse
    {
        return new JsonResponse(new TransformerEngine($plan, new PlanTransformer()));
    }
}
