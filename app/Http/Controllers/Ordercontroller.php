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

    // ==================== API METHODS ====================

    /**
     * API: Place order from cart
     */
    public function apiPlaceOrder(Request $request)
    {
        try {
            $cart = session('cart', []);

            if (empty($cart) || count($cart) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty',
                ], 400);
            }

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'number' => 'required|string|max:20',
                'address' => 'required|string',
                'payment' => 'required|in:COD,card,paypal',
            ]);

            DB::beginTransaction();

            try {
                // Calculate total
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }

                // Create order
                $order = Order::create([
                    'customer_name' => $data['name'],
                    'number' => $data['number'],
                    'customer_email' => $data['email'],
                    'address' => $data['address'],
                    'payment' => $data['payment'],
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
                        'size' => $item['size'] ?? 'One Size',
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
                    Mail::to($data['email'])
                        ->cc('alishahzad9054933@gmail.com')
                        ->send(new OrderConfirmation($order, $orderItems));
                } catch (\Exception $emailError) {
                    Log::warning('Email notification failed but order was created: ' . $emailError->getMessage());
                }

                DB::commit();
                session()->forget('cart');

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully',
                    'order_id' => $order->id,
                    'total' => $total,
                ], 201);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get all orders for authenticated user
     */
    public function apiGetOrders(Request $request)
    {
        try {
            $user = $request->user();

            // Get orders for the user (if you have user_id in orders table)
            // Otherwise, get all orders where customer_email matches
            $orders = Order::where('customer_email', $user->email)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Orders retrieved',
                'data' => $orders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get single order with items
     */
    public function apiGetOrder($id)
    {
        try {
            $order = Order::with('items')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Order retrieved',
                'data' => $order,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get order items
     */
    public function apiGetOrderItems($id)
    {
        try {
            $order = Order::findOrFail($id);
            $items = OrderItem::where('order_id', $id)->get();

            return response()->json([
                'success' => true,
                'message' => 'Order items retrieved',
                'order_id' => $id,
                'items' => $items,
                'item_count' => $items->count(),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve order items: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Cancel order
     */
    public function apiCancelOrder($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Update status to cancelled
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'order_id' => $id,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Get all orders (admin only)
     */
    public function apiGetAllOrders(Request $request)
    {
        try {
            $query = Order::query();

            // Filter by status if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by payment method if provided
            if ($request->filled('payment')) {
                $query->where('payment', $request->payment);
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate(15);

            return response()->json([
                'success' => true,
                'message' => 'All orders retrieved',
                'data' => $orders,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve orders: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Update order status (admin only)
     */
    public function apiUpdateOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $data = $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            ]);

            $order->update(['status' => $data['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated',
                'order_id' => $id,
                'status' => $data['status'],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage(),
            ], 500);
        }
    }
}
