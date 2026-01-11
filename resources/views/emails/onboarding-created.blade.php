<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Onboarding Started</title>
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
            background-color: #1e3a8a;
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
            border-left: 3px solid #1e3a8a;
        }
        .label {
            font-weight: bold;
            color: #1e3a8a;
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
            background-color: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to the Team!</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $onboardingRequest->employee->full_name }},</p>
        
        <p>Welcome aboard! We are excited to have you join our team. Your onboarding process has been initiated and you now have access to our HR Management Dashboard.</p>
        
        <div class="info-row">
            <span class="label">Employee Code:</span> {{ $onboardingRequest->employee->employee_code }}
        </div>
        
        <div class="info-row">
            <span class="label">Department:</span> {{ $onboardingRequest->employee->department ? $onboardingRequest->employee->department->name : 'Not Assigned' }}
        </div>
        
        <div class="info-row">
            <span class="label">Designation:</span> {{ $onboardingRequest->employee->designation ?? 'Not Assigned' }}
        </div>
        
        @if($onboardingRequest->line_manager_id)
        <div class="info-row">
            <span class="label">Line Manager:</span> {{ $onboardingRequest->lineManager->name ?? 'Not Assigned' }}
        </div>
        @endif
        
        <div class="info-row">
            <span class="label">Expected Completion Date:</span> {{ $onboardingRequest->expected_completion_date ? $onboardingRequest->expected_completion_date->format('F d, Y') : 'Not Set' }}
        </div>
        
        @if($onboardingRequest->employee->email)
        <div class="info-row">
            <span class="label">Official Email:</span> {{ $onboardingRequest->employee->email }}
        </div>
        @endif
        
        <p style="margin-top: 20px;">
            <strong>Next Steps:</strong><br>
            You can now log in to the HR Management Dashboard using your official email address to:
        </p>
        <ul>
            <li>View your onboarding tasks and progress</li>
            <li>Complete required documentation</li>
            <li>Accept assigned assets</li>
            <li>Access company resources and information</li>
        </ul>
        
        @if($onboardingRequest->employee->user_id)
        <p>
            <a href="{{ config('app.url') }}/login" class="button">Access Dashboard</a>
        </p>
        @endif
        
        @if($onboardingRequest->notes)
        <div class="info-row">
            <span class="label">Additional Notes:</span><br>
            {{ $onboardingRequest->notes }}
        </div>
        @endif
        
        <p style="margin-top: 20px;">
            If you have any questions or need assistance, please don't hesitate to reach out to the HR department or your line manager.
        </p>
        
        <p>
            We look forward to working with you!
        </p>
        
        <p>
            Best regards,<br>
            HR Department
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from HR Management System. Please do not reply to this email.</p>
    </div>
</body>
</html>
