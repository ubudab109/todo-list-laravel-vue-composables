<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Task
 * 
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $due_date
 * @property \Illuminate\Support\Carbon|null $date_completed
 * @property \Illuminate\Support\Carbon|null $archived_date
 * @property int $priority
 * @property array $tags
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'date_completed',
        'archived_date',
        'priority',
        'tags',
        'order',
    ];

    protected $appends = ['is_overdue', 'is_archived', 'is_completed', 'is_todo'];

    /**
     * The function checks if the due date is in the future.
     * 
     * @return bool a boolean value, indicating whether the due date of an object is in the future or
     * not.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (is_null($this->due_date)) return false;
        return strtotime($this->due_date) < strtotime(now()->format('Y-m-d'));
    }

    /**
     * The function returns true if the "archived_date" property is not null.
     * 
     * @return bool a boolean value. It returns true if the "archived_date" property of the object is
     * not null, and false otherwise.
     */
    public function getIsArchivedAttribute(): bool
    {
        return !is_null($this->archived_date);
    }

    /**
     * The function returns true if the "date_completed" attribute is not null, indicating that a task
     * is completed.
     * 
     * @return bool a boolean value. It returns true if the date_completed property is not null, and
     * false otherwise.
     */
    public function getIsCompletedAttribute(): bool
    {
        return !is_null($this->date_completed);
    }

    /**
     * The function returns true if the date_completed attribute is null, indicating that the task is
     * still pending.
     * 
     * @return bool a boolean value. It is checking if the "date_completed" attribute of the object is
     * null, and if it is, it returns true. Otherwise, it returns false.
     */
    public function getIsTodoAttribute(): bool
    {
        return is_null($this->date_completed);
    }

    /**
     * The function returns a relationship between the current model and the User model.
     * 
     * @return BelongsTo a BelongsTo relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * The function returns a hasMany relationship for the TaskFile model, with the foreign key
     * 'task_id' and the local key 'id'.
     * 
     * @return HasMany a HasMany relationship.
     */
    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class, 'task_id', 'id');
    }
}
