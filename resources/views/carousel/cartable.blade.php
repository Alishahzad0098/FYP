@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <a href="{{ route('form.carousel') }}" class="btn btn-info my-4 ms-5 text-white ">Add Carousel</a>
        <table border="2" class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Para</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($car as $products)
                    <tr>
                        <td><img src="{{asset('images/' . $products->img)}}" width="100px" height="100px"></td>
                        <td>{{ $products->para}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection