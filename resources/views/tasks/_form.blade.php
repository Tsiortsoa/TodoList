@php
$isAdmin = auth()->user()->isAdmin();
@endphp

<form action="{{ $route }}" method="POST">
    @csrf
    @if($method != 'POST')
    @method($method)
    @endif

    <div class="mb-3">
        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $task->title ?? '') }}" required>
        @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $task->description ?? '') }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="due_date" class="form-label">Due Date & Time <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', isset($task) ? $task->due_date->format('Y-m-d\TH:i') : '') }}" required>
            @error('due_date')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="user_id" class="form-label">Assign to</label>
            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" {{ $isAdmin ? '' : 'disabled' }}>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $task->user_id ?? auth()->id()) == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->email }})
                </option>
                @endforeach
            </select>
            @if(!$isAdmin)
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <small class="text-muted">Tasks are automatically assigned to you.</small>
            @endif
            @error('user_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="recurrence" class="form-label">Recurrence</label>
            <select class="form-select @error('recurrence') is-invalid @enderror" id="recurrence" name="recurrence">
                <option value="none" {{ old('recurrence', $task->recurrence ?? 'none') == 'none' ? 'selected' : '' }}>None</option>
                <option value="daily" {{ old('recurrence', $task->recurrence ?? '') == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ old('recurrence', $task->recurrence ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ old('recurrence', $task->recurrence ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ old('recurrence', $task->recurrence ?? '') == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
            @error('recurrence')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select class="form-select @error('tags') is-invalid @enderror" id="tags" name="tags[]" multiple>
                @foreach(\App\Models\Tag::all() as $tag)
                <option value="{{ $tag->id }}" ...>{{ $tag->name }}</option>
                @endforeach
            </select>
            <small class="text-muted">Hold Ctrl (Cmd) to select multiple</small>
            @error('tags')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if(isset($task) && $task->trashed())
    <div class="alert alert-warning">This task is in trash. You need to restore it before editing.</div>
    @endif

    <div class="d-flex justify-content-between">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> {{ $task ? 'Update' : 'Create' }} Task</button>
    </div>
</form>