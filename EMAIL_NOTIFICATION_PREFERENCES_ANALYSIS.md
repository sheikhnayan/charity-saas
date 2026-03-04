# Email Notification Preferences System - Analysis & Implementation Plan

## Current System Architecture

### 1. **Contact Form Email System**
**Location**: `FrontendController::contact_form()` (app/Http/Controllers/FrontendController.php:394)
- Sends to multiple emails from `$website->contact_emails` (JSON array)
- Also sends to website owner email
- Fallback email: sheikhnayan1997@gmail.com

**Current Flow**:
```
Route: /contact-form (POST)
↓
FrontendController::contact_form()
↓
Collects emails from:
  1. $website->contact_emails (JSON array)
  2. $website->user->email (website owner)
↓
Mail::send() - sends to all collected emails
```

### 2. **Transaction/Payment Email System**
**Location**: Multiple places
- `AuthorizeNetController::processTransaction()` (line 1070)
- `AdminController::resendTransactionInvoice()` (line 1523)
- After payment success

**Current Flow**:
```
Payment Processing
↓
AuthorizeNetController::processTransaction()
↓
Mail::to($transaction->email)->send(new TransactionInvoice($transaction, $website))
↓
Only sends to: $transaction->email (donor/customer email)
```

### 3. **Database Schema**
**Website Model** (`app/Models/Website.php`):
- `contact_emails` - JSON field (already exists)
- Fillable array includes `contact_emails`
- Cast as array

**Transaction Model** (needs investigation):
- Has `email` field (customer email)
- May have other fields related to tracking

## Requirements Analysis

### Current State:
1. ✅ Contact form emails: Configurable (multiple emails supported)
2. ❌ Transaction emails: NOT configurable (only sent to customer)
3. ❌ Email preference system: NOT implemented

### Required New Features:
1. **Email Preference Checkboxes** next to email input field in website settings
2. **Options**:
   - ☐ Receive Contact Form Emails
   - ☐ Receive Transaction/Payment Emails
3. **Logic**: Email is only used if checkbox is enabled

### Use Cases:
- Admin may want ONLY contact form emails
- Admin may want ONLY transaction emails  
- Admin may want BOTH
- Admin may want NEITHER

## Implementation Plan

### Phase 1: Database Migration
**Create**: `database/migrations/YYYY_MM_DD_add_email_preferences_to_websites.php`

**Changes to `websites` table**:
```sql
ALTER TABLE websites ADD email_preferences JSON DEFAULT NULL AFTER contact_emails;
-- JSON structure:
{
  "receive_contact_form": true/false,
  "receive_transaction_emails": true/false
}
```

### Phase 2: Model Updates
**Update**: `app/Models/Website.php`
```php
protected $fillable = [
    // ... existing fields ...
    'contact_emails',
    'email_preferences',  // NEW
];

protected $casts = [
    'contact_emails' => 'array',
    'email_preferences' => 'array',  // NEW
];

// NEW: Helper methods
public function shouldReceiveContactFormEmails(): bool
{
    return $this->email_preferences['receive_contact_form'] ?? true;
}

public function shouldReceiveTransactionEmails(): bool
{
    return $this->email_preferences['receive_transaction_emails'] ?? true;
}
```

### Phase 3: Admin UI Updates
**File**: `resources/views/admin/website/edit.blade.php` (lines 160-190)

**Add after contact_emails input section**:
```blade
<div class="mb-3">
    <label class="form-label">
        <i class="bx bx-mail-send me-1"></i>Email Notification Preferences
    </label>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" 
               id="receive_contact_form" 
               name="email_preferences[receive_contact_form]" 
               value="1" 
               {{ $data->email_preferences['receive_contact_form'] ?? true ? 'checked' : '' }}>
        <label class="form-check-label" for="receive_contact_form">
            Receive Contact Form Submissions
        </label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" 
               id="receive_transaction_emails" 
               name="email_preferences[receive_transaction_emails]" 
               value="1" 
               {{ $data->email_preferences['receive_transaction_emails'] ?? true ? 'checked' : '' }}>
        <label class="form-check-label" for="receive_transaction_emails">
            Receive Transaction & Payment Emails
        </label>
    </div>
    <small class="form-text text-muted d-block mt-2">
        Control which types of emails are sent to your configured email addresses
    </small>
</div>
```

