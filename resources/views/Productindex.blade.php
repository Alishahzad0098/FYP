@extends('layout')

@section('content')
<style>
    .fashion-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.4s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .fashion-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.18);
    }

    .card-img-container {
        position: relative;
        height: 220px;
        background: #f6f6f6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.6s ease;
    }

    .fashion-card:hover img {
        transform: scale(1.1);
    }

    .quick-view-btn {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: #ff9900;
        color: #fff;
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
        text-decoration: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .fashion-card:hover .quick-view-btn {
        opacity: 1;
    }

    .card-body h5 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .card-body .item_price {
        font-size: 16px;
        color: #ff9900;
        font-weight: bold;
    }

    .card-body del {
        color: #aaa;
        font-size: 13px;
        margin-left: 6px;
    }

    .add-to-cart-btn {
        width: 100%;
        border-radius: 25px;
        background: #000;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        border: none;
        padding: 8px 0;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
        background: #444;
    }

    .pagination-wrapper {
        margin-top: 30px;
    }
</style>

<div class="container mt-4">
    @if($products->count() > 0)
    <div class="row">
        @foreach($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="fashion-card h-100">
                {{-- Image --}}
                @php $images = json_decode($product->images, true); @endphp
                <div class="card-img-container">
                    @if(!empty($images))
                    <img src="{{ asset($images[0]) }}" alt="Product Image">
                    @else
                    <img src="{{ asset('images/default.png') }}" alt="Default Image">
                    @endif
                    <a href="{{ route('productshow', ['id' => $product->id]) }}" class="quick-view-btn">
                        Quick View
                    </a>
                </div>

                {{-- Caption --}}
                <div class="card-body d-flex flex-column">
                    <h5>{{ $product->name }}</h5>
                    <p>
                        <span class="item_price">${{ number_format($product->price,2) }}</span>
                        <del>${{ number_format($product->price + 50,2) }}</del>
                    </p>
                    <form action="{{ route('add.to.cart') }}" method="POST" class="mt-auto">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="add-to-cart-btn">Add To Cart</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center pagination-wrapper">
        {{ $products->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>


    {{-- Pagination --}}
    <div class="d-flex justify-content-center pagination-wrapper">
        {{ $products->withQueryString()->links('vendor.pagination.bootstrap-5') }}
    </div>
    @else
    <p class="text-center">No products found.</p>
    @endif
</div>
@endsection