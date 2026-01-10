@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <style>
        /* Variabel Warna Ungu Muda */
        :root {
            --purple-primary: #a29bfe; /* Ungu pastel */
            --purple-dark: #6c5ce7;
            --purple-soft: rgba(162, 155, 254, 0.15);
            --purple-border: #d6d1ff;
        }

        .text-purple { color: var(--purple-dark) !important; }
        .bg-purple { background-color: var(--purple-primary) !important; }
        .border-purple { border-color: var(--purple-primary) !important; }
        .bg-purple-soft { background-color: var(--purple-soft) !important; }

        /* Card Styling */
        .card-custom {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-radius: 12px;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(162, 155, 254, 0.2) !important;
        }

        /* Custom Badge */
        .badge-purple {
            background-color: var(--purple-soft);
            color: var(--purple-dark);
            border: 1px solid var(--purple-border);
        }

        /* List Group Item Hover */
        .list-group-item:hover {
            background-color: #fcfbff;
        }
    </style>

    <div class="row g-4 mb-4">
        {{-- Revenue Card --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-purple h-100 card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.8rem">Total Pendapatan</p>
                            <h4 class="fw-bold mb-0 text-purple">
                                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                            </h4>
                        </div>
                        <div class="bg-purple-soft p-3 rounded-circle">
                            <i class="bi bi-wallet2 text-purple fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Action Card --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-purple h-100 card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.8rem">Perlu Diproses</p>
                            <h4 class="fw-bold mb-0 text-purple">
                                {{ $stats['pending_orders'] }}
                            </h4>
                        </div>
                        <div class="bg-purple-soft p-3 rounded-circle">
                            <i class="bi bi-box-seam text-purple fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Low Stock Card --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-purple h-100 card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.8rem">Stok Menipis</p>
                            <h4 class="fw-bold mb-0 text-purple">
                                {{ $stats['low_stock'] }}
                            </h4>
                        </div>
                        <div class="bg-purple-soft p-3 rounded-circle">
                            <i class="bi bi-exclamation-triangle text-purple fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm border-start border-4 border-purple h-100 card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.8rem">Total Produk</p>
                            <h4 class="fw-bold mb-0 text-purple">
                                {{ $stats['total_products'] }}
                            </h4>
                        </div>
                        <div class="bg-purple-soft p-3 rounded-circle">
                            <i class="bi bi-tags text-purple fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Revenue Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold">Grafik Penjualan (7 Hari)</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title mb-0 fw-bold">Pesanan Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentOrders as $order)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3 border-light">
                                <div>
                                    <div class="fw-bold text-purple">#{{ $order->order_number }}</div>
                                    <small class="text-muted">{{ $order->user->name }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold mb-1">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                    <span class="badge rounded-pill badge-purple">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white text-center py-3 border-0">
                    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none fw-bold text-purple">
                        Lihat Semua Pesanan <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Selling Products --}}
    <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title mb-0 fw-bold">Produk Terlaris</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @foreach($topProducts as $product)
                    <div class="col-6 col-md-2 text-center">
                        <div class="card h-100 border-0 p-2 card-custom bg-light bg-opacity-50">
                            <img src="{{ $product->image_url }}" class="card-img-top rounded mb-2 mx-auto" style="width: 80px; height: 80px; object-fit: cover;">
                            <h6 class="card-title text-truncate text-purple mb-1" style="font-size: 0.85rem">{{ $product->name }}</h6>
                            <small class="fw-bold text-muted">{{ $product->sold }} terjual</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Script Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient effect untuk grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(162, 155, 254, 0.5)');
        gradient.addColorStop(1, 'rgba(162, 155, 254, 0)');

        const labels = {!! json_encode($revenueChart->pluck('date')) !!};
        const data = {!! json_encode($revenueChart->pluck('total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#a29bfe', 
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#a29bfe',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#fff',
                        titleColor: '#6c5ce7',
                        bodyColor: '#333',
                        borderColor: '#d6d1ff',
                        borderWidth: 1,
                        displayColors: false,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.03)' },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(value);
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
@endsection