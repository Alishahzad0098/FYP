@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Fashion Products</h2>
        <a href="{{ route('form.product') }}" class="btn btn-info text-white">Add Product</a>
    </div>

    <table class="table table-hover table-bordered align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>ID</th>
                <th>Brand</th>
                <th>Article</th>
                <th>Type</th>
                <th>Size</th>
                <th>Fabric</th>
                <th>Gender</th>
                <th>Description</th>
                <th>Price</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($product as $p)
            <tr class="text-center">
                <td>{{ $p->id }}</td>
                <td>{{ $p->brand_name }}</td>
                <td>{{ $p->article_name }}</td>
                <td>
                    <span class="badge bg-primary text-uppercase">{{ str_replace('_', ' ', $p->type) }}</span>
                </td>
                <td>
                    @if(!empty($p->size) && is_array($p->size))
                    @foreach($p->size as $size)
                    <span class="badge bg-secondary me-1">{{ strtoupper($size) }}</span>
                    @endforeach
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if($p->fabric)
                    <span class="badge bg-success">{{ ucfirst($p->fabric) }}</span>
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>
                    @if($p->gender)
                    <span class="badge bg-warning text-dark">{{ ucfirst($p->gender) }}</span>
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </td>
                <td>{{ Str::limit($p->description, 50) }}</td>
                <td>${{ number_format($p->price, 2) }}</td>
                <td>
                    @php
                    $images = is_array($p->images) ? $p->images : json_decode($p->images, true);
                    @endphp
                    @if(!empty($images))
                    <div class="d-flex flex-wrap justify-content-center">
                        @foreach($images as $img)
                        <img src="{{ asset($img) }}" alt="Product Image"
                            style="width: 50px; height: 50px; object-fit: cover; margin: 2px; border-radius: 4px;">
                        @endforeach
                    </div>
                    @else
                    <span class="text-muted">No images</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('edit.product', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('delete.product', $p->id) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this product?')">
                            Delete
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection