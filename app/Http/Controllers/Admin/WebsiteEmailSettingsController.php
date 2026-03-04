<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteEmailSetting;
use Illuminate\Http\Request;

class WebsiteEmailSettingsController extends Controller
{
    public function index(Website $website)
    {
        $settings = $website->emailSettings ?: new WebsiteEmailSetting(['website_id' => $website->id]);
        return view('admin.websites.email-settings', compact('website', 'settings'));
    }

    public function store(Request $request, Website $website)
    {
        $data = $this->validateData($request);
        $data['website_id'] = $website->id;
        $settings = WebsiteEmailSetting::create($data);
        return redirect()->route('admin.website.email.index', ['website' => $website->id])
            ->with('success', 'Email settings saved successfully.');
    }

    public function update(Request $request, Website $website)
    {
        $data = $this->validateData($request);
        $settings = $website->emailSettings;
        if (!$settings) {
            $data['website_id'] = $website->id;
            $settings = WebsiteEmailSetting::create($data);
        } else {
            $settings->update($data);
        }
        return redirect()->route('admin.website.email.index', ['website' => $website->id])
            ->with('success', 'Email settings updated successfully.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'mailer' => 'required|string|in:smtp',
            'host' => 'nullable|string',
            'port' => 'nullable|integer',
            'encryption' => 'nullable|string|in:tls,ssl',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'from_address' => 'nullable|email',
            'from_name' => 'nullable|string',
            'reply_to_address' => 'nullable|email',
            'reply_to_name' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);
    }
}
