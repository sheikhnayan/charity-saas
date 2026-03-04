/**
 * Investment Platform JavaScript
 * Handles form submissions, validation, and API interactions
 */

class InvestmentPlatform {
    constructor() {
        this.initializeEventListeners();
        this.initializeFormValidation();
    }

    initializeEventListeners() {
        // Investment form submission
        const investmentForm = document.getElementById('investment-form');
        if (investmentForm) {
            investmentForm.addEventListener('submit', this.handleInvestmentSubmission.bind(this));
        }

        // Contact form submission
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            contactForm.addEventListener('submit', this.handleContactSubmission.bind(this));
        }

        // Investment amount calculation
        const amountInput = document.getElementById('investment-amount');
        if (amountInput) {
            amountInput.addEventListener('input', this.calculateShares.bind(this));
        }

        // Investment status check
        const statusButton = document.getElementById('check-status');
        if (statusButton) {
            statusButton.addEventListener('click', this.checkInvestmentStatus.bind(this));
        }
    }

    initializeFormValidation() {
        // Basic form validation setup
        const forms = document.querySelectorAll('form[data-validate]');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', this.validateField.bind(this));
            });
        });
    }

    async handleInvestmentSubmission(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        
        // Disable submit button and show loading
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';
        
        try {
            // Use our local investment processing route
            const response = await fetch('/invest/save-info', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Redirect to thank you page
                window.location.href = `/invest/thank-you?id=${result.investment_id}`;
            } else {
                this.showError('Failed to process investment: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Investment submission error:', error);
            this.showError('An error occurred while processing your investment. Please try again.');
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = 'Invest Now';
        }
    }

    async handleContactSubmission(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        
        submitButton.disabled = true;
        submitButton.textContent = 'Sending...';
        
        try {
            const response = await fetch('/invest/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess(result.message);
                form.reset();
            } else {
                this.showError('Failed to send message: ' + result.message);
            }
        } catch (error) {
            console.error('Contact submission error:', error);
            this.showError('An error occurred while sending your message.');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Send Message';
        }
    }

    calculateShares() {
        const amountInput = document.getElementById('investment-amount');
        const sharesDisplay = document.getElementById('shares-display');
        const sharePrice = window.checkoutSettings?.sharePrice || 2.13;
        
        if (amountInput && sharesDisplay) {
            const amount = parseFloat(amountInput.value) || 0;
            const shares = Math.floor(amount / sharePrice);
            sharesDisplay.textContent = shares.toLocaleString();
        }
    }

    async checkInvestmentStatus() {
        const investmentId = document.getElementById('investment-id')?.value;
        
        if (!investmentId) {
            this.showError('Please enter your investment ID');
            return;
        }
        
        try {
            const response = await fetch(`/invest/status/${investmentId}`);
            const result = await response.json();
            
            if (response.ok) {
                this.displayInvestmentStatus(result);
            } else {
                this.showError(result.error || 'Investment not found');
            }
        } catch (error) {
            console.error('Status check error:', error);
            this.showError('Error checking investment status');
        }
    }

    displayInvestmentStatus(status) {
        const statusContainer = document.getElementById('status-display');
        if (statusContainer) {
            statusContainer.innerHTML = `
                <div class="status-card">
                    <h3>Investment Status</h3>
                    <div class="status-row">
                        <span>Status:</span>
                        <span class="badge badge-${this.getStatusColor(status.status)}">${status.status}</span>
                    </div>
                    <div class="status-row">
                        <span>Amount:</span>
                        <span>${status.amount}</span>
                    </div>
                    <div class="status-row">
                        <span>Shares:</span>
                        <span>${status.shares}</span>
                    </div>
                    <div class="status-row">
                        <span>KYC Status:</span>
                        <span class="badge badge-${this.getStatusColor(status.kyc_status)}">${status.kyc_status}</span>
                    </div>
                    <div class="status-row">
                        <span>AML Status:</span>
                        <span class="badge badge-${this.getStatusColor(status.aml_status)}">${status.aml_status}</span>
                    </div>
                </div>
            `;
        }
    }

    getStatusColor(status) {
        switch (status.toLowerCase()) {
            case 'completed':
            case 'approved':
                return 'success';
            case 'processing':
            case 'pending':
                return 'warning';
            case 'failed':
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        const fieldName = field.name;
        
        // Remove existing error messages
        this.clearFieldError(field);
        
        // Validation rules
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'This field is required');
            return false;
        }
        
        if (field.type === 'email' && value && !this.isValidEmail(value)) {
            this.showFieldError(field, 'Please enter a valid email address');
            return false;
        }
        
        if (fieldName === 'investment_amount' && value) {
            const amount = parseFloat(value);
            const minInvestment = window.checkoutSettings?.minInvestment || 1001.10;
            if (amount < minInvestment) {
                this.showFieldError(field, `Minimum investment is $${minInvestment}`);
                return false;
            }
        }
        
        return true;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    showFieldError(field, message) {
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        field.parentNode.appendChild(errorElement);
        field.classList.add('error');
    }

    clearFieldError(field) {
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
        field.classList.remove('error');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showNotification(message, type = 'info') {
        // Create or update notification element
        let notification = document.getElementById('notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            document.body.appendChild(notification);
        }
        
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new InvestmentPlatform();
});

// Additional utility functions for investment calculations
window.InvestmentUtils = {
    formatCurrency: function(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    formatNumber: function(number) {
        return new Intl.NumberFormat('en-US').format(number);
    },
    
    calculateTransactionFee: function(amount, feePercentage = 0.015) {
        return amount * feePercentage;
    },
    
    calculateTotalInvestment: function(amount, feePercentage = 0.015) {
        return amount + this.calculateTransactionFee(amount, feePercentage);
    }
};
