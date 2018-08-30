<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Models\Plan;
use App\Transformers\PlanTransformer;
use App\Transformers\SimplePlanTransformer;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use LukeVear\LaravelTransformer\TransformerEngine;
use Log;

/**
 * Class PlansController
 * @package App\Http\Controllers\Api
 */
class PlansController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index () : JsonResponse
    {
        $plans = Plan::orderBy('price', 'ASC')->get();
        return new JsonResponse(new TransformerEngine($plans, new SimplePlanTransformer()));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function monthly () : JsonResponse
    {
        $plans = Plan::orderBy('price', 'ASC')->get();
        //$plans = Plan::where('interval', Plan::MONTH_INTERVAL)->get();
        return new JsonResponse(new TransformerEngine($plans, new SimplePlanTransformer()));
    }

    /**
     * @param \App\Models\Plan $plan
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function show (Plan $plan) : JsonResponse
    {
        return new JsonResponse(new TransformerEngine($plan, new PlanTransformer()));
    }
}
