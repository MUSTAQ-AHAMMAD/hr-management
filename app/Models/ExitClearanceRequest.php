<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ExitClearanceRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'line_manager_id',
        'line_manager_email',
        'line_manager_name',
        'initiated_by',
        'status',
        'line_manager_approval_status',
        'line_manager_approved_at',
        'line_manager_approval_notes',
        'exit_date',
        'reason',
        'assets_returned',
        'financial_cleared',
        'clearance_date',
        'notes',
    ];

    protected $casts = [
        'exit_date' => 'date',
        'clearance_date' => 'date',
        'assets_returned' => 'boolean',
        'financial_cleared' => 'boolean',
        'line_manager_approved_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function lineManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'line_manager_id');
    }

    public function taskAssignments(): MorphMany
    {
        return $this->morphMany(TaskAssignment::class, 'assignable');
    }

    public function clearanceDocuments(): HasMany
    {
        return $this->hasMany(ClearanceDocument::class);
    }
}
