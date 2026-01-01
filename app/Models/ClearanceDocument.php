<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClearanceDocument extends Model
{
    protected $fillable = [
        'exit_clearance_request_id',
        'document_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'generated_by',
    ];

    public function exitClearanceRequest(): BelongsTo
    {
        return $this->belongsTo(ExitClearanceRequest::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
