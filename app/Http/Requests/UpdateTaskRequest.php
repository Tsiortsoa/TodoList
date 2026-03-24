<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        // Authorization will be handled by the TaskPolicy later.
        // For now, return true and rely on controller logic.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id'    => ['sometimes', 'exists:users,id'],
            'title'      => ['sometimes', 'string', 'max:255'],
            'description'=> ['nullable', 'string'],
            'due_date'   => ['sometimes', 'date', 'after_or_equal:today'],
            'status'     => ['sometimes', Rule::in(['pending', 'completed'])],
            'recurrence' => ['sometimes', Rule::in(['none', 'daily', 'weekly', 'monthly', 'yearly'])],
            'tags'       => ['sometimes', 'array'],
            'tags.*'     => ['exists:tags,id'],
        ];
    }
}
