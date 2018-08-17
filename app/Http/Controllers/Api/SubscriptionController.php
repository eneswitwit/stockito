<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Subscriptions\PaySubscriptionRequest;
use App\Models\Brand;
use App\Models\Plan;
use App\Models\User;
use App\Transformers\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LukeVear\LaravelTransformer\TransformerEngine;
use Stripe\Error\InvalidRequest;

class SubscriptionController extends Controller
{
    /**
     * @param PaySubscriptionRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function paySubscription (PaySubscriptionRequest $request) : JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        $plan = (new Plan)->where('id', $request->input('plan.id'))->first();

        $stripePlan = $plan->getStripePlan();

        $expMount = $request->input('token')['card']['exp_month'];
        $expYear = $request->input('token')['card']['exp_year'];
        $expirationDate = Carbon::createFromDate($expYear, $expMount);

        $options = [];

        if ($request->input('selectedCard', false)) {
            $options['default_source'] = $request->input('selectedCard');
        }

        if ($request->has('voucher')) {
            try {
                $user->newSubscription('main', $stripePlan->id)
                    ->withCoupon($request->input('voucher'))
                    ->create($request->input('selectedCard', false) ? null : $request->input('token.id'), $options);
            } catch (InvalidRequest $exception) {
                return new JsonResponse(['success' => false, 'errors' => ['voucher' => $exception->getMessage()]]);
            }
        } else {
            try {
                $user->newSubscription('main', $stripePlan->id)->create($request->input('selectedCard', false) ? null : $request->input('token.id'), $options);
            } catch (InvalidRequest $exception) {
                return new JsonResponse(['success' => false, 'errors' => ['voucher' => $exception->getMessage()]]);
            }
        }

        User::where('id', $user->id)->update(['trial_ends_at' => $expirationDate]);

        return new JsonResponse(['success' => true, 'data' => new TransformerEngine($user, new UserTransformer())]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function cancelSubscription (Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($user->subscription('main')) {
            $user->subscription('main')->cancel();
            return new JsonResponse(['canceled' => true, 'message' => 'Subscription has been canceled', 'data' => new TransformerEngine($user, new UserTransformer())]);
        }

        return new JsonResponse(['canceled' => false, 'message' => 'User is not subscriber!', 'data' => new TransformerEngine($user, new UserTransformer())]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function resumeSubscription (Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($user->subscription('main') && $user->subscription('main')->onGracePeriod()) {
            $user->subscription('main')->resume();
            return new JsonResponse(['resume' => true, 'message' => 'Subscription has been resumed', 'data' => new TransformerEngine($user, new UserTransformer())]);
        }

        return new JsonResponse(['canceled' => false, 'message' => 'User is not in grace period!!', 'data' => new TransformerEngine($user, new UserTransformer())]);
    }
}
