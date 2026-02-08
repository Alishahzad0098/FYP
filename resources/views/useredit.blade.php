@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="form-back">
        <div class="container">
            <h3>Edit form</h3>
            <form method="POST" action="{{ route('update.user', $u1->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputImage1" class="form-label">Name</label>
                    <input type="text" class="form-control" id="exampleInputImage1" name="name" id="name"
                        value="{{$u1->name}}">
                </div>
                <div class="mb-3">
                    <label for="exampleInputText" class="form-label">Email</label>
                    <input type="email" class="form-control" id="exampleInputText" name="email" id="email"
                        value="{{$u1->email}}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label><br>

                    <input type="radio" name="role" value="user" {{ $u1->role == 'user' ? 'checked' : '' }}>
                    <label for="role_user">User</label>

                    <input type="radio" name="role" value="admin" {{ $u1->role == 'admin' ? 'checked' : '' }}>
                    <label for="role_admin">Admin</label>
                <div class="mb-3">
                    <<div class="mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Leave empty to keep old password">
                </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    @if ($errors->any())
        <div class="alert text-danger">

            @foreach ($errors->all() as $error)
                <p>{{$error}}</p>
            @endforeach
        </div>
    @endif
    </div>
@endsection