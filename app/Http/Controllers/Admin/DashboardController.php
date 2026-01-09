<?php
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * =====================================
         * 1. STATISTIK UTAMA (SUMMARY CARDS)
         * =====================================
         */
        $stats = [
            // Total omset dari order yang sudah diproses / selesai
            'total_revenue' => Order::whereIn('status', ['processing', 'completed'])
                ->sum('total_amount'),

            // Total semua order
            'total_orders' => Order::count(),

            // Order yang perlu ditindaklanjuti admin
            'pending_orders' => Order::where('status', 'pending')->count(),

            // Total produk
            'total_products' => Product::count(),

            // Total customer (role customer)
            'total_customers' => User::where('role', 'customer')->count(),

            // Produk stok rendah (<= 5)
            'low_stock' => Product::where('stock', '<=', 5)->count(),
        ];

        /**
         * =====================================
         * 2. PESANAN TERBARU (5 TERAKHIR)
         * =====================================
         */
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        /**
         * =====================================
         * 3. PRODUK TERLARIS
         * =====================================
         */
        $topProducts = Product::withCount([
            'orderItems as sold' => function ($q) {
                $q->select(DB::raw('SUM(quantity)'))
                    ->whereHas('order', function ($query) {
                        $query->whereIn('status', ['processing', 'completed']);
                    });
            }
        ])
            ->having('sold', '>', 0)
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        /**
         * =====================================
         * 4. GRAFIK PENDAPATAN (7 HARI TERAKHIR)
         * =====================================
         */
        $revenueChart = Order::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            ])
            ->whereIn('status', ['processing', 'completed'])
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view(
            'admin.dashboard',
            compact('stats', 'recentOrders', 'topProducts', 'revenueChart')
        );
    }
}