@extends('layout.app')

@section('title', 'Add Fashion Product')

@section('content')
<div class="form-back py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="card shadow-sm rounded-3">
            <div class="card-body p-5">
                <h2 class="mb-4 text-center">Add New Fashion Product</h2>

                <form method="POST" action="{{ route('store.product') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Brand Name -->
                    <div class="mb-3">
                        <label for="brand_name" class="form-label fw-bold">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name" required>
                    </div>

                    <!-- Article Name -->
                    <div class="mb-3">
                        <label for="article_name" class="form-label fw-bold">Article Name</label>
                        <input type="text" class="form-control" id="article_name" name="article_name" placeholder="Enter article name" required>
                    </div>

                    <!-- Product Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label fw-bold">Product Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="two_piece_suit">Two Piece Suits</option>
                            <option value="three_piece_suit">Three Piece Suits</option>
                            <option value="single_shirt">Single Shirts</option>
                            <option value="tshirts">T-Shirts</option>
                            <option value="jeans">Jeans</option>
                            <option value="jackets_hoodies">Jackets & hoodies</option>
                            <option value="bags">Bags</option>
                            <option value="perfumes">Perfumes</option>
                        </select>
                    </div>

                    <!-- Size -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Available Sizes</label>
                        <div class="row">
                            @foreach (['XS','S','M','L','XL','XXL','Free'] as $size)
                            <div class="col-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="size[]"
                                        value="{{ $size }}"
                                        id="size_{{ $size }}">
                                    <label class="form-check-label" for="size_{{ $size }}">
                                        {{ $size === 'Free' ? 'Free Size' : $size }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Fabric -->
                    <div class="mb-3">
                        <label for="fabric" class="form-label fw-bold">Fabric</label>
                        <select name="fabric" id="fabric" class="form-select">
                            <option value="">Select Fabric</option>
                            <option value="cotton">Cotton</option>
                            <option value="linen">Linen</option>
                            <option value="silk">Silk</option>
                            <option value="wool">Wool</option>
                            <option value="leather">Leather</option>
                            <option value="denim">Denim</option>
                            <option value="polyester">Polyester</option>
                            <option value="mixed">Mixed Fabric</option>
                        </select>
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gender</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="men" id="men" class="form-check-input">
                            <label for="men" class="form-check-label">Men</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="women" id="women" class="form-check-input">
                            <label for="women" class="form-check-label">Women</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="kids" id="kids" class="form-check-input" checked>
                            <label for="kids" class="form-check-label">Kids</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="unisex" id="unisex" class="form-check-input" checked>
                            <label for="unisex" class="form-check-label">Unisex</label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="Enter product description" required></textarea>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label fw-bold">Price ($)</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Enter price" required>
                    </div>

                    <!-- Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label fw-bold">Upload Images</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                        <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Submit Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
<script>
    const imagesInput = document.getElementById('images');
    const previewContainer = document.getElementById('imagePreview');

    imagesInput.addEventListener('change', function() {
        previewContainer.innerHTML = ''; // Clear previous
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '5px';
                img.classList.add('shadow-sm');
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection