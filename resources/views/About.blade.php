@extends('layout')
@section('title', 'Sapphire - About Us')
<style>
    .ab-top { background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
            background-image: url('{{ asset("asset/1751798235.jpg") }}');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
        }
</style>
@section('content')
    <!-- Hero Section -->
   <div class="ab-top"
     >
    
    <h1 class="text-center text-white"
        style="padding-top: 100px; font-size: 50px; font-weight: 700; letter-spacing: 2px;">
        About Us
    </h1>

    <h5 class="text-center text-white" style="padding-bottom: 100px;">
        <a href="{{ route('home') }}" class="text-white">Home</a> &raquo; About Us
    </h5>
</div>




    <!-- About Section -->
    <div class="container my-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h3 class="fs-2 mb-3">
                    <span class="fs-1 fw-bolder" style="color:#111111;">We Work</span> for Your Best Success
                </h3>
                <p style="color: #888888; font-size: 1rem; line-height: 1.7;">
                    Consectetur adipiscing elit. Aliquam sit amet efficitur tortor. Suspendisse efficitur orci urna. 
                    In et augue ornare, tempor massa in, luctus sapien.
                </p>
                <ul class="list-unstyled mt-4" style="color: #888888; font-size: 0.95rem;">
                    <li class="py-2"><i class="fas fa-check-square me-2" style="color:#C6A15B;"></i>Ut enim ad minim veniam</li>
                    <li class="py-2"><i class="fas fa-check-square me-2" style="color:#C6A15B;"></i>Quis nostrud exercitation ullamco laboris</li>
                    <li class="py-2"><i class="fas fa-check-square me-2" style="color:#C6A15B;"></i>Nisi ut aliquip ex ea commodo consequat</li>
                </ul>
                <div class="mt-4">
                    <a href="#"><button class="btn button text-white">View Our Products</button></a>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <h1 class="number-text" style="font-size: 220px; background: linear-gradient(90deg, #C6A15B, #FFFFFF); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">28</h1>
                <span class="years-text" style="font-size: 50px; font-weight: 700; color:#C6A15B;">Years</span>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="comments py-5" style="background: #F5F5F5;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4">
                    <div class="card my-4 shadow-sm border-0">
                        <div class="card-body">
                            <p class="card-text fst-italic"> 
                                <i class="fas fa-quote-left me-2" style="color:#C6A15B;"></i>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta consequatur quia in nobis magni veniam.
                            </p>
                        </div>
                        <div class="card-footer d-flex align-items-center gap-3 border-0 bg-white">
                            <img src="{{ asset('asset/carousel/testi2.jpg') }}" alt="testimonial" class="rounded-circle" width="75">
                            <div>
                                <h5 class="card-title mb-0" style="color:#111111;">Petty Cruis</h5>
                                <small style="color:#888888;">Fashion Designer</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card my-4 shadow-sm border-0">
                        <div class="card-body">
                            <p class="card-text fst-italic"> 
                                <i class="fas fa-quote-left me-2" style="color:#C6A15B;"></i>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta consequatur quia in nobis magni veniam.
                            </p>
                        </div>
                        <div class="card-footer d-flex align-items-center gap-3 border-0 bg-white">
                            <img src="{{ asset('asset/carousel/testi2.jpg') }}" alt="testimonial" class="rounded-circle" width="75">
                            <div>
                                <h5 class="card-title mb-0" style="color:#111111;">Petty Cruis</h5>
                                <small style="color:#888888;">Fashion Designer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
