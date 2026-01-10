@extends('layouts.app')

@section('title', 'Checkout - ShoesPedia')

@section('content')

<style>
    /* KONFIGURASI WARNA UNGU SOFT */
    :root {
        --ungu-soft: rgb(199, 113, 239);
        --ungu-hover: rgb(180, 90, 220);
        --ungu-muda: rgba(199, 113, 239, 0.1);
        --ungu-gelap: #2c003e;
    }

    /* Supaya konten tidak ketiban navbar */
    .page-offset {
        margin-top: 60px;
    }

    /* Sticky sidebar aman */
    .sticky-summary {
        position: sticky;
        top: 100px;
        z-index: 10;
    }

    /* Custom Input Styling */
    .form-control {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 12px 15px;
    }
    .form-control:focus {
        border-color: var(--ungu-soft);
        box-shadow: 0 0 0 0.25rem rgba(199, 113, 239, 0.15);
    }

    /* Card Styling */
    .checkout-card {
        border: none;
        border-radius: 25px;
        overflow: hidden;
    }
    .card-header-ungu {
        background: linear-gradient(135deg, var(--ungu-soft), var(--ungu-hover));
        color: white;
        border: none;
        padding: 20px;
    }

    /* Button Styling */
    .btn-ungu {
        background-color: var(--ungu-soft);
        color: white;
        border: none;
        border-radius: 15px;
        padding: 15px;
        transition: 0.3s;
    }
    .btn-ungu:hover {
        background-color: var(--ungu-hover);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(199, 113, 239, 0.3);
    }

    .text-ungu { color: var(--ungu-soft); }
    .bg-ungu-muda { background-color: var(--ungu-muda); }
</style>

<div class="container py-5 page-offset">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <div class="text-center mb-5">
                <h2 class="fw-bold text-ungu">
                    <i class="bi bi-bag-check-fill me-2"></i> Konfirmasi Pesanan
                </h2>
                <p class="text-muted">Langkah terakhir untuk mendapatkan sepatu impianmu</p>
            </div>

            @if($cart->items->isEmpty())
                <div class="text-center py-5 bg-white shadow-sm rounded-4">
                    <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                    <h4 class="mt-3 fw-bold">Keranjangmu Masih Kosong</h4>
                    <p class="text-muted">Yuk, cari sepatu keren dulu di katalog kami.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-ungu px-5 mt-3">
                        <i class="bi bi-shop me-2"></i> Lihat Produk
                    </a>
                </div>
            @else

            @php
                $subtotal = $cart->items->sum(fn($item) => ($item->product?->price ?? 0) * $item->quantity);
                $shippingCost = 15000;
                $total = $subtotal + $shippingCost;
            @endphp

            <div class="row g-4">

                <div class="col-lg-7">
                    <div class="card checkout-card shadow-sm">
                        <div class="card-header card-header-ungu">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-geo-alt-fill me-2"></i> Informasi Pengiriman
                            </h5>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <form action="{{ route('checkout.store') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Nama Lengkap Penerima</label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Masukkan nama lengkap"
                                        value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Nomor WhatsApp</label>
                                        <input type="text" name="phone" class="form-control"
                                            placeholder="Contoh: 08123456789"
                                            value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-bold small text-uppercase text-muted">Email</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="alamat@email.com"
                                            value="{{ old('email', auth()->user()->email ?? '') }}">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Alamat Lengkap</label>
                                    <textarea name="address" rows="4" class="form-control" 
                                        placeholder="Nama jalan, Nomor rumah, RT/RW, Kecamatan, Kota"
                                        required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold small text-uppercase text-muted">Catatan Pesanan (Opsional)</label>
                                    <textarea name="notes" rows="2" class="form-control" placeholder="Contoh: Titip di satpam / warna cadangan"></textarea>
                                </div>

                                <button type="submit" class="btn btn-ungu btn-lg w-100 fw-bold shadow">
                                    <i class="bi bi-lock-fill me-2"></i> Bayar Sekarang Rp {{ number_format($total) }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card checkout-card shadow-lg sticky-summary border-0">
                        <div class="card-header bg-dark text-white p-4">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-receipt me-2 text-ungu"></i> Ringkasan Belanja
                            </h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                                @foreach($cart->items as $item)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-ungu-muda rounded-3 p-2 me-3">
                                            <i class="bi bi-box-seam text-ungu fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0 fw-bold small">{{ $item->product?->name }}</h6>
                                            <small class="text-muted">{{ $item->quantity }} Item</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold small">Rp {{ number_format($item->quantity * $item->product?->price) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="p-3 rounded-4 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal Produk</span>
                                    <span class="fw-semibold">Rp {{ number_format($subtotal) }}</span>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Biaya Pengiriman</span>
                                    <span class="fw-semibold">Rp {{ number_format($shippingCost) }}</span>
                                </div>

                                <hr class="my-3 opacity-25">

                                <div class="d-flex justify-content-between align-items-center">
                                    <strong class="fs-5">Total Bayar</strong>
                                    <strong class="fs-4 text-ungu">
                                        Rp {{ number_format($total) }}
                                    </strong>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-ungu-muda rounded-3 border border-ungu text-center">
                                <small class="text-ungu fw-bold">
                                    <i class="bi bi-patch-check-fill me-1"></i>
                                    Garansi 100% Produk Original ShoesPedia
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            @endif
        </div>
    </div>
</div>

@endsection