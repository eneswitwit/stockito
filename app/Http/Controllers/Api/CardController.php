<?php

// namespace
namespace App\Http\Controllers\Api;

// use
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

/**
 * Class CardController
 * @package App\Http\Controllers\Api
 */
class CardController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $response = \Stripe\Customer::retrieve($request->user()->stripe_id)->sources->all([
                'object' => 'card'
            ]);
            $cards = [];

            foreach ($response->data as $card) {
                $cards[] = [
                    'id' => $card->id,
                    'last4' => $card->last4,
                    'expMonth' => $card->exp_month,
                    'expYear' => $card->exp_year,
                    'funding' => $card->funding,
                    'brand' => $card->brand,
                ];
            }
        } catch (Exception $e) {
            $cards = [];
        }

        return new JsonResponse($cards);
    }
}
