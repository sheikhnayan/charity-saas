<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentMethodAnalyticsController extends Controller
{
    protected $paymentGatewayService;

    public function __construct(PaymentGatewayService $paymentGatewayService)
    {
        $this->paymentGatewayService = $paymentGatewayService;
    }

    /**
     * Payment method comparison dashboard
     */
    public function index(Request $request)
    {
        $website = Website::find($request->get('website_id', session('website_id')));
        
        if (!$website) {
            return redirect()->back()->with('error', 'Please select a website first.');
        }

        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Get payment method performance
        $paymentMethodStats = $this->getPaymentMethodPerformance($website->id, $dateFrom, $dateTo);
        
        // Get conversion rates by payment method
        $conversionByMethod = $this->getConversionByPaymentMethod($website->id, $dateFrom, $dateTo);
        
        // Get supported payment methods for this website
        $supportedMethods = $this->paymentGatewayService->getSupportedPaymentMethods($website);
        
        // Get form type performance by payment method
        $formTypeByMethod = $this->getFormTypeByPaymentMethod($website->id, $dateFrom, $dateTo);

        return view('admin.payment-methods.analytics', compact(
            'website',
            'paymentMethodStats',
            'conversionByMethod',
            'supportedMethods',
            'formTypeByMethod',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Get payment method performance statistics
     */
    protected function getPaymentMethodPerformance($websiteId, $dateFrom, $dateTo)
    {
        return PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                payment_method,
                funnel_step,
                COUNT(*) as event_count,
                COUNT(DISTINCT session_id) as unique_sessions,
                AVG(amount) as avg_amount,
                SUM(amount) as total_amount
            ')
            ->whereNotNull('payment_method')
            ->groupBy('payment_method', 'funnel_step')
            ->get()
            ->groupBy('payment_method');
    }

    /**
     * Get conversion rates by payment method
     */
    protected function getConversionByPaymentMethod($websiteId, $dateFrom, $dateTo)
    {
        $methods = ['authorize_net', 'stripe', 'crypto'];
        $conversionData = [];

        foreach ($methods as $method) {
            $events = PaymentFunnelEvent::where('website_id', $websiteId)
                ->where('payment_method', $method)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->selectRaw('
                    funnel_step,
                    COUNT(DISTINCT session_id) as unique_sessions
                ')
                ->groupBy('funnel_step')
                ->get()
                ->keyBy('funnel_step');

            $initiated = $events[PaymentFunnelEvent::PAYMENT_INITIATED]->unique_sessions ?? 0;
            $completed = $events[PaymentFunnelEvent::PAYMENT_COMPLETED]->unique_sessions ?? 0;
            $failed = $events[PaymentFunnelEvent::PAYMENT_FAILED]->unique_sessions ?? 0;

            $conversionData[$method] = [
                'initiated' => $initiated,
                'completed' => $completed,
                'failed' => $failed,
                'success_rate' => $initiated > 0 ? round(($completed / $initiated) * 100, 2) : 0,
                'failure_rate' => $initiated > 0 ? round(($failed / $initiated) * 100, 2) : 0
            ];
        }

        return $conversionData;
    }

    /**
     * Get form type performance by payment method
     */
    protected function getFormTypeByPaymentMethod($websiteId, $dateFrom, $dateTo)
    {
        return PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereIn('funnel_step', [PaymentFunnelEvent::PAYMENT_COMPLETED])
            ->selectRaw('
                form_type,
                payment_method,
                COUNT(*) as completions,
                AVG(amount) as avg_amount,
                SUM(amount) as total_revenue
            ')
            ->whereNotNull('payment_method')
            ->groupBy('form_type', 'payment_method')
            ->get()
            ->groupBy('form_type');
    }

    /**
     * API endpoint for real-time payment method stats
     */
    public function api(Request $request)
    {
        $website = Website::find($request->get('website_id'));
        
        if (!$website) {
            return response()->json(['error' => 'Website not found'], 404);
        }

        $dateFrom = $request->get('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $stats = $this->getPaymentMethodPerformance($website->id, $dateFrom, $dateTo);
        $conversions = $this->getConversionByPaymentMethod($website->id, $dateFrom, $dateTo);

        return response()->json([
            'payment_method_stats' => $stats,
            'conversion_rates' => $conversions,
            'supported_methods' => $this->paymentGatewayService->getSupportedPaymentMethods($website)
        ]);
    }

    /**
     * Export payment method analytics
     */
    public function export(Request $request)
    {
        $website = Website::find($request->get('website_id'));
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $events = PaymentFunnelEvent::where('website_id', $website->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->whereNotNull('payment_method')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "payment-methods-analytics-{$website->domain}-{$dateFrom}-to-{$dateTo}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Date',
                'Session ID',
                'Form Type',
                'Funnel Step',
                'Payment Method',
                'Amount',
                'Device Type',
                'UTM Source',
                'UTM Campaign',
                'Error Message'
            ]);

            // Add data rows
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->created_at->format('Y-m-d H:i:s'),
                    $event->session_id,
                    $event->form_type,
                    $event->funnel_step,
                    $event->payment_method,
                    $event->amount,
                    $event->device_type,
                    $event->utm_source,
                    $event->utm_campaign,
                    $event->error_message
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}