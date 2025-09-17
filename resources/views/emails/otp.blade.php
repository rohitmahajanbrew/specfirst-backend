<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #2563eb;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 8px;
            border: 2px dashed #2563eb;
        }
        .warning {
            background: #fef3cd;
            color: #664d03;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your OTP Code</h1>
        
        <p>Hello! You requested an OTP for <strong>{{ ucfirst($otpData['purpose']) }}</strong> on our Requirements Platform.</p>
        
        <div class="otp-code">
            {{ $otpData['otp_code'] }}
        </div>
        
        <p>Enter this code to complete your {{ $otpData['purpose'] }}.</p>
        
        <div class="warning">
            <strong>⚠️ Important:</strong>
            <ul style="text-align: left; margin: 10px 0;">
                <li>This code expires in {{ $otpData['expires_in'] }}</li>
                <li>Don't share this code with anyone</li>
                <li>If you didn't request this, please ignore this email</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>This is an automated email from Requirements Platform.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
