<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Models\User;
use App\Transformers\ActivityTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LukeVear\LaravelTransformer\TransformerEngine;

/**
 * Class ActivitiesController
 *
 * @package App\Http\Controllers\Api
 */
class ActivitiesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        $activities = $user->getActivities();
        $activities->load('brand');
        return new JsonResponse(new TransformerEngine($activities, new ActivityTransformer()));
    }
}
