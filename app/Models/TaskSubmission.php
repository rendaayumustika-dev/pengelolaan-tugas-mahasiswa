<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['task_id', 'tanggal_submit', 'catatan', 'file_tugas'])]
class TaskSubmission extends Model
{
    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'tanggal_submit' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
