<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - {{ $website->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: {{ $website->colors['primary'] ?? '#007bff' }};
            --accent-color: {{ $website->colors['accent'] ?? '#28a745' }};
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .thankyou-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            animation: slideUp 0.6s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: scaleIn 0.5s ease 0.3s backwards;
        }
        
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
        
        .success-icon i {
            font-size: 50px;
            color: white;
        }
        
        h1 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        
        .subtitle {
            color: #666;
            font-size: 18px;
            margin-bottom: 40px;
        }
        
        .donation-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            padding-top: 15px;
            margin-top: 10px;
            border-top: 2px solid #dee2e6;
            font-weight: 600;
            font-size: 18px;
        }
        
        .detail-label {
            color: #666;
            font-weight: 500;
        }
        
        .detail-value {
            font-weight: 600;
            color: #333;
        }
        
        .total-amount {
            color: var(--accent-color);
            font-size: 24px;
        }
        
        .transaction-id {
            background: #e7f3ff;
            border: 1px dashed #007bff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 30px;
            font-family: monospace;
            font-size: 14px;
        }
        
        .btn-home {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            color: white;
        }
        
        .receipt-note {
            color: #888;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="thankyou-card">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1>Thank You!</h1>
        <p class="subtitle">Your donation has been processed successfully</p>
        
        <div class="donation-details">
            <div class="detail-row">
                <span class="detail-label">Name</span>
                <span class="detail-value">{{ $donation->first_name }} {{ $donation->last_name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $donation->email }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Donation Type</span>
                <span class="detail-value">{{ ucfirst($donation->type) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Base Amount</span>
                <span class="detail-value">${{ number_format($donation->amount, 2) }}</span>
            </div>
            
            @if($donation->tip_amount > 0)
            <div class="detail-row">
                <span class="detail-label">Tip ({{ $donation->tip_percentage }}%)</span>
                <span class="detail-value">${{ number_format($donation->tip_amount, 2) }}</span>
            </div>
            @endif
            
            <div class="detail-row">
                <span class="detail-label">Total Amount</span>
                <span class="detail-value total-amount">${{ number_format($donation->amount + ($donation->tip_amount ?? 0), 2) }}</span>
            </div>
        </div>
        
        @if($donation->transaction_id)
        <div class="transaction-id">
            <strong>Transaction ID:</strong> {{ $donation->transaction_id }}
        </div>
        @endif
        
        <a href="https://{{ $website->domain }}" class="btn-home">
            <i class="fas fa-home me-2"></i> Return to Home
        </a>
        
        <p class="receipt-note">
            <i class="fas fa-envelope me-1"></i> A receipt has been sent to {{ $donation->email }}
        </p>
    </div>
</body>
</html>
