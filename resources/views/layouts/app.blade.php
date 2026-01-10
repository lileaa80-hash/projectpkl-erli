<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Toko Online') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Toko online terpercaya dengan produk berkualitas')">

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- STYLE UNTUK TEMA UNGU SOFT GLOBAL --}}
    <style>
        :root {
            /* Definisi warna ungu soft agar mudah dipanggil */
            --ungu-soft: rgb(199, 113, 239);
            --ungu-hover: rgb(180, 90, 220);
            --ungu-gelap: #2c003e; /* Untuk Footer */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #fcfaff; /* Background putih keunguan sangat tipis */
        }

        /* 1. PAKSA SEMUA TOMBOL PRIMARY JADI UNGU */
        .btn-primary, 
        .btn-tambah-keranjang,
        button[type="submit"],
        .btn-ungu {
            background-color: var(--ungu-soft) !important;
            border-color: var(--ungu-soft) !important;
            color: white !important;
            border-radius: 10px !important;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--ungu-hover) !important;
            transform: translateY(-2px);
        }

        /* 2. HAPUS FOOTER BAWAAN LAMA (HITAM) */
        /* Menggunakan CSS untuk menyembunyikan footer lama agar tidak double */
        footer.bg-dark, 
        .footer-lama,
        #footer-original { 
            display: none !important; 
        }

        /* 3. PERCANTIK BADGE WISHLIST */
        .badge.bg-danger {
            background-color: var(--ungu-soft) !important;
        }

        /* 4. PERCANTIK FORM INPUT SAAT DIKLIK */
        .form-control:focus {
            border-color: var(--ungu-soft);
            box-shadow: 0 0 0 0.25rem rgba(199, 113, 239, 0.25);
        }
    </style>

    @stack('styles')
</head>
<body>
    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- FLASH MESSAGES --}}
    <div class="container mt-3">
        @include('partials.flash-messages')
    </div>

    {{-- MAIN CONTENT --}}
    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    {{-- 
       Catatan: Jika footer Anda masih muncul dua kali, 
       pastikan Anda menghapus kode footer di dalam file ini 
       dan biarkan halaman (home.blade.php) yang memanggil footer ungu barunya.
    --}}
    @include('partials.footer')

    @stack('scripts')
    
    <script>
        /** AJAX Wishlist Logic **/
        async function toggleWishlist(productId) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`/wishlist/toggle/${productId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                    },
                });

                if (response.status === 401) {
                    window.location.href = "/login";
                    return;
                }

                const data = await response.json();

                if (data.status === "success") {
                    updateWishlistUI(productId, data.added);
                    updateWishlistCounter(data.count);
                    showToast(data.message);
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        function updateWishlistUI(productId, isAdded) {
            const buttons = document.querySelectorAll(`.wishlist-btn-${productId}`);
            buttons.forEach((btn) => {
                const icon = btn.querySelector("i");
                if (isAdded) {
                    icon.classList.remove("bi-heart", "text-secondary");
                    icon.classList.add("bi-heart-fill", "text-danger");
                } else {
                    icon.classList.remove("bi-heart-fill", "text-danger");
                    icon.classList.add("bi-heart", "text-secondary");
                }
            });
        }

        function updateWishlistCounter(count) {
            const badge = document.getElementById("wishlist-count");
            if (badge) {
                badge.innerText = count;
                badge.style.display = count > 0 ? "inline-block" : "none";
            }
        }
    </script>
</body>
</html>