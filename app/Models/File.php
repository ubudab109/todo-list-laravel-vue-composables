<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\File
 * 
 * @property int $id
 * @property string $filename
 * @property string $filepath
 * @property string $file_url
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = ['filename', 'filepath', 'file_url', 'type'];

    /**
     * The function returns a hasMany relationship between the current model and the TaskFile model,
     * using the 'file_id' column in the TaskFile model and the 'id' column in the current model.
     * 
     * @return HasMany a HasMany relationship.
     */
    public function taskFiles(): HasMany
    {
        return $this->hasMany(TaskFile::class, 'file_id', 'id');
    }
}
