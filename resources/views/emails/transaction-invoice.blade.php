<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .invoice-container { background: white; max-width: 800px; margin: 0 auto; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #007bff; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #007bff; }
        .invoice-details { margin: 20px 0; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
        .detail-section { background: #f8f9fa; padding: 15px; border-radius: 5px; }
        .detail-section h3 { margin: 0 0 10px 0; color: #333; font-size: 16px; }
        .detail-row { margin: 8px 0; display: flex; justify-content: space-between; }
        .detail-label { font-weight: bold; color: #555; }
        .detail-value { color: #333; }
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th { background: #007bff; color: white; padding: 12px; text-align: left; }
        .items-table td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .items-table tr:hover { background: #f8f9fa; }
        .financial-summary { background: #e9ecef; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .financial-row { display: flex; justify-content: space-between; margin: 10px 0; font-size: 16px; }
        .financial-row.total { border-top: 2px solid #007bff; padding-top: 10px; font-weight: bold; font-size: 18px; color: #007bff; }
        .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #6c757d; }
        @media print { body { background-color: white; } .invoice-container { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <div class="logo">{{ $website->name ?? 'Charity Platform' }}</div>
            @php
                $firstTransaction = $transactions->first();
                $multipleItems = $transactions->count() > 1;
                
                // Get type label for header
                $typeLabel = '';
                if ($website->type === 'fundraiser') {
                    $typeLabel = 'Fundraise';
                } elseif ($website->type === 'investment') {
                    $typeLabel = 'Investment';
                } else {
                    $typeLabel = ucfirst($firstTransaction->type);
                }
            @endphp
            <h2>{{ $multipleItems ? 'Multi-Item Invoice' : ($typeLabel . ' Receipt') }}</h2>
            <p>Transaction #{{ $firstTransaction->transaction_id }}</p>
        </div>

        <div class="invoice-details">
            <div class="details-grid">
                <div class="detail-section">
                    <h3>Transaction Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Transaction ID:</span>
                        <span class="detail-value">{{ $firstTransaction->transaction_id }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">{{ $firstTransaction->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Items:</span>
                        <span class="detail-value">{{ $transactions->count() }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">{{ $firstTransaction->status == 1 ? 'Approved' : 'Pending' }}</span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Customer Information</h3>
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value">{{ $firstTransaction->name }} {{ $firstTransaction->last_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $firstTransaction->email }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $firstTransaction->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Address:</span>
                        <span class="detail-value">
                            {{ $firstTransaction->address }}<br>
                            {{ $firstTransaction->city }}, {{ $firstTransaction->state }} {{ $firstTransaction->zip }}<br>
                            {{ $firstTransaction->country }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Items Summary Table -->
            @if($transactions->count() > 0)
            <div class="detail-section">
                <h3>Items Summary</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Tip</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $idx => $transaction)
                        @php
                            // Get item name based on transaction type
                            $itemName = '';
                            if ($transaction->type === 'student') {
                                // Student: show student name and last_name
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
                        <tr>
                            <td>{{ $itemName }}</td>
                            <td>{{ $typeLabel }}</td>
                            <td>${{ number_format($transaction->amount, 2) }}</td>
                            <td>${{ number_format($transaction->fee ?? 0, 2) }}</td>
                            <td>${{ number_format($transaction->tip_amount ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $itemTotal = $transaction->amount + ($transaction->fee ?? 0) + ($transaction->tip_amount ?? 0);
                                @endphp
                                <strong>${{ number_format($itemTotal, 2) }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div class="financial-summary">
                <h3>Financial Summary</h3>
                <div class="financial-row">
                    <span>Subtotal ({{ $transactions->count() }} {{ $transactions->count() == 1 ? 'item' : 'items' }}):</span>
                    <span>${{ number_format($totalAmount ?? 0, 2) }}</span>
                </div>
                @if(($totalFee ?? 0) > 0)
                <div class="financial-row">
                    <span>Platform Fees:</span>
                    <span>${{ number_format($totalFee ?? 0, 2) }}</span>
                </div>
                @endif
                @if(($totalTip ?? 0) > 0)
                <div class="financial-row">
                    <span>Tips:</span>
                    <span>${{ number_format($totalTip ?? 0, 2) }}</span>
                </div>
                @endif
                <div class="financial-row total">
                    <span>Total Paid:</span>
                    <span>${{ number_format($grandTotal ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>📎 A detailed PDF receipt/confirmation is attached to this email for your records.</strong></p>
            <p>{{ $website->name ?? 'Charity Platform' }} | {{ $website->domain ?? '' }}</p>
            <p><small>This is a computer-generated receipt/confirmation. If you have any questions, please contact us at {{ config('mail.from.address', 'noreply@charity.local') }}</small></p>
        </div>
    </div>
</body>
</html>