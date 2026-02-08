<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome & Google Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --dark-color: #2c3e50;
            --light-color: #ffffff;
            --hover-color: #3a5bd9;
            --border-color: #e3e6f0;
            --text-color: #d1d3e2;
            --active-color: rgba(78, 115, 223, 0.2);
        }

        /* Sidebar */
        .main-menu {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: var(--dark-color);
            display: flex;
            flex-direction: column;
            transition: width 0.3s ease;
            font-family: 'Poppins', sans-serif;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .main-menu.collapsed {
            width: 80px;
        }

        /* Menu Header */
        .menu-header {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .menu-logo {
            color: var(--light-color);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .menu-toggle {
            color: var(--light-color);
            font-size: 1.2rem;
            cursor: pointer;
        }

        /* Menu Items */
        .menu-items {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }

        .menu-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 10px 0;
        }

        .main-menu li {
            list-style: none;
        }

        .main-menu li>a {
            display: flex;
            align-items: center;
            color: var(--text-color);
            padding: 12px 20px;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }

        .main-menu li>a:hover,
        .main-menu li>a.active {
            color: var(--light-color);
            background-color: var(--active-color);
        }

        .main-menu li>a:hover::before,
        .main-menu li>a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--primary-color);
        }

        /* Icons & Text */
        .main-menu .fa {
            width: 30px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-text {
            margin-left: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .main-menu.collapsed .nav-text,
        .main-menu.collapsed .dropdown-arrow,
        .main-menu.collapsed .menu-logo {
            display: none;
        }

        /* Hide dropdown menus completely when sidebar is collapsed */
        .main-menu.collapsed .dropdown-menu {
            display: none !important;
        }


        /* Dropdown */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            display: block;
            /* ensures it occupies space */
            position: relative;
            /* keep it relative to parent */
            transition: max-height 0.3s ease;
            background-color: rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu.show {
            max-height: 500px;
        }

        .dropdown-menu li a {
            padding: 10px 20px 10px 50px;
            font-size: 0.85rem;
        }

        .dropdown-menu li a:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }

        .dropdown-arrow {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle.active .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Logout */
        .logout-btn {
            color: #e74c3c;
        }

        .logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.2) !important;
        }

        /* Scrollbar */
        .menu-items::-webkit-scrollbar {
            width: 5px;
        }

        .menu-items::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* Main content spacing */
        .main-content {
            transition: margin-left 0.3s ease, padding-left 0.3s ease;
            padding-top: 20px;
            padding-right: 20px;
        }

        @media (max-width: 768px) {
            .main-menu {
                width: 80px;
            }

            .main-menu.expanded {
                width: 250px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar Navigation -->
    <nav class="main-menu">
        <div class="menu-header">
            <span class="menu-logo">Admin Panel</span>
            <i class="fa fa-bars menu-toggle"></i>
        </div>

        <ul class="menu-items">
            <li>
                <a href="{{ route('dashboard') }}" class="active">
                    <i class="fa fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle">
                    <i class="fa fa-align-left"></i>
                    <span class="nav-text">Tables</span>
                    <i class="fa fa-angle-down dropdown-arrow"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('table.product') }}"><i class="fa fa-cube me-2"></i> Products Table</a></li>
                    <li><a href="{{ route('authtable') }}"><i class="fa fa-users me-2"></i> Users Table</a></li>
                    <li><a href="{{ route('admintable') }}"><i class="fa fa-users me-2"></i> Admins Table</a></li>
                    <li><a href="{{ route('order.table') }}"><i class="fa fa-shopping-cart me-2"></i> Orders Table</a></li>
                    <li><a href="{{ route('orderitem.table') }}"><i class="fa fa-shopping-cart me-2"></i> Orders items Table</a></li>
                    <li><a href="{{ route('table.car') }}"><i class="fa fa-shopping-cart me-2"></i> Carousel Table</a></li>
                </ul>
            </li>

            <li>
                <a href="#">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
        </ul>

        <ul class="menu-footer">
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn text-white w-100 text-start border-0 bg-transparent p-2">
                        <i class="fa fa-power-off"></i>
                        <span class="nav-text">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content container">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const mainMenu = document.querySelector('.main-menu');
            const content = document.querySelector('.main-content');
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

            // Adjust content margin
            function adjustContent() {
                if (mainMenu.classList.contains('collapsed')) {
                    content.style.marginLeft = '80px';
                    content.style.paddingLeft = '20px';
                } else if (mainMenu.classList.contains('expanded')) {
                    content.style.marginLeft = '250px';
                    content.style.paddingLeft = '20px';
                } else {
                    content.style.marginLeft = '250px';
                    content.style.paddingLeft = '20px';
                }
            }

            adjustContent();

            // Sidebar toggle
            menuToggle.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    mainMenu.classList.toggle('expanded'); // Mobile
                } else {
                    mainMenu.classList.toggle('collapsed'); // Desktop
                }
                adjustContent();
            });

            // Dropdown toggle
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    this.classList.toggle('active');
                    dropdownMenu.classList.toggle('show');
                });
            });

            // Auto-collapse on resize
            function handleResize() {
                if (window.innerWidth <= 768) {
                    mainMenu.classList.add('collapsed');
                    mainMenu.classList.remove('expanded');
                } else {
                    mainMenu.classList.remove('collapsed');
                    mainMenu.classList.remove('expanded');
                }
                adjustContent();
            }

            window.addEventListener('resize', handleResize);
            handleResize();
        });
    </script>

    @stack('scripts')
</body>

</html>