<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Show the cart page
     */
        public function index()
    {
        $cart = session('cart', []);
        
        // Debug: Log the cart contents
        \Log::info('Cart index - cart contents', [
            'cart' => $cart,
            'cartSize' => count($cart),
            'sessionId' => session()->getId()
        ]);
        
        return view('companies.cart.index', [
            'cartItems' => $cart,
            'total' => collect($cart)->sum('price')
        ]);
    }

    /**
     * Add a report to cart
     */
        public function addToCart(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Add to cart request', $request->all());
 
        $request->validate([
            'company_id' => 'required|integer',
            'report_id' => 'required|integer',
            'report_name' => 'required|string',
            'country' => 'required|string|in:SG,MX',
            'company_name' => 'required|string',
            'price' => 'required|numeric'
        ]);

        $cart = session('cart', []);

        // Generate unique cart item ID
        $cartItemId = uniqid();

        $cart[$cartItemId] = [
            'company_id' => $request->company_id,
            'report_id' => $request->report_id,
            'report_name' => $request->report_name,
            'country' => $request->country,
            'company_name' => $request->company_name,
            'price' => $request->price,
            'added_at' => now()
        ];

        session(['cart' => $cart]);
        return redirect()->back()->with('success', 'Report added to cart successfully!');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($itemId)
    {
        $cart = session('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            session(['cart' => $cart]);
            return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
        }

        return redirect()->route('cart.index')->with('error', 'Item not found in cart!');
    }

    /**
     * Clear the entire cart
     */
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
    }
}
