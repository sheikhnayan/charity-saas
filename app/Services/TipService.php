<?php

namespace App\Services;

class TipService
{
    /**
     * Default tip percentages to suggest
     */
    const DEFAULT_TIP_PERCENTAGES = [10, 15, 20, 25];

    /**
     * Calculate tip amount from percentage
     */
    public function calculateTipFromPercentage(float $baseAmount, float $percentage): float
    {
        return round(($baseAmount * $percentage) / 100, 2);
    }

    /**
     * Calculate tip percentage from amount
     */
    public function calculateTipPercentage(float $baseAmount, float $tipAmount): float
    {
        if ($baseAmount <= 0) {
            return 0;
        }
        return round(($tipAmount / $baseAmount) * 100, 2);
    }

    /**
     * Get suggested tip amounts for a base amount
     */
    public function getSuggestedTips(float $baseAmount): array
    {
        $suggestions = [];
        
        foreach (self::DEFAULT_TIP_PERCENTAGES as $percentage) {
            $tipAmount = $this->calculateTipFromPercentage($baseAmount, $percentage);
            $suggestions[] = [
                'percentage' => $percentage,
                'amount' => $tipAmount,
                'total' => $baseAmount + $tipAmount,
                'label' => $percentage . '% ($' . number_format($tipAmount, 2) . ')'
            ];
        }
        
        return $suggestions;
    }

    /**
     * Validate tip amount
     */
    public function validateTip(float $baseAmount, float $tipAmount): bool
    {
        // Tip must be non-negative
        if ($tipAmount < 0) {
            return false;
        }

        // Tip should not exceed 100% of base amount (reasonable limit)
        if ($tipAmount > $baseAmount) {
            return false;
        }

        return true;
    }

    /**
     * Get default tip message based on amount
     */
    public function getTipMessage(float $baseAmount): string
    {
        if ($baseAmount < 50) {
            return "Help us cover processing fees and keep the platform running.";
        } elseif ($baseAmount < 200) {
            return "Your tip supports our mission and helps us serve more people.";
        } else {
            return "Thank you! Your generosity helps us make a bigger impact.";
        }
    }

    /**
     * Calculate optimal tip suggestion based on amount
     */
    public function getOptimalTipPercentage(float $baseAmount): int
    {
        // Suggest higher percentage for smaller amounts to cover fixed costs
        if ($baseAmount < 25) {
            return 20; // 20% for small donations
        } elseif ($baseAmount < 100) {
            return 15; // 15% for medium donations
        } else {
            return 10; // 10% for large donations
        }
    }

    /**
     * Format tip display string
     */
    public function formatTipDisplay(float $tipAmount, ?float $percentage = null): string
    {
        $display = '$' . number_format($tipAmount, 2);
        
        if ($percentage !== null) {
            $display .= ' (' . number_format($percentage, 1) . '%)';
        }
        
        return $display;
    }

    /**
     * Check if tipping is enabled for website
     */
    public function isTippingEnabled($website): bool
    {
        // Check if website has tipping enabled (you can add a setting to websites table)
        return true; // Default to enabled for now
    }

    /**
     * Get tip statistics for reporting
     */
    public function getTipStatistics($websiteId, $startDate = null, $endDate = null): array
    {
        $query = \App\Models\Donation::where('website_id', $websiteId)
            ->where('tip_amount', '>', 0);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $totalTips = $query->sum('tip_amount');
        $tipCount = $query->count();
        $avgTip = $tipCount > 0 ? $totalTips / $tipCount : 0;
        $avgPercentage = $query->whereNotNull('tip_percentage')->avg('tip_percentage');

        return [
            'total_tips' => $totalTips,
            'tip_count' => $tipCount,
            'average_tip' => $avgTip,
            'average_percentage' => $avgPercentage ?? 0,
            'tip_participation_rate' => $this->getTipParticipationRate($websiteId, $startDate, $endDate)
        ];
    }

    /**
     * Calculate tip participation rate (% of donations with tips)
     */
    public function getTipParticipationRate($websiteId, $startDate = null, $endDate = null): float
    {
        $query = \App\Models\Donation::where('website_id', $websiteId);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        $totalDonations = $query->count();
        $donationsWithTips = $query->where('tip_amount', '>', 0)->count();

        return $totalDonations > 0 ? ($donationsWithTips / $totalDonations) * 100 : 0;
    }
}
