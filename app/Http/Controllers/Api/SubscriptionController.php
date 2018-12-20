<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Http\Requests\Subscriptions\PaySubscriptionRequest;
use App\Http\Requests\Subscriptions\SwapSubscriptionRequest;
use App\Models\Plan;
use App\Models\GDPR;
use App\Models\Subscription;
use App\Models\User;
use App\Transformers\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Cashier\Billable;
use LukeVear\LaravelTransformer\TransformerEngine;
use Stripe\Error\InvalidRequest;
use Illuminate\Notifications\Notifiable;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers\Api
 */
class SubscriptionController extends Controller
{

    use Notifiable;

    /**
     * @param \App\Http\Requests\Subscriptions\SwapSubscriptionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function upgrade(SwapSubscriptionRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        /**
         * @var Plan $plan
         */
        $plan = (new Plan)->where('id', $request->input('plan.id'))->first();

        // Stripe plan
        $stripePlan = $plan->getStripePlan();

        // Upgrade
        try {
            $user->subscription('main')->swap($stripePlan->id);

            // Delete if there are any downgrade plans
            Subscription::where('user_id', $user->id)->first()->cancelDowngrade();

            // Create GDPR entry
            $gdpr = new GDPR;
            $gdpr->user_id = $user->id;
            $gdpr->stripe_plan_id = $plan->stripe_id;
            $gdpr->save();


        } catch (InvalidRequest $exception) {
            return new JsonResponse(['success' => false, 'errors' => ['voucher' => $exception->getMessage()]]);
        }

