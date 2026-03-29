@extends('layout')

@section('title', 'Fashion Store')

@section('content')
    <style>
        .fashion-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .fashion-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
        }

        .image-box {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: #f6f6f6;
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.6s ease;
        }

        .fashion-card:hover img {
            transform: scale(1.1);
        }

        .size-badge {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.75);
            color: #fff;
            padding: 6px 10px;
            font-size: 12px;
            border-radius: 6px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .fashion-card:hover .size-badge {
            opacity: 1;
            transform: translateY(0);
        }

        .quick-view {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #000;
            color: #fff;
            padding: 10px 18px;
            font-size: 13px;
            text-decoration: none;
            border-radius: 25px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .fashion-card:hover .quick-view {
            opacity: 1;
        }

        .card-caption {
            padding: 16px;
            text-align: center;
        }

        .card-caption .brand {
            font-size: 14px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 4px;
        }

        .card-caption .article {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .price .current {
            font-weight: bold;
            font-size: 16px;
        }

        .price del {
            color: #aaa;
            font-size: 14px;
            margin-left: 6px;
        }

        .add-btn {
            width: 100%;
            background: #000;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 25px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            background: #444;
        }

        /* Mobile Filter Styles */
        .filter-btn-mobile {
            display: none;
            width: 100%;
            margin-bottom: 20px;
        }

        .filter-btn-mobile button {
            transition: all 0.3s ease;
        }

        .filter-btn-mobile button:not(.collapsed) {
            background-color: #333 !important;
        }

        .mobile-filter-menu {
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .mobile-filter-menu .collapse {
            overflow: hidden;
        }

        .mobile-filter-menu .collapse:not(.show) {
            display: none;
        }

        .mobile-filter-menu .collapse.show {
            animation: slideDown 0.4s ease-out forwards;
        }

        .mobile-filter-menu .collapse:not(.show) {
            animation: slideUp 0.4s ease-out forwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
                max-height: 0;
            }
            to {
                opacity: 1;
                transform: translateY(0);
                max-height: 1000px;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
                max-height: 1000px;
            }
            to {
                opacity: 0;
                transform: translateY(-20px);
                max-height: 0;
            }
        }

        @media (max-width: 991.98px) {
            .filter-btn-mobile {
                display: block;
            }

            .desktop-sidebar {
                display: none !important;
            }
        }

        @media (min-width: 992px) {
            .mobile-filter-menu {
                display: none !important;
            }
             .hero {
            background: url("{{ asset('asset/banner.jpeg') }}") center/cover no-repeat;
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.5rem;
            margin-top: 1rem;
        }
        }
 
    #bannerCarousel .carousel-control-prev,
    #bannerCarousel .carousel-control-next {
        width: 60px;
        opacity: 1;
    }

    #bannerCarousel .carousel-control-prev {
        left: 16px;
    }

    #bannerCarousel .carousel-control-next {
        right: 16px;
    }

    </style>

     @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if($banners->count() > 0)
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">

    <div class="carousel-indicators">
        @foreach($banners as $index => $banner)
            <button type="button" data-bs-target="#bannerCarousel"
                data-bs-slide-to="{{ $index }}"
                class="{{ $index === 0 ? 'active' : '' }}">
            </button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ asset('images/' . $banner->img) }}"
                     class="d-block w-100"
                     alt="Banner"
                     style="height:70vh; object-fit: cover;">
            </div>
        @endforeach
    </div>

    @if($banners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
    <div style="
        width: 48px; height: 48px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255,255,255,0.4);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    "
    onmouseover="this.style.background='rgba(255,255,255,0.35)'; this.style.transform='scale(1.1)'"
    onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='scale(1)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
        </svg>
    </div>
</button>

<button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
    <div style="
        width: 48px; height: 48px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255,255,255,0.4);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    "
    onmouseover="this.style.background='rgba(255,255,255,0.35)'; this.style.transform='scale(1.1)'"
    onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='scale(1)'">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
        </svg>
    </div>
</button>
    @endif

