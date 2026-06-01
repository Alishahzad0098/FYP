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

        <table id="carousel-table" class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Banner Image</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(function () {
                $('#carousel-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("datatable.carousel") }}',
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'img', name: 'img' },
                        { data: 'para', name: 'para' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    @endpush
@endsection