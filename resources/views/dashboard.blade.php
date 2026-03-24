@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="fas fa-chart-pie"></i> Dashboard</h2>
    </div>
</div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Tasks</h6>
                        <h2 class="mb-0">{{ $stats['total_tasks'] }}</h2>
                    </div>
                    <i class="fas fa-tasks fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Pending</h6>
                        <h2 class="mb-0">{{ $stats['pending_tasks'] }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Completed</h6>
                        <h2 class="mb-0">{{ $stats['completed_tasks'] }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Overdue</h6>
                        <h2 class="mb-0">{{ $stats['overdue_tasks'] }}</h2>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user"></i> My Tasks</h5>
                <h3>{{ $stats['my_tasks'] }}</h3>
                <small class="text-muted">Tasks assigned to you</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-pen"></i> Created by Me</h5>
                <h3>{{ $stats['created_by_me'] }}</h3>
                <small class="text-muted">Tasks you have created</small>
            </div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-tie"></i> Assigned by Admin</h5>
                <h3>{{ $stats['assigned_by_admin'] }}</h3>
                <small class="text-muted">Tasks admins assigned to others</small>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Recent Tasks -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-history"></i> Recent Tasks</h5>
            </div>
            <div class="card-body">
                @if($recentTasks->count() > 0)
                <div class="list-group">
                    @foreach($recentTasks as $task)
                    <a href="{{ route('tasks.show', $task) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $task->title }}</h6>
                            <small>{{ $task->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">{{ Str::limit($task->description, 80) }}</p>
                        <small>
                            <span class="badge {{ $task->status == 'completed' ? 'bg-success' : 'bg-info' }}">
                                {{ $task->status }}
                            </span>
                            @if($task->assignedUser)
                            <span class="badge bg-secondary">Assigned to: {{ $task->assignedUser->name }}</span>
                            @endif
                        </small>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-muted">No recent tasks.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection