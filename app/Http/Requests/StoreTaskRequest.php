<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * We'll rely on policies later, but for now allow if user is approved.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isApproved();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id'    => ['required', 'exists:users,id'], // assigned user
            'title'      => ['required', 'string', 'max:255'],
            'description'=> ['nullable', 'string'],
            'due_date'   => ['required', 'date', 'after_or_equal:today'],
            'status'     => ['sometimes', Rule::in(['pending', 'completed'])],
            'recurrence' => ['required', Rule::in(['none', 'daily', 'weekly', 'monthly', 'yearly'])],
            'tags'       => ['sometimes', 'array'],
            'tags.*'     => ['exists:tags,id'],
        ];
    }
}
