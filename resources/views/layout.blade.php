<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Maison Chic')</title>

    <!-- Fonts & Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    @stack('styles')
    <style>
        .collection-card {
            position: relative;
            height: 380px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.4s ease;
        }

        .collection-card:hover {
            transform: scale(1.03);
        }

        .collection-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top,
                    rgba(0, 0, 0, 0.65),
                    rgba(0, 0, 0, 0.15));
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px;
        }

        .collection-overlay h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .collection-overlay p {
            font-size: 0.95rem;
            margin-bottom: 12px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Playfair Display', serif;
        }

        .navbar {
            background-color: #000;
        }

        .navbar .nav-link {
            color: #fff !important;
        }

        .hero {
            background: url('https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;
            height: 90vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.5rem;
            margin-top: 1rem;
        }

        .collections img {
            width: 100%;
            border-radius: 10px;
        }

        .footer {
            background-color: #111;
            color: #aaa;
            padding: 50px 0;
        }

        .footer a {
            color: #fff;
            text-decoration: none;
        }

        .footer a:hover {
            color: #f8c55c;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="container mt-2 text-secondary">
        <div class="d-flex flex-wrap align-items-center justify-content-between px-2">
            <h5 class="mb-2 mb-md-0" style="margin-left: 10px;">
                Welcome to Maison Chic
            </h5>
            <div class="d-flex flex-wrap align-items-center" style="margin-right: 10px;">
                @guest
                    <a href="{{ route('loginpage') }}" class="text-secondary text-decoration-none me-3">
                        <h6 class="mb-0"><i class="fa-solid fa-right-to-bracket me-1"></i> Login</h6>
                    </a>
                    <a href="{{ route('view') }}" class="text-secondary text-decoration-none">
                        <h6 class="mb-0"><i class="fa-solid fa-circle-user me-1"></i> Register</h6>
                    </a>
                @endguest
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-secondary text-decoration-none p-0">
                            <h6 class="mb-0"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</h6>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mt-3">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="{{ route('home') }}">
                <h2>SAPPHIRE</h2>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products') }}">Collections</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-3">
                <!-- Cart Icon -->
                <a href="{{ route('cart.show') }}" class="text-white fs-5 position-relative">
                    <i class="bi bi-bag"></i>
                    <!-- Optional badge -->
                </a>

                <!-- Theme Toggle -->
                <button id="themeToggle" class="btn btn-link text-white fs-5 p-0">
                    <i class="bi bi-moon-fill"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div>
        @yield('content')
    </div>

    <!-- Featured Collections -->
    <div class="container collections my-5">
        <h2 class="text-center mb-4">Trending Collections</h2>
        <div class="row g-4">

            <div class="col-md-4">
                <div class="collection-card"
                    style="background-image: url('https://thumbs.dreamstime.com/b/hand-drawn-beautiful-two-young-women-shopping-bags-fashion-woman-red-skirt-street-background-black-white-sketch-171473694.jpg');">
                    <div class="collection-overlay">
                        <h4>Street Style</h4>
                        <p>Bold looks for everyday wear</p>
                        <a href="{{ route('products') }}" class="btn btn-light btn-sm">Shop Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="collection-card"
                    style="background-image: url('https://images.unsplash.com/photo-1542068829-1115f7259450?auto=format&fit=crop&w=800&q=80');">
                    <div class="collection-overlay">
                        <h4>Classic Wear</h4>
                        <p>Timeless fashion essentials</p>
                        <a href="{{ route('products') }}" class="btn btn-light btn-sm">Explore</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="collection-card"
                    style="background-image: url('https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80');">
                    <div class="collection-overlay">
                        <h4>Summer Drop</h4>
                        <p>Fresh fits for hot days</p>
                        <a href="{{ route('products') }}" class="btn btn-light btn-sm">Discover</a>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="#">Lookbook</a></li>
                        <li><a href="# }}">Terms of Use</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>Contact</h5>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Fashion Ave, NYC</p>
                    <p><i class="fas fa-phone"></i> +1 234 567 890</p>
                    <p><i class="fas fa-envelope"></i> contact@maisonchic.com</p>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>Newsletter</h5>
                    <form action="#" method="post" class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Email">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>Follow Us</h5>
                    <a href="#"><i class="fab fa-facebook-f me-3"></i></a>
                    <a href="#"><i class="fab fa-instagram me-3"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script>
        const toggleBtn = document.getElementById('themeToggle');
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');

            if (document.body.classList.contains('dark-mode')) {
                icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
            } else {
                icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>