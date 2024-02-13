<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\ToggleTaskDateRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /** @var TaskService */
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = $request->get('order', []);
        $param = $request->get('param', []);
        $with = $request->get('with', []);
        $response = $this->taskService->getTasks($order, $param, $with, $request->get('show', 10));
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function stores a task by creating it using the input data from the request and returns a
     * JSON response.
     * 
     * @param TaskRequest $request The parameter `` is an instance of the `TaskRequest` class.
     * It is used to retrieve the input data from the HTTP request made to the `store` method.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function store(TaskRequest $request): JsonResponse
    {
        $input = $request->all();
        $response = $this->taskService->createTask($input);
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function "show" retrieves and returns the details of a task in JSON format, with optional
     * related data, after authorizing the user.
     * 
     * @param Request $request The `` parameter is an instance of the `Illuminate\Http\Request`
     * class. It represents the current HTTP request being handled by the application and contains
     * information such as the request method, headers, query parameters, and request body.
     * @param Task $task The `` parameter is an instance of the `Task` model. It represents a
     * specific task that we want to show the details of.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function show(Request $request, Task $task): JsonResponse
    {
        $this->authorize('detailOrUpdate', $task);
        $with = $request->get('with', []);
        $response = $this->taskService->detailTask($task, $with);
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function updates a task using the input from a request and returns a JSON response.
     * 
     * @param TaskUpdateRequest $request The  parameter is an instance of the TaskUpdateRequest
     * class. It is used to retrieve the input data from the HTTP request.
     * @param Task $task The "task" parameter is an instance of the "Task" model. It represents the task
     * that needs to be updated.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function update(TaskUpdateRequest $request, Task $task): JsonResponse
    {
        $this->authorize('detailOrUpdate', $task);
        $input = $request->all();
        $response = $this->taskService->updateTask($task, $input);
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function destroys a task and returns a JSON response.
     * 
     * @param Task $task The "task" parameter is an instance of the Task model. It represents the task
     * that needs to be deleted.
     * 
     * @return JsonResponse a JsonResponse.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('detailOrUpdate', $task);
        $response = $this->taskService->deleteTasks($task);
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function "markComplete" toggles the completion date of a task and returns a JSON response.
     * 
     * @param ToggleTaskDateRequest $request The  parameter is an instance of the
     * ToggleTaskDateRequest class. It is used to retrieve the input data from the request.
     * @param Task $task The "task" parameter is an instance of the Task model. It represents the task
     * that needs to be marked as complete.
     * 
     * @return JsonResponse A JsonResponse is being returned.
     */
    public function markComplete(ToggleTaskDateRequest $request, Task $task): JsonResponse
    {
        $input = $request->all();
        $this->authorize('detailOrUpdate', $task);
        $response = $this->taskService->toggleDate($task, 'date_completed', $input['type']);
        return response()->json($response, $response['status_code']);
    }

    /**
     * The function "markArchived" toggles the archived date of a task based on the input type and
     * returns a JSON response.
     * 
     * @param ToggleTaskDateRequest $request The  parameter is an instance of the
     * ToggleTaskDateRequest class. It is used to retrieve the input data from the request.
     * @param Task $task The "task" parameter is an instance of the Task model. It represents a specific
     * task that needs to be marked as archived.
     * 
     * @return JsonResponse A JsonResponse is being returned.
     */
    public function markArchived(ToggleTaskDateRequest $request, Task $task): JsonResponse
    {
        $input = $request->all();
        $this->authorize('detailOrUpdate', $task);
        $response = $this->taskService->toggleDate($task, 'archived_date', $input['type']);
        return response()->json($response, $response['status_code']);
    }
}
