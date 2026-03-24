<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Task Manager') - TaskFlow</title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    @stack('styles')

</head>

<body>
    <!-- Mobile toggler -->
    <button class="navbar-toggler" type="button" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-sticky">
            <div class="text-center py-3">
                <h5 class="text-white">TaskFlow</h5>
            </div>
            <ul class="px-4 nav flex-column">
                @auth
                @if(auth()->user()->isApproved())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                        <i class="fas fa-tasks"></i> My Tasks
                    </a>
                </li>
                @can('viewAny', App\Models\User::class)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users-cog"></i> Manage Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
                        <i class="fas fa-tags"></i> Manage Tags
                    </a>
                </li>
                @endcan
                @else
                <li class="nav-item">
                    <span class="nav-link text-warning"><i class="fas fa-clock"></i> Pending Approval</span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100" style="color: #f8f9fa;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
                @endif
                <hr class="text-white-50">
                <li class="nav-item mt-4">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100" style="color: #f8f9fa;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
                @endauth
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                </li>
                @endguest
            </ul>
            @if(request()->routeIs('tasks.index'))
            <hr class="text-white-50">
            <div class="px-3">
                <h6 class="text-white-50"><i class="fas fa-filter"></i> Filters</h6>
                @include('tasks._filters')
            </div>
            @endif
        </div>
    </div>

    <!-- Main content -->
    <main>
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="{{asset('js/all.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    <!-- Custom JS for sidebar toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
    @stack('scripts')

</body>

</html>