<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentFunnelEvent;
use App\Models\Website;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentFunnelController extends Controller
{
    public function index(Request $request)
    {
        $website = Website::find($request->get('website_id', session('website_id')));
        
        if (!$website) {
            return redirect()->back()->with('error', 'Please select a website first.');
        }

        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Get funnel conversion data
        $conversionData = PaymentFunnelEvent::getFunnelConversion($website->id, $dateFrom, $dateTo);
        
        // Get abandonment analysis
        $abandonmentData = PaymentFunnelEvent::getAbandonmentAnalysis($website->id, $dateFrom, $dateTo);
        
        // Get form type breakdown
        $formTypeData = PaymentFunnelEvent::where('website_id', $website->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('form_type, COUNT(DISTINCT session_id) as unique_sessions')
            ->groupBy('form_type')
            ->get()
            ->keyBy('form_type');

        // Get daily funnel data for charts
        $dailyData = PaymentFunnelEvent::where('website_id', $website->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date, funnel_step, COUNT(DISTINCT session_id) as unique_sessions')
            ->groupBy('date', 'funnel_step')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        // Get top exit points
        $exitPoints = $this->getExitPoints($website->id, $dateFrom, $dateTo);

        // Calculate revenue impact
        $revenueData = $this->getRevenueAnalysis($website->id, $dateFrom, $dateTo);

        return view('admin.payment-funnel.index', compact(
            'website',
            'conversionData',
            'abandonmentData',
            'formTypeData',
            'dailyData',
            'exitPoints',
            'revenueData',
            'dateFrom',
            'dateTo'
        ));
    }

    public function api(Request $request)
    {
        $website = Website::find($request->get('website_id'));
        
        if (!$website) {
            return response()->json(['error' => 'Website not found'], 404);
        }

        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $conversionData = PaymentFunnelEvent::getFunnelConversion($website->id, $dateFrom, $dateTo);
        $abandonmentData = PaymentFunnelEvent::getAbandonmentAnalysis($website->id, $dateFrom, $dateTo);

        return response()->json([
            'conversion' => $conversionData,
            'abandonment' => $abandonmentData
        ]);
    }

    protected function getExitPoints($websiteId, $dateFrom, $dateTo)
    {
        // Get sessions that started but didn't complete
        $exitPoints = PaymentFunnelEvent::where('website_id', $websiteId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                session_id,
                MAX(CASE WHEN funnel_step = "form_view" THEN 1 ELSE 0 END) as started_form,
                MAX(CASE WHEN funnel_step = "amount_entered" THEN 1 ELSE 0 END) as entered_amount,
                MAX(CASE WHEN funnel_step = "personal_info_completed" THEN 1 ELSE 0 END) as completed_info,
                MAX(CASE WHEN funnel_step = "payment_initiated" THEN 1 ELSE 0 END) as initiated_payment,
                MAX(CASE WHEN funnel_step = "payment_completed" THEN 1 ELSE 0 END) as completed_payment,
                MAX(created_at) as last_activity
            ')
            ->groupBy('session_id')
            ->get();

        // Analyze where people exit
        $exitAnalysis = [
            'view_to_amount' => $exitPoints->where('started_form', 1)->where('entered_amount', 0)->count(),
            'amount_to_info' => $exitPoints->where('entered_amount', 1)->where('completed_info', 0)->count(),
            'info_to_payment' => $exitPoints->where('completed_info', 1)->where('initiated_payment', 0)->count(),
            'payment_to_complete' => $exitPoints->where('initiated_payment', 1)->where('completed_payment', 0)->count(),
        ];

        return $exitAnalysis;
    }

    protected function getRevenueAnalysis($websiteId, $dateFrom, $dateTo)
    {
        // Get completed payments
        $completedPayments = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('funnel_step', PaymentFunnelEvent::PAYMENT_COMPLETED)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        // Get failed payments
        $failedPayments = PaymentFunnelEvent::where('website_id', $websiteId)
            ->where('funnel_step', PaymentFunnelEvent::PAYMENT_FAILED)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $totalRevenue = $completedPayments->sum('amount');
        $lostRevenue = $failedPayments->sum('amount');
        $averageOrderValue = $completedPayments->count() > 0 ? $totalRevenue / $completedPayments->count() : 0;

        return [
            'total_revenue' => $totalRevenue,
            'lost_revenue' => $lostRevenue,
            'average_order_value' => $averageOrderValue,
            'completed_transactions' => $completedPayments->count(),
            'failed_transactions' => $failedPayments->count(),
            'success_rate' => ($completedPayments->count() + $failedPayments->count()) > 0 
                ? ($completedPayments->count() / ($completedPayments->count() + $failedPayments->count())) * 100 
                : 0
        ];
    }

    public function export(Request $request)
    {
        $website = Website::find($request->get('website_id'));
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $events = PaymentFunnelEvent::where('website_id', $website->id)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "payment-funnel-{$website->domain}-{$dateFrom}-to-{$dateTo}.csv";

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
                'Amount',
                'Device Type',
                'Browser',
                'UTM Source',
                'UTM Medium',
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
                    $event->amount,
                    $event->device_type,
                    $event->browser,
                    $event->utm_source,
                    $event->utm_medium,
                    $event->utm_campaign,
                    $event->error_message
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}