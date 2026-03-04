<?php

namespace App\Services;

use App\Models\FraudRule;
use App\Models\FraudDetection;
use App\Models\Transaction;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FraudDetectionService
{
    /**
     * Analyze a transaction for fraud
     */
    public function analyzeTransaction($transaction, $type = 'transaction')
    {
        $rules = FraudRule::where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        $detections = [];
        $totalRiskScore = 0;

        foreach ($rules as $rule) {
            $result = $this->executeRule($rule, $transaction, $type);
            
            if ($result['triggered']) {
                $detection = FraudDetection::create([
                    $type . '_id' => $transaction->id,
                    'user_id' => $transaction->user_id ?? null,
                    'fraud_rule_id' => $rule->id,
                    'status' => $this->determineStatus($rule->action),
                    'risk_score' => $rule->risk_score,
                    'detection_details' => $result['details'],
                ]);

                $detections[] = $detection;
                $totalRiskScore += $rule->risk_score;

                // Take action based on rule
                if ($rule->action === 'block') {
                    $this->blockTransaction($transaction, $type);
                }
            }
        }

        return [
            'detections' => $detections,
            'total_risk_score' => $totalRiskScore,
            'action_required' => $totalRiskScore >= 70,
            'recommended_action' => $this->getRecommendedAction($totalRiskScore),
        ];
    }

    /**
     * Execute a specific fraud rule
     */
    protected function executeRule($rule, $transaction, $type)
    {
        $method = 'check' . str_replace('_', '', ucwords($rule->rule_type, '_'));
        
        if (method_exists($this, $method)) {
            return $this->$method($rule, $transaction, $type);
        }

        return ['triggered' => false, 'details' => []];
    }

    /**
     * Check velocity (multiple transactions in short time)
     */
    protected function checkVelocity($rule, $transaction, $type)
    {
        $params = $rule->parameters;
        $timeWindow = $params['time_window_minutes'] ?? 60;
        $maxTransactions = $params['max_transactions'] ?? 5;
        $thresholdAmount = $params['threshold_amount'] ?? null;

        $cutoffTime = Carbon::now()->subMinutes($timeWindow);
        
        $query = DB::table($type === 'donation' ? 'donations' : 'transactions')
            ->where('created_at', '>=', $cutoffTime);

        // Check by IP address
        if (!empty($transaction->ip_address)) {
            $query->where('ip_address', $transaction->ip_address);
        }
        // Or by user
        elseif (!empty($transaction->user_id)) {
            $query->where('user_id', $transaction->user_id);
        } else {
            return ['triggered' => false, 'details' => []];
        }

        $recentCount = $query->count();
        $recentSum = $query->sum('amount');

        $triggered = $recentCount >= $maxTransactions;
        
        if ($thresholdAmount && $recentSum > $thresholdAmount) {
            $triggered = true;
        }

        return [
            'triggered' => $triggered,
            'details' => [
                'transactions_in_window' => $recentCount,
                'total_amount' => $recentSum,
                'time_window_minutes' => $timeWindow,
                'trigger_reason' => $recentCount >= $maxTransactions ? 'transaction_count' : 'amount_threshold',
            ]
        ];
    }

    /**
     * Check geolocation anomalies
     */
    protected function checkGeolocation($rule, $transaction, $type)
    {
        $params = $rule->parameters;
        
        // Check if IP country matches billing country
        if (!empty($transaction->ip_address) && !empty($transaction->billing_country)) {
            $ipCountry = $this->getCountryFromIP($transaction->ip_address);
            
            if ($ipCountry && $ipCountry !== $transaction->billing_country) {
                return [
                    'triggered' => true,
                    'details' => [
                        'ip_country' => $ipCountry,
                        'billing_country' => $transaction->billing_country,
                        'mismatch_type' => 'country_mismatch',
                    ]
                ];
            }
        }

        // Check for high-risk countries
        $highRiskCountries = $params['high_risk_countries'] ?? [];
        if (!empty($transaction->billing_country) && in_array($transaction->billing_country, $highRiskCountries)) {
            return [
                'triggered' => true,
                'details' => [
                    'country' => $transaction->billing_country,
                    'mismatch_type' => 'high_risk_country',
                ]
            ];
        }

        return ['triggered' => false, 'details' => []];
    }

    /**
     * Check amount thresholds
     */
    protected function checkAmountthreshold($rule, $transaction, $type)
    {
        $params = $rule->parameters;
        $maxAmount = $params['max_amount'] ?? 10000;
        $minAmount = $params['min_amount'] ?? 0.01;

        $amount = $transaction->amount ?? 0;

        if ($amount > $maxAmount || $amount < $minAmount) {
            return [
                'triggered' => true,
                'details' => [
                    'amount' => $amount,
                    'max_threshold' => $maxAmount,
                    'min_threshold' => $minAmount,
                    'trigger_reason' => $amount > $maxAmount ? 'exceeds_max' : 'below_min',
                ]
            ];
        }

        return ['triggered' => false, 'details' => []];
    }

    /**
     * Check for card testing patterns
     */
    protected function checkCardtesting($rule, $transaction, $type)
    {
        $params = $rule->parameters;
        $timeWindow = $params['time_window_minutes'] ?? 30;
        $minAttempts = $params['min_attempts'] ?? 3;
        $maxSuccessRate = $params['max_success_rate'] ?? 0.3;

        $cutoffTime = Carbon::now()->subMinutes($timeWindow);
        
        $query = DB::table($type === 'donation' ? 'donations' : 'transactions')
            ->where('created_at', '>=', $cutoffTime);

        // Check by IP or user
        if (!empty($transaction->ip_address)) {
            $query->where('ip_address', $transaction->ip_address);
        } elseif (!empty($transaction->user_id)) {
            $query->where('user_id', $transaction->user_id);
        } else {
            return ['triggered' => false, 'details' => []];
        }

        $totalAttempts = $query->count();
        $successfulAttempts = $query->where('status', 'completed')->count();

        if ($totalAttempts >= $minAttempts) {
            $successRate = $totalAttempts > 0 ? $successfulAttempts / $totalAttempts : 0;
            
            if ($successRate <= $maxSuccessRate) {
                return [
                    'triggered' => true,
                    'details' => [
                        'total_attempts' => $totalAttempts,
                        'successful_attempts' => $successfulAttempts,
                        'success_rate' => round($successRate * 100, 2) . '%',
                        'time_window_minutes' => $timeWindow,
                    ]
                ];
            }
        }

        return ['triggered' => false, 'details' => []];
    }

    /**
     * Check for pattern matching (suspicious patterns)
     */
    protected function checkPatternmatching($rule, $transaction, $type)
    {
        $params = $rule->parameters;
        $patterns = $params['patterns'] ?? [];

        foreach ($patterns as $pattern) {
            switch ($pattern['type']) {
                case 'sequential_amounts':
                    // Check for sequential donation amounts (1.00, 2.00, 3.00 - card testing)
                    $recent = DB::table($type === 'donation' ? 'donations' : 'transactions')
                        ->where('ip_address', $transaction->ip_address)
                        ->where('created_at', '>=', Carbon::now()->subHour())
                        ->orderBy('created_at')
                        ->pluck('amount')
                        ->toArray();
                    
                    if (count($recent) >= 3 && $this->isSequential($recent)) {
                        return [
                            'triggered' => true,
                            'details' => [
                                'pattern_type' => 'sequential_amounts',
                                'amounts' => $recent,
                            ]
                        ];
                    }
                    break;

                case 'round_amounts':
                    // Multiple round amount transactions (100, 200, 500 - suspicious)
                    if ($transaction->amount == floor($transaction->amount) && $transaction->amount >= 100) {
                        $roundCount = DB::table($type === 'donation' ? 'donations' : 'transactions')
                            ->where('ip_address', $transaction->ip_address)
                            ->where('created_at', '>=', Carbon::now()->subHour())
                            ->whereRaw('amount = FLOOR(amount)')
                            ->count();
                        
                        if ($roundCount >= 3) {
                            return [
                                'triggered' => true,
                                'details' => [
                                    'pattern_type' => 'multiple_round_amounts',
                                    'round_transactions' => $roundCount,
                                ]
                            ];
                        }
                    }
                    break;
            }
        }

        return ['triggered' => false, 'details' => []];
    }

    /**
     * Helper: Check if amounts are sequential
     */
    protected function isSequential($amounts)
    {
        if (count($amounts) < 3) return false;
        
        for ($i = 1; $i < count($amounts) - 1; $i++) {
            if ($amounts[$i] - $amounts[$i-1] !== 1.0) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Helper: Get country from IP
     */
    protected function getCountryFromIP($ipAddress)
    {
        // Placeholder - would integrate with GeoIP service
        // For now, check if we have it in analytics_events
        $event = DB::table('analytics_events')
            ->where('ip_address', $ipAddress)
            ->whereNotNull('country')
            ->first();
        
        return $event->country ?? null;
    }

    /**
     * Determine detection status based on action
     */
    protected function determineStatus($action)
    {
        return match($action) {
            'block' => 'blocked',
            'review' => 'reviewing',
            'flag' => 'flagged',
            default => 'flagged',
        };
    }

    /**
     * Block a transaction
     */
    protected function blockTransaction($transaction, $type)
    {
        $table = $type === 'donation' ? 'donations' : 'transactions';
        
        DB::table($table)
            ->where('id', $transaction->id)
            ->update([
                'status' => 'blocked',
                'updated_at' => now(),
            ]);

        Log::warning("Transaction blocked by fraud detection", [
            'type' => $type,
            'id' => $transaction->id,
            'amount' => $transaction->amount ?? 0,
        ]);
    }

    /**
     * Get recommended action based on risk score
     */
    protected function getRecommendedAction($riskScore)
    {
        if ($riskScore >= 90) return 'block_immediately';
        if ($riskScore >= 70) return 'manual_review_required';
        if ($riskScore >= 50) return 'flag_for_review';
        if ($riskScore >= 30) return 'monitor';
        return 'allow';
    }

    /**
     * Get fraud statistics for dashboard
     */
    public function getStatistics($startDate = null, $endDate = null, $websiteId = null)
    {
        $startDate = $startDate ?? Carbon::now()->subDays(30);
        $endDate = $endDate ?? Carbon::now();

        // Base query with website filtering
        $baseQuery = function() use ($startDate, $endDate, $websiteId) {
            return FraudDetection::when($websiteId, function($query) use ($websiteId) {
                $query->where(function($q) use ($websiteId) {
                    $q->whereHas('transaction', function($tq) use ($websiteId) {
                        $tq->where('website_id', $websiteId);
                    })
                    ->orWhereHas('donation', function($dq) use ($websiteId) {
                        $dq->where('website_id', $websiteId);
                    });
                });
            })->whereBetween('created_at', [$startDate, $endDate]);
        };

        return [
            'total_detections' => $baseQuery()->count(),
            'blocked' => $baseQuery()->where('status', 'blocked')->count(),
            'reviewing' => $baseQuery()->where('status', 'reviewing')->count(),
            'cleared' => $baseQuery()->where('status', 'cleared')->count(),
            'avg_risk_score' => $baseQuery()->avg('risk_score') ?? 0,
            'high_risk_count' => $baseQuery()->where('risk_score', '>=', 70)->count(),
        ];
    }
}
