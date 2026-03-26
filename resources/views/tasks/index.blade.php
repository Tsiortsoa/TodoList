@extends('layouts.app')

@section('title', 'Mes Tâches')

@push('styles')
<style>
    /* ─── FILTER BAR ─────────────────────────────────────────── */
    .filter-bar {
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid #e8edf4;
        box-shadow: var(--shadow);
        padding: 18px 22px;
        margin-bottom: 22px;
        animation: fadeUp .3s ease both;
    }

    .filter-bar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-bar-title {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-bar-title i {
        color: var(--accent);
    }

    .filter-reset {
        font-size: .78rem;
        font-weight: 500;
        color: var(--text-muted);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color var(--transition);
    }

    .filter-reset:hover {
        color: var(--danger);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 10px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-label {
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .05em;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .filter-control {
        height: 36px;
        padding: 0 12px;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-sm);
        background: #f8fafc;
        color: var(--text-primary);
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        outline: none;
        transition: border-color var(--transition), background var(--transition), box-shadow var(--transition);
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    .filter-control:focus {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(91, 108, 249, .12);
    }

    /* Select arrow */
    .filter-select-wrap {
        position: relative;
    }

    .filter-select-wrap::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 11px;
        top: 50%;
        transform: translateY(-50%);
        font-size: .75rem;
        color: var(--text-muted);
        pointer-events: none;
    }

    /* Date inputs */
    input[type="date"].filter-control {
        padding: 0 10px;
        cursor: pointer;
    }

    /* Submit button */
    .filter-submit {
        height: 36px;
        padding: 0 18px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        font-size: .85rem;
        font-weight: 600;
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background var(--transition), box-shadow var(--transition), transform var(--transition);
        box-shadow: 0 3px 10px var(--accent-glow);
        white-space: nowrap;
        align-self: flex-end;
    }

    .filter-submit:hover {
        background: var(--accent-hover);
        box-shadow: 0 5px 16px var(--accent-glow);
        transform: translateY(-1px);
    }

    /* Active filter chips (summary row) */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }

    .active-filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .75rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 99px;
        background: rgba(91, 108, 249, .1);
        color: var(--accent);
        border: 1px solid rgba(91, 108, 249, .2);
    }

    .chip-remove {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: rgba(91, 108, 249, .15);
        color: var(--accent);
        text-decoration: none;
        font-size: .6rem;
        margin-left: 2px;
        transition: background var(--transition);
    }

    .chip-remove:hover {
        background: rgba(91, 108, 249, .3);
        color: var(--accent);
    }

    /* ─── PAGE HEADER ────────────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .page-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -.02em;
        margin: 0;
        line-height: 1.2;
    }

    .page-subtitle {
        font-size: .875rem;
        color: var(--text-secondary);
        margin: 4px 0 0;
    }

    .header-actions {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-new-task {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--accent);
        color: #fff;
        font-size: .875rem;
        font-weight: 600;
        padding: 9px 18px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: none;
        transition: background var(--transition), box-shadow var(--transition), transform var(--transition);
        box-shadow: 0 4px 14px var(--accent-glow);
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
    }

    .btn-new-task:hover {
        background: var(--accent-hover);
        box-shadow: 0 6px 20px var(--accent-glow);
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-trash {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--surface);
        color: var(--text-secondary);
        font-size: .875rem;
        font-weight: 500;
        padding: 9px 16px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: background var(--transition), color var(--transition), border-color var(--transition);
        font-family: 'DM Sans', sans-serif;
    }

    .btn-trash:hover {
        background: #fee2e2;
        color: var(--danger);
        border-color: #fca5a5;
    }

    /* ─── TASKS GRID ─────────────────────────────────────────── */
    .tasks-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 32px;
    }

    @media (max-width: 1199px) {
        .tasks-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .tasks-grid {
            grid-template-columns: 1fr;
        }
    }

    /* ─── TASK CARD ──────────────────────────────────────────── */
    .task-card {
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid #e8edf4;
        box-shadow: var(--shadow);
        display: flex;
        flex-direction: column;
        transition: transform var(--transition), box-shadow var(--transition);
        overflow: hidden;
        position: relative;
        animation: fadeUp .35s ease both;
    }

    .task-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    /* Colored top stripe */
    .task-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--info);
        border-radius: var(--radius) var(--radius) 0 0;
    }

    .task-card.is-completed::before {
        background: var(--success);
    }

    .task-card.is-overdue::before {
        background: var(--danger);
    }

    /* ── Card Head ── */
    .task-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 99px;
    }

    .badge-pending {
        background: #fef9c3;
        color: #a16207;
    }

    .badge-completed {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-overdue {
        background: #fee2e2;
        color: #b91c1c;
    }

    .recurrence-icon {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #f1f5f9;
        display: grid;
        place-items: center;
        color: var(--text-muted);
        font-size: .75rem;
        cursor: default;
    }

    /* ── Card Body ── */
    .task-card-body {
        padding: 12px 16px 14px;
        flex: 1;
    }

    .task-title-link {
        font-family: 'Syne', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        text-decoration: none;
        letter-spacing: -.01em;
        line-height: 1.3;
        display: block;
        margin-bottom: 6px;
        transition: color var(--transition);
    }

    .task-title-link:hover {
        color: var(--accent);
    }

    .task-card.is-completed .task-title-link {
        color: var(--text-muted);
        text-decoration: line-through;
        text-decoration-color: #94a3b8;
    }

    .task-desc {
        font-size: .825rem;
        color: var(--text-secondary);
        line-height: 1.55;
        margin-bottom: 12px;
    }

    .task-due {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .775rem;
        color: var(--text-muted);
        background: #f8fafc;
        border: 1px solid #e8edf4;
        border-radius: 6px;
        padding: 4px 10px;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .task-card.is-overdue .task-due {
        background: #fff1f2;
        border-color: #fecaca;
        color: #b91c1c;
    }

    /* Tags */
    .tags-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 10px;
    }

    .tag-pill {
        font-size: .7rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 99px;
        color: #fff;
        letter-spacing: .02em;
        opacity: .9;
    }

    /* Assignment */
    .assignment-row {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .assign-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .72rem;
        font-weight: 500;
        padding: 3px 9px;
        border-radius: 99px;
        background: #f1f5f9;
        color: var(--text-secondary);
    }

    .assign-badge.creator {
        background: rgba(91, 108, 249, .08);
        color: var(--accent);
    }

    /* ── Card Footer ── */
    .task-card-footer {
        padding: 10px 16px 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .footer-actions {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Action buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: var(--radius-sm);
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: var(--text-secondary);
        font-size: .8rem;
        text-decoration: none;
        cursor: pointer;
        transition: all var(--transition);
    }

    .action-btn:hover {
        color: var(--text-primary);
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .action-btn.edit:hover {
        background: rgba(91, 108, 249, .1);
        border-color: var(--accent);
        color: var(--accent);
    }

    .action-btn.complete:hover {
        background: #dcfce7;
        border-color: #86efac;
        color: #15803d;
    }

    .action-btn.delete:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: var(--danger);
    }

    .action-btn.restore:hover {
        background: #dcfce7;
        border-color: #86efac;
        color: #15803d;
    }

    .action-btn-text {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: var(--radius-sm);
        border: 1px solid #fca5a5;
        background: #fee2e2;
        color: #b91c1c;
        font-size: .775rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: all var(--transition);
    }

    .action-btn-text:hover {
        background: #fecaca;
    }

    .footer-time {
        font-size: .75rem;
        color: var(--text-muted);
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* ─── EMPTY STATE ────────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 80px 24px;
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid #e8edf4;
        box-shadow: var(--shadow);
    }

    .empty-icon {
        width: 72px;
        height: 72px;
        background: #f1f5f9;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.75rem;
        color: var(--text-muted);
        margin: 0 auto 20px;
    }

    .empty-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
    }

    .empty-desc {
        font-size: .875rem;
        color: var(--text-muted);
        margin-bottom: 24px;
    }

    /* ─── PAGINATION ─────────────────────────────────────────── */
    .pagination {
        gap: 4px;
    }

    .page-link {
        border-radius: var(--radius-sm) !important;
        border: 1px solid #e2e8f0;
        color: var(--text-secondary);
        font-size: .85rem;
        font-weight: 500;
        padding: 7px 13px;
        font-family: 'DM Sans', sans-serif;
        transition: all var(--transition);
    }

    .page-link:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
    }

    .page-item.active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
        box-shadow: 0 4px 12px var(--accent-glow);
    }

    .page-item.disabled .page-link {
        background: #f8fafc;
        color: var(--text-muted);
    }

    /* ─── MODAL ──────────────────────────────────────────────── */
    .modal-content {
        border: none;
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 18px 22px;
        background: #f8fafc;
    }

    .modal-title {
        font-family: 'Syne', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .modal-body {
        padding: 20px 22px;
        font-size: .9rem;
        color: var(--text-secondary);
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 14px 22px;
        gap: 8px;
    }

    .modal-footer .btn {
        border-radius: var(--radius-sm);
        font-size: .875rem;
        font-weight: 600;
        padding: 8px 18px;
        font-family: 'DM Sans', sans-serif;
    }

    /* warning block inside modal */
    .modal-warning {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        margin-top: 12px;
        font-size: .85rem;
        color: #9a3412;
    }

    .modal-warning .warn-icon {
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 1px;
        color: var(--warning);
    }

    /* ─── ANIMATIONS ─────────────────────────────────────────── */
    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(14px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .recurrence-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 4px 8px;
        border-radius: 99px;
        background: #e6f4ff;
        color: #0057b3;
    }

    .action-btn.disabled,
    .action-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    @for ($i =1; $i <=12; $i++) .tasks-grid .task-card:nth-child({
            {
            $i
        }

    }) {
        animation-delay: {
                {
                ($i - 1) * 0.05
            }
        }

        s;
    }

    @endfor
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            @if(request('status') == 'trash')
            <i class="fas fa-trash-alt me-2" style="color:var(--danger);font-size:1.4rem"></i>Corbeille
            @else
            Mes Tâches
            @endif
        </h1>
        <p class="page-subtitle">
            @if(request('status') == 'trash')
            Tâches supprimées — restaurez ou supprimez définitivement
            @else
            {{ $tasks->total() }} tâche{{ $tasks->total() > 1 ? 's' : '' }} au total
            @endif
        </p>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="header-actions">
        @if(request('status') != 'trash')
        <a href="{{ route('tasks.create') }}" class="btn-new-task">
            <i class="fas fa-plus fa-sm"></i> Nouvelle tâche
        </a>
        @endif
        <a href="{{ route('tasks.index', ['status' => request('status') == 'trash' ? null : 'trash']) }}"
            class="btn-trash">
            @if(request('status') == 'trash')
            <i class="fas fa-arrow-left fa-sm"></i> Retour
            @else
            <i class="fas fa-trash-alt fa-sm"></i> Corbeille
            @endif
        </a>
    </div>
    @endif
</div>

<!-- Filters (tasks page only) -->
@if(request()->routeIs('tasks.index'))
<div class="filter-bar">
    <div class="filter-bar-header">
        <span class="filter-bar-title">
            <i class="fas fa-sliders"></i> Filtres
        </span>
        @if(request()->hasAny(['status', 'filter', 'assigned', 'tag']))
        <a href="{{ route('tasks.index') }}" class="filter-reset">
            <i class="fas fa-rotate-left fa-xs"></i> Réinitialiser
        </a>
        @endif
    </div>
    @include('tasks._filters')
</div>
@endif

<!-- Tasks Grid -->
@if($tasks->count() > 0)

<div class="tasks-grid">
    @foreach($tasks as $task)

    @php
    $isCompleted = $task->status == 'completed';
    $isOverdue = $task->isOverdue();
    $cardClass = $isCompleted ? 'is-completed' : ($isOverdue ? 'is-overdue' : '');
    @endphp

    <div class="task-card {{ $cardClass }}">

        <!-- Head -->
        <div class="task-card-head">
            <div style="display: flex; gap: 6px; align-items: center;">
                <!-- Status badge -->
                @if($isCompleted)
                <span class="status-badge badge-completed"><i class="fas fa-check fa-xs"></i> Terminée</span>
                @elseif($isOverdue)
                <span class="status-badge badge-overdue"><i class="fas fa-triangle-exclamation fa-xs"></i> En retard</span>
                @else
                <span class="status-badge badge-pending"><i class="fas fa-clock fa-xs"></i> En attente</span>
                @endif

                <!-- Recurrence badge (visible text) -->
                @if($task->recurrence != 'none')
                <span class="recurrence-badge">
                    <i class="fas fa-redo-alt fa-xs"></i> {{ ucfirst($task->recurrence) }}
                </span>
                @endif
            </div>
        </div>

        <!-- Body -->
        <div class="task-card-body">
            <a href="{{ route('tasks.show', $task) }}" class="task-title-link">
                {{ $task->title }}
            </a>

            @if($task->description)
            <p class="task-desc">{{ Str::limit($task->description, 90) }}</p>
            @endif

            <div class="task-due">
                <i class="fas fa-calendar-day fa-xs"></i>
                {{ $task->due_date->format('d M Y, H:i') }}
            </div>

            <!-- Tags -->
            @if($task->tags->count() > 0)
            <div class="tags-row">
                @foreach($task->tags as $tag)
                <span class="tag-pill" style="background-color: {{ $tag->color ?? '#64748b' }};">
                    {{ $tag->name }}
                </span>
                @endforeach
            </div>
            @endif

            <!-- Assignment -->
            <div class="assignment-row">
                @if($task->assignedUser)
                <span class="assign-badge">
                    <i class="fas fa-user fa-xs"></i>
                    {{ $task->assignedUser->name }}
                    @if($task->creator && $task->creator->id !== $task->assignedUser->id)
                    <span style="opacity:.6">· assigné</span>
                    @endif
                </span>
                @endif
                @if($task->creator && $task->creator->id !== auth()->id())
                <span class="assign-badge creator">
                    <i class="fas fa-user-pen fa-xs"></i>
                    {{ $task->creator->name }}
                </span>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="task-card-footer">
            <div class="footer-actions">

                @if($task->trashed())
                {{-- Trashed actions --}}
                @can('restore', $task)
                <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="action-btn restore" data-bs-toggle="tooltip" title="Restaurer">
                        <i class="fas fa-rotate-left"></i>
                    </button>
                </form>
                @endcan

                @can('forceDelete', $task)
                <button type="button"
                    class="action-btn-text"
                    data-bs-toggle="modal"
                    data-bs-target="#forceDeleteModal{{ $task->id }}">
                    <i class="fas fa-trash fa-xs"></i> Supprimer définitivement
                </button>
                @endcan

                @else
                {{-- Active actions --}}
                @if(auth()->user()->role === 'admin')
                @can('update', $task)
                <a href="{{ route('tasks.edit', $task) }}"
                    class="action-btn edit"
                    data-bs-toggle="tooltip" title="Modifier">
                    <i class="fas fa-pen"></i>
                </a>
                @endcan
                @endif

                @if($task->status == 'pending')
                @php
                $isFutureRecurring = ($task->recurrence !== 'none' && $task->due_date->isFuture());
                @endphp
                @if($isFutureRecurring)
                <button class="action-btn complete disabled" disabled
                    data-bs-toggle="tooltip" title="Cette tâche récurrente ne peut être terminée avant sa date d'échéance">
                    <i class="fas fa-check"></i>
                </button>
                @else
                <form action="{{ route('tasks.complete', $task) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="action-btn complete" data-bs-toggle="tooltip" title="Marquer comme terminée">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                @endif
                @endif

                @if(auth()->user()->role === 'admin')
                @can('delete', $task)
                <button type="button"
                    class="action-btn delete"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal{{ $task->id }}"
                    data-bs-toggle2="tooltip" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
                @endcan
                @endif
                @endif

            </div>

            <span class="footer-time">
                <i class="fas fa-clock fa-xs me-1"></i>{{ $task->created_at->diffForHumans() }}
            </span>
        </div>
    </div>

    {{-- ── Delete modal (soft) ── --}}
    @if(!$task->trashed())
    <div class="modal fade" id="deleteModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-trash me-2" style="color:var(--danger)"></i>Mettre à la corbeille
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Voulez-vous déplacer <strong>« {{ $task->title }} »</strong> vers la corbeille ?</p>
                    <div class="modal-warning">
                        <i class="fas fa-circle-info warn-icon"></i>
                        <span>La tâche pourra être restaurée depuis la corbeille.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Déplacer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @else
    {{-- ── Force delete modal ── --}}
    <div class="modal fade" id="forceDeleteModal{{ $task->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-triangle-exclamation me-2" style="color:var(--danger)"></i>Suppression définitive
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Supprimer définitivement <strong>« {{ $task->title }} »</strong> ?</p>
                    <div class="modal-warning" style="background:#fff1f2;border-color:#fecaca;color:#991b1b;">
                        <i class="fas fa-triangle-exclamation warn-icon" style="color:var(--danger)"></i>
                        <span>Cette action est <strong>irréversible</strong>. La tâche sera supprimée pour toujours.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('tasks.forceDelete', $task->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-fire me-1"></i> Supprimer définitivement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @endforeach
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center mt-2 mb-4">
    {{ $tasks->appends(request()->query())->links() }}
</div>

@else

<!-- Empty state -->
<div class="empty-state">
    <div class="empty-icon">
        @if(request('status') == 'trash')
        <i class="fas fa-trash-alt"></i>
        @else
        <i class="fas fa-inbox"></i>
        @endif
    </div>
    <div class="empty-title">
        @if(request('status') == 'trash') Corbeille vide @else Aucune tâche trouvée @endif
    </div>
    <p class="empty-desc">
        @if(request('status') == 'trash')
        Aucune tâche supprimée pour l'instant.
        @else
        Commencez par créer votre première tâche.
        @endif
    </p>
    @if(auth()->user()->role === 'admin' && request('status') != 'trash')
    <a href="{{ route('tasks.create') }}" class="btn-new-task" style="display:inline-flex;">
        <i class="fas fa-plus fa-sm"></i> Créer une tâche
    </a>
    @endif
</div>

@endif

@endsection

@push('scripts')
<script>
    // Init tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, {
            trigger: 'hover'
        });
    });
</script>
@endpush