<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_prioritas'])]
class TaskPriority extends Model
{
    public $timestamps = false;

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'priority_id');
    }
}
