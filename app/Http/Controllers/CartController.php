<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function showCart()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $selectedSize = $request->input('selected_size');

        $product = Products::find($productId);
        if (!$product) return redirect()->back()->with('error', 'Product not found.');

        $productSizes = is_array($product->size) ? $product->size : [];
        if (!empty($productSizes)) {
            if (!$selectedSize) return redirect()->back()->with('error', 'Please select a size.');
            if (!in_array($selectedSize, $productSizes)) return redirect()->back()->with('error', 'Invalid size selected.');
        }

        $imageArray = [];
        if (!empty($product->images)) {
            if (is_array($product->images)) $imageArray = array_filter($product->images);
            else $imageArray = [$product->images];
        }
        if (empty($imageArray)) $imageArray = ['images/no-image.png'];

        $cart = session()->get('cart', []);
        $cartKey = $selectedSize ? "{$productId}_{$selectedSize}" : $productId;

        if (isset($cart[$cartKey])) $cart[$cartKey]['quantity'] += $quantity;
        else $cart[$cartKey] = [
            'product_id' => $productId,
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

        session()->put('cart', $cart);
        Log::info('Cart updated', ['cart' => $cart]);

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) unset($cart[$id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Item removed from cart.');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = max(1, $request->quantity);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }
}
