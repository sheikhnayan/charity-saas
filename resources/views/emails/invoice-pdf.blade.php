<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Invoice - {{ $transactions->first()->transaction_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.4;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        .invoice-title {
            font-size: 20px;
            margin: 10px 0;
            color: #333;
        }
        .invoice-meta {
            font-size: 12px;
            color: #666;
        }
        .details-section {
            margin: 20px 0;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dee2e6;
        }
        .details-grid {
            width: 100%;
            margin: 15px 0;
        }
        .details-row {
            display: table;
            width: 100%;
            margin: 8px 0;
        }
        .detail-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #555;
            vertical-align: top;
        }
        .detail-value {
            display: table-cell;
            width: 60%;
            color: #333;
            vertical-align: top;
        }
        .financial-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        .financial-row {
            display: table;
            width: 100%;
            margin: 8px 0;
            font-size: 14px;
        }
        .financial-label {
            display: table-cell;
            width: 70%;
            font-weight: bold;
        }
        .financial-amount {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-weight: bold;
        }
        .financial-row.total {
            border-top: 2px solid #007bff;
            padding-top: 10px;
            margin-top: 15px;
            font-size: 16px;
            color: #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        .two-column {
            width: 100%;
            margin: 20px 0;
        }
        .column {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin-right: 4%;
        }
        .column:last-child {
            margin-right: 0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">{{ $website->name ?? 'Invoice' }}</div>
            <h1 class="invoice-title">TRANSACTION INVOICE</h1>
            <div class="invoice-meta">
                Invoice #: {{ $transactions->first()->transaction_id }}<br>
                Date: {{ $transactions->first()->created_at->format('M d, Y') }}<br>
                Items: {{ $transactions->count() }}
            </div>
        </div>

        <div class="two-column">
            <div class="column">
                <div class="section-title">Transaction Details</div>
                <div class="details-grid">
                    <div class="details-row">
                        <div class="detail-label">Transaction ID:</div>
                        <div class="detail-value">{{ $transactions->first()->transaction_id }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Type:</div>
                            <div class="detail-value">
                                @if($website && $website->type === 'fundraiser')
                                    Fundraiser
                                @elseif($website && $website->type === 'investment')
                                    Investment
                                @else
                                    {{ ucfirst($transactions->first()->type) }}
                                @endif
                            </div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Payment Method:</div>
                        <div class="detail-value">{{ ctype_digit($transactions->first()->transaction_id[0]) ? 'Authorize.net' : 'Stripe' }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Website:</div>
                        <div class="detail-value">{{ $website->name ?? 'N/A' }}</div>
                    </div>
                    @if($transactions->first()->ip_address)
                    <div class="details-row">
                        <div class="detail-label">IP Address:</div>
                        <div class="detail-value">{{ $transactions->first()->ip_address }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="column">
                <div class="section-title">Customer Information</div>
                <div class="details-grid">
                    <div class="details-row">
                        <div class="detail-label">Name:</div>
                        <div class="detail-value">{{ $transactions->first()->name }} {{ $transactions->first()->last_name }}</div>
                    </div>
                    <div class="details-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">{{ $transactions->first()->email }}</div>
                    </div>
                    @if($transactions->first()->phone)
                    <div class="details-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">{{ $transactions->first()->phone }}</div>
                    </div>
                    @endif
                    @if($transactions->first()->address)
                    <div class="details-row">
                        <div class="detail-label">Address:</div>
                        <div class="detail-value">
                            @if($transactions->first()->apartment){{ $transactions->first()->apartment }}, @endif
                            {{ $transactions->first()->address }}<br>
                            {{ $transactions->first()->city }}, {{ $transactions->first()->state }} {{ $transactions->first()->zip }}<br>
                            {{ $transactions->first()->country }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="details-section">
            <div class="section-title">Items Purchased</div>
            <table style="width: 100%; border-collapse: collapse; margin: 15px 0;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                        <th style="padding: 12px; text-align: left; font-weight: bold; border: 1px solid #dee2e6;">Item Name</th>
                        <th style="padding: 12px; text-align: left; font-weight: bold; border: 1px solid #dee2e6;">Type</th>
                        <th style="padding: 12px; text-align: right; font-weight: bold; border: 1px solid #dee2e6;">Amount</th>
                        <th style="padding: 12px; text-align: right; font-weight: bold; border: 1px solid #dee2e6;">Fee</th>
                        <th style="padding: 12px; text-align: right; font-weight: bold; border: 1px solid #dee2e6;">Tip</th>
                        <th style="padding: 12px; text-align: right; font-weight: bold; border: 1px solid #dee2e6;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $idx => $transaction)
                    @php
                        // Get item name based on transaction type
                        $itemName = '';
                        if ($transaction->type === 'student') {
                            // Student: show student name and last_name via donation
                            $donation = \App\Models\Donation::find($transaction->reference_id);
                            $student = $donation ? $donation->user : null;
                            $itemName = $student ? ($student->name . ' ' . $student->last_name) : 'Student';
                        } elseif ($transaction->type === 'ticket') {
                            // Ticket: show ticket name
                            $ticket = \App\Models\Ticket::find($transaction->reference_id);
                            $itemName = $ticket ? $ticket->name : 'Ticket';
                        } elseif ($transaction->type === 'auction') {
                            // Auction: show auction name
                            $auction = \App\Models\Auction::find($transaction->reference_id);
                            $itemName = $auction ? $auction->name : 'Auction Item';
                        } elseif ($transaction->type === 'investment') {
                            // Investment: show website name
                            $itemName = $website->name ?? 'Investment';
                        } else {
                            // General: show website name
                            $itemName = $website->name ?? 'Donation';
                        }
                        
                        // Get transaction type label based on transaction type
                        $typeLabel = '';
                        if ($transaction->type === 'student' || $transaction->type === 'general' || $transaction->type === 'donation') {
                            $typeLabel = 'Donation';
                        } elseif ($transaction->type === 'investment') {
                            $typeLabel = 'Investment';
                        } elseif ($transaction->type === 'ticket') {
                            $typeLabel = 'Product Purchase';
                        } elseif ($transaction->type === 'auction') {
                            $typeLabel = 'Auction';
                        } else {
                            $typeLabel = ucfirst($transaction->type);
                        }
                    @endphp
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $itemName }}</td>
                        <td style="padding: 12px; border: 1px solid #dee2e6;">{{ $typeLabel }}</td>
                        <td style="padding: 12px; text-align: right; border: 1px solid #dee2e6;">${{ number_format($transaction->amount, 2) }}</td>
                        <td style="padding: 12px; text-align: right; border: 1px solid #dee2e6;">${{ number_format($transaction->fee ?? 0, 2) }}</td>
                        <td style="padding: 12px; text-align: right; border: 1px solid #dee2e6;">${{ number_format($transaction->tip_amount ?? 0, 2) }}</td>
                        <td style="padding: 12px; text-align: right; border: 1px solid #dee2e6; font-weight: bold;">
                            ${{ number_format(($transaction->amount + ($transaction->fee ?? 0) + ($transaction->tip_amount ?? 0)), 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 12px; text-align: center; border: 1px solid #dee2e6;">No items found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="financial-summary">
            <div class="section-title">Financial Summary</div>
            <div class="financial-row">
                <div class="financial-label">Subtotal ({{ $transactions->count() }} items):</div>
                <div class="financial-amount">${{ number_format($totalAmount, 2) }}</div>
            </div>
            @if($totalFee > 0)
            <div class="financial-row">
                <div class="financial-label">Platform Fees:</div>
                <div class="financial-amount">${{ number_format($totalFee, 2) }}</div>
            </div>
            @endif
            @if($totalTip > 0)
            <div class="financial-row">
                <div class="financial-label">Tips:</div>
                <div class="financial-amount">${{ number_format($totalTip, 2) }}</div>
            </div>
            @endif
            <div class="financial-row total">
                <div class="financial-label">Grand Total:</div>
                <div class="financial-amount">${{ number_format($grandTotal, 2) }}</div>
            </div>
        </div>

        <div class="footer">
            <p><strong>For complete transaction details of your purchase please login to your customer dashboard.</strong></p>
            <p>{{ $website->name ?? 'Organization' }} | {{ $website->domain ?? 'N/A' }}</p>
            <p>
                <small>
                    This invoice was generated on {{ now()->format('M d, Y \a\t g:i A') }}<br>
                    For questions about this transaction, please contact us at {{ config('mail.from.address', 'noreply@charity.local') }}
                </small>
            </p>
        </div>
    </div>
</body>
</html>