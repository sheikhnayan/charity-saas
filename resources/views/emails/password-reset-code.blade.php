<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Code</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .email-header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .email-body {
            padding: 30px;
            text-align: center;
        }
        .reset-code {
            display: inline-block;
            background: #f3f0ff;
            color: #5f27cd;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 16px 32px;
            border-radius: 8px;
            margin: 24px 0;
        }
        .email-footer {
            background: #f5f5f5;
            color: #888;
            text-align: center;
            padding: 18px 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Password Reset Request</h1>
        <p>Hello{{ isset($name) ? ' ' . $name : '' }},</p>
    </div>
    <div class="email-body">
        <p>We received a request to reset your password. Use the code below to reset it. If you did not request this, you can safely ignore this email.</p>
        <div class="reset-code">{{ $code }}</div>
        <p>This code will expire in 15 minutes.</p>
    </div>
    <div class="email-footer">
        &copy; {{ date('Y') }} Charity Platform. All rights reserved.
    </div>
</div>
</body>
</html>
