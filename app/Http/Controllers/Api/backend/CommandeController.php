<?php

namespace App\Http\Controllers\Api\backend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Commande;
use App\Models\User;
use App\Models\UserBasket;
use App\Traits\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Validator;
use DB;

class CommandeController extends Controller
{
    const ITEM_PER_PAGE = 15;






    public function checkout(Request $request)
    {
        $currentUser = $request->user();
        $totalPriceArtWorks = 0;
        $artwork_basket = $currentUser->artwork_in_basket;
        $stripe = new \Stripe\StripeClient(
            'sk_test_51LPvKKGWFeTB9Tq2Rw8Ufli5GJVAm9tZQuE2co9DbVYFKGglXKX3O2X42nCetbqi3js2JIsA1e7DRmL3Uz8sqdh200rxRhx8YL'
        );
        $ephemeralKey = $stripe->ephemeralKeys->create(
            ["customer" => $currentUser["customer_id"]],
            ["stripe_version" => "2020-08-27"]
        );
        $emphemeralKeyId = $ephemeralKey['id'];
        foreach ($artwork_basket as $item) {
            $totalPriceArtWorks += $item['price'] + 0;
        }
        $username = $currentUser['first_name'] . ' ' . $currentUser['last_name'];
        $country = $currentUser['country'];

        $order = [
            'description' => 'buy artwork',
            'user_name' => $username,
            'place' => $country,
            'total_price' => $totalPriceArtWorks,
        ];

        DB::beginTransaction();
        $commande = Commande::make($order);
        $savedOrder = $commande->save();
        DB::commit();
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $totalPriceArtWorks * 100,
            'currency' => 'usd',
            'automatic_payment_methods[enabled]' => true,
            'customer' => $currentUser["customer_id"],
            'metadata' => [
                'order_id' => $commande['id']
            ]
        ]);
        DB::beginTransaction();
        UserBasket::where('user_id', $currentUser['id'])->delete();
        DB::commit();
        return response()->json(new JsonResponse([
            'publishableKey' => $paymentIntent['client_secret'],
            'paymentIntent' => $paymentIntent['id'],
            'customer' => $paymentIntent['customer'],
            'ephemeralKey' => $ephemeralKey['id']
        ], '', 'Commande saved successfully'), Response::HTTP_OK);
    }
}
