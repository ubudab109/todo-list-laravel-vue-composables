<?php

namespace App\Services;

use App\Http\Resources\PaginationResource;
use App\Interfaces\FileInterface;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use App\Utilities\InternalResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService extends InternalResponse
{
    /** @var TaskInterface */
    private $taskRepository;

    /** @var FileInterface */
    private $fileRepository;

    /**
     * Task Service Constructor
     * 
     * @return void
     */
    public function __construct(TaskInterface $taskRepository, FileInterface $fileRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->fileRepository = $fileRepository;
    }

    /**
     * The function retrieves tasks based on specified parameters and returns them along with
     * pagination information.
     * 
     * @param array $order The "order" parameter is used to specify the order in which the tasks should
     * be retrieved. It is an array that can contain one or more elements, where each element
     * represents a field to order by and the order direction (ascending or descending).
     * @param array $param The "param" parameter is an array that allows you to specify additional
     * conditions or filters for retrieving tasks. It can include various key-value pairs, where the
     * key represents the field or attribute of the task, and the value represents the desired value or
     * condition for that field. For example, you can use
     * @param array $with The "with" parameter is an array that specifies the relationships to be eager
     * loaded with the tasks. Eager loading allows you to load the related models in a single query,
     * which can improve performance when accessing the related data.
     * @param int $show The "show" parameter determines the number of tasks to be displayed per page. It
     * is an integer value that specifies the maximum number of tasks to be shown.
     * 
     * @return array an array containing the message "Task Fetched Successfully" and an instance of the
     * PaginationResource class.
     */
    public function getTasks(array $order = [], array $param = [], array $with = [], int $show = 10): array
    {
        $data = $this->taskRepository->listTasks($order, $param, $with, $show);
        $pagination = new PaginationResource($data);
        return $this->respondSuccess('Task Fetched Successfully', $pagination);
    }

    /**
     * The function creates a task in the database and handles any errors that occur during the
     * process.
     * 
     * @param array $data An array containing the data for creating a task. This can include information
     * such as the task title, description, due date, and any other relevant details.
     * 
     * @return array an array.
     */
    public function createTask(array $data): array
    {
        DB::beginTransaction();
        try {
            if (!empty($data['tags'])) $data['tags'] = json_encode($data['tags']);
            $data['user_id'] = Auth::user()->id;
            $task = $this->taskRepository->createTask($data, $data['files'] ?? []);
            DB::commit();
            return $this->respondSuccess('Task Created Successfully', $task);
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->respondInternalError($err->getMessage());
        }
    }

    /**
     * The function "detailTask" retrieves a task with the given ID and additional related data, and
     * returns a success response with the task data if found, or a not found response if not found.
     * 
     * @param int $taskId The taskId parameter is an integer that represents the unique identifier of
     * the task you want to retrieve the details for.
     * @param array $with The "with" parameter is an array that specifies the relationships to be eager
     * loaded with the task. It allows you to retrieve the task along with its related data in a single
     * query, reducing the number of database queries and improving performance.
     * 
     * @return array an array.
     */
    public function detailTask(Task $task, array $with): array
    {
        $data = $this->taskRepository->getTask($task->id, $with);
        if (!$data) {
            return $this->respondNotFound('Task not found');
        }
        return $this->respondSuccess('Task successfully fetched', $data);
    }

    /**
     * The function updates a task with the provided data and returns a success response with the
     * updated task or an error response with the error message.
     * 
     * @param Task task The  parameter is an instance of the Task class. It represents the task
     * that needs to be updated.
     * @param array data The `` parameter is an array that contains the updated information for
     * the task. It can include fields such as the task title, description, due date, priority, and any
     * other relevant information.
     * 
     * @return array an array. If the task is updated successfully, it returns an array with the
     * message "Task Updated Successfully" and the updated task. If there is an error, it returns an
     * array with the error message.
     */
    public function updateTask(Task $task, array $data): array
    {
        DB::beginTransaction();
        try {
            if (!empty($data['tags'])) $data['tags'] = json_encode($data['tags']);
            $task = $this->taskRepository->updateTask($task->id, $data, $data['files'] ?? []);
            DB::commit();
            return $this->respondSuccess('Task Updated Successfully', $task);
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->respondInternalError($err->getMessage());
        }
    }

    /**
     * This PHP function deletes a task and its associated files from the database, while also handling
     * any errors that may occur.
     * 
     * @param Task $task The parameter `` is an instance of the `Task` class. It represents the
     * task that needs to be deleted.
     * 
     * @return array an array.
     */
    public function deleteTasks(Task $task): array
    {
        DB::beginTransaction();
        try {
            // NEED TO DELETE TASK IMAGES FIRST
            $getTaskFiles = $this->taskRepository->getTask($task->id, ['files']);
            foreach($getTaskFiles->files as $file) {
                removeFile(public_path($file->file->filepath));
                $this->fileRepository->deleteFile($file->file_id);
            }
            $this->taskRepository->removeTask($task->id);
            DB::commit();
            return $this->respondNotContent();
        } catch (\Exception $err) {
            DB::rollBack();
            return $this->respondInternalError($err->getMessage());
        }
    }

    /**
     * The function `toggleDate` updates the due date or completion date of a task and returns a
     * success response with the updated task.
     * 
     * @param Task $task The  parameter is an instance of the Task class. It represents the task
     * that needs to be updated with a new date value.
     * @param string $dateType The  parameter is a string that specifies the type of date to
     * toggle. It can have two possible values: 'archived_date' or any other value.
     * @param string $setType either set or remove, if `set` then the value will be `now()` else if `remove` 
     * then the value will be null
     * @return array an array.
     */
    public function toggleDate(Task $task, string $dateType, string $setType): array
    {
        $data[$dateType] = $setType == 'set' ? now() : null;
        $task = $this->taskRepository->updateTask($task->id, $data, []);
        return $this->respondSuccess('Task Status Updated Successfully', $task);
    }
}