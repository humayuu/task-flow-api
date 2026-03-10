<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();

        return response()->json([
            'status' => true,
            'message' => 'Task Retrieved SUccessfully',
            'task' => $tasks,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $taskValidate = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'description' => 'required|min:20',
            'status' => 'required',
            'due_date' => 'required|date',
        ]);

        if ($taskValidate->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $taskValidate->errors()->all(),
            ], 422);
        }

        try {
            Task::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'priority' => $request->priority,
                'due_date' => $request->due_date,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Task Created SUccessfully',
            ], 201);
        } catch (Exception $e) {
            Log::error('Error in task creation ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create task',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Task Retrieved SUccessfully',
            'task' => $task,
        ], 202);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        $taskValidate = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'description' => 'required|min:20',
            'status' => 'required',
            'due_date' => 'required|date',
        ]);

        if ($taskValidate->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $taskValidate->errors()->all(),
            ], 422);
        }

        try {
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'due_date' => $request->due_date,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Task updated SUccessfully',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in task update ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update task',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json([
            'status' => true,
            'message' => 'Task Deleted SUccessfully',
        ], 200);
    }

    /**
     * For Filter by status
     */
    public function FilterByStatus($status)
    {
        $tasks = Task::where('status', $status)->get();

        return response()->json([
            'status' => true,
            'message' => 'Successfully filtered tasks with status: ' . $status,
            'data' => $tasks,
        ]);
    }

    /**
     * For Filter by priority
     */
    public function FilterByPriority($priority)
    {
        $tasks = Task::where('priority', $priority)->get();
        return response()->json([
            'status' => true,
            'message' => 'Successfully filtered tasks with priority: ' . $priority,
            'data' => $tasks,
        ]);
    }
}