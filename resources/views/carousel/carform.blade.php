@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="form-back">
        <div class="container">
            <form method="POST" action="{{ route('store.car') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputImage1" class="form-label">Carousel Banner</label>
                    <input type="file" class="form-control" id="exampleInputImage1" name="img" id="img">
                </div>
                <div class="mb-3">
                    <label for="exampleInputText" class="form-label">Text on Banner</label>
                    <input type="text" class="form-control" id="exampleInputText" name="para" id="para">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
