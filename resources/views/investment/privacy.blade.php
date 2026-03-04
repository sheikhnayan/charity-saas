<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <link href="{{ asset('investment/css/main.min.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .privacy-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .privacy-content {
            line-height: 1.6;
        }
        .privacy-content h2 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .privacy-content h3 {
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
    <div class="privacy-container">
        <a href="javascript:history.back()" class="back-btn">← Back</a>
        
        <h1>Privacy Policy</h1>
        
        <div class="privacy-content">
            <h2>1. Information We Collect</h2>
            <h3>1.1 Personal Information</h3>
            <p>We collect personal information that you provide directly to us, including:</p>
            <ul>
                <li>Name and contact information</li>
                <li>Financial information for investment purposes</li>
                <li>Identity verification documents</li>
                <li>Investment preferences and history</li>
            </ul>
            
            <h3>1.2 Automatically Collected Information</h3>
            <p>We automatically collect certain information when you use our platform:</p>
            <ul>
                <li>IP address and device information</li>
                <li>Usage data and analytics</li>
                <li>Cookies and similar tracking technologies</li>
            </ul>
            
            <h2>2. How We Use Your Information</h2>
            <p>We use your information for the following purposes:</p>
            <ul>
                <li>Processing and managing your investments</li>
                <li>KYC/AML compliance and verification</li>
                <li>Providing customer support</li>
                <li>Sending important updates and communications</li>
                <li>Improving our services</li>
            </ul>
            
            <h2>3. Information Sharing</h2>
            <h3>3.1 Service Providers</h3>
            <p>We may share your information with trusted third-party service providers who assist us in:</p>
            <ul>
                <li>Payment processing</li>
                <li>Identity verification</li>
                <li>Technology infrastructure</li>
                <li>Legal and compliance services</li>
            </ul>
            
            <h3>3.2 Legal Requirements</h3>
            <p>We may disclose your information when required by law or to protect our rights and the safety of our users.</p>
            
            <h2>4. Data Security</h2>
            <p>We implement industry-standard security measures to protect your personal information, including:</p>
            <ul>
                <li>Encryption of sensitive data</li>
                <li>Secure data transmission protocols</li>
                <li>Access controls and authentication</li>
                <li>Regular security audits</li>
            </ul>
            
            <h2>5. Data Retention</h2>
            <p>We retain your personal information for as long as necessary to fulfill the purposes outlined in this policy and to comply with legal obligations.</p>
            
            <h2>6. Your Rights</h2>
            <p>Depending on your jurisdiction, you may have the following rights:</p>
            <ul>
                <li>Access to your personal information</li>
                <li>Correction of inaccurate information</li>
                <li>Deletion of your information (subject to legal requirements)</li>
                <li>Portability of your data</li>
                <li>Opt-out of certain communications</li>
            </ul>
            
            <h2>7. Cookies Policy</h2>
            <p>We use cookies to enhance your experience on our platform. You can control cookie settings through your browser preferences.</p>
            
            <h2>8. International Transfers</h2>
            <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers.</p>
            
            <h2>9. Children's Privacy</h2>
            <p>Our services are not intended for individuals under 18 years of age. We do not knowingly collect personal information from children.</p>
            
            <h2>10. Changes to This Policy</h2>
            <p>We may update this privacy policy from time to time. We will notify you of significant changes through email or prominent notices on our platform.</p>
            
            <h2>11. Contact Us</h2>
            <p>If you have questions about this privacy policy or our data practices, please contact our privacy team.</p>
            
            <p><strong>Last updated:</strong> {{ date('F d, Y') }}</p>
        </div>
    </div>
</body>
</html>
