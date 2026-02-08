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

    /**
     * API: Show cart items
     */
    public function apiShowCart(Request $request)
    {
        try {
            $cart = session()->get('cart', []);

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart retrieved',
                'data' => $cart,
                'item_count' => count($cart),
                'total' => $total,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cart: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Add product to cart
     */
    public function apiAddToCart(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'selected_size' => 'nullable|string',
            ]);

            $product = Products::findOrFail($data['product_id']);
            $quantity = $data['quantity'];
            $selectedSize = $data['selected_size'] ?? null;

            // Parse product sizes
            $productSizes = is_array($product->size) ? $product->size : [];

            // Validate size if needed
            if (!empty($productSizes) && !$selectedSize) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a size',
                ], 400);
            }

            // Get images
            $imageArray = [];
            if (!empty($product->images)) {
                $imageArray = is_array($product->images) ? $product->images : [$product->images];
            }
            if (empty($imageArray)) {
                $imageArray = ['images/no-image.png'];
            }

            $cart = session()->get('cart', []);
            $cartKey = $selectedSize ? "{$data['product_id']}_{$selectedSize}" : $data['product_id'];

            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $quantity;
            } else {
                $cart[$cartKey] = [
                    'product_id' => $data['product_id'],
                    'brand_name' => $product->brand_name,
                    'article_name' => $product->article_name,
                    'type' => $product->type,
                    'size' => $selectedSize ?? 'One Size',
                    'fabric' => $product->fabric,
                    'gender' => $product->gender,
                    'description' => $product->description,
                    'price' => $product->price,
                    'images' => $imageArray,
                    'quantity' => $quantity,
                ];
            }

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_item_count' => count($cart),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add to cart: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update cart item quantity
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = session()->get('cart', []);

            if (!isset($cart[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found',
                ], 404);
            }

            $cart[$id]['quantity'] = $data['quantity'];
            session()->put('cart', $cart);

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart updated',
                'item' => $cart[$id],
                'total' => $total,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Remove item from cart
     */
    public function apiRemove($id)
    {
        try {
            $cart = session()->get('cart', []);

            if (!isset($cart[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found',
                ], 404);
            }

            unset($cart[$id]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'item_count' => count($cart),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Clear entire cart
     */
    public function apiClearCart(Request $request)
    {
        try {
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart: ' . $e->getMessage(),
            ], 500);
        }
    }
}
