@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-edit"></i> Edit Task</h4>
            </div>
            <div class="card-body">
                @include('tasks._form', [
                    'task' => $task,
                    'users' => $users,
                    'route' => route('tasks.update', $task),
                    'method' => 'PUT'
                ])
            </div>
        </div>
    </div>
</div>
@endsection