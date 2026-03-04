# Investment Form Data Collection Flow

## Overview
This system implements a multi-step form data collection process where:
1. **Data Collection**: Form data is automatically stored client-side as users interact with forms on the website
2. **Data Persistence**: All collected data is saved in localStorage and persists across page reloads
3. **Final Submission**: When investor information is submitted, ALL collected data is sent together to the backend

## How It Works

### 1. Data Collection (Automatic)
- Any form input on pages built with the page builder automatically triggers data storage
- Data is stored in `localStorage` with a unique form identifier
- Visual indicators show users when their data is saved
- No server requests until final submission

### 2. Data Storage Structure
```javascript
{
  "form_1": {
    "name": "John Doe",
    "interest": "Technology"
  },
  "form_2": {
    "phone": "555-1234",
    "amount": "5000"
  }
}
```

### 3. Final Submission
When the investor submits their information:
- All previously collected form data is bundled together
- Sent to `/invest/save-info` endpoint
- Stored in the `investor_data` JSON field in the database
- localStorage is cleared on successful submission

## Usage Examples

### For Page Builder Forms
Forms created in the page builder automatically participate in data collection. No additional code needed.

### For Custom Forms
Add the `no-store` class to prevent data collection:
```html
<form class="no-store">
  <!-- This form won't be stored -->
</form>
```

### Accessing Stored Data (JavaScript)
```javascript
// Get all stored data
const allData = window.formDataStorage.getAllData();

// Get specific form data
const formData = window.formDataStorage.getFormData('form_1');

// Clear all data
window.formDataStorage.clearFormData();
```

## Error Handling

### "Something Wrong" Error
**Issue**: `dd($request->all());` in controller was dumping data instead of processing it.
**Solution**: Replaced with proper try-catch error handling and JSON responses.

### Validation Errors
The system now returns proper JSON responses with validation errors:
```json
{
  "success": false,
  "errors": {
    "investor_email": ["The investor email field is required."]
  },
  "message": "Validation failed"
}
```

## Database Storage

Form data is stored in the `investments` table:
- `investor_data` (JSON): Contains all collected form data plus investor information
- `investment_amount`: The investment amount
- `investor_name`, `investor_email`, `investor_phone`: Direct fields for core investor info

## Frontend Integration

### Investment Page (`invest.blade.php`)
- Displays summary of previously collected data
- Handles final submission with all collected data
- Shows submission status and success/error messages

### Website Pages (`page-investment.blade.php`)
- Automatically initializes form data collection
- Shows subtle save indicators
- Works with any form on the page

## Benefits

1. **Better UX**: Users don't lose their progress
2. **Complete Data**: All form interactions are captured
3. **Single Submission**: One final submit with all data
4. **Persistent**: Data survives page reloads and navigation
5. **Automatic**: No additional coding required for forms

## Security Considerations

- Data stored client-side only until submission
- CSRF protection on all submissions
- Input validation on server-side
- JSON data sanitization before database storage