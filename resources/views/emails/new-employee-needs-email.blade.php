<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Employee Needs Email ID</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>New Employee Needs Email ID</h1>
    </div>
    
    <div class="content">
        <p>Dear IT Team,</p>
        
        <p>A new employee has been added to the system and requires an email ID to be created.</p>
        
        <div class="info-row">
            <span class="label">Employee Name:</span> {{ $user->name }}
        </div>
        
        <div class="info-row">
            <span class="label">Department:</span> {{ $user->department ? $user->department->name : 'Not Assigned' }}
        </div>
        
        <div class="info-row">
            <span class="label">Phone:</span> {{ $user->phone ?? 'Not Provided' }}
        </div>
        
        <div class="info-row">
            <span class="label">Status:</span> {{ ucfirst($user->status) }}
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Action Required:</strong><br>
            Please create an email ID for this employee and update it in the HR Management System. Once updated, the HR team will be automatically notified to proceed with the onboarding process.
        </p>
        
        <p style="margin-top: 20px;">
            Thank you for your cooperation.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from HR Management System. Please do not reply to this email.</p>
    </div>
</body>
</html>
