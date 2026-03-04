/**
 * Payment Funnel Tracking JavaScript
 * Tracks user interactions through donation forms
 */

class PaymentFunnelTracker {
    constructor() {
        this.baseUrl = window.location.origin;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.formType = null;
        this.trackedSteps = new Set();
        
        this.init();
    }

    init() {
        // Auto-detect form type
        this.detectFormType();
        
        // Track form view immediately
        if (this.formType) {
            this.trackFormView();
        }
        
        // Set up event listeners
        this.setupEventListeners();
    }

    detectFormType() {
        // Check URL patterns and form elements to detect form type
        const url = window.location.pathname;
        
        if (url.includes('student') || document.querySelector('#student-donation-form')) {
            this.formType = 'student';
        } else if (url.includes('general') || document.querySelector('#general-donation-form')) {
            this.formType = 'general';
        } else if (url.includes('ticket') || document.querySelector('#ticket-form')) {
            this.formType = 'ticket';
        } else if (url.includes('auction') || document.querySelector('#auction-form')) {
            this.formType = 'auction';
        } else if (url.includes('investment') || document.querySelector('#investment-form')) {
            this.formType = 'investment';
        } else if (document.querySelector('input[name="donation_amount"]') || document.querySelector('input[name="amount"]')) {
            this.formType = 'general'; // Default for donation forms
        }
    }

    setupEventListeners() {
        // Track amount entry
        this.setupAmountTracking();
        
        // Track personal info interactions
        this.setupPersonalInfoTracking();
        
        // Track form submission
        this.setupFormSubmissionTracking();
        
        // Track button clicks
        this.setupButtonTracking();
    }

