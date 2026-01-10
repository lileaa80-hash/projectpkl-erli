{{-- ================================================
FILE: resources/views/partials/footer.blade.php
STYLE: Dwaa Lux – Elegant Purple Footer
================================================ --}}

<style>
/* Footer Lux – Tema Ungu Elegan */
.footer-lux {
    background: linear-gradient(135deg, #5e2a84, #7b3fa1); /* Gradient ungu */
    color: #f3e9ff;
}

.footer-lux .footer-brand {
    color: #ffffff;
    font-weight: 600;
}

.footer-lux .footer-text {
    color: #e0cfff;
}

.footer-lux .footer-title {
    color: #ffffff;
    font-weight: 500;
    margin-bottom: 1rem;
}

.footer-lux .footer-link {
    color: #d8bfff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-lux .footer-link:hover {
    color: #ffffff;
}

.footer-lux .footer-social {
    color: #f3e9ff;
    font-size: 1.2rem;
    transition: color 0.3s ease, text-shadow 0.3s ease;
}

.footer-lux .footer-social:hover {
    color: #ffffff;
    text-shadow: 0 0 8px rgba(255,255,255,0.6); /* efek glow */
}

.footer-lux .footer-divider {
    border-color: rgba(255, 255, 255, 0.2);
}
</style>

<footer class="footer-lux mt-5">
    <div class="container py-5">

        <div class="row g-4">

            {{-- Brand --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-brand mb-3">
                    <i class="bi bi-lightbulb-fill me-2"></i>
                    Dwaa Lux Lighting
                </h5>
                <p class="footer-text small">
                    Toko lampu hias premium untuk mempercantik setiap sudut rumah Anda.
                    Desain elegan, cahaya hangat, kualitas terbaik.
                </p>

                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="footer-social"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            {{-- Menu --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Menu</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <a href="{{ route('catalog.index') }}" class="footer-link">
                            Katalog Produk
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            Tentang Kami
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            Kontak
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-title">Bantuan</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <a href="#" class="footer-link">FAQ</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">Cara Belanja</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">Kebijakan Privasi</a>
                    </li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-title">Hubungi Kami</h6>
                <ul class="list-unstyled footer-text small">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>
                        Bandung, Indonesia
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        (022) 123-4567
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        support@dwaalux.com
                    </li>
                </ul>
            </div>

        </div>

        <hr class="footer-divider my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="footer-text">
                    © {{ date('Y') }} Dwaa Lux Lighting. All rights reserved.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <img src="{{ asset('images/images.png') }}"
                     alt="Metode Pembayaran"
                     height="28">
            </div>
        </div>

    </div>
</footer>
