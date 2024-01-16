<?php

namespace App\Http\Controllers\api;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function submitTask(Request $request)
    {
        // Ensure user is authenticated
        $user = $request->user();
        if (!$user) {
            return $this->respondUnauthorized();
        }

        // Check if the user has available credits
        if ($user->credits <= 0) {
            return response()->json(['message' => 'Insufficient credits'], 403);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return response()->json(['errors' => $errors, 'status' => false], 422);
        }

        // Create the task and consume one credit
        $task = Task::create([
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
        ]);

        // Update user credits
        $user->decrement('credits');

        return response()->json(['message' => 'Task submitted successfully', 'task' => $task]);
    }



    public function lastTenTasks(Request $request)
    {
        // Ensure user is authenticated
        $user = $request->user();
    
        if (!$user) {
            return $this->respondUnauthorized();
        }
    
        // Retrieve the last ten submitted tasks for the logged-in user
        $tasks = Task::where('user_id', $user->id)->latest()->take(10)->get();
    
        return response()->json(['tasks' => $tasks]);
    }

    private function respondUnauthorized()
    {
        return response()->json(['error' => 'Unauthenticated, kindly try again after authentication'], Response::HTTP_UNAUTHORIZED);
    }
}