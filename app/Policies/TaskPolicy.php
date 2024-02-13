<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;
    
    /**
     * The function checks if a given user is the owner of a given task.
     * 
     * @param User $user The "user" parameter is an instance of the User class. It represents a user in
     * the system.
     * @param Task $task The  parameter is an instance of the Task class. It represents a specific
     * task object.
     * 
     * @return bool a boolean value.
     */
    public function detailOrUpdate(User $user, Task $task): bool
    {
        return $task->user_id == $user->id;
    }
}
