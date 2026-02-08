@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome to Dashboard</h1>
    <p>You are logged in as {{ Auth::user()->name }}</p>
    <a href="{{ route('home') }}"><button class="btn btn-outline-secondary">View Site</button></a>
@endsection
