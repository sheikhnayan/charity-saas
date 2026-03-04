<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentFunnelService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FunnelTrackingController extends Controller
{
    protected $funnelService;

    public function __construct(PaymentFunnelService $funnelService)
    {
        $this->funnelService = $funnelService;
    }

    /**
     * Track a funnel event
     */
    public function trackEvent(Request $request): JsonResponse
    {
        try {
            $step = $request->input('step');
            $formType = $request->input('form_type');
            
            // Validate step
            $validSteps = [
                'form_view',
                'amount_entered',
                'personal_info_started',
                'personal_info_completed',
                'payment_initiated',
                'payment_completed',
                'payment_failed'
            ];
            
            if (!in_array($step, $validSteps)) {
                return response()->json(['error' => 'Invalid funnel step'], 400);
            }

            // Prepare data based on step
            $data = [];
            
            switch ($step) {
                case 'amount_entered':
                    $data = [
                        'amount' => $request->input('amount'),
                        'fee_option' => $request->input('fee_option')
                    ];
                    break;
                    
                case 'personal_info_started':
                case 'personal_info_completed':
                    $data = [
                        'first_name' => $request->input('first_name'),
                        'last_name' => $request->input('last_name'),
                        'email' => $request->input('email'),
                        'phone' => $request->input('phone'),
                        'user_id' => $request->input('user_id')
                    ];
                    break;
                    
                case 'payment_initiated':
                    $data = [
                        'amount' => $request->input('amount'),
                        'payment_method' => $request->input('payment_method'),
                        'user_id' => $request->input('user_id'),
                        'form_data' => $request->only(['first_name', 'last_name', 'email', 'phone'])
                    ];
                    break;
                    
                case 'payment_completed':
                    $data = [
                        'amount' => $request->input('amount'),
                        'payment_method' => $request->input('payment_method'),
                        'transaction_id' => $request->input('transaction_id'),
                        'user_id' => $request->input('user_id')
                    ];
                    break;
                    
                case 'payment_failed':
                    $data = [
                        'amount' => $request->input('amount'),
                        'payment_method' => $request->input('payment_method'),
                        'error_message' => $request->input('error_message'),
                        'user_id' => $request->input('user_id')
                    ];
                    break;
            }

            // Track the event
            $event = $this->funnelService->trackEvent($step, $formType, $data);
            
            return response()->json([
                'success' => true,
                'event_id' => $event ? $event->id : null,
                'message' => 'Event tracked successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Funnel tracking error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to track event'
            ], 500);
        }
    }

    /**
     * Get current session's funnel progress
     */
    public function getSessionProgress(Request $request): JsonResponse
    {
        try {
            $formType = $request->input('form_type');
            $progress = $this->funnelService->getSessionProgress($formType);
            
            return response()->json([
                'success' => true,
                'progress' => $progress->map(function ($event) {
                    return [
                        'step' => $event->funnel_step,
                        'form_type' => $event->form_type,
                        'completed_at' => $event->completed_at,
                        'amount' => $event->amount,
                        'data' => $event->form_data
                    ];
                })
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Session progress error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to get session progress'
            ], 500);
        }
    }

    /**
     * Check if user has completed a specific step
     */
    public function checkStepCompletion(Request $request): JsonResponse
    {
        try {
            $step = $request->input('step');
            $formType = $request->input('form_type');
            
            $completed = $this->funnelService->hasCompletedStep($step, $formType);
            $lastStep = $this->funnelService->getLastCompletedStep($formType);
            
            return response()->json([
                'success' => true,
                'completed' => $completed,
                'last_completed_step' => $lastStep
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Step completion check error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to check step completion'
            ], 500);
        }
    }

    /**
     * Bulk track multiple events (for form submissions)
     */
    public function bulkTrackEvents(Request $request): JsonResponse
    {
        try {
            $events = $request->input('events', []);
            $results = [];
            
            foreach ($events as $eventData) {
                $step = $eventData['step'];
                $formType = $eventData['form_type'];
                $data = $eventData['data'] ?? [];
                
                $event = $this->funnelService->trackEvent($step, $formType, $data);
                $results[] = [
                    'step' => $step,
                    'success' => $event !== null,
                    'event_id' => $event ? $event->id : null
                ];
            }
            
            return response()->json([
                'success' => true,
                'results' => $results,
                'message' => 'Bulk events tracked successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Bulk funnel tracking error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to track bulk events'
            ], 500);
        }
    }
}