<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class OnboardingRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'personal_email',
        'line_manager_id',
        'line_manager_email',
        'line_manager_name',
        'initiated_by',
        'status',
        'notes',
        'expected_completion_date',
        'actual_completion_date',
    ];

    protected $casts = [
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
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

    public function customFieldValues(): MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }
}
