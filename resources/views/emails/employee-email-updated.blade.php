<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Email ID Updated</title>
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
            border-left: 3px solid #059669;
        }
        .label {
            font-weight: bold;
            color: #1e3a8a;
        }
        .email-box {
            background-color: #d1fae5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #065f46;
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
        <h1>Employee Email ID Updated</h1>
    </div>
    
    <div class="content">
        <p>Dear HR Team,</p>
        
        <p>The email ID for the following employee has been successfully updated by the IT team.</p>
        
        <div class="info-row">
            <span class="label">Employee Name:</span> {{ $user->name }}
        </div>
        
        <div class="info-row">
            <span class="label">Department:</span> {{ $user->department ? $user->department->name : 'Not Assigned' }}
        </div>
        
        <div class="email-box">
            Email ID: {{ $user->email }}
        </div>
        
        <p style="margin-top: 20px;">
            <strong>Next Steps:</strong><br>
            You can now proceed with the onboarding process for this employee. All email communications and system notifications will be sent to the above email address.
        </p>
        
        <p style="margin-top: 20px;">
            Thank you.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from HR Management System. Please do not reply to this email.</p>
    </div>
</body>
</html>
