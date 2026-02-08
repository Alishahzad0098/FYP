@extends('layout.app')

@section('title', 'Dashboard')

@section('content') <div class="container mt-4">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u1)
                    <tr>
                        <td>{{ $u1->id }}</td>
                        <td>{{ $u1->name }}</td>
                        <td>{{ $u1->email }}</td>
                        <td>{{ $u1->role }}</td>
                        <td>{{ $u1->password }}</td>
                        <td>
                            <a href="{{ route('edit.user', $u1->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('delete.user', $u1->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this user?');"
                                style="display:inline;">
                                @csrf
                                @method('DELETE') <!-- This is the required fix -->
                                <button type="submit" class="btn btn-danger text-white">Delete</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection