<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'department_id',
        'designation',
        'joining_date',
        'exit_date',
        'status',
        'user_id',
        'email_created_by_it',
        'email_created_at',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'exit_date' => 'date',
        'email_created_by_it' => 'boolean',
        'email_created_at' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function onboardingRequests(): HasMany
    {
        return $this->hasMany(OnboardingRequest::class);
    }

    public function exitClearanceRequests(): HasMany
    {
        return $this->hasMany(ExitClearanceRequest::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if employee is pending email creation by IT
     */
    public function isPendingEmailCreation(): bool
    {
        // Pending if email is empty and hasn't been marked as created by IT
        return empty($this->email);
    }
}
