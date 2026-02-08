@extends('layout.app')

@section('title', 'Ordered Items ')

@section('content')
    <div class="container mt-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Brand Name</th>
                    <th>Article</th>
                    <th>Size</th>
                    <th>Gender</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Images</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderitem as $products)
                    <tr>
                        <td>{{ $products->id }}</td>
                        <td>{{ $products->order_id }}</td>
                        <td>{{ $products->brand_name }}</td>
                        <td>{{ $products->article_name }}</td>
                        <td>{{ $products->size }}</td>
                        <td>{{ $products->gender }}</td>
                        <td>{{ $products->price }}</td>
                        <td>{{ $products->quantity }}</td>
                        <td>
                            @php
                                $images = json_decode($products->images, true);
                            @endphp
                            @if(is_array($images) && count($images) > 0)
                                <img src="{{ asset($images[0]) }}" style="width: 50px; height: 50px; object-fit: cover;"
                                    class="img-thumbnail">
                            @else
                                <span class="text-muted">No image</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
