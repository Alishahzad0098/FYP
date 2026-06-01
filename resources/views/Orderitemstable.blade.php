@extends('layout.app')

@section('title', 'Ordered Items ')

@section('content')
    <div class="container mt-4">
        <table id="orderitems-table" class="table table-bordered table-striped">
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
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(function () {
                // Get order_id from URL path (e.g., /admin/orderitemtable/5)
                var pathParts = window.location.pathname.split('/');
                var orderId = null;

                // Find numeric ID in URL path
                for (var i = 0; i < pathParts.length; i++) {
                    if (pathParts[i] && /^\d+$/.test(pathParts[i])) {
                        orderId = pathParts[i];
                        break;
                    }
                }

                var url = '{{ route("datatable.orderitems") }}';
                if (orderId) {
                    url += '?order_id=' + orderId;
                }

                $('#orderitems-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: url,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'order_id', name: 'order_id' },
                        { data: 'brand_name', name: 'brand_name' },
                        { data: 'article_name', name: 'article_name' },
                        { data: 'size', name: 'size' },
                        { data: 'gender', name: 'gender' },
                        { data: 'price', name: 'price' },
                        { data: 'quantity', name: 'quantity' },
                        { data: 'images', name: 'images' }
                    ]
                });
            });
        </script>
    @endpush
@endsection