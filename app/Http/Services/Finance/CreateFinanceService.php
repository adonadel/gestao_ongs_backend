<?php

namespace App\Http\Services\Finance;

use App\Repositories\FinanceRepository;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;

class CreateFinanceService
{

    public function create(array $data)
    {
        $repository = new FinanceRepository();

        $stripeClient = new StripeClient(env('STRIPE_SECRET'));

        $product = $stripeClient->products->create([
            'name' => "Doação Patinhas Carentes",
        ]);

        $price = $stripeClient->prices->create([
            'currency' => 'brl',
            'unit_amount' => data_get($data, 'value') * 100,
            'product' => $product->id,
        ]);

        if (!isset($data['date'])) {
            $data['date'] = Carbon::now()->timezone('America/Sao_Paulo');
        }

        $finance = $repository->create($data);

        $checkOut = $stripeClient->checkout->sessions->create([
            "success_url" => env("APP_URL") . "/donate/success/{$finance->id}",
            "cancel_url" => env("APP_URL") . "/donate/cancel/{$finance->id}",
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
        ]);

        if ($checkOut && $checkOut->id) {
            $finance->update([
                'session_id' => $checkOut->id,
            ]);
        }else {
            throw new \Exception('Ocorreu um erro no pagamento!');
        }

        $finance->session = $checkOut;

        return $finance;
    }
}
