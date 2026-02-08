@extends('layout.app')

@section('title', 'Edit Fashion Product')

@section('content')
<div class="form-back py-5" style="background: #f8f9fa;">
    <div class="container">
        <div class="card shadow-sm rounded-3">
            <div class="card-body p-5">
                <h2 class="mb-4 text-center">Edit Fashion Product</h2>

                <form method="POST" action="{{ route('update.product', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                  

                    <!-- Brand Name -->
                    <div class="mb-3">
                        <label for="brand_name" class="form-label fw-bold">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" 
                               value="{{ old('brand_name', $product->brand_name) }}" required>
                    </div>

                    <!-- Article Name -->
                    <div class="mb-3">
                        <label for="article_name" class="form-label fw-bold">Article Name</label>
                        <input type="text" class="form-control" id="article_name" name="article_name" 
                               value="{{ old('article_name', $product->article_name) }}" required>
                    </div>

                    <!-- Product Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label fw-bold">Product Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="two_piece_suit" {{ $product->type == 'two_piece_suit' ? 'selected' : '' }}>Two Piece Suits</option>
                            <option value="three_piece_suit" {{ $product->type == 'three_piece_suit' ? 'selected' : '' }}>Three Piece Suits</option>
                            <option value="single_shirt" {{ $product->type == 'single_shirt' ? 'selected' : '' }}>Single Shirts</option>
                            <option value="bags" {{ $product->type == 'bags' ? 'selected' : '' }}>Bags</option>
                            <option value="perfumes" {{ $product->type == 'perfumes' ? 'selected' : '' }}>Perfumes</option>
                        </select>
                    </div>

                    <!-- Size -->
                    <div class="mb-3">
                        <label for="size" class="form-label fw-bold">Size</label>
                        <select name="size" id="size" class="form-select">
                            <option value="">Select Size</option>
                            @foreach(['XS','S','M','L','XL','XXL','Free'] as $size)
                                <option value="{{ $size }}" {{ $product->size == $size ? 'selected' : '' }}>
                                    {{ $size == 'Free' ? 'Free Size' : $size }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fabric -->
                    <div class="mb-3">
                        <label for="fabric" class="form-label fw-bold">Fabric</label>
                        <select name="fabric" id="fabric" class="form-select">
                            <option value="">Select Fabric</option>
                            @foreach(['cotton','linen','silk','wool','leather','denim','polyester','mixed'] as $fabric)
                                <option value="{{ $fabric }}" {{ $product->fabric == $fabric ? 'selected' : '' }}>
                                    {{ ucfirst($fabric) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Gender</label>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="men" id="men" class="form-check-input" {{ $product->gender == 'men' ? 'checked' : '' }}>
                            <label for="men" class="form-check-label">Men</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="women" id="women" class="form-check-input" {{ $product->gender == 'women' ? 'checked' : '' }}>
                            <label for="women" class="form-check-label">Women</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="gender" value="unisex" id="unisex" class="form-check-input" {{ $product->gender == 'unisex' ? 'checked' : '' }}>
                            <label for="unisex" class="form-check-label">Unisex</label>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label fw-bold">Price ($)</label>
                        <input type="number" name="price" id="price" class="form-control" 
                               value="{{ old('price', $product->price) }}" required>
                    </div>

                    <!-- Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label fw-bold">Upload New Images (optional)</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                        <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    </div>

                    <!-- Show existing images -->
                    @if($product->images)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Images:</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(json_decode($product->images) as $img)
                                    <img src="{{ asset($img) }}" alt="Product Image" width="100" height="100" style="object-fit:cover; border-radius:5px;">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Update Product</button>
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
