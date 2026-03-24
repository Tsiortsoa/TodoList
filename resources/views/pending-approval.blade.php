@extends('layouts.app')

@section('title', 'Pending Approval')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0"><i class="fas fa-clock"></i> Account Pending Approval</h4>
            </div>
            <div class="card-body text-center">
                <div class="py-5">
                    <i class="fas fa-hourglass-half fa-4x text-muted mb-3"></i>
                    <h3>Your account is awaiting administrator approval</h3>
                    <p class="lead">You will be able to access the system once an admin approves your registration.</p>
                    <p>If you have any questions, please contact the administrator.</p>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection