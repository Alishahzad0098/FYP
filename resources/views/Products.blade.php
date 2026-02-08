@extends('layout')

@section('title', 'Fashion Mart')

@section('content')
<style>
    .fashion-card {
        width: 18rem;
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

    /* Image box */
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

    /* Size badge */
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

    /* Quick view */
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

    /* Caption */
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

    .price {
        margin-bottom: 12px;
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
    .sapphire-tabs {
    border-bottom: none;
    gap: 30px;
}

.sapphire-tabs .nav-link {
    position: relative;
    border: none;
    background: none;
    color: #444;
    font-weight: 500;
    font-size: 1.05rem;
    padding: 10px 5px;
    transition: color 0.3s ease;
}

.sapphire-tabs .nav-link::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 2px;
    background-color: #000;
    transition: width 0.3s ease;
}

.sapphire-tabs .nav-link:hover {
    color: #000;
}

.sapphire-tabs .nav-link:hover::after {
    width: 100%;
}

.sapphire-tabs .nav-link.active {
    color: #000;
    font-weight: 600;
}

.sapphire-tabs .nav-link.active::after {
    width: 100%;
}


    /* Add to cart */
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
</style>
<div class="container mt-5">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="newproducts mt-5 text-center">
        <h1>Our <span style="font-weight: 800;">Fashion Collection</span></h1>
    </div>

    <div class="container d-flex">
        <div class="col-lg-3 d-none d-lg-block">
            <form method="GET" action="{{ route('home') }}">
                <div class="p-3 bg-light rounded">

                    <!-- Gender -->
                    <h5>Gender</h5>
                    @foreach (['men'=>'Men','women'=>'Women','children'=>'Children','unisex'=>'Unisex'] as $key => $label)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="{{ $key }}"
                            {{ request('gender') == $key ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $label }}</label>
                    </div>
                    @endforeach

                    <!-- Type -->
                    <h5 class="mt-4">Type</h5>
                    @foreach ([
                    'two_piece_suit'=>'Two Piece Suit',
                    'three_piece_suit'=>'Three Piece Suit',
                    'single_shirt'=>'Single Shirt',
                    'bags'=>'Bags',
                    'perfumes'=>'Perfumes'
                    ] as $key => $label)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="{{ $key }}"
                            {{ request('type') == $key ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $label }}</label>
                    </div>
                    @endforeach

                    <!-- Fabric -->
                    <h5 class="mt-4">Fabric</h5>
                    @foreach (['cotton','linen','silk','wool','leather','denim','polyester','mixed'] as $fabric)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="fabric" value="{{ $fabric }}"
                            {{ request('fabric') == $fabric ? 'checked' : '' }}>
                        <label class="form-check-label text-capitalize">{{ $fabric }}</label>
                    </div>
                    @endforeach

                    <!-- Brand -->
                    <h5 class="mt-4">Brand</h5>
                    @foreach ($brands as $brand)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="brand" value="{{ $brand }}"
                            {{ request('brand') == $brand ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $brand }}</label>
                    </div>
                    @endforeach

                    <!-- Price -->
                    <h5 class="mt-4">Price</h5>
                    @foreach ([
                    '0-50'=>'Under $50',
                    '50-100'=>'$50 - $100',
                    '100+'=>'Above $100'
                    ] as $key => $label)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="price" value="{{ $key }}"
                            {{ request('price') == $key ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $label }}</label>
                    </div>
                    @endforeach

                    <!-- Buttons -->
                    <div class="mt-4">
                        <button class="btn btn-dark w-100 mb-2">Apply Filters</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    </div>

                </div>
            </form>
        </div>
        <div class="col-lg-9">
            <!-- Tabs -->
           <ul class="nav nav-tabs justify-content-center sapphire-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#men">Men</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#women">Women</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#kids">Kids</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#accessories">Accessories</button>
    </li>
</ul>

            <div class="tab-content mt-4">

                <!-- MEN -->
                <div class="tab-pane fade show active" id="men">
                    <div class="row product-card-wrapper">
                        @foreach ($product as $item)
                        @php
                        $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                        $sizes = is_array($item->size) ? $item->size : json_decode($item->size, true);
                        @endphp

                        <div class="fashion-card m-3">
                            <!-- Image -->
                            <div class="image-box">
                                @if(!empty($images))
                                <img src="{{ asset($images[0]) }}" alt="Product Image">
                                @else
                                <img src="{{ asset('images/default.png') }}" alt="Default Image">
                                @endif

                                <!-- Size badge -->
                                <div class="size-badge">
                                    @if(!empty($sizes))
                                    @foreach ($sizes as $s)
                                    <span class="badge bg-light text-dark me-1">{{ $s }}</span>
                                    @endforeach
                                    @else
                                    <span class="text-secondary">N/A</span>
                                    @endif
                                </div>

                                <!-- Quick view -->
                                <a href="{{ route('productshow', ['id' => $item->id]) }}" class="quick-view">
                                    Quick View
                                </a>
                            </div>

                            <!-- Caption -->
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
                        @endforeach
                    </div>


                    {{ $product->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                </div>

                <!-- WOMEN -->
                <div class="tab-pane fade" id="women">
                    <div class="row product-card-wrapper">
                        @foreach ($p2 as $item)
                        @php
                        $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                        $sizes = is_array($item->size) ? $item->size : json_decode($item->size, true);
                        @endphp

                        <div class="fashion-card m-3">
                            <!-- Image -->
                            <div class="image-box">
                                @if(!empty($images))
                                <img src="{{ asset($images[0]) }}" alt="Product Image">
                                @else
                                <img src="{{ asset('images/default.png') }}" alt="Default Image">
                                @endif

                                <!-- Size badge -->
                                <div class="size-badge">
                                    @if(!empty($sizes))
                                    @foreach ($sizes as $s)
                                    <span class="badge bg-light text-dark me-1">{{ $s }}</span>
                                    @endforeach
                                    @else
                                    <span class="text-secondary">N/A</span>
                                    @endif
                                </div>

                                <!-- Quick view -->
                                <a href="{{ route('productshow', ['id' => $item->id]) }}" class="quick-view">
                                    Quick View
                                </a>
                            </div>

                            <!-- Caption -->
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
                        @endforeach
                    </div>

                    {{ $p2->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                </div>

                <!-- KIDS -->
                <div class="tab-pane fade" id="kids">
                    <div class="row product-card-wrapper">
                        <div class="row product-card-wrapper">
                            @foreach ($p3 as $item)
                            @php
                            $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                            $sizes = is_array($item->size) ? $item->size : json_decode($item->size, true);
                            @endphp

                            <div class="fashion-card m-3">
                                <!-- Image -->
                                <div class="image-box">
                                    @if(!empty($images))
                                    <img src="{{ asset($images[0]) }}" alt="Product Image">
                                    @else
                                    <img src="{{ asset('images/default.png') }}" alt="Default Image">
                                    @endif

                                    <!-- Size badge -->
                                    <div class="size-badge">
                                        @if(!empty($sizes))
                                        @foreach ($sizes as $s)
                                        <span class="badge bg-light text-dark me-1">{{ $s }}</span>
                                        @endforeach
                                        @else
                                        <span class="text-secondary">N/A</span>
                                        @endif
                                    </div>

                                    <!-- Quick view -->
                                    <a href="{{ route('productshow', ['id' => $item->id]) }}" class="quick-view">
                                        Quick View
                                    </a>
                                </div>

                                <!-- Caption -->
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
                            @endforeach
                        </div>

                    </div>
                    {{ $p3->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                </div>

                <!-- ACCESSORIES -->
                <div class="tab-pane fade" id="accessories">
                    <div class="row product-card-wrapper">
                        @foreach ($p4 as $item)
                        @php
                        $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
                        $sizes = is_array($item->size) ? $item->size : json_decode($item->size, true);
                        @endphp

                        <div class="fashion-card m-3">
                            <!-- Image -->
                            <div class="image-box">
                                @if(!empty($images))
                                <img src="{{ asset($images[0]) }}" alt="Product Image">
                                @else
                                <img src="{{ asset('images/default.png') }}" alt="Default Image">
                                @endif

                                <!-- Size badge -->
                                <div class="size-badge">
                                    @if(!empty($sizes))
                                    @foreach ($sizes as $s)
                                    <span class="badge bg-light text-dark me-1">{{ $s }}</span>
                                    @endforeach
                                    @else
                                    <span class="text-secondary">N/A</span>
                                    @endif
                                </div>

                                <!-- Quick view -->
                                <a href="{{ route('productshow', ['id' => $item->id]) }}" class="quick-view">
                                    Quick View
                                </a>
                            </div>

                            <!-- Caption -->
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
                        @endforeach
                    </div>

                </div>
                {{ $p4->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection