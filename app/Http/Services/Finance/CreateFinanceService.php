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
            'name' => "Doação Patinhas Felizes",
        ]);

        $price = $stripeClient->prices->create([
            'currency' => 'brl',
            'unit_amount' => data_get($data, 'price') * 100,
            'product' => $product->id,
        ]);

        $checkOut = $stripeClient->checkout->sessions->create([
            'success_url' => env('APP_URL') . 'payment/success',
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
        ]);

        if ($checkOut && $checkOut->id) {
            $data['date'] = Carbon::now()->timezone('America/Sao_Paulo');
            $finance = $repository->create($data);
        }else {
            throw new \Exception('Ocorreu um erro no pagamento!');
        }

        return $finance;
    }
}
