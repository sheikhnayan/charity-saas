<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Approved</title>
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
            border-bottom: 2px solid #28a745;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        .content {
            color: #333;
            font-size: 16px;
        }
        .content h2 {
            color: #28a745;
            margin-top: 0;
        }
        .content p {
            margin: 15px 0;
        }
        .highlight-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
        }
        .login-button {
            display: inline-block;
            background: #28a745;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .login-button:hover {
            background: #218838;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .emoji {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{{ $websiteName }}</div>
            <p style="color: #6c757d; margin: 0;">Account Approval Notification</p>
        </div>

        <div class="content">
            <h2>Great news — your account has been approved!</h2>
            
            <p>Hello {{ $userName }},</p>
            
            <p>We're excited to inform you that your account has been successfully verified and approved.</p>
            
            <div class="highlight-box">
                <strong>You can now log in using the credentials associated with your registration.</strong>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="login-button">
                    <span class="emoji">👉</span> Log in here
                </a>
            </div>
            
            <p>Welcome aboard, and thank you for your patience during the verification process.</p>
            
            <p style="margin-top: 30px;">
                <strong>Your Account Details:</strong><br>
                Email: {{ $userEmail }}<br>
                Website: {{ $websiteDomain }}
            </p>
            
            <p style="color: #6c757d; font-size: 14px; margin-top: 20px;">
                If you did not request this account or have any questions, please contact our support team immediately.
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} {{ $websiteName }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
