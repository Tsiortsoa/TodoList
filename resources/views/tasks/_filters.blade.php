<form method="GET" action="{{ route('tasks.index') }}" class="filter-container">
    <div class="filter-grid">
        <div class="filter-group" style="grid-column: span 2: min-width: 0;">
            <label class="filter-label">Status</label>
            <div class="filter-select-wrap">
                <select name="status" class="filter-control" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminées</option>
                    <option value="trash" {{ request('status') == 'trash' ? 'selected' : '' }}>Supprimées</option>
                </select>
            </div>
        </div>

        <div class="filter-group" style="grid-column: span 2: min-width: 0;">
            <label class="filter-label">Échéance</label>
            <div class="filter-select-wrap">
                <select name="filter" class="filter-control" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <option value="overdue" {{ request('filter') == 'overdue' ? 'selected' : '' }}>En retard</option>
                    <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="upcoming" {{ request('filter') == 'upcoming' ? 'selected' : '' }}>Future</option>
                </select>
            </div>
        </div>

        @can('viewAny', App\Models\User::class)
        <div class="filter-group" style="grid-column: span 2: min-width: 0;">
            <label class="filter-label">Assignment</label>
            <div class="filter-select-wrap">
                <select name="assigned" class="filter-control" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <option value="my" {{ request('assigned') == 'my' ? 'selected' : '' }}>Assignée à moi</option>
                    <option value="by_admin" {{ request('assigned') == 'by_admin' ? 'selected' : '' }}>Assignée par l'admin</option>
                </select>
            </div>
        </div>
        @endcan

        <div class="filter-group" style="grid-column: span 2: min-width: 0;">
            <label class="filter-label">Tag</label>
            <div class="filter-select-wrap">
                <select name="tag" class="filter-control" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    @foreach(\App\Models\Tag::all() as $tag)
                        <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</form>