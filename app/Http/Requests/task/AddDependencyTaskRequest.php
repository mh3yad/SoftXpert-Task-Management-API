<?php

namespace App\Http\Requests\task;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddDependencyTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request)
    {
        if(!Auth::user()->isManager()){
            return false;
        }
        // Check if the dependency is valid (avoid circular dependencies)
        if ($request->task->id == $request->depends_on_id) {
            return false;
        }

        //circular dependency
        if($request->task->dependentsTasks()->where('task_id', $request->depends_on_id)->exists()){
            return false;
        }

        // Check if the dependency already exists
        if ($request->task->dependencyTasks()->where('depends_on_id', $request->depends_on_id)->exists()) {
            return false;
        }
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
            'depends_on_id' => 'required|exists:tasks,id'
        ];
    }
}
