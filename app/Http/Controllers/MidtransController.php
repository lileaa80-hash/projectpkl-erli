<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback()
    {
        // 🔥 WAJIB: bukti callback benar-benar masuk
        Log::info('MIDTRANS CALLBACK MASUK');

        $notif = new Notification();

        Log::info('MIDTRANS DATA', (array) $notif);

        /**
         * Contoh order_id:
         * ORDER-12-1700000000
         */
        $parts = explode('-', $notif->order_id);
        $orderId = $parts[1] ?? null;

        if (!$orderId) {
            Log::error('ORDER ID INVALID');
            return response()->json(['message' => 'Invalid order_id'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            Log::error('ORDER TIDAK DITEMUKAN', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        if (in_array($notif->transaction_status, ['capture', 'settlement'])) {
            $order->update([
                'status' => 'processing', // ⬅️ bikin tombol hilang
                'payment_status' => 'paid',
                'payment_type' => $notif->payment_type,
            ]);
        }

        if (in_array($notif->transaction_status, ['deny', 'expire', 'cancel'])) {
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);
        }

        Log::info('ORDER UPDATED', [
            'order_id' => $orderId,
            'status' => $order->status
        ]);

        return response()->json(['message' => 'OK']);
    }
}