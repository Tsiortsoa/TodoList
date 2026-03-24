<form method="GET" action="{{ route('tasks.index') }}" id="filterForm">
    <div class="mb-3">
        <label class="form-label text-white">Status</label>
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Active (Pending)</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="trash" {{ request('status') == 'trash' ? 'selected' : '' }}>Trash</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label text-white">Due Date</label>
        <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="overdue" {{ request('filter') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Today</option>
            <option value="upcoming" {{ request('filter') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
        </select>
    </div>

    @can('viewAny', App\Models\User::class)
    <div class="mb-3">
        <label class="form-label text-white">Assignment</label>
        <select name="assigned" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All tasks</option>
            <option value="my" {{ request('assigned') == 'my' ? 'selected' : '' }}>Assigned to me</option>
            <option value="by_admin" {{ request('assigned') == 'by_admin' ? 'selected' : '' }}>Assigned by admin</option>
        </select>
    </div>
    @endcan

    <div class="mb-3">
        <label class="form-label text-white">Tag</label>
        <select name="tag" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All tags</option>
            @foreach(\App\Models\Tag::all() as $tag)
                <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

    @if(request()->anyFilled(['status', 'filter', 'assigned', 'tag']))
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-secondary w-100">
            <i class="fas fa-times"></i> Clear Filters
        </a>
    @endif
</form>