</div>
@endif
    <div class="container mt-5">
        <!-- Page Heading -->
        <div class="text-center my-5">
            <h1>Our <span class="fw-bold">New Products</span></h1>
        </div>

        <div class="row">
            <!-- Mobile Filter Toggle Button -->
            <div class="col-12 filter-btn-mobile">
                <button class="btn btn-dark w-100" type="button" data-bs-toggle="collapse" data-bs-target="#mobileFilterMenu" aria-expanded="false" aria-controls="mobileFilterMenu">
                    <i class="fas fa-filter"></i> Filters
                </button>
            </div>

            <!-- Mobile Filter Menu (Collapsible) -->
            <div class="col-12">
                <div class="collapse mobile-filter-menu" id="mobileFilterMenu">
                    <form method="GET" action="{{ route('home') }}" class="p-3">
                        <!-- Gender -->
                        <h5>Gender</h5>
                        @foreach (['men' => 'Men', 'women' => 'Women', 'children' => 'Children', 'unisex' => 'Unisex'] as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="{{ $key }}" {{ request('gender') == $key ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach

                        <!-- Type -->
                        <h5 class="mt-4">Type</h5>
                        @foreach ([
                                'two_piece_suit' => 'Two Piece Suit',
                                'three_piece_suit' => 'Three Piece Suit',
                                'single_shirt' => 'Single Shirt',
                                'bags' => 'Bags',
                                'perfumes' => 'Perfumes'
                            ] as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="{{ $key }}" {{ request('type') == $key ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach

                        <!-- Fabric -->
                        <h5 class="mt-4">Fabric</h5>
                        @foreach (['cotton', 'linen', 'silk', 'wool', 'leather', 'denim', 'polyester', 'mixed'] as $fabric)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fabric" value="{{ $fabric }}" {{ request('fabric') == $fabric ? 'checked' : '' }}>
                                <label class="form-check-label text-capitalize">{{ $fabric }}</label>
                            </div>
                        @endforeach

                        <!-- Brand -->
                        <h5 class="mt-4">Brand</h5>
                        @foreach ($brands as $brand)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brand" value="{{ $brand }}" {{ request('brand') == $brand ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $brand }}</label>
                            </div>
                        @endforeach

                        <!-- Price -->
                        <h5 class="mt-4">Price</h5>
                        @foreach ([
                                '0-50' => 'Under $50',
                                '50-100' => '$50 - $100',
                                '100+' => 'Above $100'
                            ] as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" value="{{ $key }}" {{ request('price') == $key ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach

                        <!-- Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-dark w-100 mb-2">Apply Filters</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Desktop Sidebar -->
            <div class="col-lg-3 d-none d-lg-block desktop-sidebar">
                <form method="GET" action="{{ route('home') }}">
                    <div class="p-3 bg-light rounded">

                        <!-- Gender -->
                        <h5>Gender</h5>
                        @foreach (['men' => 'Men', 'women' => 'Women', 'children' => 'Children', 'unisex' => 'Unisex'] as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="{{ $key }}" {{ request('gender') == $key ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach

                        <!-- Type -->
                        <h5 class="mt-4">Type</h5>
                        @foreach ([
                                'two_piece_suit' => 'Two Piece Suit',
                                'three_piece_suit' => 'Three Piece Suit',
                                'single_shirt' => 'Single Shirt',
                                'bags' => 'Bags',
                                'perfumes' => 'Perfumes'
                            ] as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" value="{{ $key }}" {{ request('type') == $key ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach


                        <!-- Fabric -->
                        <h5 class="mt-4">Fabric</h5>
                        @foreach (['cotton', 'linen', 'silk', 'wool', 'leather', 'denim', 'polyester', 'mixed'] as $fabric)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fabric" value="{{ $fabric }}" {{ request('fabric') == $fabric ? 'checked' : '' }}>
                                <label class="form-check-label text-capitalize">{{ $fabric }}</label>
                            </div>
                        @endforeach


                        <!-- Brand -->
                        <h5 class="mt-4">Brand</h5>
                        @foreach ($brands as $brand)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brand" value="{{ $brand }}" {{ request('brand') == $brand ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $brand }}</label>
                            </div>
                        @endforeach


                        <!-- Price -->
                        <h5 class="mt-4">Price</h5>
                        @foreach ([
                                '0-50' => 'Under $50',
                                '50-100' => '$50 - $100',
                                '100+' => 'Above $100'
                            ] as $key => $label)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" value="{{ $key }}" {{ request('price') == $key ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $label }}</label>
                            </div>
                        @endforeach

                        <!-- Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-dark w-100 mb-2">Apply Filters</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">Reset</a>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Product Cards -->
            <div class="col-lg-9">
                <div class="row g-4">
                    @foreach ($product as $item)
                        @php
                            $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                        @endphp
                        <div class="col-md-4">
                            <div class="fashion-card">
                                <div class="image-box">
                                    @if(!empty($images))
                                        <img src="{{ asset($images[0]) }}" alt="Product Image">
                                    @else
                                        <img src="{{ asset('images/default.png') }}" alt="Default Image">
                                    @endif

                                    <div class="size-badge">
                                        @if(!empty($item->size))
                                            @foreach ($item->size as $s)
                                                <span class="badge me-1">{{ $s }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-secondary">N/A</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('productshow', ['id' => $item->id]) }}" class="quick-view">Quick View</a>
                                </div>

                                <div class="card-caption">
                                    <h6 class="brand">{{ $item->brand_name }}</h6>
                                    <p class="article">{{ $item->article_name }}</p>

                                    <div class="price">
                                        <span class="current">${{ number_format($item->price, 2) }}</span>
                                        <del>${{ number_format($item->price + 50, 2) }}</del>
                                    </div>

                                    <form action="{{ route('add.to.cart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        @if(!empty($item->size) && is_array($item->size))
                                            <div class="mb-2">
                                                <select name="selected_size" class="form-select form-select-sm" required>
                                                    <option value="">Select Size</option>
                                                    @foreach($item->size as $size)
                                                        <option value="{{ $size }}">{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <button type="submit" class="add-btn">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection