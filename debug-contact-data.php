<?php
// Quick debug script to test contact top bar data saving
// Place this in your Laravel routes/web.php temporarily to test

// Add this route to test data saving
/*
Route::get('/debug-contact-data/{header_id}', function($header_id) {
    $header = \App\Models\Header::find($header_id);
    
    if (!$header) {
        return response()->json(['error' => 'Header not found'], 404);
    }
    
    $contactData = [
        'show_contact_topbar' => $header->show_contact_topbar ?? 'null',
        'contact_phone' => $header->contact_phone ?? 'null',
        'contact_email' => $header->contact_email ?? 'null',
        'contact_address' => $header->contact_address ?? 'null',
        'contact_cta_text' => $header->contact_cta_text ?? 'null',
        'contact_cta_url' => $header->contact_cta_url ?? 'null',
        'contact_topbar_bg_color' => $header->contact_topbar_bg_color ?? 'null',
        'contact_topbar_text_color' => $header->contact_topbar_text_color ?? 'null',
        'contact_cta_bg_color' => $header->contact_cta_bg_color ?? 'null',
        'contact_cta_text_color' => $header->contact_cta_text_color ?? 'null',
    ];
    
    return response()->json([
        'header_id' => $header_id,
        'website_id' => $header->website_id,
        'contact_data' => $contactData,
        'all_fields' => $header->toArray()
    ], 200, [], JSON_PRETTY_PRINT);
});
*/

// Usage: Visit /debug-contact-data/{header_id} in your browser
// Replace {header_id} with the actual header ID you're testing
// This will show you exactly what data is stored in the database

// Example: http://localhost/charity/debug-contact-data/1