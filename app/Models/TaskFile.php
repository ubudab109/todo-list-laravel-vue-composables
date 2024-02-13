<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\TaskFile
 * 
 * @property int $id
 * @property int $task_id
 * @property int $file_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'file_id'];

    /**
     * The function returns a relationship between the current model and the File model, using the
     * 'file_id' column in the current model and the 'id' column in the File model.
     * 
     * @return BelongsTo a BelongsTo relationship.
     */
    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
