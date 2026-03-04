<?php

namespace App\Services;

use App\Models\Cohort;
use App\Models\CohortMember;
use App\Models\CohortRetention;
use App\Models\User;
use App\Models\Donation;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CohortService
{
    /**
     * Create a new cohort
     */
    public function createCohort($websiteId, $data)
    {
        $cohort = Cohort::create([
            'website_id' => $websiteId,
            'name' => $data['name'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'definition' => $data['definition'],
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
            'is_active' => true
        ]);

        // Populate cohort members based on definition
        $this->populateCohort($cohort);

        return $cohort;
    }

    /**
     * Populate cohort with members based on definition
     */
    public function populateCohort(Cohort $cohort)
    {
        $users = $this->getUsersForCohort($cohort);
        
        foreach ($users as $user) {
            CohortMember::updateOrCreate(
                [
                    'cohort_id' => $cohort->id,
                    'user_id' => $user->id
                ],
                [
                    'joined_at' => $user->created_at,
                    'lifetime_value' => $this->calculateLifetimeValue($user->id),
                    'transaction_count' => $this->getTransactionCount($user->id),
                    'last_activity_at' => $this->getLastActivity($user->id)
                ]
            );
        }

        // Update member count
        $cohort->update(['member_count' => $users->count()]);

        return $cohort;
    }

    /**
     * Get users for cohort based on type and definition
     */
    private function getUsersForCohort(Cohort $cohort)
    {
        $query = User::query();

        switch ($cohort->type) {
            case 'first_time':
                // Users with exactly 1 donation
                $query->has('donations', '=', 1);
                break;

            case 'repeat':
                // Users with 2+ donations
                $query->has('donations', '>=', 2);
                break;

            case 'high_value':
                // Users with total donations > threshold
                $threshold = $cohort->definition['threshold'] ?? 100;
                $query->whereHas('donations', function($q) use ($threshold) {
                    $q->select(DB::raw('SUM(amount)'))
                        ->havingRaw('SUM(amount) >= ?', [$threshold]);
                });
                break;

            case 'lapsed':
                // Users with no activity in X days
                $days = $cohort->definition['days'] ?? 90;
                $query->whereHas('donations', function($q) use ($days) {
                    $q->where('created_at', '<', Carbon::now()->subDays($days));
                })
                ->whereDoesntHave('donations', function($q) use ($days) {
                    $q->where('created_at', '>=', Carbon::now()->subDays($days));
                });
                break;

            case 'by_date':
                // Users who joined in specific date range
                if ($cohort->start_date) {
                    $query->whereDate('created_at', '>=', $cohort->start_date);
                }
                if ($cohort->end_date) {
                    $query->whereDate('created_at', '<=', $cohort->end_date);
                }
                break;

            case 'custom':
                // Apply custom filters from definition
                if (isset($cohort->definition['filters'])) {
                    foreach ($cohort->definition['filters'] as $filter) {
                        $this->applyFilter($query, $filter);
                    }
                }
                break;
        }

        return $query->get();
    }

    /**
     * Apply custom filter to query
     */
    private function applyFilter($query, $filter)
    {
        $field = $filter['field'];
        $operator = $filter['operator'];
        $value = $filter['value'];

        $query->where($field, $operator, $value);
    }

    /**
     * Calculate retention metrics for a cohort
     */
    public function calculateRetention(Cohort $cohort, array $periods = [0, 1, 7, 14, 30, 60, 90])
    {
        $baseDate = $cohort->start_date ?? $cohort->created_at;
        
        foreach ($periods as $period) {
            $periodDate = Carbon::parse($baseDate)->addDays($period);
            
            // Count users who made a donation on or after this period
            $retainedUsers = CohortMember::where('cohort_id', $cohort->id)
                ->whereHas('user.donations', function($q) use ($periodDate) {
                    $q->whereDate('created_at', '>=', $periodDate);
                })
                ->count();

            $retentionRate = $cohort->member_count > 0 
                ? ($retainedUsers / $cohort->member_count) * 100 
                : 0;

            // Calculate revenue for this period
            $revenue = Donation::whereIn('user_id', function($query) use ($cohort) {
                    $query->select('user_id')
                        ->from('cohort_members')
                        ->where('cohort_id', $cohort->id);
                })
                ->whereDate('created_at', '>=', $periodDate)
                ->whereDate('created_at', '<', Carbon::parse($periodDate)->addDays(1))
                ->sum('amount');

            $transactions = Donation::whereIn('user_id', function($query) use ($cohort) {
                    $query->select('user_id')
                        ->from('cohort_members')
                        ->where('cohort_id', $cohort->id);
                })
                ->whereDate('created_at', '>=', $periodDate)
                ->whereDate('created_at', '<', Carbon::parse($periodDate)->addDays(1))
                ->count();

            CohortRetention::updateOrCreate(
                [
                    'cohort_id' => $cohort->id,
                    'period' => $period
                ],
                [
                    'period_date' => $periodDate,
                    'retained_users' => $retainedUsers,
                    'retention_rate' => round($retentionRate, 2),
                    'revenue' => $revenue,
                    'transactions' => $transactions
                ]
            );
        }
    }

    /**
     * Compare multiple cohorts
     */
    public function compareCohorts(array $cohortIds, $websiteId)
    {
        $cohorts = Cohort::whereIn('id', $cohortIds)->get();
        
        $comparison = [];
        
        foreach ($cohorts as $cohort) {
            $comparison[] = [
                'cohort' => $cohort,
                'metrics' => [
                    'member_count' => $cohort->member_count,
                    'avg_lifetime_value' => $this->getAverageLTV($cohort->id),
                    'avg_transaction_count' => $this->getAverageTransactions($cohort->id),
                    'retention_rates' => $this->getRetentionRates($cohort->id),
                    'total_revenue' => $this->getTotalRevenue($cohort->id)
                ]
            ];
        }

        return $comparison;
    }

    /**
     * Get cohort statistics
     */
    public function getCohortStats($websiteId)
    {
        // Calculate average retention across all active cohorts
        $activeCohorts = Cohort::where('website_id', $websiteId)->where('is_active', true)->get();
        $avgRetention = 0;
        if ($activeCohorts->count() > 0) {
            $totalRetention = 0;
            foreach ($activeCohorts as $cohort) {
                $totalRetention += $this->calculateRetentionRate($cohort->id, 30); // 30-day retention
            }
            $avgRetention = $totalRetention / $activeCohorts->count();
        }

        // Calculate average LTV
        $memberIds = CohortMember::whereHas('cohort', function($q) use ($websiteId) {
            $q->where('website_id', $websiteId);
        })->pluck('user_id')->unique();
        
        $avgLtv = 0;
        if ($memberIds->count() > 0) {
            $totalLtv = Donation::whereIn('user_id', $memberIds)->sum('amount');
            $avgLtv = $totalLtv / $memberIds->count();
        }

        return [
            'total_cohorts' => Cohort::where('website_id', $websiteId)->count(),
            'active_cohorts' => Cohort::where('website_id', $websiteId)->where('is_active', true)->count(),
            'total_members' => $memberIds->count(),
            'avg_retention' => round($avgRetention, 1),
            'avg_ltv' => round($avgLtv, 2),
            'cohorts_by_type' => Cohort::where('website_id', $websiteId)
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get()
        ];
    }

    // Helper methods
    
    private function calculateLifetimeValue($userId)
    {
        return Donation::where('user_id', $userId)->sum('amount');
    }

    private function getTransactionCount($userId)
    {
        return Donation::where('user_id', $userId)->count();
    }

    private function getLastActivity($userId)
    {
        $lastDonation = Donation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        return $lastDonation ? $lastDonation->created_at : null;
    }

    private function getAverageLTV($cohortId)
    {
        return CohortMember::where('cohort_id', $cohortId)
            ->avg('lifetime_value');
    }

    private function getAverageTransactions($cohortId)
    {
        return CohortMember::where('cohort_id', $cohortId)
            ->avg('transaction_count');
    }

    private function getRetentionRates($cohortId)
    {
        return CohortRetention::where('cohort_id', $cohortId)
            ->orderBy('period')
            ->pluck('retention_rate', 'period');
    }

    private function getTotalRevenue($cohortId)
    {
        return CohortMember::where('cohort_id', $cohortId)
            ->sum('lifetime_value');
    }

    /**
     * Calculate retention rate for a cohort at a specific period
     * 
     * @param int $cohortId
     * @param int $period Number of days from cohort start
     * @return float Retention rate as percentage
     */
    public function calculateRetentionRate($cohortId, $period = 30)
    {
        $cohort = Cohort::find($cohortId);
        if (!$cohort || $cohort->member_count == 0) {
            return 0;
        }

        $baseDate = $cohort->start_date ?? $cohort->created_at;
        $periodDate = Carbon::parse($baseDate)->addDays($period);
        
        // Count users who made a donation on or after this period
        $retainedUsers = CohortMember::where('cohort_id', $cohortId)
            ->whereHas('user.donations', function($q) use ($periodDate) {
                $q->whereDate('created_at', '>=', $periodDate);
            })
            ->count();

        return ($retainedUsers / $cohort->member_count) * 100;
    }
}
