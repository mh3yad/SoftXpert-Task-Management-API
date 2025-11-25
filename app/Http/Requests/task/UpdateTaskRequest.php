<?php

namespace App\Http\Requests\task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::user()->isManager();
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assignee_id' => 'nullable|exists:users,id',
            'status' => 'in:pending,completed,canceled',
        ];
    }
}
