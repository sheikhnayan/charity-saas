<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentFunnelEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'session_id',
        'visitor_id',
        'funnel_step',
        'form_type',
        'user_id',
        'amount',
        'form_data',
        'payment_method',
        'transaction_id',
        'error_message',
        'completed_at',
        'referrer_url',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'device_type',
        'browser',
        'ip_address',
        'country',
        'country_code',
        'state',
        'city'
    ];

    protected $casts = [
        'form_data' => 'array',
        'completed_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    // Funnel step constants
    const FORM_VIEW = 'form_view';
    const AMOUNT_ENTERED = 'amount_entered';
    const PERSONAL_INFO_STARTED = 'personal_info_started';
    const PERSONAL_INFO_COMPLETED = 'personal_info_completed';
    const PAYMENT_INITIATED = 'payment_initiated';
    const PAYMENT_COMPLETED = 'payment_completed';
    const PAYMENT_FAILED = 'payment_failed';

    // Form type constants
    const FORM_STUDENT = 'student';
    const FORM_GENERAL = 'general';
    const FORM_TICKET = 'ticket';
    const FORM_AUCTION = 'auction';
    const FORM_INVESTMENT = 'investment';

    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }

    /**
     * Get funnel conversion rates for a website
     */
    public static function getFunnelConversion($websiteId, $dateFrom = null, $dateTo = null)
    {
        $query = self::where('website_id', $websiteId);
        
        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        $steps = $query->selectRaw('
            funnel_step,
            COUNT(*) as count,
            COUNT(DISTINCT session_id) as unique_sessions
        ')
        ->groupBy('funnel_step')
        ->get()
        ->keyBy('funnel_step');

        // Calculate conversion rates
        $formViews = $steps[self::FORM_VIEW]->unique_sessions ?? 0;
        
        return [
            'form_view' => [
                'count' => $formViews,
                'conversion_rate' => 100.0
            ],
            'amount_entered' => [
                'count' => $steps[self::AMOUNT_ENTERED]->unique_sessions ?? 0,
                'conversion_rate' => $formViews > 0 ? round((($steps[self::AMOUNT_ENTERED]->unique_sessions ?? 0) / $formViews) * 100, 2) : 0
            ],
            'personal_info_completed' => [
                'count' => $steps[self::PERSONAL_INFO_COMPLETED]->unique_sessions ?? 0,
                'conversion_rate' => $formViews > 0 ? round((($steps[self::PERSONAL_INFO_COMPLETED]->unique_sessions ?? 0) / $formViews) * 100, 2) : 0
            ],
            'payment_initiated' => [
                'count' => $steps[self::PAYMENT_INITIATED]->unique_sessions ?? 0,
                'conversion_rate' => $formViews > 0 ? round((($steps[self::PAYMENT_INITIATED]->unique_sessions ?? 0) / $formViews) * 100, 2) : 0
            ],
            'payment_completed' => [
                'count' => $steps[self::PAYMENT_COMPLETED]->unique_sessions ?? 0,
                'conversion_rate' => $formViews > 0 ? round((($steps[self::PAYMENT_COMPLETED]->unique_sessions ?? 0) / $formViews) * 100, 2) : 0
            ]
        ];
    }

    /**
     * Get abandonment points analysis
     */
    public static function getAbandonmentAnalysis($websiteId, $dateFrom = null, $dateTo = null)
    {
        $query = self::where('website_id', $websiteId);
        
        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        // Get sessions and their last completed step
        $sessionSteps = $query->selectRaw('
            session_id,
            MAX(CASE WHEN funnel_step = ? THEN 1 ELSE 0 END) as form_viewed,
            MAX(CASE WHEN funnel_step = ? THEN 1 ELSE 0 END) as amount_entered,
            MAX(CASE WHEN funnel_step = ? THEN 1 ELSE 0 END) as personal_info_completed,
            MAX(CASE WHEN funnel_step = ? THEN 1 ELSE 0 END) as payment_initiated,
            MAX(CASE WHEN funnel_step = ? THEN 1 ELSE 0 END) as payment_completed
        ', [
            self::FORM_VIEW,
            self::AMOUNT_ENTERED,
            self::PERSONAL_INFO_COMPLETED,
            self::PAYMENT_INITIATED,
            self::PAYMENT_COMPLETED
        ])
        ->groupBy('session_id')
        ->get();

        $abandonedAtFormView = $sessionSteps->where('form_viewed', 1)
            ->where('amount_entered', 0)->count();
        
        $abandonedAtAmountEntry = $sessionSteps->where('amount_entered', 1)
            ->where('personal_info_completed', 0)->count();
        
        $abandonedAtPersonalInfo = $sessionSteps->where('personal_info_completed', 1)
            ->where('payment_initiated', 0)->count();
        
        $abandonedAtPayment = $sessionSteps->where('payment_initiated', 1)
            ->where('payment_completed', 0)->count();

        return [
            'abandoned_at_form_view' => $abandonedAtFormView,
            'abandoned_at_amount_entry' => $abandonedAtAmountEntry,
            'abandoned_at_personal_info' => $abandonedAtPersonalInfo,
            'abandoned_at_payment' => $abandonedAtPayment,
            'total_abandoned' => $abandonedAtFormView + $abandonedAtAmountEntry + $abandonedAtPersonalInfo + $abandonedAtPayment,
            'completed' => $sessionSteps->where('payment_completed', 1)->count()
        ];
    }

    /**
     * Get average time between funnel steps
     */
    public static function getFunnelTiming($websiteId, $dateFrom = null, $dateTo = null)
    {
        $query = self::where('website_id', $websiteId);
        
        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        // Get timing between steps for each session
        return $query->selectRaw('
            session_id,
            MIN(CASE WHEN funnel_step = ? THEN created_at END) as form_view_time,
            MIN(CASE WHEN funnel_step = ? THEN created_at END) as amount_entered_time,
            MIN(CASE WHEN funnel_step = ? THEN created_at END) as personal_info_time,
            MIN(CASE WHEN funnel_step = ? THEN created_at END) as payment_initiated_time,
            MIN(CASE WHEN funnel_step = ? THEN created_at END) as payment_completed_time
        ', [
            self::FORM_VIEW,
            self::AMOUNT_ENTERED,
            self::PERSONAL_INFO_COMPLETED,
            self::PAYMENT_INITIATED,
            self::PAYMENT_COMPLETED
        ])
        ->groupBy('session_id')
        ->havingRaw('form_view_time IS NOT NULL')
        ->get();
    }
}