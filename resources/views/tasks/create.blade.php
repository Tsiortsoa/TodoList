@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle"></i> Create New Task</h4>
            </div>
            <div class="card-body">
                @include('tasks._form', [
                    'task' => null,
                    'users' => $users,
                    'route' => route('tasks.store'),
                    'method' => 'POST'
                ])
            </div>
        </div>
    </div>
</div>
@endsection