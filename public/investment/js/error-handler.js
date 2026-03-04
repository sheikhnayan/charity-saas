/**
 * Investment Platform Error Handler
 * Handles JavaScript errors gracefully
 */

// Global error handler
window.addEventListener('error', function(event) {
    console.warn('JavaScript Error:', {
        message: event.message,
        filename: event.filename,
        lineno: event.lineno,
        colno: event.colno,
        error: event.error
    });
    
    // Prevent errors from breaking the page
    if (event.message.includes('lockdown-install')) {
        event.preventDefault();
        return true;
    }
    
    if (event.message.includes('wording')) {
        event.preventDefault();
        // Ensure wording object exists
        if (window.checkoutSettings && !window.checkoutSettings.wording) {
            window.checkoutSettings.wording = {
                price: 'Share price',
                perks: 'Shares',
                shares: 'Shares'
            };
        }
        return true;
    }
});

// Handle unhandled promise rejections
window.addEventListener('unhandledrejection', function(event) {
    console.warn('Unhandled Promise Rejection:', event.reason);
    event.preventDefault();
});

// Initialize error protection
document.addEventListener('DOMContentLoaded', function() {
    // Ensure checkout settings exist
    if (typeof window.checkoutSettings === 'undefined') {
        window.checkoutSettings = {
            dealId: null,
            disableRadioButtons: false,
            investmentTiers: [1000, 2500, 5000, 10000],
            sharePrice: 1.00,
            minInvestment: 1000.00,
            sharePriceMinFractionDigits: 2,
            sharePriceMaxFractionDigits: 2,
            disclaimer: '*All bonus shares (if any) will be issued after the completion or termination of this Offering.',
            resumeInvestmentText: 'Already started an investment in this round?',
            enableManaged: true,
            adjustScrollOfset: 90,
            wording: {
                price: 'Share price',
                perks: 'Shares',
                shares: 'Shares'
            },
            logoUrl: '/investment/images/default-logo.png',
            buttonColor: '#007bff',
            brandColor: '#000',
            companyName: 'Investment Platform',
            companyEmail: 'invest@company.com'
        };
    }
    
    // Ensure wording object exists
    if (window.checkoutSettings && !window.checkoutSettings.wording) {
        window.checkoutSettings.wording = {
            price: 'Share price',
            perks: 'Shares',
            shares: 'Shares'
        };
    }
    
    console.log('Error handler initialized, checkout settings verified');
});
