@extends('layout')

@section('title', 'Electronic Mart - Checkout')

@section('content')
    <style>
        .ab-top {
          background: linear-gradient(rgba(17,17,17,0.5), rgba(17,17,17,0.5));
            background-image: url('{{ asset("asset/1751798235.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 100px 0;    
        }

        .ab-top h1 {
            font-size: clamp(28px, 8vw, 50px);
            margin-bottom: 10px;
        }

        .ab-top h5 {
            font-size: clamp(14px, 4vw, 18px);
        }

        .ab-top h5 a {
            text-decoration: underline;
        }

        /* Checkout Form Styles */
        .checkout-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-section h3 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #667eea;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.95rem;
        }

        .form-group input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-group input:focus {
            border-color: #667eea;
            background-color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .payment-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .payment-section h4 {
            color: #333;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #fff;
            border-radius: 6px;
            border: 2px solid #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 0.75rem;
        }

        .payment-option:hover {
            border-color: #667eea;
            background-color: #f8f9fa;
        }

        .payment-option input[type="radio"] {
            margin-right: 1rem;
            cursor: pointer;
            width: 1.2rem;
            height: 1.2rem;
            accent-color: #667eea;
        }

        .payment-option label {
            margin: 0;
            cursor: pointer;
            flex: 1;
            font-weight: 500;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.05rem;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
            color: #fff;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .page-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-title h2 {
            color: #333;
            font-weight: 800;
            font-size: clamp(24px, 6vw, 36px);
            margin-bottom: 0.5rem;
        }

        .page-title p {
            color: #666;
            font-size: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .form-section {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-section h3 {
                font-size: 1.25rem;
                margin-bottom: 1rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .btn-submit {
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
            }

            .page-title h2 {
                font-size: 24px;
            }
        }
    </style>

    <!-- Banner Section -->
    <div class="ab-top py-5">
        <h1 class="text-center text-white mb-3">Checkout</h1>
        <h5 class="text-center text-white">
            <a href="{{ route('home') }}" class="text-white">Home</a> >> Checkout
        </h5>
    </div>

    <!-- Checkout Form -->
    <div class="container py-5">
        <div class="checkout-container">
            <!-- Page Title -->
            <div class="page-title mb-5">
                <h2>Complete Your Order</h2>
                <p>Please fill in your details to proceed with checkout</p>
            </div>

            <form action="{{ route('checkout') }}" method="POST">
                @csrf

                <!-- Personal Information Section -->
                <div class="form-section">
                    <h3><i class="fas fa-user me-2"></i>Personal Information</h3>

                    <div class="form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name"
                            value="{{ Auth::user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                            value="{{ Auth::user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="number">Mobile Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="number" name="number"
                            placeholder="Enter your mobile number" required>
                    </div>
                </div>

                <!-- Delivery Address Section -->
                <div class="form-section">
                    <h3><i class="fas fa-map-marker-alt me-2"></i>Delivery Address</h3>

                    <div class="form-group">
                        <label for="address">Full Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Enter your complete address" required>
                    </div>
                </div>

                <!-- Payment Method Section -->
                <div class="form-section">
                    <h3><i class="fas fa-credit-card me-2"></i>Payment Method</h3>

                    <div class="payment-option">
                        <input type="radio" class="form-check-input" id="cod" name="payment" value="COD" checked required>
                        <label class="form-check-label" for="cod">
                            <strong>Cash On Delivery (COD)</strong>
                            <br>
                            <small class="text-muted">Pay when your order arrives</small>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-check-circle me-2"></i>Place Your Order
                </button>
            </form>
        </div>
    </div>

@endsection