<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\WebsitePaymentSetting;
use App\Services\PaymentGatewayService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WebsitePaymentController extends Controller
{
    protected $paymentGatewayService;

    public function __construct(PaymentGatewayService $paymentGatewayService)
    {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    /**
     * Display payment settings for a website
     */
    public function show(Website $website)
    {
        // Check if user has access to this website
        // if (Auth::user()->id !== $website->user_id && !Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access to website payment settings');
        // }

        $paymentSettings = $website->paymentSettings;
        
        return view('admin.website.payment-settings', compact('website', 'paymentSettings'));
    }

    /**
     * Update payment settings for a website
     */
    public function update(Request $request, Website $website)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:stripe,authorize',
            'fee' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'stripe_publishable_key' => 'required_if:payment_method,stripe',
            'stripe_secret_key' => 'required_if:payment_method,stripe',
            'stripe_webhook_secret' => 'nullable|string',
            'authorize_login_id' => 'required_if:payment_method,authorize',
            'authorize_transaction_key' => 'required_if:payment_method,authorize',
            'authorize_sandbox' => 'boolean',
            'coinbase_enabled' => 'boolean',
            'coinbase_api_key' => 'required_if:coinbase_enabled,true',
            'coinbase_webhook_secret' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create or update payment settings
        $paymentSettings = $website->paymentSettings ?: new WebsitePaymentSetting();
        $paymentSettings->website_id = $website->id;
        $paymentSettings->payment_method = $request->payment_method;
        $paymentSettings->fee = $request->fee;
        $paymentSettings->is_active = $request->has('is_active');

        // Handle primary payment method (Stripe or Authorize.net)
        if ($request->payment_method === 'stripe') {
            $paymentSettings->stripe_publishable_key = $request->stripe_publishable_key;
            $paymentSettings->stripe_secret_key = $request->stripe_secret_key;
            $paymentSettings->stripe_webhook_secret = $request->stripe_webhook_secret;
            
            // Clear authorize fields when switching to stripe
            $paymentSettings->authorize_login_id = null;
            $paymentSettings->authorize_transaction_key = null;
        } else {
            // Authorize.net
            $paymentSettings->authorize_login_id = $request->authorize_login_id;
            $paymentSettings->authorize_transaction_key = $request->authorize_transaction_key;
            $paymentSettings->authorize_sandbox = $request->has('authorize_sandbox');
            
            // Clear stripe fields when switching to authorize
            $paymentSettings->stripe_publishable_key = null;
            $paymentSettings->stripe_secret_key = null;
            $paymentSettings->stripe_webhook_secret = null;
        }

        // Handle Coinbase (available alongside primary method)
        $paymentSettings->coinbase_enabled = $request->has('coinbase_enabled');
        if ($paymentSettings->coinbase_enabled) {
            $paymentSettings->coinbase_api_key = $request->coinbase_api_key;
            $paymentSettings->coinbase_webhook_secret = $request->coinbase_webhook_secret;
        } else {
            // Clear coinbase fields if disabled
            $paymentSettings->coinbase_api_key = null;
            $paymentSettings->coinbase_webhook_secret = null;
        }

        // Handle Tipping option
        $paymentSettings->tipping_enabled = $request->has('tipping_enabled');

        $paymentSettings->save();

        return back()->with('success', 'Payment settings updated successfully');
    }

    /**
     * Test payment gateway connection
     */
    public function test(Website $website)
    {
        // Check if user has access to this website
        // if (Auth::user()->id !== $website->user_id && !Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access to website payment settings');
        // }

        $result = $this->paymentGatewayService->testPaymentGateway($website);
        
        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'details' => $result['details'] ?? []
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }
    }

    /**
     * Delete payment settings
     */
    public function destroy(Website $website)
    {
        // Check if user has access to this website
        // if (Auth::user()->id !== $website->user_id && !Auth::user()->is_admin) {
        //     abort(403, 'Unauthorized access to website payment settings');
        // }

        $paymentSettings = $website->paymentSettings;
        if ($paymentSettings) {
            $paymentSettings->delete();
            return back()->with('success', 'Payment settings deleted. Website will use default settings.');
        }

        return back()->with('error', 'No payment settings found to delete.');
    }

    /**
     * List all websites with payment settings (admin only)
     */
    public function index()
    {
        if (!Auth::user()->is_admin) {
            abort(403, 'Admin access required');
        }

        $websites = Website::with(['paymentSettings', 'user'])
            ->paginate(20);

        return view('admin.website.payment-index', compact('websites'));
    }
}
