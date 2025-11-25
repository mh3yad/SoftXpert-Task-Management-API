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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

//       return TaskResource::collection(Task::with(['assignee','creator','dependencies','dependents'])->get());
        $query = Task::with(['assignee','creator','dependencies','dependents']);
        // If user is not a manager, only show assigned tasks
        if (Auth::user()->role !== 'manager') {
            $query->where('assignee_id', Auth::id());
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by due date range
        if ($request->has('due_date_from')) {
            $query->where('due_date', '>=', $request->due_date_from);
        }
        if ($request->has('due_date_to')) {
            $query->where('due_date', '<=', $request->due_date_to);
        }

        // Filter by assigned user (only for managers)
        if ($request->has('assignee_id') && Auth::user()->role === 'manager') {
            $query->where('assignee_id', $request->assignee_id);
        }
       return TaskResource::collection($query->get());
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
