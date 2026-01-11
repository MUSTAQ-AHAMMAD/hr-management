<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exit Clearance Approval Required</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 5px 5px;
        }
        .info-row {
            margin: 10px 0;
            padding: 10px;
            background-color: white;
            border-left: 3px solid #dc2626;
        }
        .label {
            font-weight: bold;
            color: #dc2626;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #16a34a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .button-reject {
            background-color: #dc2626;
        }
        .button-group {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Exit Clearance Approval Required</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $exitRequest->lineManager->name ?? 'Line Manager' }},</p>
        
        <p>An exit clearance request has been initiated for one of your team members and requires your approval before the HR department can proceed with the clearance process.</p>
        
        <div class="info-row">
            <span class="label">Employee Code:</span> {{ $exitRequest->employee->employee_code }}
        </div>
        
        <div class="info-row">
            <span class="label">Employee Name:</span> {{ $exitRequest->employee->full_name }}
        </div>
        
        <div class="info-row">
            <span class="label">Department:</span> {{ $exitRequest->employee->department ? $exitRequest->employee->department->name : 'Not Assigned' }}
        </div>
        
        <div class="info-row">
            <span class="label">Designation:</span> {{ $exitRequest->employee->designation ?? 'Not Assigned' }}
        </div>
        
        <div class="info-row">
            <span class="label">Exit Date:</span> {{ $exitRequest->exit_date ? $exitRequest->exit_date->format('F d, Y') : 'Not Set' }}
        </div>
        
        @if($exitRequest->reason)
        <div class="info-row">
            <span class="label">Reason for Exit:</span><br>
            {{ $exitRequest->reason }}
        </div>
        @endif
        
        <p style="margin-top: 20px;">
            <strong>Action Required:</strong><br>
            As the line manager, you need to review and approve this exit clearance request. Once approved, the HR department will assign clearance tasks to relevant departments.
        </p>
        
        <div class="button-group">
            <a href="{{ route('exit-clearance-requests.line-manager-approve', ['exitClearanceRequest' => $exitRequest->id, 'token' => $approvalToken]) }}" class="button">Approve Exit Clearance</a>
            <a href="{{ route('exit-clearance-requests.line-manager-reject', ['exitClearanceRequest' => $exitRequest->id, 'token' => $approvalToken]) }}" class="button button-reject">Reject Request</a>
        </div>
        
        <p style="margin-top: 20px;">
            You can also log in to the HR Management Dashboard to review the request in detail and provide any additional notes.
        </p>
        
        <p>
            Thank you for your prompt attention to this matter.
        </p>
        
        <p>
            Best regards,<br>
            HR Department
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from HR Management System.</p>
    </div>
</body>
</html>
