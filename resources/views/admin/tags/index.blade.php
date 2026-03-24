@extends('layouts.app')

@section('title', 'Manage Tags')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-tags"></i> Tags</h2>
    <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Tag
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($tags->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Color</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tags as $tag)
                    <tr>
                        <td>{{ $tag->name }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $tag->color ?? '#6c757d' }}; color: white;">
                                {{ $tag->color ?? 'default' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this tag?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $tags->links() }}
        @else
        <p class="text-muted">No tags yet. <a href="{{ route('admin.tags.create') }}">Create the first one</a></p>
        @endif
    </div>
</div>
@endsection