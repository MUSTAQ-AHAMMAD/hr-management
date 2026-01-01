<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TaskAssignment extends Model
{
    protected $fillable = [
        'task_id',
        'assigned_to',
        'assignable_type',
        'assignable_id',
        'status',
        'due_date',
        'completed_date',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }
}
