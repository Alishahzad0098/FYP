@extends('layout')

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
    <div class="container py-4">
        <h2 class="mb-4">Search Results for "{{ $query }}"</h2>

        @if($products->count() > 0)
        <div class="row">
            @foreach ($products as $item)
            @php
            $images = is_array($item->images) ? $item->images : json_decode($item->images, true);
            @endphp

            <div class="col-lg-4 col-md-4 col-sm-6 mb-4">
                <div class="fashion-card h-100">
                    <div class="image-box">
                        <img src="{{ !empty($images) ? asset($images[0]) : asset('images/default.png') }}" alt="Product Image">

                        <div class="size-badge">
                            @if(!empty($item->size) && is_array($item->size))
                            @foreach ($item->size as $s)
                            <span class="badge bg-secondary">{{ $s }}</span>
                            @endforeach
                            @else
                            <span class="text-secondary">N/A</span>
                            @endif
                        </div>

                        <a href="{{ route('productshow', ['id' => $item->id]) }}" class="quick-view">
                            Quick View
                        </a>
                    </div>

                    <div class="card-caption d-flex flex-column">
                        <h6 class="brand">{{ $item->brand_name }}</h6>
                        <p class="article">{{ $item->article_name }}</p>

                        <div class="price">
                            <span class="current">${{ number_format($item->price,2) }}</span>
                            <del>${{ number_format($item->price + 50,2) }}</del>
                        </div>

                        <form action="{{ route('add.to.cart') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center pagination-wrapper">
            {{ $products->links('vendor.pagination.bootstrap-5') }}
        </div>
        @else
        <div class="alert alert-info mt-4">
            No products found matching your search.
        </div>
        @endif

    </div>
    @endsection