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
        'initiated_by',
        'status',
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
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
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
