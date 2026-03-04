<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Registration - Approval Required | {{ $website->name }}</title>
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
            border-bottom: 2px solid #667eea;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .content {
            color: #333;
            font-size: 16px;
        }
        .content h2 {
            color: #667eea;
            margin-top: 0;
        }
        .content p {
            margin: 15px 0;
        }
        .highlight-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .details-box {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .details-table {
            width: 100%;
            margin-top: 15px;
        }
        .details-table tr {
            border-bottom: 1px solid #f0f0f0;
        }
        .details-table td {
            padding: 12px 0;
        }
        .details-table .label {
            font-weight: bold;
            color: #667eea;
            width: 30%;
        }
        .details-table .value {
            color: #333;
        }
        .details-table tr:last-child {
            border-bottom: none;
        }
        .btn-primary {
            display: inline-block;
            background: #667eea;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #0099ff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #0066cc;
            font-size: 14px;
        }
        .footer {
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
            margin-top: 30px;
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">{{ $website->name }}</div>
            <h2 style="margin: 10px 0 0 0; font-size: 20px;">New Registration Pending</h2>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello Admin,</p>

            <!-- Alert -->
            <div class="highlight-box">
                <strong>A new {{ $userRole == 'Parents' ? 'Parents/Guardian' : $userRole }} has registered and requires approval.</strong>
            </div>

            <!-- Registration Details -->
            <div class="details-box">
                <h3 style="color: #333; font-size: 16px; margin-top: 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">Registration Details</h3>
                <table class="details-table">
                    <tr>
                        <td class="label">Name:</td>
                        <td class="value">{{ $newUser->name }} {{ $newUser->last_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email:</td>
                        <td class="value">{{ $newUser->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Account Type:</td>
                        <td class="value">
                            @if ($userRole == 'Parents')
                                Parents/Guardian
                            @else
                            {{ $userRole }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Registered:</td>
                        <td class="value">{{ $registrationDate }}</td>
                    </tr>
                </table>
            </div>

            <!-- Action Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $approvalLink }}" class="btn-primary" style="color: #fff !important;">Review & Approve Registrations</a>
            </div>

            <!-- Status Info -->
            <div class="info-box">
                <strong>Note:</strong> This account will remain inactive until you approve it. Once approved, the user will receive an email confirming their access.
            </div>

            <!-- Footer -->
            <div class="footer">
                This is an automated notification from {{ $website->name }}. If you have any questions, please contact your system administrator.
            </div>
        </div>
    </div>
</body>
</html>
