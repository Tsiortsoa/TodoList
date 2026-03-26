@extends('layouts.guest')

@section('title', 'Welcome to TaskFlow')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-layer-group fa-3x text-primary mb-3"></i>
            <h1 class="display-3 hero-title">TaskFlow</h1>
        </div>
        <p class="lead text-muted mb-5 px-4" style="max-width: 600px;">
            Optimisez votre productivité. Gérez vos projets, collaborez en temps réel et ne manquez plus aucune échéance.
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
            <button type="button" class="btn btn-primary btn-lg shadow" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-rocket me-2"></i>Se connecter
            </button>
            <button type="button" class="btn btn-outline-dark btn-lg" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="fas fa-user-plus me-2"></i>S'inscrire
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="fw-bold">Bon retour !</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" name="email" placeholder="nom@exemple.com" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-bold">Mot de passe</label>
                            <a href="{{ route('password.request') }}" class="small text-decoration-none">Oublié ?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control border-start-0" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Rester connecté</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm">Se connecter</button>
                </form>
                <div class="text-center mt-4">
                    <p class="small text-muted mb-0">Pas encore de compte ? 
                        <a href="#" class="fw-bold text-decoration-none" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="fw-bold">Rejoindre TaskFlow</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nom</label>
                        <input type="text" class="form-control" name="name" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="john@example.com" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label small fw-bold">Mot de passe</label>
                            <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                        </div>
                        <div class="col">
                            <label class="form-label small fw-bold">Confirmation</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Rôle</label>
                        <select name="role" class="form-select border-0 bg-light py-2">
                            <option value="user">Utilisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm">Créer mon compte</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection