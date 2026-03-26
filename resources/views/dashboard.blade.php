@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    /* ─── PAGE HEADER ────────────────────────────────────────── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
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

    .page-title span {
        color: var(--accent);
    }

    .page-subtitle {
        font-size: .875rem;
        color: var(--text-secondary);
        margin: 4px 0 0;
        font-weight: 400;
    }

    .header-date {
        font-size: .8rem;
        color: var(--text-muted);
        background: var(--surface);
        border: 1px solid #e2e8f0;
        border-radius: 99px;
        padding: 6px 14px;
        font-weight: 500;
    }

    /* ─── STAT CARDS ─────────────────────────────────────────── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 1199px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 22px 22px 18px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
        border: 1px solid #e8edf4;
        transition: transform var(--transition), box-shadow var(--transition);
        cursor: default;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    /* Colored left border accent */
    .stat-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }

    .stat-card.primary::before  { background: var(--accent); }
    .stat-card.warning::before  { background: var(--warning); }
    .stat-card.success::before  { background: var(--success); }
    .stat-card.danger::before   { background: var(--danger); }

    /* Background icon */
    .stat-card .stat-bg-icon {
        position: absolute;
        right: 16px; top: 50%;
        transform: translateY(-50%);
        font-size: 3.5rem;
        opacity: .06;
        pointer-events: none;
        transition: opacity var(--transition);
    }
    .stat-card:hover .stat-bg-icon { opacity: .1; }

    .stat-label {
        font-size: .775rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .stat-value {
        font-family: 'Syne', sans-serif;
        font-size: 2.25rem;
        font-weight: 700;
        line-height: 1;
        color: var(--text-primary);
        letter-spacing: -.03em;
        margin-bottom: 10px;
    }

    .stat-card.primary  .stat-value { color: var(--accent); }
    .stat-card.warning  .stat-value { color: var(--warning); }
    .stat-card.success  .stat-value { color: #16a34a; }
    .stat-card.danger   .stat-value { color: var(--danger); }

    .stat-footer {
        font-size: .78rem;
        color: var(--text-muted);
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 5px;
        border-top: 1px solid #f1f5f9;
        padding-top: 10px;
        margin-top: 4px;
    }

    /* ─── SECONDARY CARDS ────────────────────────────────────── */
    .secondary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 991px) { .secondary-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 575px)  { .secondary-grid { grid-template-columns: 1fr; } }

    .info-card {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 20px 22px;
        box-shadow: var(--shadow);
        border: 1px solid #e8edf4;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform var(--transition), box-shadow var(--transition);
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .info-card-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .icon-indigo   { background: rgba(91,108,249,.1);  color: var(--accent); }
    .icon-orange   { background: rgba(249,115,22,.1);  color: #f97316; }
    .icon-teal     { background: rgba(20,184,166,.1);  color: #14b8a6; }

    .info-card-body {}

    .info-card-label {
        font-size: .775rem;
        font-weight: 600;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 4px;
    }

    .info-card-value {
        font-family: 'Syne', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -.025em;
        line-height: 1;
        margin-bottom: 3px;
    }

    .info-card-desc {
        font-size: .78rem;
        color: var(--text-muted);
    }

    /* ─── RECENT TASKS SECTION ───────────────────────────────── */
    .section-card {
        background: var(--surface);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid #e8edf4;
        overflow: hidden;
    }

    .section-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid #f1f5f9;
    }

    .section-card-title {
        font-family: 'Syne', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-card-title .title-icon {
        width: 30px; height: 30px;
        background: rgba(91,108,249,.1);
        border-radius: 8px;
        display: grid;
        place-items: center;
        color: var(--accent);
        font-size: .8rem;
    }

    .btn-view-all {
        font-size: .8rem;
        font-weight: 600;
        color: var(--accent);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: gap var(--transition);
    }
    .btn-view-all:hover { gap: 8px; color: var(--accent-hover); }

    /* Task list */
    .task-list { padding: 8px; }

    .task-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 14px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        transition: background var(--transition);
        border-bottom: 1px solid #f8fafc;
    }

    .task-item:last-child { border-bottom: none; }
    .task-item:hover { background: #f8fafc; }

    .task-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 5px;
    }

    .task-dot.completed { background: var(--success); }
    .task-dot.pending   { background: var(--warning); }
    .task-dot.other     { background: var(--info); }

    .task-item-body { flex: 1; min-width: 0; }

    .task-item-title {
        font-size: .875rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
    }

    .task-item-desc {
        font-size: .8rem;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 6px;
    }

    .task-item-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .task-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 99px;
        line-height: 1;
    }

    .badge-completed { background: #dcfce7; color: #15803d; }
    .badge-pending   { background: #fef9c3; color: #a16207; }
    .badge-info      { background: #dbeafe; color: #1d4ed8; }
    .badge-secondary { background: #f1f5f9; color: #475569; }

    .task-item-time {
        margin-left: auto;
        font-size: .75rem;
        color: var(--text-muted);
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }

    .empty-state-icon {
        width: 64px; height: 64px;
        background: #f1f5f9;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.5rem;
        color: var(--text-muted);
        margin: 0 auto 16px;
    }

    .empty-state-text {
        font-size: .875rem;
        color: var(--text-muted);
        margin: 0;
    }

    /* ─── ANIMATIONS ─────────────────────────────────────────── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .anim { animation: fadeUp .4s ease both; }
    .anim-1 { animation-delay: .05s; }
    .anim-2 { animation-delay: .10s; }
    .anim-3 { animation-delay: .15s; }
    .anim-4 { animation-delay: .20s; }
    .anim-5 { animation-delay: .25s; }
    .anim-6 { animation-delay: .30s; }
    .anim-7 { animation-delay: .35s; }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header anim">
    <div>
        <h1 class="page-title">Bonjour, <span>{{ auth()->user()->name }}</span></h1>
        <p class="page-subtitle">Voici un aperçu de votre activité</p>
    </div>
    <span class="header-date">
        <i class="fas fa-calendar-day me-1"></i>
        {{ now()->translatedFormat('l d F Y') }}
    </span>
</div>

<!-- ── Stats Grid ── -->
<div class="stats-grid">
    <div class="stat-card primary anim anim-1">
        <div class="stat-bg-icon"><i class="fas fa-layer-group"></i></div>
        <div class="stat-label">Total Tâches</div>
        <div class="stat-value">{{ $stats['total_tasks'] }}</div>
        <div class="stat-footer">
            <i class="fas fa-circle-info fa-xs"></i> Toutes les tâches
        </div>
    </div>

    <div class="stat-card warning anim anim-2">
        <div class="stat-bg-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-label">En attente</div>
        <div class="stat-value">{{ $stats['pending_tasks'] }}</div>
        <div class="stat-footer">
            <i class="fas fa-clock fa-xs"></i> À traiter
        </div>
    </div>

    <div class="stat-card success anim anim-3">
        <div class="stat-bg-icon"><i class="fas fa-circle-check"></i></div>
        <div class="stat-label">Terminées</div>
        <div class="stat-value">{{ $stats['completed_tasks'] }}</div>
        <div class="stat-footer">
            <i class="fas fa-trophy fa-xs"></i> Bien joué !
        </div>
    </div>

    <div class="stat-card danger anim anim-4">
        <div class="stat-bg-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-label">En retard</div>
        <div class="stat-value">{{ $stats['overdue_tasks'] }}</div>
        <div class="stat-footer">
            <i class="fas fa-bolt fa-xs"></i> Nécessite attention
        </div>
    </div>
</div>

<!-- ── Secondary Cards ── -->
<div class="secondary-grid">
    <div class="info-card anim anim-5">
        <div class="info-card-icon icon-indigo">
            <i class="fas fa-user"></i>
        </div>
        <div class="info-card-body">
            <div class="info-card-label">Mes tâches</div>
            <div class="info-card-value">{{ $stats['my_tasks'] }}</div>
            <div class="info-card-desc">Assignées à moi</div>
        </div>
    </div>

    <div class="info-card anim anim-6">
        <div class="info-card-icon icon-orange">
            <i class="fas fa-pen-to-square"></i>
        </div>
        <div class="info-card-body">
            <div class="info-card-label">Créées par moi</div>
            <div class="info-card-value">{{ $stats['created_by_me'] }}</div>
            <div class="info-card-desc">Tâches créées</div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    <div class="info-card anim anim-7">
        <div class="info-card-icon icon-teal">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="info-card-body">
            <div class="info-card-label">Assignées (Admin)</div>
            <div class="info-card-value">{{ $stats['assigned_by_admin'] }}</div>
            <div class="info-card-desc">Tâches déléguées</div>
        </div>
    </div>
    @endif
</div>

<!-- ── Recent Tasks ── -->
<div class="section-card anim anim-7">
    <div class="section-card-header">
        <h5 class="section-card-title">
            <span class="title-icon"><i class="fas fa-clock-rotate-left"></i></span>
            Tâches récentes
        </h5>
        <a href="{{ route('tasks.index') }}" class="btn-view-all">
            Voir tout <i class="fas fa-arrow-right fa-xs"></i>
        </a>
    </div>

    <div class="task-list">
        @if($recentTasks->count() > 0)
            @foreach($recentTasks as $task)
            <a href="{{ route('tasks.show', $task) }}" class="task-item">
                <div class="task-dot {{ $task->status == 'completed' ? 'completed' : ($task->status == 'pending' ? 'pending' : 'other') }}"></div>

                <div class="task-item-body">
                    <div class="task-item-title">{{ $task->title }}</div>
                    @if($task->description)
                    <div class="task-item-desc">{{ Str::limit($task->description, 90) }}</div>
                    @endif
                    <div class="task-item-meta">
                        @php
                            $badgeClass = match($task->status) {
                                'completed' => 'badge-completed',
                                'pending'   => 'badge-pending',
                                default     => 'badge-info'
                            };
                        @endphp
                        <span class="task-badge {{ $badgeClass }}">
                            @if($task->status == 'completed') <i class="fas fa-check fa-xs"></i>
                            @elseif($task->status == 'pending') <i class="fas fa-clock fa-xs"></i>
                            @else <i class="fas fa-spinner fa-xs"></i> @endif
                            {{ ucfirst($task->status) }}
                        </span>
                        @if($task->assignedUser)
                        <span class="task-badge badge-secondary">
                            <i class="fas fa-user fa-xs"></i>
                            {{ $task->assignedUser->name }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="task-item-time">
                    {{ $task->created_at->diffForHumans() }}
                </div>
            </a>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <p class="empty-state-text">Aucune tâche récente pour le moment.</p>
            </div>
        @endif
    </div>
</div>

@endsection