@extends('layout')

@section('title', 'Electronic Mart')

@section('content')
    <style>
        .ab-top {
            background-image: url('{{ asset("asset/1751798235.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
        }

        .ab-top h1 {
            font-size: 50px;
            font-weight: 800;
        }

        .ab-top h5 a {
            text-decoration: underline;
        }

        /* Responsive Cart Table */
        .cart-table img {
            width: 60px;
            height: auto;
        }

        .cart-table td,
        .cart-table th {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.85rem;
        }

        /* Quantity Control */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-input {
            width: 60px;
            /* short input */
            padding: 0.375rem 0.5rem;
            text-align: center;
        }

        /* Mobile adjustments */
        @media (max-width: 767.98px) {
            .cart-table {
                width: 100% !important;
                border: none !important;
            }

            .cart-table thead {
                display: none;
            }

            .cart-table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                padding: 1rem;
                background-color: #fff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .cart-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem 0;
                border: none;
            }

            .cart-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #667eea;
            }

            .cart-table tbody td img {
                margin-right: 1rem;
            }

            .quantity-control {
                flex-wrap: wrap;
                margin-top: 0.5rem;
            }

            .quantity-input {
                width: 70px;
            }

            .quantity-control button {
                flex: 1;
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
            }

            .btn-danger-mobile {
                width: 100%;
                margin-top: 0.5rem;
            }

            .cart-actions {
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
            }

            .cart-actions a {
                width: 100%;
            }
        }

        /* Desktop layout */
        @media (min-width: 768px) {
            .cart-actions {
                display: flex;
                justify-content: space-between;
                gap: 1rem;
            }

            .btn-danger-mobile {
                width: auto;
            }
        }

        .total-row {
            margin: 2rem 0;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            text-align: right;
        }

        .total-row h4 {
            margin: 0;
            font-weight: 700;
        }
    </style>

    <div class="ab-top text-center text-white">
        <h1>Checkout</h1>
        <h5>
            <a href="{{ route('home') }}" class="text-white">Product</a> >> Cart
        </h5>
    </div>

    <div class="container mt-5">
        @php
            $cart = session('cart', []);
            $totalItems = count($cart);
            $totalPrice = 0;

            // Helper function to get first image safely
            function firstImage($item)
            {
                if (!empty($item['images'])) {
                    $images = is_string($item['images']) ? json_decode($item['images'], true) : $item['images'];
                    if (!empty($images[0]))
                        return $images[0];
                }
                return 'images/no-image.png';
            }
        @endphp

        <div class="text-center mb-4">
            <h2>
                <strong>
                    Your Shopping Cart has: {{ $totalItems }} Product{{ $totalItems !== 1 ? 's' : '' }}
                </strong>
            </h2>
        </div>

        @if($totalItems > 0)
            <table class="table table-light table-bordered table-hover text-center align-middle cart-table">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    @php $count = 1; @endphp
                    @foreach($cart as $id => $item)
                        @php
                            $itemTotal = $item['price'] * $item['quantity'];
                            $totalPrice += $itemTotal;
                            $size = $item['size'] ?? 'One Size';
                            if (is_array($size)) {
                                $size = !empty($size[0]) ? $size[0] : 'One Size';
                            }
                            $imagePath = firstImage($item);
                        @endphp
                        <tr>
                            <td data-label="#">{{ $count++ }}</td>
                            <td data-label="Product" class="text-start d-flex align-items-center">
                                <img src="{{ asset($imagePath) }}" alt="product">
                                <span class="d-none d-md-inline ms-2">{{ $item['article_name'] ?? 'Product' }}</span>
                            </td>
                            <td data-label="Size"><span class="badge bg-secondary">{{ strtoupper($size) }}</span></td>
                            <td data-label="Quantity">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="quantity-control">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="form-control quantity-input">
                                    <button class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td data-label="Price">${{ number_format($item['price'], 2) }}</td>
                            <td data-label="Total">${{ number_format($itemTotal, 2) }}</td>
                            <td data-label="Remove">
                                <form method="POST" action="{{ route('cart.remove', $id) }}">
                                    @csrf
                                    <button class="btn btn-danger btn-sm btn-danger-mobile">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-row">
                <h4>Total Price: <span class="text-success">${{ number_format($totalPrice, 2) }}</span></h4>
            </div>

            <div class="cart-actions mb-5">
                <a href="{{ route('home') }}" class="btn btn-dark">Continue Shopping</a>
                <a href="{{ route('checkoutpage') }}" class="btn btn-warning">Proceed to Checkout</a>
            </div>
        @else
            <div class="text-center my-5">
                <h3>Your cart is empty!</h3>
                <a href="{{ route('home') }}" class="btn btn-dark mt-3">Continue Shopping</a>
            </div>
        @endif
    </div>
@endsection