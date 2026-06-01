@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Fashion Products</h2>
            <a href="{{ route('form.product') }}" class="btn btn-info text-white">Add Product</a>
        </div>

        <table id="products-table" class="table table-hover table-bordered align-middle">
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
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(function () {
                $('#products-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("datatable.products") }}',
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'brand_name', name: 'brand_name' },
                        { data: 'article_name', name: 'article_name' },
                        { data: 'type', name: 'type' },
                        { data: 'size', name: 'size' },
                        { data: 'fabric', name: 'fabric' },
                        { data: 'gender', name: 'gender' },
                        { data: 'description', name: 'description' },
                        { data: 'price', name: 'price' },
                        { data: 'images', name: 'images' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    @endpush
@endsection