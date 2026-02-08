@extends('layout')

@section('content')
    <style>
        .product-carousel-image-container {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 1px solid #b8b2b2ff;
            border-radius: 8px;
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

        .product-description {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #b8b2b2ff;
            border-radius: 8px;
        }

        .compare-section {
            margin-bottom: 30px;

        }
    </style>

    <div class="container">
        <h2 class="text-center mb-5">Compare Products</h2>
        <form method="GET" action="">
            <label>Select product to compare:</label>
            <select name="compare_id" class="form-control mb-3" onchange="this.form.submit()">
                <option value="">-- Select --</option>
                @foreach($otherProducts as $p)
                    <option value="{{ $p->id }}" {{ request('compare_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </form>
        <div class="row mt-5">
            <!-- Left Section (Current Product) -->
            <div class="col-md-6 compare-section">
                <h4 class="mb-3">{{ $product->name }}</h4>

                @php
                    $images = !empty($product->images) ? json_decode($product->images, true) : [];
                @endphp

                <!-- Carousel for left product -->
                <div id="leftProductCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" style="background-color:#f8f9fa; border-radius:8px;">
                        @if(!empty($images))
                            @foreach ($images as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="product-carousel-image-container">
                                        <img src="{{ asset($image) }}" class="product-carousel-image" alt="{{ $product->name }}">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <div class="product-carousel-image-container">
                                    <img src="{{ asset('images/default.png') }}" class="product-carousel-image" alt="No Image">
                                </div>
                            </div>
                        @endif
                    </div>
                    @if(count($images) > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#leftProductCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#leftProductCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    @endif
                </div>

                <div class="product-description mt-3">
                    {{-- RAM --}}
                    <div class="mb-3">
                        <label class="form-label">RAM Usage</label>
                        <div class="progress" role="progressbar" aria-valuenow="{{ $product->ram }}" aria-valuemin="0"
                            aria-valuemax="64">
                            <div class="progress-bar bg-info" style="width: {{ ($product->ram / 64) * 100 }}%">
                                {{ $product->ram }} GB
                            </div>
                        </div>
                    </div>

                    {{-- Storage --}}
                    <div class="mb-3">
                        <label class="form-label">Storage</label>
                        <div class="progress" role="progressbar" aria-valuenow="{{ $product->storage }}" aria-valuemin="0"
                            aria-valuemax="1028">
                            <div class="progress-bar bg-warning" style="width: {{ ($product->storage / 1028) * 100 }}%">
                                {{ $product->storage }} GB
                            </div>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="progress" role="progressbar" aria-valuenow="{{ $product->price }}" aria-valuemin="0"
                            aria-valuemax="1000"> {{-- adjust max price --}}
                            <div class="progress-bar bg-success" style="width: {{ ($product->price / 1000) * 100 }}%">
                                ${{ number_format($product->price) }}
                            </div>
                        </div>
                    </div>
                    <p><strong>Other description:</strong></p>
                    <p>{!! nl2br(e($product->description)) !!}</p>
                </div>
            </div>

            <!-- Right Section (Dropdown to Select Another Product) -->
            <div class="col-md-6 compare-section">
                @if(request('compare_id'))
                    @php
                        $compareProduct = \App\Models\Products::find(request('compare_id'));
                        $compareImages = $compareProduct && !empty($compareProduct->images)
                            ? json_decode($compareProduct->images, true)
                            : [];
                    @endphp

                    @if($compareProduct)
                        <h4 class="mb-3">{{ $compareProduct->name }}</h4>

                        <!-- Carousel for right product -->
                        <div id="rightProductCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner" style="background-color:#f8f9fa; border-radius:8px;">
                                @if(!empty($compareImages))
                                    @foreach ($compareImages as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <div class="product-carousel-image-container">
                                                <img src="{{ asset($image) }}" class="product-carousel-image"
                                                    alt="{{ $compareProduct->name }}">
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="carousel-item active">
                                        <div class="product-carousel-image-container">
                                            <img src="{{ asset('images/default.png') }}" class="product-carousel-image" alt="No Image">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(count($compareImages) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#rightProductCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#rightProductCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>

                        <div class="product-description mt-3">
                            {{-- RAM --}}
                            <div class="mb-3">
                                <label class="form-label">RAM Usage</label>
                                <div class="progress" role="progressbar" aria-valuenow="{{ $product->ram }}" aria-valuemin="0"
                                    aria-valuemax="64">
                                    <div class="progress-bar bg-info" style="width: {{ ($product->ram / 64) * 100 }}%">
                                        {{ $product->ram }} GB
                                    </div>
                                </div>
                            </div>

                            {{-- Storage --}}
                            <div class="mb-3">
                                <label class="form-label">Storage</label>
                                <div class="progress" role="progressbar" aria-valuenow="{{ $product->storage }}" aria-valuemin="0"
                                    aria-valuemax="1028">
                                    <div class="progress-bar bg-warning" style="width: {{ ($product->storage / 1028) * 100 }}%">
                                        {{ $product->storage }} GB
                                    </div>
                                </div>
                            </div>

                            {{-- Price --}}
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <div class="progress" role="progressbar" aria-valuenow="{{ $product->price }}" aria-valuemin="0"
                                    aria-valuemax="100000"> {{-- adjust max price --}}
                                    <div class="progress-bar bg-success" style="width: {{ ($product->price / 100000) * 100 }}%">
                                        Rs. {{ number_format($product->price) }}
                                    </div>
                                </div>
                            </div>

                            <p>{!! nl2br(e($compareProduct->description)) !!}</p>

                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection