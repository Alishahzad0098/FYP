@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <table id="orders-table" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Payment</th>
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
                $('#orders-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("datatable.orders") }}',
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'customer_name', name: 'customer_name' },
                        { data: 'number', name: 'number' },
                        { data: 'customer_email', name: 'customer_email' },
                        { data: 'address', name: 'address' },
                        { data: 'total_amount', name: 'total_amount' },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return '<a href="/admin/orderitemtable/' + row.id + '" class="btn btn-info btn-sm">View Items</a>';
                            }
                        }
                    ]
                });
            });
        </script>
    @endpush
@endsection