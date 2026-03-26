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
    @section('title', 'Pending Approval')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0"><i class="fas fa-clock"></i> Compte en attente d'approbation</h4>
                </div>
                <div class="card-body text-center">
                    <div class="py-5">
                        <i class="fas fa-hourglass-half fa-4x text-muted mb-3"></i>
                        <h3>Votre compte est en attente d'approbation par un administrateur</h3>
                        <p class="lead">Vous pourrez accéder au système une fois qu'un administrateur aura approuvé votre inscription.</p>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


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