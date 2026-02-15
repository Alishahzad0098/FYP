<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\TestMail;

class Ordercontroller extends Controller
{

    public function show()
    {
        $cart = session('cart', []);
        if (empty($cart))
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        return view('Checkout', compact('cart'));
    }

    public function placeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart))
            return redirect()->route('home')->with('error', 'Cart is empty!');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'customer_name' => $request->name,
                'number' => $request->number,
                'customer_email' => $request->email,
                'address' => $request->address,
                'payment' => $request->payment,
                'total_amount' => $total,
            ]);

            $orderItems = [];
            foreach ($cart as $item) {
                $orderItems[] = OrderItem::create([
                    'order_id' => $order->id,
                    'brand_name' => $item['brand_name'],
                    'article_name' => $item['article_name'],
                    'type' => $item['type'],
                    'size' => $item['size'],
                    'fabric' => $item['fabric'],
                    'gender' => $item['gender'],
                    'description' => $item['description'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'images' => json_encode($item['images']),
                ]);
            }

            try {
                Mail::to($request->email)
                    ->cc('alishahzad9054933@gmail.com')
                    ->send(new OrderConfirmation($order, $orderItems));
            } catch (\Exception $e) {
                Log::warning("Email failed: {$e->getMessage()}");
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('home')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order failed: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function order()
    {
        return view('Ordertable', ['order' => Order::all()]);
    }
    public function orderitem()
    {
        return view('orderitemstable', ['orderitem' => OrderItem::all()]);
    }

    public function sendTestEmail(Request $request)
    {
        $to = $request->query('email', config('mail.from.address'));
        try {
            Mail::to($to)->send(new TestMail('Test email at ' . now()));
            return response()->json(['status' => 'sent', 'to' => $to]);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
