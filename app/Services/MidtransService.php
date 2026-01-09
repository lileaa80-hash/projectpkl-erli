<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = config('midtrans.is_sanitized', true);
        Config::$is3ds        = config('midtrans.is_3ds', true);
    }

    /**
     * Membuat Snap Token untuk order tertentu
     *
     * @param Order $order
     * @return string
     * @throws Exception
     */
    public function createSnapToken(Order $order): string
    {
        if ($order->items->isEmpty()) {
            throw new Exception('Order tidak memiliki item.');
        }

        // Pastikan order_id unik untuk Midtrans
        $uniqueOrderId = $order->order_number . '-' . time();

        // Transaction details
        $transactionDetails = [
            'order_id'     => $uniqueOrderId,
            'gross_amount' => (int) $order->total_amount,
        ];

        // Customer details (pastikan tidak null)
        $customerDetails = [
            'first_name' => $order->user->name ?? 'Customer',
            'email'      => $order->user->email ?? 'no-reply@example.com',
            'phone'      => $order->shipping_phone ?? $order->user->phone ?? '08123456789',
            'billing_address' => [
                'first_name' => $order->shipping_name ?? 'Customer',
                'phone'      => $order->shipping_phone ?? '08123456789',
                'address'    => $order->shipping_address ?? '-',
            ],
            'shipping_address' => [
                'first_name' => $order->shipping_name ?? 'Customer',
                'phone'      => $order->shipping_phone ?? '08123456789',
                'address'    => $order->shipping_address ?? '-',
            ],
        ];

        // Item details
        $itemDetails = $order->items->map(function ($item) {
            return [
                'id'       => (string) $item->product_id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => substr($item->product_name, 0, 50),
            ];
        })->toArray();

        // Ongkir
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Biaya Pengiriman',
            ];
        }

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details'    => $customerDetails,
            'item_details'        => $itemDetails,
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (Exception $e) {
            logger()->error('Midtrans Snap Token Error', [
                'order_id' => $uniqueOrderId,
                'error'    => $e->getMessage(),
            ]);
            throw new Exception('Gagal membuat transaksi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Cek status transaksi
     */
    public function checkStatus(string $orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (Exception $e) {
            throw new Exception('Gagal mengecek status: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan transaksi
     */
    public function cancelTransaction(string $orderId)
    {
        try {
            return Transaction::cancel($orderId);
        } catch (Exception $e) {
            throw new Exception('Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}