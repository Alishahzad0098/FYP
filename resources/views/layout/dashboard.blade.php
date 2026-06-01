@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome to Dashboard</h1>
    <p>You are logged in as {{ Auth::user()->name }}</p>
    <a href="{{ route('home') }}"><button class="btn btn-outline-secondary">View Site</button></a>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center bg-warning p-3 rounded text-white">
                    <div>
                        <h6 class="text-muted">Total Products</h6>
                        <h2 class="mb-0">{{ $totalProducts }}</h2>
                    </div>
                    <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center bg-success p-3 rounded text-white">
                    <div>
                        <h6 class="text-muted">Total Orders</h6>
                        <h2 class="mb-0">{{ $totalOrders }}</h2>
                    </div>
                    <div class="stats-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center bg-info p-3 rounded text-white">
                    <div>
                        <h6 class="text-muted">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div class="stats-icon bg-info bg-opacity-10 text-info">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between align-items-center bg-primary p-3 rounded text-white">
                    <div>
                        <h6 class="text-muted">Contact Messages</h6>
                        <h2 class="mb-0">{{ $totalMessages }}</h2>
                    </div>
                    <div class="stats-icon bg-secondary bg-opacity-10 text-secondary">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-7">
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('datatable.orders') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>

                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="table-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Recent Contact Messages</h5>
                    <a href="{{ route('messages.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMessages as $message)
                                <tr>
                                    <td>{{ $message->name }}</td>
                                    <td>{{ $message->email }}</td>
                                    <td>{{ $message->subject }}</td>
                                    <td>{{ $message->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No messages yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection