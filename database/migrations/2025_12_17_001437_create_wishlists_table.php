<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Tampilkan halaman wishlist user
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil produk yang di-wishlist user, paginate 12 per page
        $products = $user->wishlists()->paginate(12);

        return view('wishlist.index', compact('products'));
    }

    /**
     * Toggle wishlist (add/remove)
     * Bisa dipanggil via AJAX atau form POST
     */
    public function toggle(Product $product, Request $request)
    {
        $user = $request->user();

        if ($user->hasInWishlist($product)) {
            // Sudah ada → hapus
            $user->wishlists()->detach($product->id);
            $added = false;
        } else {
            // Belum ada → tambah
            $user->wishlists()->attach($product->id);
            $added = true;
        }

        // Jika request AJAX, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'added' => $added,
                'product_id' => $product->id,
            ]);
        }

        // Kalau request biasa, redirect back
        return back()->with('success', $added ? 'Produk ditambahkan ke wishlist.' : 'Produk dihapus dari wishlist.');
    }

    /**
     * Optional: Move produk wishlist ke cart
     */
    public function moveToCart(Product $product)
    {
        $user = Auth::user();

        // Tambahkan ke cart via CartService
        $cartService = app()->make(\App\Services\CartService::class);
        $cartService->addProduct($product, 1);

        // Hapus dari wishlist
        $user->wishlists()->detach($product->id);

        return back()->with('success', 'Produk dipindahkan ke keranjang.');
    }
}