    setupAmountTracking() {
        // Amount input fields
        const amountInputs = document.querySelectorAll('input[name="donation_amount"], input[name="amount"], input[name="ticket_quantity"]');
        
        amountInputs.forEach(input => {
            let timeoutId;
            
            input.addEventListener('input', (e) => {
                clearTimeout(timeoutId);
                
                // Debounce the tracking to avoid too many events
                timeoutId = setTimeout(() => {
                    const amount = parseFloat(e.target.value);
                    
                    if (amount > 0 && !this.trackedSteps.has('amount_entered')) {
                        this.trackAmountEntered(amount);
                        this.trackedSteps.add('amount_entered');
                    }
                }, 1000); // Wait 1 second after user stops typing
            });
        });

        // Amount preset buttons
        const amountButtons = document.querySelectorAll('.amount-btn, .donation-amount-btn, [data-amount]');
        
        amountButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const amount = parseFloat(e.target.dataset.amount || e.target.textContent.replace(/[^0-9.]/g, ''));
                
                if (amount > 0 && !this.trackedSteps.has('amount_entered')) {
                    this.trackAmountEntered(amount);
                    this.trackedSteps.add('amount_entered');
                }
            });
        });
    }

    setupPersonalInfoTracking() {
        const personalInfoFields = document.querySelectorAll('input[name="first_name"], input[name="last_name"], input[name="email"], input[name="phone"]');
        
        let personalInfoStarted = false;
        
        personalInfoFields.forEach(field => {
            // Track when user starts filling personal info
            field.addEventListener('focus', () => {
                if (!personalInfoStarted && !this.trackedSteps.has('personal_info_started')) {
                    this.trackPersonalInfoStarted();
                    personalInfoStarted = true;
                    this.trackedSteps.add('personal_info_started');
                }
            });
            
            // Track when fields are filled
            field.addEventListener('blur', () => {
                this.checkPersonalInfoCompletion();
            });
        });
    }

    setupFormSubmissionTracking() {
        // Track form submissions (payment initiation)
        const forms = document.querySelectorAll('form[action*="donation"], form[action*="ticket"], form[action*="authorize"]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                // Don't prevent submission, just track it
                this.trackPaymentInitiated(form);
            });
        });
    }

    setupButtonTracking() {
        // Track specific button clicks
        const donateButtons = document.querySelectorAll('button[type="submit"], input[type="submit"], .donate-btn, .submit-btn');
        
        donateButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                // Track payment initiation on button click
                const form = button.closest('form');
                if (form && !this.trackedSteps.has('payment_initiated')) {
                    this.trackPaymentInitiated(form);
                    this.trackedSteps.add('payment_initiated');
                }
            });
        });
    }

    checkPersonalInfoCompletion() {
        const firstName = document.querySelector('input[name="first_name"]')?.value;
        const lastName = document.querySelector('input[name="last_name"]')?.value;
        const email = document.querySelector('input[name="email"]')?.value;
        
        if (firstName && lastName && email && !this.trackedSteps.has('personal_info_completed')) {
            this.trackPersonalInfoCompleted({
                first_name: firstName,
                last_name: lastName,
                email: email,
                phone: document.querySelector('input[name="phone"]')?.value || null
            });
            this.trackedSteps.add('personal_info_completed');
        }
    }

    // API Methods
    async trackFormView() {
        try {
            await this.sendTrackingEvent('/api/track-funnel', {
                step: 'form_view',
                form_type: this.formType
            });
        } catch (error) {
            console.warn('Failed to track form view:', error);
        }
    }

    async trackAmountEntered(amount) {
        try {
            await this.sendTrackingEvent('/api/track-funnel', {
                step: 'amount_entered',
                form_type: this.formType,
                amount: amount,
                fee_option: document.querySelector('input[name="fee_option"]:checked')?.value || null
            });
        } catch (error) {
            console.warn('Failed to track amount entered:', error);
        }
    }

    async trackPersonalInfoStarted() {
        try {
            await this.sendTrackingEvent('/api/track-funnel', {
                step: 'personal_info_started',
                form_type: this.formType,
                first_name: document.querySelector('input[name="first_name"]')?.value || null,
                last_name: document.querySelector('input[name="last_name"]')?.value || null,
                email: document.querySelector('input[name="email"]')?.value || null
            });
        } catch (error) {
            console.warn('Failed to track personal info started:', error);
        }
    }

    async trackPersonalInfoCompleted(formData) {
        try {
            await this.sendTrackingEvent('/api/track-funnel', {
                step: 'personal_info_completed',
                form_type: this.formType,
                ...formData
            });
        } catch (error) {
            console.warn('Failed to track personal info completed:', error);
        }
    }

    async trackPaymentInitiated(form) {
        try {
            const formData = new FormData(form);
            const amount = formData.get('donation_amount') || formData.get('amount') || 0;
            
            await this.sendTrackingEvent('/api/track-funnel', {
                step: 'payment_initiated',
                form_type: this.formType,
                amount: parseFloat(amount),
                payment_method: this.detectPaymentMethod(form),
                user_id: formData.get('student_id') || null
            });
        } catch (error) {
            console.warn('Failed to track payment initiated:', error);
        }
    }

    detectPaymentMethod(form) {
        const action = form.getAttribute('action') || '';
        
        if (action.includes('stripe')) {
            return 'stripe';
        } else if (action.includes('authorize')) {
            return 'authorize_net';
        }
        
        return 'unknown';
    }

    async sendTrackingEvent(endpoint, data) {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }

    // Public methods for manual tracking
    static trackPaymentCompleted(transactionId, amount, paymentMethod) {
        const tracker = new PaymentFunnelTracker();
        tracker.sendTrackingEvent('/api/track-funnel', {
            step: 'payment_completed',
            form_type: tracker.formType,
            amount: amount,
            payment_method: paymentMethod,
            transaction_id: transactionId
        });
    }

    static trackPaymentFailed(errorMessage, amount, paymentMethod) {
        const tracker = new PaymentFunnelTracker();
        tracker.sendTrackingEvent('/api/track-funnel', {
            step: 'payment_failed',
            form_type: tracker.formType,
            amount: amount,
            payment_method: paymentMethod,
            error_message: errorMessage
        });
    }
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.paymentFunnelTracker = new PaymentFunnelTracker();
    });
} else {
    window.paymentFunnelTracker = new PaymentFunnelTracker();
}

// Export for use in other scripts
window.PaymentFunnelTracker = PaymentFunnelTracker;