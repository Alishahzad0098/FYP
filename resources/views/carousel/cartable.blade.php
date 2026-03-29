@extends('layout.app')

@section('title', 'Banners')

@section('content')
    <div class="container mt-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <a href="{{ route('form.carousel') }}" class="btn btn-info my-4 ms-5 text-white">
            + Add Banner
        </a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Banner Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($car as $index => $banner)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <img src="{{ asset('images/' . $banner->img) }}"
                                 width="150px" height="80px"
                                 style="object-fit: cover; border-radius: 6px;">
                        </td>
                        <td>
                            <form action="{{ route('delete.car', $banner->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this banner?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No banners uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection