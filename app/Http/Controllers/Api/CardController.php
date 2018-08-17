<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
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

        return new JsonResponse($cards);
    }
}
