<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .terms-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .terms-content {
            line-height: 1.6;
        }
        .terms-content h2 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .terms-content h3 {
            color: #555;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .back-btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="terms-container">
        <a href="javascript:history.back()" class="back-btn">← Back</a>
        
        <h1>Terms and Conditions</h1>
        
        <div class="terms-content">
            <h2>1. Investment Overview</h2>
            <p>This investment opportunity is offered subject to compliance with federal and state securities laws. All investments carry risk and you may lose some or all of your investment.</p>
            
            <h2>2. Eligibility Requirements</h2>
            <h3>2.1 Accredited Investor Status</h3>
            <p>Certain investment opportunities may be limited to accredited investors as defined by SEC regulations.</p>
            
            <h3>2.2 Geographic Restrictions</h3>
            <p>This offering may not be available to residents of certain states or countries due to regulatory restrictions.</p>
            
            <h2>3. Know Your Customer (KYC) and Anti-Money Laundering (AML)</h2>
            <p>As required by federal regulations, all investors must complete KYC and AML verification processes. This includes:</p>
            <ul>
                <li>Identity verification</li>
                <li>Address verification</li>
                <li>Source of funds verification</li>
                <li>Background screening</li>
            </ul>
            
            <h2>4. Investment Terms</h2>
            <h3>4.1 Minimum Investment</h3>
            <p>The minimum investment amount is specified in the offering materials and may be subject to transaction fees.</p>
            
            <h3>4.2 Lock-up Period</h3>
            <p>Investments may be subject to lock-up periods during which shares cannot be transferred or sold.</p>
            
            <h2>5. Risk Disclosures</h2>
            <p>Investment in securities involves significant risk and may result in partial or total loss of your investment. Key risks include:</p>
            <ul>
                <li>Market risk</li>
                <li>Liquidity risk</li>
                <li>Business risk</li>
                <li>Regulatory risk</li>
            </ul>
            
            <h2>6. Data Privacy</h2>
            <p>Your personal and financial information will be handled in accordance with our Privacy Policy and applicable data protection laws.</p>
            
            <h2>7. Dispute Resolution</h2>
            <p>Any disputes arising from this investment will be subject to binding arbitration in accordance with the rules of the American Arbitration Association.</p>
            
            <h2>8. Governing Law</h2>
            <p>These terms are governed by the laws of the jurisdiction where the company is incorporated.</p>
            
            <h2>9. Contact Information</h2>
            <p>For questions about these terms or your investment, please contact our investor relations team.</p>
            
            <p><strong>Last updated:</strong> {{ date('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>
