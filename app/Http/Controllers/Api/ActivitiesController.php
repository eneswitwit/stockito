<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Transformers\ActivityTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LukeVear\LaravelTransformer\TransformerEngine;

class ActivitiesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
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
