<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;

class MergeCartListener
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        // contoh logika merge cart (pseudo)
        if (session()->has('cart')) {
            foreach (session('cart') as $item) {
                Cart::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'product_id' => $item['product_id'],
                    ],
                    [
                        'quantity' => $item['quantity'],
                    ]
                );
            }

            session()->forget('cart');
        }
    }
}