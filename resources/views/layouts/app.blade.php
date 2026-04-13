<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IT Project Management')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fff !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .nav-link:hover {
            color: #fff !important;
        }

 

.project-card {
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.nav-link:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
}
    </style>

    @yield('css')
</head>
<body>

<!-- 🔥 NAVBAR LUÔN HIỆN -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-list-check"></i> IT Project Manager
        </a>

        <!-- Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                @guest
                    <!-- CHƯA LOGIN -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
    <a class="nav-link" href="{{ route('projects.index') }}">
        <i class="fas fa-chart-line"></i> Dashboard
    </a>
</li>

                    <li class="nav-item">
                        <span class="nav-link text-white">
                            {{ auth()->user()->name }}
                        </span>
                    </li>

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-white">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </button>
                        </form>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="w-100">
    @yield('content')
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@yield('js')
</body>
</html>