<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exit Clearance Certificate</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #1e40af;
        }
        .header h1 {
            color: #1e40af;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin: 0;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #1e40af;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            padding: 8px 10px;
            background-color: #f3f4f6;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }
        .info-value {
            display: table-cell;
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
        }
        .asset-table, .task-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .asset-table th, .task-table th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            border: 1px solid #e5e7eb;
            font-weight: bold;
        }
        .asset-table td, .task-table td {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-returned {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-assigned {
            background-color: #dbeafe;
            color: #1e3a8a;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .signature-section {
            margin-top: 60px;
            page-break-inside: avoid;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            margin-right: 5%;
            vertical-align: top;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        .clearance-note {
            background-color: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
        }
        .clearance-note strong {
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>EXIT CLEARANCE CERTIFICATE</h1>
        <p>HR Management System</p>
        <p>Certificate No: ECR-{{ str_pad($exitRequest->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="section">
        <div class="section-title">EMPLOYEE INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Employee Name</div>
                <div class="info-value">{{ $exitRequest->employee->full_name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Employee Code</div>
                <div class="info-value">{{ $exitRequest->employee->employee_code }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department</div>
                <div class="info-value">{{ $exitRequest->employee->department->name ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Designation</div>
                <div class="info-value">{{ $exitRequest->employee->designation ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $exitRequest->employee->email }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">EXIT DETAILS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Exit Date</div>
                <div class="info-value">{{ $exitRequest->exit_date?->format('F d, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Joining Date</div>
                <div class="info-value">{{ $exitRequest->employee->joining_date?->format('F d, Y') ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Initiated By</div>
                <div class="info-value">{{ $exitRequest->initiatedBy->name }}</div>
            </div>
            @if($exitRequest->reason)
            <div class="info-row">
                <div class="info-label">Reason for Exit</div>
                <div class="info-value">{{ $exitRequest->reason }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Clearance Date</div>
                <div class="info-value">{{ now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    @if($exitRequest->employee->assets->count() > 0)
    <div class="section">
        <div class="section-title">ASSET RETURN STATUS</div>
        <table class="asset-table">
            <thead>
                <tr>
                    <th>Asset Type</th>
                    <th>Asset Name</th>
                    <th>Serial Number</th>
                    <th>Status</th>
                    <th>Return Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exitRequest->employee->assets as $asset)
                <tr>
                    <td>{{ $asset->asset_type }}</td>
                    <td>{{ $asset->asset_name }}</td>
                    <td>{{ $asset->serial_number ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ $asset->status }}">
                            {{ strtoupper($asset->status) }}
                        </span>
                    </td>
                    <td>{{ $asset->return_date?->format('M d, Y') ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">DEPARTMENT CLEARANCES</div>
        <table class="task-table">
            <thead>
                <tr>
                    <th>Department</th>
                    <th>Task</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Completed Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exitRequest->taskAssignments as $assignment)
                <tr>
                    <td>{{ $assignment->task->department->name ?? 'N/A' }}</td>
                    <td>{{ $assignment->task->name }}</td>
                    <td>{{ $assignment->assignedTo->name ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ $assignment->status }}">
                            {{ strtoupper(str_replace('_', ' ', $assignment->status)) }}
                        </span>
                    </td>
                    <td>{{ $assignment->completed_date?->format('M d, Y') ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($exitRequest->taskAssignments->where('status', 'completed')->count() === $exitRequest->taskAssignments->count())
    <div class="clearance-note">
        <strong>✓ CLEARANCE COMPLETED</strong><br>
        All required clearances have been completed. The employee has returned all company assets and completed all exit formalities.
    </div>
    @endif

    <div class="section">
        <div class="section-title">ADDITIONAL NOTES</div>
        <div style="padding: 10px; min-height: 60px; border: 1px solid #e5e7eb;">
            {{ $exitRequest->notes ?? 'No additional notes.' }}
        </div>
    </div>

    <div class="signature-section">
        <div class="section-title">DIGITAL SIGNATURES</div>
        
        @if($exitRequest->line_manager_name && $exitRequest->line_manager_approval_status === 'approved')
        <div class="signature-box">
            <div class="signature-line">
                <strong>Line Manager Approval</strong><br>
                Name: {{ $exitRequest->line_manager_name }}<br>
                Email: {{ $exitRequest->line_manager_email }}<br>
                Date: {{ $exitRequest->line_manager_approved_at?->format('F d, Y h:i A') }}<br>
                Status: <strong style="color: #10b981;">APPROVED</strong>
            </div>
        </div>
        @endif
        
        @foreach($exitRequest->taskAssignments->where('status', 'completed')->sortBy('digital_signature_date') as $assignment)
            <div class="signature-box">
                <div class="signature-line">
                    <strong>{{ $assignment->task->department->name ?? 'N/A' }} Department</strong><br>
                    Task: {{ $assignment->task->name }}<br>
                    Approved by: {{ $assignment->approved_by_name ?? $assignment->assignedTo->name ?? 'N/A' }}<br>
                    Email: {{ $assignment->approved_by_email ?? $assignment->assignedTo->email ?? 'N/A' }}<br>
                    Date: {{ $assignment->digital_signature_date ? $assignment->digital_signature_date->format('F d, Y h:i A') : ($assignment->completed_date?->format('F d, Y') ?? 'N/A') }}<br>
                    Status: <strong style="color: #10b981;">CLEARED</strong>
                    @if($assignment->notes)
                        <br>Notes: {{ Str::limit($assignment->notes, 100) }}
                    @endif
                </div>
            </div>
        @endforeach
        
        <div class="signature-box">
            <div class="signature-line">
                <strong>HR Manager</strong><br>
                Name: {{ $exitRequest->initiatedBy->name }}<br>
                Date: {{ now()->format('F d, Y') }}<br>
                Status: <strong style="color: #10b981;">VERIFIED</strong>
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; padding: 15px; background-color: #f3f4f6; border-radius: 5px; text-align: center;">
        <p style="font-size: 10px; color: #666; margin: 0;">
            <strong>Digital Signature Verification:</strong> This document contains digital signatures from all relevant departments and line manager.
            All signatures were electronically recorded in the HR Management System on the dates indicated above.
        </p>
    </div>

    <div class="footer">
        <p style="text-align: center; color: #666; font-size: 10px;">
            This is a computer-generated document. Generated on {{ now()->format('F d, Y h:i A') }}<br>
            HR Management System - Exit Clearance Module
        </p>
    </div>
</body>
</html>
