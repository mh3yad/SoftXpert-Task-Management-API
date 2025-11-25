<?php

namespace App\Http\Controllers;

use App\Http\Requests\task\AddDependencyTaskRequest;
use App\Http\Requests\task\DeleteTaskRequest;
use App\Http\Requests\task\StoreTaskRequest;
use App\Http\Requests\task\UpdateTaskRequest;
use App\Http\Requests\task\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'creator', 'dependencyTasks']);

        // Apply filters
        if ($request->user()->isUser()) {
            // Users can only see tasks assigned to them
            $query->where('assignee_id', $request->user()->id);
        }

        $tasks = $query->filter($request->only(['status', 'due_date_from', 'due_date_to', 'assignee_id']))
            ->latest()
            ->paginate(10);
       return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by_id'] = Auth::id();
        $task = Task::create($data);
        return response()->json(new TaskResource($task));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task) : JsonResponse
    {
        abort_if(Auth::user()->role == 'user' && Auth::user()->id != $task->assignee->id ,403,'You don\'t have access to this task ');
        return response()->json(new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $data = $request->validated();
        $task->update($data);
        return response()->json(new TaskResource($task));
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        // Check if all dependencies are completed before allowing status update to be completed
        abort_if(
            $request->status === 'completed' && !$task->allDependenciesCompleted(),
               403,
            'Cannot complete task. All dependencies must be completed first.');
        $task->update(['status' => $request->status]);
        return response()->json(new TaskResource($task));
    }

    public function addDependency(AddDependencyTaskRequest $request, Task $task)
    {
        $request->validated();
        $task->dependencyTasks()->attach($request->depends_on_id);
        return response()->json(['message' => 'Dependency added'], 201);
    }

    public function removeDependency(Task $task, Task $dependency)
    {
        abort_if(!Auth::user()->isManager(),403,'You don\'t have access to this');
        $task->dependencyTasks()->detach($dependency->id);
        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteTaskRequest $request,Task $task) : JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully.']);
    }
}
