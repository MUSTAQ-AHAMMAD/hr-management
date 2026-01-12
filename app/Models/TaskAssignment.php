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
        'is_partially_closed',
        'partial_closure_reason',
        'partial_closure_date',
        'notify_on_availability',
        'approved_by_name',
        'approved_by_email',
        'digital_signature_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
        'partial_closure_date' => 'date',
        'is_partially_closed' => 'boolean',
        'notify_on_availability' => 'boolean',
        'digital_signature_date' => 'datetime',
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

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
