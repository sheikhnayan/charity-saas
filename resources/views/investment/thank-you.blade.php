<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Complete - Thank You</title>
    <link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .thank-you-container {
            max-width: 600px;
            margin: 200px auto;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .success-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 20px;
        }
        .investment-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <div class="success-icon">✓</div>
        <h1>Thank You for Your Investment!</h1>
        <p>Your investment has been successfully processed. Below are the details of your investment:</p>
        
        <div class="investment-details">
            <div class="detail-row">
                <strong>Investment ID:</strong>
                <span>#{{ $investment->id }}</span>
            </div>
            <div class="detail-row">
                <strong>Investor Name:</strong>
                <span>{{ $investment->investor_name }}</span>
            </div>
            <div class="detail-row">
                <strong>Investment Amount:</strong>
                <span>{{ $investment->formatted_amount }}</span>
            </div>
            <div class="detail-row">
                <strong>Share Quantity:</strong>
                <span>{{ number_format($investment->share_quantity) }} shares</span>
            </div>
            <div class="detail-row">
                <strong>Status:</strong>
                <span class="badge badge-{{ $investment->status_color }}">{{ ucfirst($investment->status) }}</span>
            </div>
            <div class="detail-row">
                <strong>Date:</strong>
                <span>{{ $investment->created_at->format('M d, Y at h:i A') }}</span>
            </div>
        </div>
        
        <div class="next-steps">
            <h3>What happens next?</h3>
            <p>You will receive a confirmation email shortly. Our team will process your KYC/AML verification within 2-3 business days. You will be notified once your investment is fully approved.</p>
        </div>
        
        <div class="actions">
            <a href="{{ route('invest.status', $investment->id) }}" class="btn-primary">Check Status</a>
            <a href="{{ route('home') }}" class="btn-secondary">Return Home</a>
        </div>
    </div>
</body>
</html>
