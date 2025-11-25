<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'string|min:3|max:255',
            'status' => 'string|in:pending,completed,canceled',
            'assignee' => 'required|string|exists:users,id|nullable',
            'created_by' => 'required|string|exists:users,id|nullable',
            'due_date' => 'date',
        ];
    }
}
