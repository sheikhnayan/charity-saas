<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
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
            padding: 40px 30px;
            color: #333333;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .message {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        .verification-code-container {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .verification-label {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        .verification-code {
            font-size: 42px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 8px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
        }
        .code-expiry {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 15px;
        }
        .instructions {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .instructions p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
        }
        .instructions strong {
            color: #78350f;
        }
        .resend-info {
            font-size: 14px;
            color: #64748b;
            margin-top: 25px;
            padding: 20px;
            background: #f1f5f9;
            border-radius: 8px;
        }
        .resend-info strong {
            color: #334155;
        }
        .email-footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .email-footer p {
            margin: 8px 0;
            font-size: 13px;
            color: #64748b;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 8px;
            }
            .email-header {
                padding: 30px 20px;
            }
            .email-header h1 {
                font-size: 24px;
            }
            .email-body {
                padding: 30px 20px;
            }
            .verification-code {
                font-size: 36px;
                letter-spacing: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>✓ Verify Your Account</h1>
            <p>Registration Signup Verification Code</p>
        </div>

        <div class="email-body">
            <div class="greeting">
                Hello {{ $name }},
            </div>

            <div class="message">
                Thank you for registering! To complete your account setup and ensure the security of your account, please verify your email address using the verification code below.
            </div>

            <div class="verification-code-container">
                <div class="verification-label">Your Verification Code</div>
                <div class="verification-code">{{ $code }}</div>
                <div class="code-expiry">This code is valid for 24 hours</div>
            </div>

            <div class="instructions">
                <p><strong>How to verify:</strong> Enter this code on the verification page to confirm your email address and activate your account.</p>
            </div>

            <div class="resend-info">
                <strong>Code not working?</strong><br>
                If the code doesn't work, return to the submission form and click <strong>Resend</strong> to receive a new verification code.
            </div>

            <div class="divider"></div>

            <p style="font-size: 13px; color: #94a3b8; margin: 20px 0 0 0;">
                If you didn't request this verification code, please ignore this email or contact our support team if you have concerns.
            </p>
        </div>

        <div class="email-footer">
            <p style="color: #334155; font-weight: 600; margin-bottom: 10px;">Need Help?</p>
            <p>If you have any questions, feel free to reach out to our support team.</p>
            <p style="margin-top: 20px;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
