<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Show the cart page
    public function showCart()
    {
        if (Auth::check()) {
            $cart = session()->get('cart', []);
            return view('cart', compact('cart'));
        } else {
            return redirect()->route('loginpage');
        }
    }

    // Add product to cart
    public function addToCart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('loginpage');
        }

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $selectedSize = $request->input('selected_size', null); // Get selected size from form

        $product = Products::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Parse product sizes (already cast to array in model)
        $productSizes = is_array($product->size) ? $product->size : [];

        // Validate that selected size is provided and available if sizes exist
        if (!empty($productSizes)) {
            if (!$selectedSize) {
                return redirect()->back()->with('error', 'Please select a size.');
            }
            if (!in_array($selectedSize, $productSizes)) {
                return redirect()->back()->with('error', 'Invalid size selected.');
            }
        }

        // Get images (already cast to array in model)
        $imageArray = [];
        if (!empty($product->images)) {
            if (is_array($product->images)) {
                $imageArray = array_filter($product->images); // Remove empty values
            } elseif (is_string($product->images)) {
                $imageArray = [$product->images]; // Convert single string to array
            }
        }

        // Ensure we have at least a fallback image path
        if (empty($imageArray)) {
            $imageArray = ['images/no-image.png'];
        }

        $cart = session()->get('cart', []);

        // Use product ID + size as unique key to allow same product with different sizes
        $cartKey = $selectedSize ? "{$productId}_{$selectedSize}" : $productId;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'brand_name' => $product->brand_name,
                'article_name' => $product->article_name,
                'type' => $product->type,
                'size' => $selectedSize ?? 'One Size', // Store selected size
                'fabric' => $product->fabric,
                'gender' => $product->gender,
                'description' => $product->description,
                'price' => $product->price,
                'images' => $imageArray,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        // Log cart contents for debugging image storage
        Log::info('Cart updated', ['cart' => $cart]);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Item removed from cart.');
    }
    public function update(Request $request, $id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, $request->quantity);
            session(['cart' => $cart]);
        }
        return redirect()->back();
    }

    // ==================== API METHODS ====================
}