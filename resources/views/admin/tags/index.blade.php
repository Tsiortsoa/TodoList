@extends('layouts.app')

@section('title', 'Gérer les Tags')

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

    .btn-new-tag {
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
    }
    .btn-new-tag:hover {
        background: var(--accent-hover);
        box-shadow: 0 6px 20px var(--accent-glow);
        transform: translateY(-1px);
        color: #fff;
    }

    /* ─── TAGS GRID ──────────────────────────────────────────── */
    .tags-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 14px;
        margin-bottom: 32px;
    }

    /* ─── TAG CARD ───────────────────────────────────────────── */
    .tag-card {
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid #e8edf4;
        box-shadow: var(--shadow);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform var(--transition), box-shadow var(--transition);
        animation: fadeUp .35s ease both;
    }

    .tag-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Stagger */
    @for ($i = 1; $i <= 20; $i++)
    .tags-grid .tag-card:nth-child({{ $i }}) { animation-delay: {{ ($i - 1) * 0.04 }}s; }
    @endfor

    /* Color banner */
    .tag-banner {
        height: 6px;
        width: 100%;
        flex-shrink: 0;
    }

    /* Card body */
    .tag-card-body {
        padding: 16px 18px 14px;
        flex: 1;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .tag-swatch {
        width: 42px; height: 42px;
        border-radius: 11px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        font-size: 1rem;
        color: rgba(255,255,255,.9);
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
        transition: transform var(--transition);
    }
    .tag-card:hover .tag-swatch { transform: scale(1.08) rotate(-4deg); }

    .tag-info { flex: 1; min-width: 0; }

    .tag-name {
        font-family: 'Syne', sans-serif;
        font-size: .975rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -.01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 3px;
    }

    .tag-hex {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--text-muted);
        background: #f1f5f9;
        border-radius: 5px;
        padding: 2px 7px;
        font-family: 'DM Mono', 'Fira Code', monospace;
    }

    /* Card footer */
    .tag-card-footer {
        padding: 10px 18px 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
    }

    .btn-delete-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .8rem;
        font-weight: 600;
        color: var(--text-muted);
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: var(--radius-sm);
        padding: 6px 12px;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: all var(--transition);
    }
    .btn-delete-tag:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: var(--danger);
    }

    /* ─── EMPTY STATE ────────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 80px 24px;
        background: var(--surface);
        border-radius: var(--radius);
        border: 1px solid #e8edf4;
        box-shadow: var(--shadow);
        animation: fadeUp .35s ease both;
    }

    .empty-icon {
        width: 68px; height: 68px;
        background: #f1f5f9;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 1.6rem;
        color: var(--text-muted);
        margin: 0 auto 18px;
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
    .pagination-wrap {
        display: flex;
        justify-content: center;
        margin-top: 8px;
        margin-bottom: 4px;
    }

    .pagination { gap: 4px; }

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

    /* Tag preview in modal */
    .modal-tag-preview {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: var(--radius-sm);
        background: #f8fafc;
        border: 1px solid #e8edf4;
        margin-bottom: 14px;
    }

    .modal-tag-dot {
        width: 14px; height: 14px;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .modal-tag-label {
        font-size: .875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-warning {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: var(--radius-sm);
        padding: 13px 15px;
        font-size: .85rem;
        color: #9a3412;
    }
    .modal-warning .warn-icon { flex-shrink: 0; margin-top: 1px; color: var(--warning); }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Gestion des tags</h1>
        <p class="page-subtitle">{{ $tags->total() }} tag{{ $tags->total() > 1 ? 's' : '' }} configuré{{ $tags->total() > 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('admin.tags.create') }}" class="btn-new-tag">
        <i class="fas fa-plus fa-sm"></i> Nouveau tag
    </a>
</div>

@if($tags->count())

<!-- Tags Grid -->
<div class="tags-grid">
    @foreach($tags as $tag)
    @php $color = $tag->color ?? '#64748b'; @endphp

    <div class="tag-card">
        <!-- Color banner -->
        <div class="tag-banner" style="background: {{ $color }};"></div>

        <!-- Body -->
        <div class="tag-card-body">
            <div class="tag-swatch" style="background: {{ $color }};">
                <i class="fas fa-tag fa-sm"></i>
            </div>
            <div class="tag-info">
                <div class="tag-name">{{ $tag->name }}</div>
                <span class="tag-hex">{{ $color }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="tag-card-footer">
            <button type="button"
                    class="btn-delete-tag"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteTagModal{{ $tag->id }}">
                <i class="fas fa-trash fa-xs"></i> Supprimer
            </button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteTagModal{{ $tag->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-triangle-exclamation me-2" style="color:var(--danger)"></i>
                        Supprimer le tag
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-tag-preview">
                        <span class="modal-tag-dot" style="background: {{ $color }};"></span>
                        <span class="modal-tag-label">{{ $tag->name }}</span>
                        <span style="font-size:.75rem;color:var(--text-muted);font-family:monospace;">{{ $color }}</span>
                    </div>
                    <p style="margin:0 0 12px;font-size:.875rem;">Voulez-vous vraiment supprimer ce tag ?</p>
                    <div class="modal-warning">
                        <i class="fas fa-circle-info warn-icon"></i>
                        <span>Ce tag sera retiré de toutes les tâches auxquelles il est associé.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endforeach
</div>

@if($tags->hasPages())
<div class="pagination-wrap">
    {{ $tags->links() }}
</div>
@endif

@else

<!-- Empty state -->
<div class="empty-state">
    <div class="empty-icon">
        <i class="fas fa-tags"></i>
    </div>
    <div class="empty-title">Aucun tag pour l'instant</div>
    <p class="empty-desc">Les tags permettent d'organiser et filtrer vos tâches facilement.</p>
    <a href="{{ route('admin.tags.create') }}" class="btn-new-tag" style="display:inline-flex;">
        <i class="fas fa-plus fa-sm"></i> Créer le premier tag
    </a>
</div>

@endif

@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el, { trigger: 'hover' });
    });
</script>
@endpush