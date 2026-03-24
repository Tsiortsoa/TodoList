@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-tasks"></i> Tasks</h2>
    </div>
    @if(auth()->user()->role === 'admin')
    <div class="col-md-4 text-end">
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Task
        </a>
        <a href="{{ route('tasks.index', ['status' => 'trash']) }}" class="btn btn-outline-secondary">
            <i class="fas fa-trash-alt"></i> Trash
        </a>
    </div>
    @endif
</div>

<!-- Tasks List -->
@if($tasks->count() > 0)
<div class="row">
    @foreach($tasks as $task)
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card task-card h-100 {{ $task->isOverdue() ? 'overdue' : '' }} {{ $task->status == 'completed' ? 'completed' : '' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-tag me-1"></i>
                    @if($task->status == 'completed')
                    <span class="badge bg-success">Completed</span>
                    @elseif($task->isOverdue())
                    <span class="badge bg-danger">Overdue</span>
                    @else
                    <span class="badge bg-info">Pending</span>
                    @endif
                </span>
                <small class="text-muted">
                    @if($task->recurrence != 'none')
                    <i class="fas fa-redo-alt" data-bs-toggle="tooltip" title="Recurs {{ $task->recurrence }}"></i>
                    @endif
                </small>
            </div>
            <div class="card-body">
                <h5 class="card-title">
                    <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none">
                        {{ $task->title }}
                    </a>
                </h5>
                <p class="card-text">{{ Str::limit($task->description, 80) }}</p>
                <div class="mb-2">
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt"></i> Due: {{ $task->due_date->format('M d, Y H:i') }}
                    </small>
                </div>

                <!-- Tags -->
                @if($task->tags->count() > 0)
                <div class="mb-2">
                    @foreach($task->tags as $tag)
                    <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}; color: white;">
                        {{ $tag->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                <!-- Assignment info -->
                <div class="small">
                    @if($task->assignedUser)
                    <span class="badge bg-secondary">
                        <i class="fas fa-user"></i> {{ $task->assignedUser->name }}
                        @if($task->creator && $task->creator->id !== $task->assignedUser->id)
                        (assigned)
                        @endif
                    </span>
                    @endif
                    @if($task->creator && $task->creator->id !== auth()->id())
                    <span class="badge bg-info">
                        <i class="fas fa-user-tie"></i> by {{ $task->creator->name }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between">
                <div>
                    @if($task->trashed())
                    <!-- Actions for trashed tasks -->
                    @can('restore', $task)
                    <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Restore">
                            <i class="fas fa-undo-alt"></i>
                        </button>
                    </form>
                    @endcan
                    @can('forceDelete', $task)
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#forceDeleteModal{{ $task->id }}">
                        <i class="fas fa-trash-alt"></i> Delete Permanently
                    </button>
                    @endcan
                    @else
                    <!-- Actions for active tasks -->
                    @if(auth()->user()->role === 'admin')
                    @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    @endcan
                    @endif

                    @if($task->status == 'pending')
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Mark Complete">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @endif

                    @if(auth()->user()->role === 'admin')
                    @can('delete', $task)
                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $task->id }}">
                        <i class="fas fa-trash"></i>
                    </button>
                    @endcan
                    @endif
                    @endif

                </div>
                <small class="text-muted">{{ $task->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal (for soft delete) -->
    @if(!$task->trashed())
    <div class="modal fade" id="deleteModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Move "{{ $task->title }}" to trash?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Yes, move to trash</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Force Delete Modal -->
    <div class="modal fade" id="forceDeleteModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Permanently Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-danger">This action cannot be undone. Delete "{{ $task->title }}" permanently?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('tasks.forceDelete', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Permanently Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $tasks->appends(request()->query())->links() }}
</div>
@else
<div class="alert alert-info text-center">
    <i class="fas fa-info-circle"></i> No tasks found.
</div>
@endif

@endsection