<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(User $user, array $shippingData): Order
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception("Keranjang belanja kosong.");
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {

            // ========================
            // A. VALIDASI STOK & HITUNG TOTAL (AMAN)
            // ========================
            $totalAmount = 0;

            foreach ($cart->items as $item) {
                $product = $item->product;

                if (!$product) {
                    throw new \Exception("Produk tidak ditemukan.");
                }

                if ($item->quantity > $product->stock) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                // 🔐 HARGA WAJIB DARI DATABASE (ANTI HACK)
                $price = $product->discount_price ?? $product->price;

                if ($price === null || $price <= 0) {
                    throw new \Exception("Harga produk {$product->name} tidak valid.");
                }

                $totalAmount += $price * $item->quantity;
            }

            // ========================
            // B. BUAT ORDER
            // ========================
            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => 'ORD-' . now()->format('YmdHis') . '-' . rand(100,999),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'shipping_name'    => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone'   => $shippingData['phone'],
                'total_amount'     => $totalAmount,
            ]);

            // ========================
            // C. SIMPAN ORDER ITEMS
            // ========================
            foreach ($cart->items as $item) {
                $product = $item->product;
                $price   = $product->discount_price ?? $product->price;

                $order->items()->create([
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $price * $item->quantity,
                ]);

                // Kurangi stok
                $product->decrement('stock', $item->quantity);
            }

            // ========================
            // D. MIDTRANS
            // ========================
            try {
                $midtransService = new \App\Services\MidtransService();
                $order->update([
                    'snap_token' => $midtransService->createSnapToken($order)
                ]);
            } catch (\Exception $e) {
                // snap_token boleh null
            }

            // ========================
            // E. BERSIHKAN CART
            // ========================
            $cart->items()->delete();

            return $order;
        });
    }
}