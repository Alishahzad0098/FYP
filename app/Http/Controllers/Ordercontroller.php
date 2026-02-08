<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Orderitems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // if storing user_id
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\OrderConfirmation;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class Ordercontroller extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            $cart = session('cart', []);
            if (empty($cart)) {
                return redirect()->route('home')->with('error', 'Your cart is empty!');
            }
            return view('Checkout', compact('cart'));
        } else {
            return redirect()->route('loginpage');
        }
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart) || count($cart) === 0) {
            return redirect()->route('home')->with('error', 'Cart is empty! Please add items to cart first.');
        }

        DB::beginTransaction();

        try {
            // Calculate total
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Create order with all required fields
            $order = Order::create([
                'customer_name' => $request->input('name', 'Guest'),
                'number' => $request->input('number', ''),
                'customer_email' => $request->input('email', ''),
                'address' => $request->input('address', ''),
                'payment' => $request->input('payment', 'COD'),
                'total_amount' => $total,
            ]);

            if (!$order || !$order->id) {
                throw new \Exception('Failed to create order');
            }

            // Create order items
            $orderItems = [];
            foreach ($cart as $item) {
                $imageJson = is_array($item['images']) ? json_encode($item['images']) : $item['images'];

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'brand_name' => $item['brand_name'] ?? '',
                    'article_name' => $item['article_name'] ?? '',
                    'type' => $item['type'] ?? '',
                    'size' => isset($item['size']) ? $item['size'] : 'One Size',
                    'fabric' => $item['fabric'] ?? null,
                    'gender' => $item['gender'] ?? null,
                    'description' => $item['description'] ?? '',
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'images' => $imageJson,
                ]);

                if (!$orderItem) {
                    throw new \Exception('Failed to create order item');
                }

                $orderItems[] = $orderItem;
            }

            // Send email
            try {
                Mail::to($request->input('email'))
                    ->cc('alishahzad9054933@gmail.com')
                    ->send(new OrderConfirmation($order, $orderItems));
            } catch (\Exception $emailError) {
                Log::warning('Email notification failed but order was created: ' . $emailError->getMessage());
            }

            DB::commit();

            // Clear cart from session
            session()->forget('cart');

            // Redirect to home with success
            return redirect()->route('home')->with('success', 'Order placed successfully! You will receive a confirmation email shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order failed: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());

            return redirect()->back()->with('error', 'Something went wrong while placing your order. Please try again. ' . $e->getMessage());
        }
    }

    public function order()
    {
        $order = Order::all();
        return view('Ordertable', compact('order'));
    }
    public function orderitem()
    {
        $orderitem = Orderitem::all();
        return view('orderitemstable', compact('orderitem'));
    }

    /**
     * Send a simple test email to verify SMTP configuration.
     * Usage: /test-mail?email=you@domain.tld
     */
    public function sendTestEmail(Request $request)
    {
        $to = $request->query('email', config('mail.from.address'));

        try {
            Mail::to($to)->send(new TestMail('This is a test email sent at ' . now()));

            // Some transports populate failures
            if (method_exists(Mail::class, 'failures') && count(Mail::failures()) > 0) {
                Log::warning('Mail reported failures: ' . implode(',', Mail::failures()));
                return response()->json(['status' => 'failed', 'failures' => Mail::failures()], 500);
            }

            return response()->json(['status' => 'sent', 'to' => $to]);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}