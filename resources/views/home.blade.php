    @extends('layouts.app')

    @section('title', 'Beranda - ShoesPedia')

    @section('content')
    <style>
        /* DEFINISI WARNA UNGU SOFT */
        :root {
            --ungu-soft: rgb(199, 113, 239);
            --ungu-hover: rgb(179, 93, 219);
        }

        .bg-soft-purple {
            background-color: var(--ungu-soft) !important;
        }

        .text-soft-purple {
            color: var(--ungu-soft) !important;
        }

        /* Update Tombol Utama */
        .btn-light {
            color: var(--ungu-soft) !important;
            background-color: #ffffff !important;
            border: none !important;
            font-weight: bold;
            border-radius: 12px;
        }

        /* Paksa Tombol Keranjang Jadi Ungu (Jika ada di partials) */
        .btn-primary, .btn-tambah-keranjang {
            background-color: var(--ungu-soft) !important;
            border-color: var(--ungu-soft) !important;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: var(--ungu-hover) !important;
        }

        /* Logo Styling */
        .logo-box {
            background: white;
            padding: 10px;
            border-radius: 15px;
            display: inline-flex;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Card Styling */
        .hover-lift {
            transition: all 0.3s ease;
            border-radius: 20px;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(199, 113, 239, 0.2) !important;
        }

        .title-decorator {
            width: 50px;
            height: 3px;
            background: var(--ungu-soft);
            display: block;
            margin: 8px 0 20px 0;
            border-radius: 10px;
        }

        /* Banner Promo Ungu */
        .promo-purple {
            background: linear-gradient(135deg, rgb(199, 113, 239) 0%, rgb(150, 80, 200) 100%) !important;
            border-radius: 25px !important;
        }
    </style>

    {{-- Hero Section --}}
    <section class="text-white py-5 bg-soft-purple">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center mb-4">
                        <div class="logo-box">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="rgb(199, 113, 239)" viewBox="0 0 16 16">
                                <path d="M11.5 4a.5.5 0 0 1 .5.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-4 0 1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h9.5a.5.5 0 0 1 0 1H11.5ZM3 11a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm10 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm-1-7H2v7h1.22a2 2 0 0 1 3.56 0h4.44a2 2 0 0 1 3.56 0h.22V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 12.02 6H11v-1.5a.5.5 0 0 0-.5-.5Z"/>
                            </svg>
                        </div>
                        <div class="ms-3 text-white">
                            <h4 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 2px;">Cool ShoeShop</h4>
                            <small class="opacity-75">Premium Aesthetic Shop</small>
                        </div>
                    </div>

                    <h1 class="display-4 fw-bold mb-3">Belanja Online Mudah & Terpercaya</h1>
                    <p class="lead mb-4">Dapatkan koleksi sepatu terbaik dengan sentuhan warna favoritmu.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg shadow-sm px-4">
                        <i class="bi bi-bag-check me-2"></i>Mulai Belanja
                    </a>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <img src="https://www.svgrepo.com/show/422280/shoe-running-sport.svg" alt="Hero" class="img-fluid" 
                        style="max-height: 380px; filter: brightness(0) invert(1); opacity: 0.9;">
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori Populer --}}
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="fw-bold mb-0">Kategori Populer</h2>
            <span class="title-decorator"></span>
            <div class="row g-4">
                @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm text-center h-100 hover-lift">
                            <div class="card-body">
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                    class="rounded-circle mb-3 border p-1" width="80" height="80" style="object-fit: cover;">
                                <h6 class="card-title mb-0 text-dark fw-bold small">{{ $category->name }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Produk Unggulan --}}
    <section class="py-5 bg-light">
        <div class="container py-4 border bg-white shadow-sm" style="border-radius: 30px;">
            <div class="row align-items-end mb-4 px-3">
                <div class="col-md-8">
                    <span class="text-soft-purple fw-bold text-uppercase small">Pilihan Terbaik</span>
                    <h2 class="fw-bold mb-0">Produk Unggulan</h2>
                    <span class="title-decorator"></span>
                </div>
                <div class="col-md-4 text-md-end mt-2">
                    <a href="{{ route('catalog.index') }}" class="text-soft-purple fw-bold text-decoration-none">
                        Lihat Semua <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="hover-lift h-100 shadow-sm border-0 bg-white overflow-hidden">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Banner Promo (DIBUAT UNGU) --}}
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card text-white border-0 shadow-sm promo-purple" style="min-height: 200px;">
                        <div class="card-body d-flex flex-column justify-content-center p-4">
                            <h3 class="fw-bold">Flash Sale!</h3>
                            <p>Diskon hingga 50% untuk produk pilihan</p>
                            <a href="#" class="btn btn-light px-4" style="width: fit-content;">Lihat Promo</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white border-0 shadow-sm promo-purple" style="min-height: 200px; opacity: 0.9;">
                        <div class="card-body d-flex flex-column justify-content-center p-4">
                            <h3 class="fw-bold">Member Baru?</h3>
                            <p>Dapatkan voucher Rp 50.000 untuk Anda</p>
                            <a href="{{ route('register') }}" class="btn btn-light px-4" style="width: fit-content;">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Terbaru --}}
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="fw-bold mb-0">Koleksi Terbaru</h2>
            <span class="title-decorator"></span>
            <div class="row g-4">
                @foreach($latestProducts as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="hover-lift h-100 shadow-sm border-0 bg-white overflow-hidden">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endsection