<?php

namespace App\Repositories;

use App\Interfaces\TaskInterface;
use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class TaskRepository implements TaskInterface
{
    /**
    * @var Task
    */
    protected $task;

	/**
	 * Task Repository Constructor
	 * 
	 * @return void
	 */
    public function __construct(Task $task)
    {
		$this->task = $task;
    }

	/**
	 * The function `listTasks` retrieves a paginated list of tasks based on various parameters and
	 * sorting options.
	 * 
	 * @param array $order The "order" parameter is an array that specifies the sorting order for the
	 * tasks. It can contain the following keys:
	 * @param array $param The `param` parameter is an array that contains various filters and search
	 * criteria for the tasks. It can include the following keys:
	 * @param array $with The `` parameter is an array that specifies the relationships to be eager
	 * loaded with the tasks. It allows you to load related models along with the tasks in a more
	 * efficient way. For example, if you have a relationship between tasks and users, you can pass
	 * `['user']` to load
	 * @param int $show The "show" parameter determines the number of tasks to be displayed per page in the
	 * paginated result. The default value is 10, but you can pass a different value to display a
	 * different number of tasks per page.
	 * 
	 * @return LengthAwarePaginator a LengthAwarePaginator object.
	 */
	public function listTasks(array $order = [], array $param = [], array $with = [], int $show = 10): LengthAwarePaginator
	{
        $data = $this->task
		->with($with)
		->where('user_id', Auth::user()->id)
        ->when(!empty($param['completed_date_start']) && !empty($param['completed_date_end']), function ($query) use ($param) {
            $query->whereDate('date_completed', '>=', carbonTimestampFormatter($param['completed_date_start']));
            $query->whereDate('date_completed', '<=', carbonTimestampFormatter($param['completed_date_end']));
        })
		->when(isset($param['priority']) && $param['priority'] != '', function ($query) use ($param) {
			$query->where('priority', (string)$param['priority']);
		})
		->when(!empty($param['due_date_start']) && !empty($param['due_date_end']), function ($query) use ($param) {
            $query->whereDate('due_date', '>=', carbonTimestampFormatter($param['due_date_start']));
            $query->whereDate('due_date', '<=', carbonTimestampFormatter($param['due_date_end']));
        })
		->when(!empty($param['archived_date_start']) && !empty($param['archived_date_end']), function ($query) use ($param) {
            $query->whereDate('archived_date', '>=', carbonTimestampFormatter($param['archived_date_start']));
            $query->whereDate('archived_date', '<=', carbonTimestampFormatter($param['archived_date_end']));
        })
		->when(!empty($param['query']), function ($query) use ($param) {
			$query->where('title', 'LIKE', '%'. $param['query']. '%')
			->orWhere('description', 'LIKE', '%'. $param['query']. '%');
		})
		->when(isset($param['is_todo']), function ($query) use ($param) {
			if ($param['is_todo'] == 1) $query->whereNull('date_completed');
			else $query->whereNotNull('date_completed');
		})
		->orderBy($order['by'] ?? 'id', $order['type'] ?? 'asc')
		->paginate($show ?? 10);
		return $data;
    }

	/**
	 * The function creates a task and optionally attaches files to it.
	 * 
	 * @param array $taskData An array containing the data for creating a new task. This can include
	 * attributes such as the task title, description, due date, priority, etc.
	 * @param array $taskFileData An optional array of data for creating task files.
	 * 
	 * @return Task a Task object that has been loaded with its associated files.
	 */
	public function createTask(array $taskData, array $taskFileData = []): Task
	{
		$latestTask = $this->task->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
		if ($latestTask) {
			$taskData['order'] = $latestTask->order + 1;
		} else {
			$taskData['order'] = 1;
		}
		$task = $this->task->create($taskData);
		if (!empty($taskFileData)) {
			foreach($taskFileData as $file) {
				$task->files()->create(['file_id' => $file['id']]);
			}
		}
		return $task->load('files');
	}

	/**
	 * The function "getTask" retrieves a task by its ID and includes additional related data specified in
	 * the "with" parameter.
	 * 
	 * @param int $taskId The taskId parameter is an integer that represents the unique identifier of the
	 * task you want to retrieve.
	 * @param array $with The "with" parameter is an array that specifies the relationships to be eager
	 * loaded with the task. It allows you to retrieve the task along with its related data in a single
	 * query, which can help improve performance.
	 * 
	 * @return Task|bool an instance of the Task class.
	 */
	public function getTask(int $taskId, array $with): Task|bool
	{
		$data = $this->task->with($with)->where('id', $taskId)->first();
		return $data;
	}

	/**
	 * The function updates a task with the given task ID, data, and optional files data, and returns a
	 * boolean indicating if the update was successful.
	 * 
	 * @param int $taskId The ID of the task that needs to be updated.
	 * @param array $data The `` parameter is an array that contains the updated information for the
	 * task. It could include fields such as the task title, description, due date, etc.
	 * @param array $filesData The `filesData` parameter is an optional array that contains data about
	 * files related to the task. It is an array of arrays, where each inner array represents a file and
	 * contains information such as the file ID.
	 * 
	 * @return Task|bool.
	 */
	public function updateTask(int $taskId, array $data, array $filesData = []): Task|bool
	{
		$task = $this->task->find($taskId);
		if (!empty($filesData)) {
			$task->files()->delete();
			foreach($filesData as $file) {
				$task->files()->create(['file_id' => $file['id']]);
			}
		}
		$task->update($data);
		return $this->task->with('files.file')->find($taskId);
	}

	/**
	 * The function removes a task from the database based on its ID and returns a boolean indicating
	 * whether the deletion was successful.
	 * 
	 * @param int $taskId The taskId parameter is an integer that represents the unique identifier of the
	 * task that needs to be removed.
	 * 
	 * @return bool a boolean value. It returns true if the task is successfully deleted, and false if the
	 * task is not found or unable to be deleted.
	 */
	public function removeTask(int $taskId): bool
	{
		$task = $this->task->find($taskId);
		return $task->delete();
	}
}