### Phase 4: Controller Updates
**File**: `app/Http/Controllers/AdminController.php` (or appropriate website controller)

**In store/update website method**:
```php
// Handle email preferences
$emailPreferences = [
    'receive_contact_form' => $request->has('email_preferences.receive_contact_form'),
    'receive_transaction_emails' => $request->has('email_preferences.receive_transaction_emails'),
];
$website->email_preferences = $emailPreferences;
```

### Phase 5: Frontend Email Logic Updates

**A. Contact Form** - `FrontendController::contact_form()` (line 394)
```php
// After collecting emails, check preference
$website = \App\Models\Website::where('domain', $host)->first();

if ($website && !$website->shouldReceiveContactFormEmails()) {
    // Don't send to contact_emails list
    // But still send to website owner? (needs clarification)
    $emails = [];
}
```

**B. Transaction Email** - `AuthorizeNetController::processTransaction()` (line 1070)
```php
// Before sending TransactionInvoice
$website = $transaction->website;

if ($website && !$website->shouldReceiveTransactionEmails()) {
    // Log but don't send
    \Log::info('Transaction email skipped: preference disabled', ['transaction_id' => $transaction->id]);
} else {
    Mail::to($transaction->email)->send(new TransactionInvoice($transaction, $website));
}
```

## Data Flow Diagram

```
CONTACT FORM SUBMISSION:
┌─────────────────────────────────────┐
│ User submits contact form            │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│ FrontendController::contact_form()   │
└──────────────┬──────────────────────┘
               │
    ┌──────────┴──────────┐
    │                     │
    ▼                     ▼
Check preference:    Check preference:
receive_contact_     receive_contact_
form_emails?         form_emails?
    │                     │
    YES                   NO
    │                     │
    ▼                     ▼
Send to contact_   Skip sending
_emails recipients  to contact_emails


TRANSACTION EMAIL:
┌─────────────────────────────────────┐
│ Payment successful                   │
└──────────────┬──────────────────────┘
               │
               ▼
┌─────────────────────────────────────┐
│ AuthorizeNetController::process()    │
└──────────────┬──────────────────────┘
               │
               ▼
    ┌──────────────────────────┐
    │ Check preference:         │
    │ receive_transaction_      │
    │ emails?                   │
    └──────────┬──────────┬────┘
               │          │
              YES         NO
               │          │
               ▼          ▼
          Send email   Skip email
          to customer  (log action)
```

## Implementation Priority

1. **Priority 1 (Critical)**:
   - Database migration
   - Model helper methods
   - Admin UI checkboxes
   - Controller form handling

2. **Priority 2 (Important)**:
   - Contact form email preference check
   - Transaction email preference check

3. **Priority 3 (Nice-to-have)**:
   - Logging of skipped emails
   - Email preference reset option
   - Bulk preference update for multiple websites

## Edge Cases to Handle

1. **Existing websites**: Use defaults (both enabled)
2. **No preferences set**: Default to `true` for both
3. **Email list empty + preference enabled**: Should still process
4. **Website owner email**: Keep sending regardless? (Clarify with user)
5. **Contact form with no emails**: Handle gracefully

## Files to Modify

1. ✏️ `database/migrations/YYYY_MM_DD_*.php` - NEW
2. ✏️ `app/Models/Website.php` - ADD methods + fillable
3. ✏️ `resources/views/admin/website/edit.blade.php` - ADD UI
4. ✏️ `resources/views/admin/website/create.blade.php` - ADD UI (if needed)
5. ✏️ `app/Http/Controllers/AdminController.php` - UPDATE store/update
6. ✏️ `app/Http/Controllers/FrontendController.php` - UPDATE contact_form()
7. ✏️ `app/Http/Controllers/AuthorizeNetController.php` - UPDATE processTransaction()

## Testing Checklist

- [ ] Create website with both preferences enabled
- [ ] Create website with only contact form enabled
- [ ] Create website with only transaction emails enabled
- [ ] Create website with both disabled
- [ ] Test contact form submission with each preference combo
- [ ] Test transaction email with each preference combo
- [ ] Test existing websites (should default to enabled)
- [ ] Test email list with special characters
- [ ] Test multiple contact emails with preferences
