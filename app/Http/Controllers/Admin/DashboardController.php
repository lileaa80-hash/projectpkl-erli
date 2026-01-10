<?php
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
        // Kode ini mengambil semua pesanan tanpa peduli status agar Dashboard tidak Rp 0
        $stats = [
            'total_revenue' => Order::sum('total_amount'), 
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'low_stock' => Product::where('stock', '<=', 5)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Ambil produk terlaris berdasarkan semua pesanan yang ada
        $topProducts = Product::withCount(['orderItems as sold' => function ($q) {
                $q->select(DB::raw('SUM(quantity)'));
            }])
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        $revenueChart = Order::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            ])
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts', 'revenueChart'));
    }
}