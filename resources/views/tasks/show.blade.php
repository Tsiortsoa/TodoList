@extends('layouts.app')

@section('title', $task->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="fas fa-info-circle"></i> Task Details</h4>
                <div>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <h2>{{ $task->title }}</h2>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Status:</strong>
                            @if($task->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($task->isOverdue())
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-info">Pending</span>
                            @endif
                        </p>

                        <p><strong>Due Date:</strong> {{ $task->due_date->format('F j, Y H:i') }}</p>

                        <p><strong>Recurrence:</strong> {{ ucfirst($task->recurrence) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Assigned to:</strong> {{ $task->assignedUser->name }} ({{ $task->assignedUser->email }})</p>
                        <p><strong>Created by:</strong> {{ $task->creator->name }} ({{ $task->creator->email }})</p>
                        <p><strong>Created at:</strong> {{ $task->created_at->format('F j, Y H:i') }}</p>
                        @if($task->deleted_at)
                            <p><strong>Deleted at:</strong> {{ $task->deleted_at->format('F j, Y H:i') }}</p>
                        @endif
                    </div>
                </div>

                @if($task->description)
                    <div class="mt-3">
                        <h5>Description</h5>
                        <div class="p-3 bg-light rounded">
                            {{ nl2br(e($task->description)) }}
                        </div>
                    </div>
                @endif

                @if($task->tags->count() > 0)
                    <div class="mt-3">
                        <h5>Tags</h5>
                        @foreach($task->tags as $tag)
                            <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}; color: white; font-size: 0.9rem;">
                                {{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                @if($task->status == 'pending' && !$task->trashed())
                    <div class="mt-4">
                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Mark as Completed</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection