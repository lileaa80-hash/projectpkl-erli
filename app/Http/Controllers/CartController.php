<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Tampilkan halaman keranjang
     */
    public function index()
    {
        $cart = $this->cartService->getCart();
        $cart->load(['items.product.primaryImage']);

        return view('cart.index', compact('cart'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $this->cartService->addProduct($product, $request->quantity);

            return back()->with('success', 'Produk ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update quantity item di keranjang
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $this->cartService->updateQuantity($itemId, $request->quantity);
            return back()->with('success', 'Keranjang diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Hapus item dari keranjang
     */
    public function remove($itemId)
    {
        try {
            $this->cartService->removeItem($itemId);
            return back()->with('success', 'Item dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}