<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['course_id', 'judul_tugas', 'deskripsi', 'deadline', 'status_id', 'priority_id'])]
class Task extends Model
{
    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TaskPriority::class, 'priority_id');
    }

    public function submission(): HasOne
    {
        return $this->hasOne(TaskSubmission::class);
    }
}
