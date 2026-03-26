@extends('layouts.app')

@section('title', 'Gérer les utilisateurs')

@push('styles')
<style>
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

    .header-actions { display: flex; gap: 8px; flex-wrap: wrap; }

    .btn-filter {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: .85rem;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        border: 1px solid #e2e8f0;
        background: var(--surface);
        color: var(--text-secondary);
        transition: all var(--transition);
        font-family: 'DM Sans', sans-serif;
        cursor: pointer;
    }
    .btn-filter:hover { background: #f8fafc; border-color: #cbd5e1; color: var(--text-primary); }
    .btn-filter.active-filter {
        background: #fef9c3;
        border-color: #fde047;
        color: #854d0e;
    }
    .btn-filter.active-filter:hover { background: #fef08a; }

    .btn-filter-all {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
        box-shadow: 0 4px 12px var(--accent-glow);
    }
    .btn-filter-all:hover {
        background: var(--accent-hover);
        border-color: var(--accent-hover);
        color: #fff;
    }

    /* Pending count chip */
    .pending-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        border-radius: 99px;
        background: var(--warning);
        color: #fff;
        font-size: .68rem;
        font-weight: 700;
        padding: 0 6px;
        line-height: 1;
    }

    /* ─── CARD WRAPPER ───────────────────────────────────────── */
    .users-card {
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid #e8edf4;
        box-shadow: var(--shadow);
        overflow: hidden;
        animation: fadeUp .35s ease both;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── TABLE ──────────────────────────────────────────────── */
    .users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .875rem;
    }

    .users-table thead tr {
        background: #f8fafc;
        border-bottom: 1px solid #e8edf4;
    }

    .users-table thead th {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .07em;
        text-transform: uppercase;
        color: var(--text-muted);
        padding: 13px 18px;
        white-space: nowrap;
        border: none;
    }

    .users-table thead th:first-child { padding-left: 22px; }
    .users-table thead th:last-child  { padding-right: 22px; text-align: right; }

    .users-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background var(--transition);
    }
    .users-table tbody tr:last-child { border-bottom: none; }
    .users-table tbody tr:hover { background: #fafbfd; }

    .users-table tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        border: none;
        color: var(--text-primary);
    }

    .users-table tbody td:first-child { padding-left: 22px; }
    .users-table tbody td:last-child  { padding-right: 22px; }

    /* ID cell */
    .cell-id {
        font-family: 'DM Mono', 'Fira Code', monospace;
        font-size: .78rem;
        color: var(--text-muted);
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        font-weight: 500;
    }

    /* Avatar + name */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: .85rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        text-transform: uppercase;
        font-family: 'Syne', sans-serif;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .user-joined {
        font-size: .75rem;
        color: var(--text-muted);
        margin-top: 1px;
    }

    /* Email cell */
    .cell-email {
        color: var(--text-secondary);
        font-size: .85rem;
    }

    .cell-email a {
        color: var(--text-secondary);
        text-decoration: none;
        transition: color var(--transition);
    }
    .cell-email a:hover { color: var(--accent); }

    /* Role badge */
    .role-badge {
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

    .role-admin { background: rgba(239,68,68,.1); color: #b91c1c; }
    .role-user  { background: #f1f5f9; color: #475569; }

    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 99px;
    }

    .badge-approved { background: #dcfce7; color: #15803d; }
    .badge-pending  { background: #fef9c3; color: #a16207; }

    /* Dot indicator */
    .status-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }
    .dot-approved { background: var(--success); }
    .dot-pending  { background: var(--warning); animation: pulse 1.6s ease infinite; }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: .45; }
    }

    /* Actions cell */
    .actions-cell {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: var(--radius-sm);
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: var(--text-secondary);
        font-size: .8rem;
        cursor: pointer;
        transition: all var(--transition);
    }
    .action-btn:hover { background: #f1f5f9; border-color: #cbd5e1; color: var(--text-primary); }

    .action-btn.approve:hover { background: #dcfce7; border-color: #86efac; color: #15803d; }
    .action-btn.promote:hover { background: #fef9c3; border-color: #fde047; color: #854d0e; }
    .action-btn.delete:hover  { background: #fee2e2; border-color: #fca5a5; color: var(--danger); }

    /* ─── EMPTY STATE ────────────────────────────────────────── */
    .empty-row td {
        padding: 64px 24px !important;
        text-align: center;
    }

    .empty-icon {
        width: 56px; height: 56px;
        background: #f1f5f9;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.3rem;
        color: var(--text-muted);
        margin: 0 auto 14px;
    }

    .empty-text {
        font-size: .875rem;
        color: var(--text-muted);
    }

    /* ─── PAGINATION ─────────────────────────────────────────── */
    .pagination-wrap {
        padding: 16px 22px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: center;
    }

    .pagination { gap: 4px; margin: 0; }

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
    .page-link:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
    .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #fff; box-shadow: 0 4px 12px var(--accent-glow); }
    .page-item.disabled .page-link { background: #f8fafc; color: var(--text-muted); }

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
    .modal-body { padding: 20px 22px; font-size: .9rem; color: var(--text-secondary); }
    .modal-footer { border-top: 1px solid #f1f5f9; padding: 14px 22px; gap: 8px; }
    .modal-footer .btn {
        border-radius: var(--radius-sm);
        font-size: .875rem;
        font-weight: 600;
        padding: 8px 18px;
        font-family: 'DM Sans', sans-serif;
    }
    .modal-warning {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #fff1f2;
        border: 1px solid #fecaca;
        border-radius: var(--radius-sm);
        padding: 14px 16px;
        margin-top: 14px;
        font-size: .85rem;
        color: #991b1b;
    }
    .modal-warning .warn-icon { flex-shrink: 0; margin-top: 1px; color: var(--danger); }

    /* Responsive table scroll */
    @media (max-width: 767px) {
        .users-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .page-title { font-size: 1.4rem; }
    }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Gestion des utilisateurs</h1>
        <p class="page-subtitle">{{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }} au total</p>
    </div>

    <div class="header-actions">
        <a href="{{ route('admin.users.index', ['pending' => 1]) }}"
           class="btn-filter {{ request('pending') ? 'active-filter' : '' }}">
            <i class="fas fa-hourglass-half fa-xs"></i>
            En attente
            {{-- Optionnel : badge count --}}
            {{-- <span class="pending-chip">3</span> --}}
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="btn-filter {{ !request('pending') ? 'btn-filter-all' : '' }}">
            <i class="fas fa-users fa-xs"></i>
            Tous les utilisateurs
        </a>
    </div>
</div>

<!-- Users Table -->
<div class="users-card">
    <div class="users-table-wrap">
        <table class="users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    /* Avatar color based on first letter */
                    $colors = ['#5b6cf9','#f97316','#14b8a6','#a855f7','#ec4899','#22c55e','#ef4444','#3b82f6'];
                    $avatarColor = $colors[ord(strtolower($user->name[0])) % count($colors)];
                @endphp
                <tr>
                    <!-- ID -->
                    <td><span class="cell-id">#{{ $user->id }}</span></td>

                    <!-- User -->
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar" style="background:{{ $avatarColor }}">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-joined">Depuis {{ $user->created_at->format('M Y') }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Email -->
                    <td class="cell-email">
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </td>

                    <!-- Role -->
                    <td>
                        @if($user->role == 'admin')
                            <span class="role-badge role-admin">
                                <i class="fas fa-shield-halved fa-xs"></i> Admin
                            </span>
                        @else
                            <span class="role-badge role-user">
                                <i class="fas fa-user fa-xs"></i> Utilisateur
                            </span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td>
                        @if($user->approved_at)
                            <span class="status-badge badge-approved">
                                <span class="status-dot dot-approved"></span> Approuvé
                            </span>
                        @else
                            <span class="status-badge badge-pending">
                                <span class="status-dot dot-pending"></span> En attente
                            </span>
                        @endif
                    </td>

                    <!-- Date -->
                    <td>
                        <span style="font-size:.825rem; color:var(--text-secondary); font-weight:500;">
                            {{ $user->created_at->format('d/m/Y') }}
                        </span>
                        <div style="font-size:.75rem;color:var(--text-muted);">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </td>

                    <!-- Actions -->
                    <td>
                        <div class="actions-cell">
                            @can('update', $user)
                                @if(!$user->approved_at)
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn approve"
                                            data-bs-toggle="tooltip"
                                            title="Approuver l'utilisateur">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                @if($user->role != 'admin')
                                <form action="{{ route('admin.users.promote', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="action-btn promote"
                                            data-bs-toggle="tooltip"
                                            title="Promouvoir en Admin">
                                        <i class="fas fa-user-tie"></i>
                                    </button>
                                </form>
                                @endif
                            @endcan

                            @can('delete', $user)
                            <button type="button"
                                    class="action-btn delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal{{ $user->id }}"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>

                @can('delete', $user)
                <!-- Delete Modal -->
                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-triangle-exclamation me-2" style="color:var(--danger)"></i>
                                    Supprimer l'utilisateur
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="user-cell mb-3">
                                    <div class="user-avatar" style="background:{{ $avatarColor }}; width:42px;height:42px;font-size:.95rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-joined">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <p style="margin:0;font-size:.875rem;">Voulez-vous vraiment supprimer cet utilisateur ?</p>
                                <div class="modal-warning">
                                    <i class="fas fa-triangle-exclamation warn-icon"></i>
                                    <span>Cette action est <strong>irréversible</strong>. Toutes les tâches associées à cet utilisateur seront également supprimées.</span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash me-1"></i> Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @empty
                <tr class="empty-row">
                    <td colspan="7">
                        <div class="empty-icon">
                            <i class="fas fa-users-slash"></i>
                        </div>
                        <p class="empty-text">
                            @if(request('pending'))
                                Aucun utilisateur en attente d'approbation.
                            @else
                                Aucun utilisateur trouvé.
                            @endif
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination-wrap">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover' });
    });
</script>
@endpush