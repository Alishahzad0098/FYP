@extends('layout.app')

@section('title', 'Add Banner')

@section('content')
    <div class="form-back">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('store.car') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Carousel Banner Image</label>
                    <input type="file" class="form-control @error('img') is-invalid @enderror"
                           name="img" accept="image/*">
                    @error('img')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Upload Banner</button>
            </form>

        </div>
    </div>
@endsection