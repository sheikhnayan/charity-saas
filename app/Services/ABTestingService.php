<?php

namespace App\Services;

use App\Models\ABTest;
use App\Models\ABTestVariant;
use App\Models\ABTestAssignment;
use App\Models\ABTestConversion;
use App\Models\ABTestResult;
use App\Models\ABTestEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ABTestingService
{
    /**
     * Create a new A/B test
     */
    public function createTest($websiteId, $data)
    {
        $test = ABTest::create([
            'website_id' => $websiteId,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'test_type' => $data['test_type'],
            'variants' => $data['variants'],
            'traffic_split' => $data['traffic_split'],
            'status' => 'draft',
            'goal_metric' => $data['goal_metric'],
            'goal_value' => $data['goal_value'] ?? null,
            'min_sample_size' => $data['min_sample_size'] ?? 100,
            'confidence_level' => $data['confidence_level'] ?? 95.00
        ]);

        // Create variant records
        foreach ($data['variants'] as $variantData) {
            ABTestVariant::create([
                'test_id' => $test->id,
                'name' => $variantData['name'],
                'configuration' => $variantData['configuration'],
                'is_control' => $variantData['is_control'] ?? false,
                'traffic_percentage' => $variantData['traffic_percentage'] ?? 50
            ]);
        }

        return $test->fresh(['testVariants']);
    }

    /**
     * Start an A/B test
     */
    public function startTest($testId)
    {
        $test = ABTest::findOrFail($testId);
        
        $test->update([
            'status' => 'running',
            'started_at' => Carbon::now()
        ]);

        return $test;
    }

    /**
     * Assign a user to a variant
     */
    public function assignVariant($testId, $userIdentifier, $identifierType = 'cookie', $userId = null, $sessionId = null)
    {
        // Check if already assigned
        $existing = ABTestAssignment::where('test_id', $testId)
            ->where('user_identifier', $userIdentifier)
            ->first();

        if ($existing) {
            return $existing->variant;
        }

        $test = ABTest::with('testVariants')->findOrFail($testId);
        
        if ($test->status !== 'running') {
            return null;
        }

        // Select variant based on traffic split
        $variant = $this->selectVariant($test);

        // Create assignment
        ABTestAssignment::create([
            'test_id' => $testId,
            'variant_id' => $variant->id,
            'user_identifier' => $userIdentifier,
            'identifier_type' => $identifierType,
            'user_id' => $userId,
            'session_id' => $sessionId,
            'assigned_at' => Carbon::now()
        ]);

        // Track impression event
        ABTestEvent::create([
            'test_id' => $testId,
            'variant_id' => $variant->id,
            'assignment_id' => null,
            'event_type' => 'impression',
            'event_at' => Carbon::now()
        ]);

        return $variant;
    }

    /**
     * Select a variant based on traffic split
     */
    private function selectVariant(ABTest $test)
    {
        $variants = $test->testVariants;
        $random = mt_rand(1, 100);
        $cumulative = 0;

        foreach ($variants as $variant) {
            $cumulative += $variant->traffic_percentage;
            if ($random <= $cumulative) {
                return $variant;
            }
        }

        // Fallback to first variant
        return $variants->first();
    }

    /**
     * Track a conversion
     */
    public function trackConversion($testId, $userIdentifier, $conversionType, $conversionValue = null, $metadata = null)
    {
        $assignment = ABTestAssignment::where('test_id', $testId)
            ->where('user_identifier', $userIdentifier)
            ->first();

        if (!$assignment) {
            return false;
        }

        ABTestConversion::create([
            'test_id' => $testId,
            'variant_id' => $assignment->variant_id,
            'assignment_id' => $assignment->id,
            'conversion_type' => $conversionType,
            'conversion_value' => $conversionValue,
            'metadata' => $metadata,
            'converted_at' => Carbon::now()
        ]);

        return true;
    }

    /**
     * Track an event
     */
    public function trackEvent($testId, $userIdentifier, $eventType, $eventData = null)
    {
        $assignment = ABTestAssignment::where('test_id', $testId)
            ->where('user_identifier', $userIdentifier)
            ->first();

        if (!$assignment) {
            return false;
        }

        ABTestEvent::create([
            'test_id' => $testId,
            'variant_id' => $assignment->variant_id,
            'assignment_id' => $assignment->id,
            'event_type' => $eventType,
            'page_url' => request()->url(),
            'event_data' => $eventData,
            'event_at' => Carbon::now()
        ]);

        return true;
    }

    /**
     * Calculate test results with statistical analysis
     */
    public function calculateResults($testId)
    {
        $test = ABTest::with('testVariants')->findOrFail($testId);
        
        $controlVariant = null;
        $results = [];

        foreach ($test->testVariants as $variant) {
            $impressions = ABTestAssignment::where('variant_id', $variant->id)->count();
            $conversions = ABTestConversion::where('variant_id', $variant->id)->count();
            $totalRevenue = ABTestConversion::where('variant_id', $variant->id)->sum('conversion_value');
            
            $conversionRate = $impressions > 0 ? ($conversions / $impressions) * 100 : 0;
            $avgRevenuePerUser = $impressions > 0 ? $totalRevenue / $impressions : 0;

            $result = ABTestResult::updateOrCreate(
                [
                    'test_id' => $testId,
                    'variant_id' => $variant->id,
                    'calculated_at' => Carbon::now()->startOfHour()
                ],
                [
                    'impressions' => $impressions,
                    'conversions' => $conversions,
                    'conversion_rate' => round($conversionRate, 2),
                    'total_revenue' => $totalRevenue,
                    'avg_revenue_per_user' => round($avgRevenuePerUser, 2)
                ]
            );

            $results[] = $result;

            if ($variant->is_control) {
                $controlVariant = $result;
            }
        }

        // Calculate statistical significance for each variant vs control
        if ($controlVariant) {
            foreach ($results as $result) {
                if ($result->variant_id !== $controlVariant->variant_id) {
                    $stats = $this->calculateStatisticalSignificance(
                        $controlVariant->conversions,
                        $controlVariant->impressions,
                        $result->conversions,
                        $result->impressions
                    );

                    $result->update([
                        'p_value' => $stats['p_value'],
                        'confidence_level' => $stats['confidence'],
                        'is_significant' => $stats['is_significant']
                    ]);
                }
            }
        }

        return $results;
    }

    /**
     * Calculate statistical significance (Chi-square test)
     */
    private function calculateStatisticalSignificance($controlConversions, $controlImpressions, $variantConversions, $variantImpressions)
    {
        if ($controlImpressions == 0 || $variantImpressions == 0) {
            return [
                'p_value' => 1.0,
                'confidence' => 0,
                'is_significant' => false
            ];
        }

        $controlRate = $controlConversions / $controlImpressions;
        $variantRate = $variantConversions / $variantImpressions;
        
        // Pooled probability
        $pooledProb = ($controlConversions + $variantConversions) / ($controlImpressions + $variantImpressions);
        
        // Standard error
        $se = sqrt($pooledProb * (1 - $pooledProb) * (1/$controlImpressions + 1/$variantImpressions));
        
        if ($se == 0) {
            return [
                'p_value' => 1.0,
                'confidence' => 0,
                'is_significant' => false
            ];
        }
        
        // Z-score
        $zScore = abs($variantRate - $controlRate) / $se;
        
        // P-value (two-tailed test, simplified)
        $pValue = $this->calculatePValue($zScore);
        
        // Confidence level
        $confidence = (1 - $pValue) * 100;
        
        // Is significant? (p < 0.05 for 95% confidence)
        $isSignificant = $pValue < 0.05;

        return [
            'p_value' => round($pValue, 8),
            'confidence' => round($confidence, 2),
            'is_significant' => $isSignificant
        ];
    }

    /**
     * Calculate p-value from z-score (simplified)
     */
    private function calculatePValue($zScore)
    {
        // Simplified p-value calculation using standard normal distribution approximation
        // For more accurate results, use a statistical library
        
        if ($zScore < 0) {
            $zScore = abs($zScore);
        }

        // Approximation for p-value from z-score
        if ($zScore > 6) {
            return 0.000001;
        }

        // Using lookup table approximation
        $table = [
            0.0 => 1.0000,
            0.5 => 0.6170,
            1.0 => 0.3173,
            1.5 => 0.1336,
            1.96 => 0.0500,
            2.0 => 0.0455,
            2.5 => 0.0124,
            3.0 => 0.0027,
            3.5 => 0.0005,
            4.0 => 0.00006
        ];

        // Find closest z-score in table
        $closestZ = 0;
        foreach (array_keys($table) as $z) {
            if ($zScore >= $z) {
                $closestZ = $z;
            } else {
                break;
            }
        }

        return $table[$closestZ];
    }

    /**
     * Determine winning variant
     */
    public function determineWinner($testId)
    {
        $test = ABTest::with('testVariants')->findOrFail($testId);
        $results = ABTestResult::where('test_id', $testId)
            ->orderBy('calculated_at', 'desc')
            ->get()
            ->groupBy('variant_id')
            ->map(fn($group) => $group->first());

        // Check if minimum sample size is met
        $minSampleMet = $results->every(fn($result) => $result->conversions >= $test->min_sample_size);

        if (!$minSampleMet) {
            return null;
        }

        // Find variant with highest conversion rate that is statistically significant
        $winner = $results
            ->where('is_significant', true)
            ->sortByDesc('conversion_rate')
            ->first();

        if ($winner) {
            $test->update(['winning_variant_id' => $winner->variant_id]);
        }

        return $winner;
    }

    /**
     * End test
     */
    public function endTest($testId)
    {
        $test = ABTest::findOrFail($testId);
        
        // Calculate final results
        $this->calculateResults($testId);
        
        // Determine winner
        $this->determineWinner($testId);
        
        $test->update([
            'status' => 'completed',
            'ended_at' => Carbon::now()
        ]);

        return $test->fresh(['testVariants', 'results', 'winningVariant']);
    }

    /**
     * Get test statistics
     */
    public function getTestStats($websiteId)
    {
        $query = ABTest::query();
        if ($websiteId) {
            $query->where('website_id', $websiteId);
        }

        $runningTests = (clone $query)->where('status', 'running')->count();
        $winnersFound = (clone $query)->whereNotNull('winning_variant_id')->count();
        
        $assignmentsQuery = ABTestAssignment::query();
        if ($websiteId) {
            $assignmentsQuery->whereHas('test', function($q) use ($websiteId) {
                $q->where('website_id', $websiteId);
            });
        }
        $totalParticipants = $assignmentsQuery->count();

        // Calculate average lift across all completed tests
        $completedTests = (clone $query)->where('status', 'completed')->with('results')->get();
        $avgLift = 0;
        $liftCount = 0;
        
        foreach ($completedTests as $test) {
            $control = $test->results->where('variant.is_control', true)->first();
            $variants = $test->results->where('variant.is_control', false);
            
            foreach ($variants as $variant) {
                if ($control && $control->conversion_rate > 0) {
                    $lift = (($variant->conversion_rate - $control->conversion_rate) / $control->conversion_rate) * 100;
                    $avgLift += $lift;
                    $liftCount++;
                }
            }
        }
        
        $avgLift = $liftCount > 0 ? round($avgLift / $liftCount, 1) : 0;

        return [
            'running' => $runningTests,
            'winners' => $winnersFound,
            'total_participants' => $totalParticipants,
            'avg_lift' => $avgLift
        ];
    }
}
