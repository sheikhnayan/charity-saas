<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007bff;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .content {
            color: #333;
            font-size: 16px;
        }
        .content h2 {
            color: #007bff;
            margin-top: 0;
        }
        .content p {
            margin: 15px 0;
        }
        .highlight-box {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{{ $websiteName }}</div>
            <p style="color: #6c757d; margin: 0;">Registration Confirmation</p>
        </div>

        <div class="content">
            <h2>Congratulations — your registration has been successfully submitted.</h2>
            
            <p>Hello {{ $userName }},</p>
            
            <p>This email confirms we've received your information. Your account is currently under review to ensure all details are accurate and legitimate.</p>
            
            <div class="highlight-box">
                <strong>What happens next?</strong>
                <p style="margin: 10px 0 0 0;">Once approved, you'll receive a follow-up email with login access instructions.</p>
            </div>
            
            <p>If you have any questions in the meantime, feel free to contact our support team.</p>
            
            <p style="margin-top: 30px;">
                <strong>Registration Details:</strong><br>
                Email: {{ $userEmail }}<br>
                Website: {{ $websiteDomain }}
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ $websiteName }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