        $user->sendUpgradeEmail(md5(time()));
        return new JsonResponse(['success' => true, 'data' => new TransformerEngine($user, new UserTransformer())]);
    }

    /**
     * @param \App\Http\Requests\Subscriptions\SwapSubscriptionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function downgrade(SwapSubscriptionRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        /**
         * @var Plan $plan
         */
        $plan = (new Plan)->where('id', $request->input('plan.id'))->first();

        // Stripe plan
        $stripePlan = $plan->getStripePlan();

        // Downgrade
        try {

            Subscription::where('user_id', $user->id)->first()->downgradeToPlan($stripePlan->id);
            if ($user->subscription('main')->onGracePeriod()) {
                $user->subscription('main')->resume();
            }
            // Create GDPR entry
            $gdpr = new GDPR;
            $gdpr->user_id = $user->id;
            $gdpr->stripe_plan_id = $plan->stripe_id;
            $gdpr->save();

        } catch (InvalidRequest $exception) {
            return new JsonResponse(['success' => false, 'errors' => ['voucher' => $exception->getMessage()]]);
        }

        $user->sendDowngradeEmail(md5(time()));
        return new JsonResponse(['success' => true, 'data' => new TransformerEngine($user, new UserTransformer())]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function downgradeSubscription(Request $request): JsonResponse
    {

        /**
         * @var User $user
         */
        $user = $request->user();

        /**
         * @var Plan $plan
         */
        $plan = (new Plan)->where('id', $request->input('plan.id'))->first();

        // Stripe plan
        $stripePlan = $plan->getStripePlan();

        // downgrade
        $subscription = $user->subscription('main');
        $subscription->cancel();
        Subscription::where('user_id', $user->id)->first()->downgradeToPlan($stripePlan->id);

        $newSub = $user->newSubscription('main', $stripePlan->id)->create();

        $stripeNewSub = $newSub->asStripeSubscription();
        $stripeNewSub->plan = $stripePlan;
        $stripeNewSub->cancel_at_period_end = false;
        $stripeNewSub->prorate = false;
        $stripeNewSub->trial_end = $subscription->ends_at;
        $stripeNewSub->save();

        // Create GDPR entry
        $gdpr = new GDPR;
        $gdpr->user_id = $user->id;
        $gdpr->stripe_plan_id = $plan->stripe_id;
        $gdpr->save();

        return new JsonResponse(['success' => true, 'data' => new TransformerEngine($user, new UserTransformer())]);
    }

    /**
     * @param \App\Http\Requests\Subscriptions\PaySubscriptionRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function paySubscription(PaySubscriptionRequest $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();

        /**
         * @var Plan $plan
         */
        $plan = (new Plan)->where('id', $request->input('plan.id'))->first();

        // Stripe plan
        $stripePlan = $plan->getStripePlan();

        // Creditcard details
        $expMount = $request->input('token')['card']['exp_month'];
        $expYear = $request->input('token')['card']['exp_year'];
        $expirationDate = Carbon::createFromDate($expYear, $expMount);

        // Stripe options
        $options['business_vat_id'] =  $user->brand->eur_uid;
        $options['description'] = $user->brand->company_name;
        $options['metadata'] = [
            'address_line1' => $user->brand->address_1,
            'address_line2' => $user->brand->address_2,
            'address_city' => $user->brand->city,
            'address_zip' => $user->brand->zip,
            'address_country' => $user->brand->country->iso_3166_2,
            'tax_number' => $user->brand->eur_uid
        ];

        if ($request->input('selectedCard', false)) {
            $options['default_source'] = $request->input('selectedCard');
        }

        // Create new subscription or upgrade/downgrade
        if ($request->has('voucher')) {
            try {
                if (!$user->subscribed('main')) {
                    $user->newSubscription('main', $stripePlan->id)
                        ->withCoupon($request->input('voucher'))
                        ->withMetadata(['ip_address' => \Request::ip()])
                        ->create($request->input('selectedCard', false) ? null : $request->input('token.id'), $options);
                } else {
                    // Upgrade or Downgrade
                    $user->subscription('main')->swap($stripePlan->id);
                }
            } catch (InvalidRequest $exception) {
                return new JsonResponse(['success' => false, 'errors' => ['voucher' => $exception->getMessage()]]);
            }
        } else {
            try {
                if (!$user->subscribed('main')) {
                    $user->newSubscription('main', $stripePlan->id)
                        ->withMetadata(['ip_address' => \Request::ip()])
                        ->create($request->input('selectedCard', false) ? null : $request->input('token.id'), $options);
                } else {
                    // Upgrade or Downgrade
                    $user->subscription('main')->swap($stripePlan->id);
                }
            } catch (InvalidRequest $exception) {
                return new JsonResponse(['success' => false, 'errors' => ['voucher' => $exception->getMessage()]]);
            }
        }

        // Update expiration date
        User::where('id', $user->id)->update(['trial_ends_at' => $expirationDate]);

        // Create GDPR entry
        $gdpr = new GDPR;
        $gdpr->user_id = $user->id;
        $gdpr->stripe_plan_id = $plan->stripe_id;
        $gdpr->save();

        return new JsonResponse(['success' => true, 'data' => new TransformerEngine($user, new UserTransformer())]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function cancelSubscription(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($user->subscription('main')) {
            $user->subscription('main')->cancel();
            Subscription::where('user_id', $user->id)->first()->cancelDowngrade();

            return new JsonResponse([
                'canceled' => true,
                'message' => 'Subscription has been canceled',
                'data' => new TransformerEngine($user, new UserTransformer())
            ]);
        }

        return new JsonResponse([
            'canceled' => false,
            'message' => 'User is not subscribed.',
            'data' => new TransformerEngine($user, new UserTransformer())
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function resumeSubscription(Request $request): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        if ($user->subscription('main') && $user->subscription('main')->onGracePeriod()) {
            $user->subscription('main')->resume();
            return new JsonResponse([
                'resume' => true,
                'message' => 'Subscription has been resumed',
                'data' => new TransformerEngine($user, new UserTransformer())
            ]);
        }

        return new JsonResponse([
            'canceled' => false,
            'message' => 'User is not in grace period!!',
            'data' => new TransformerEngine($user, new UserTransformer())
        ]);
    }

    /**
     * @return int|null
     */
    public function getTaxPercentage() {
        return Billable::taxPercentage();
    }
}
