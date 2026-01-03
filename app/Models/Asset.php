<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'employee_id',
        'asset_type',
        'asset_name',
        'serial_number',
        'description',
        'assigned_by',
        'assigned_date',
        'return_date',
        'status',
        'return_notes',
        'acceptance_status',
        'depreciation_value',
        'damage_notes',
        'acceptance_date',
        'task_assignment_id',
        'department_id',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'return_date' => 'date',
        'acceptance_date' => 'date',
        'depreciation_value' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function taskAssignment(): BelongsTo
    {
        return $this->belongsTo(TaskAssignment::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
