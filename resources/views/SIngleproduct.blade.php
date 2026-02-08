@extends('layout')

@section('title', $product->brand_name . ' - ' . $product->article_name)

@section('content')
    <style>
        .product-carousel-image-container {
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .product-carousel-image {
            max-height: 100%;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        /* Carousel Controls */
        .carousel-control-prev,
        .carousel-control-next {
            background-color: rgba(0, 0, 0, 0.1);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }

        .carousel-control-prev {
            left: 15px;
        }

        .carousel-control-next {
            right: 15px;
        }

        /* Product Info Styling */
        .item_price {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .add-to-cart-btn {
            background-color: rgb(255, 153, 0);
            border: none;
            transition: all 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: rgb(230, 138, 0);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-description {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert"
            style="z-index: 1050;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="ab-top">
        <h1 class="text-center text-white" style="padding-top: 100px; font-size: 50px;">Products</h1>
        <h5 class="text-center text-white" style="padding-bottom: 100px;">
            <a href="{{ route('home') }}" class="text-white">Home</a> >> {{ $product->brand_name }}
        </h5>
    </div>

    <div class="newproducts mt-5 text-center">
        <h1>Product: <span style="font-weight: 800;">{{ $product->article_name }}</span></h1>
    </div>

    <div class="product">
        <div class="container my-5">
            <div class="row">
                <!-- Product Images Carousel -->
                <div class="col-lg-5">
                    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" style="background-color: #f8f9fa; border-radius: 8px;">
                            @php $images = json_decode($product->images, true); @endphp

                            @if ($images && is_array($images))
                                @foreach ($images as $index => $image)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <div class="product-carousel-image-container">
                                            <img src="{{ asset($image) }}" class="product-carousel-image" alt="Product Image">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="carousel-item active">
                                    <div class="product-carousel-image-container">
                                        <img src="{{ asset('images/default.png') }}" class="product-carousel-image"
                                            alt="No Image">
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($images && count($images) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Product Details -->
                <div class="col-lg-7">
                    <div class="single-right-left simpleCart_shelfItem">
                        <h3 class="mb-3 fw-bolder">{{ $product->brand_name }} - {{ $product->article_name }}</h3>

                        <p class="mb-3 fw-semibold fs-5">
                            <span class="item_price text-warning">${{ $product->price }}</span>
                            <del class="mx-2 font-weight-light text-muted">${{ $product->price + 40 }}</del>
                            <span class="badge bg-danger ms-2">Save $40</span>
                        </p>

                        <ul class="text-muted mb-3">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Cash on Delivery Eligible
                            </li>
                            <li class="mb-2"><i class="fas fa-shipping-fast text-primary me-2"></i>Fast Shipping</li>
                        </ul>

                        <hr>

                        <div class=" flex-wrap gap-3 mb-3">
                            <p><strong>Type:</strong> {{ ucwords(str_replace('_', ' ', $product->type)) }}</p>
                            <p><strong>Available Sizes:</strong>
                                @if(!empty($product->size) && is_array($product->size))
                                    @foreach($product->size as $size)
                                        <span class="badge bg-secondary me-1">{{ strtoupper($size) }}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </p>
                            <p><strong>Fabric:</strong> {{ ucwords($product->fabric ?? 'N/A') }}</p>
                            <p><strong>Gender:</strong> {{ ucwords($product->gender ?? 'Unisex') }}</p>
                        </div>

                        <div class="product-description mb-4">
                            <h5 class="fw-semibold">Description:</h5>
                            <p>{!! nl2br(e($product->description)) !!}</p>
                        </div>

                        <form action="{{ route('add.to.cart') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">

                            <!-- Size Selector Dropdown -->
                            @if(!empty($product->size) && is_array($product->size))
                                <div class="mb-3">
                                    <label for="sizeSelector" class="form-label fw-semibold">Select Size:</label>
                                    <select id="sizeSelector" name="selected_size" class="form-select" required>
                                        <option value="">-- Choose a Size --</option>
                                        @foreach($product->size as $size)
                                            <option value="{{ $size }}">{{ strtoupper($size) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-warning text-white add-to-cart-btn px-4 py-2">
                                <i class="fas fa-cart-plus me-2"></i>Add To Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection