@extends('layouts.app')

@section('title', 'Katalog Produk - ShoesPedia')

@section('content')
<style>
    /* KONFIGURASI WARNA UNGU SOFT */
    :root {
        --ungu-soft: rgb(199, 113, 239);
        --ungu-hover: rgb(180, 90, 220);
        --ungu-muda: rgba(199, 113, 239, 0.1);
        --ungu-gelap: #2c003e;
    }

    /* Sidebar Styling */
    .filter-card {
        border: none;
        border-radius: 20px;
        background: #ffffff;
    }
    .filter-header {
        background: transparent;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.25rem;
    }
    .filter-title {
        color: var(--ungu-soft);
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Input & Radio Styling */
    .form-check-input:checked {
        background-color: var(--ungu-soft);
        border-color: var(--ungu-soft);
    }
    .form-control:focus {
        border-color: var(--ungu-soft);
        box-shadow: 0 0 0 0.25rem rgba(199, 113, 239, 0.25);
    }

    /* Badge & Button Custom */
    .badge-ungu {
        background-color: var(--ungu-muda);
        color: var(--ungu-soft);
        border-radius: 8px;
    }
    .btn-ungu {
        background-color: var(--ungu-soft);
        color: white;
        border: none;
        border-radius: 10px;
        transition: 0.3s;
    }
    .btn-ungu:hover {
        background-color: var(--ungu-hover);
        color: white;
        transform: translateY(-2px);
    }
    .btn-outline-ungu {
        border: 1px solid var(--ungu-soft);
        color: var(--ungu-soft);
        border-radius: 10px;
        transition: 0.3s;
    }
    .btn-outline-ungu:hover {
        background-color: var(--ungu-soft);
        color: white;
    }

    /* Product Grid Styling */
    .catalog-header {
        background-color: white;
        padding: 1.5rem;
        border-radius: 20px;
        margin-bottom: 2rem;
    }

    /* Pagination Styling */
    .pagination .page-item.active .page-link {
        background-color: var(--ungu-soft);
        border-color: var(--ungu-soft);
    }
    .pagination .page-link {
        color: var(--ungu-soft);
        border-radius: 8px;
        margin: 0 3px;
    }

    .hover-lift {
        transition: all 0.3s ease;
        border-radius: 20px;
    }
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(199, 113, 239, 0.15) !important;
    }
</style>

<div class="container py-5">
    <div class="row">
        {{-- SIDEBAR FILTER --}}
        <div class="col-lg-3 mb-4">
            <div class="card filter-card shadow-sm sticky-top" style="top: 100px; z-index: 10;">
                <div class="card-header filter-header">
                    <h5 class="mb-0 filter-title">
                        <i class="bi bi-funnel-fill me-2"></i>Filter Produk
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        {{-- Filter Kategori --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 small text-uppercase" style="letter-spacing: 1px;">Kategori</h6>
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="category"
                                           id="cat-{{ $category->slug }}"
                                           value="{{ $category->slug }}"
                                           {{ request('category') == $category->slug ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label d-flex justify-content-between align-items-center w-100"
                                           for="cat-{{ $category->slug }}">
                                        <span class="small">{{ $category->name }}</span>
                                        <span class="badge badge-ungu">{{ $category->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Filter Harga --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 small text-uppercase" style="letter-spacing: 1px;">Rentang Harga</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-ungu btn-sm w-100 mt-2 shadow-sm">
                                Terapkan Harga
                            </button>
                        </div>

                        {{-- Filter Diskon --}}
                        <div class="mb-4 border-top pt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="on_sale" id="on_sale" value="1" {{ request('on_sale') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label small fw-bold" for="on_sale">
                                    <i class="bi bi-percent text-danger me-1"></i> Sedang Diskon
                                </label>
                            </div>
                        </div>

                        {{-- Reset Filter --}}
                        @if(request()->hasAny(['category', 'min_price', 'max_price', 'on_sale']))
                            <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary btn-sm w-100 rounded-3">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Semua
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            {{-- Catalog Header & Sorting --}}
            <div class="catalog-header shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        @if(request('q'))
                            Pencarian: <span class="text-soft-purple">"{{ request('q') }}"</span>
                        @elseif(request('category'))
                            Kategori: <span class="text-soft-purple">{{ $categories->firstWhere('slug', request('category'))?->name }}</span>
                        @else
                            Semua Koleksi Sepatu
                        @endif
                    </h4>
                    <p class="text-muted small mb-0">{{ $products->total() }} produk tersedia untuk Anda</p>
                </div>
                
                <div class="d-flex align-items-center bg-light p-2 rounded-3">
                    <i class="bi bi-sort-down me-2 text-soft-purple ms-2"></i>
                    <select class="form-select form-select-sm border-0 bg-transparent" style="width: 180px; cursor: pointer;"
                            onchange="window.location.href = this.value">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>
            </div>

            {{-- Product Grid --}}
            @if($products->count())
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            <div class="hover-lift h-100 shadow-sm overflow-hidden">
                                @include('partials.product-card', ['product' => $product])
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white shadow-sm rounded-4">
                    <div class="mb-4">
                        <i class="bi bi-search text-muted display-1 opacity-25"></i>
                    </div>
                    <h5 class="fw-bold">Yah, produk tidak ditemukan...</h5>
                    <p class="text-muted">Coba cari dengan kata kunci lain atau hapus filter</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-ungu px-4 py-2 mt-2">
                        Lihat Semua Koleksi
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script untuk auto-submit filter harga jika perlu --}}
<script>
    // Opsional: Jika ingin form otomatis submit saat min/max harga diketik (dengan delay)
    // Biar UX lebih enak, tombol "Terapkan" sudah cukup baik.
</script>
@endsection