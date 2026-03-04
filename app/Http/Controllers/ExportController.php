<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportController extends Controller
{
    /**
     * Export analytics data
     */
    public function analytics(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,json,excel',
            'event_types' => 'nullable|array',
            'include_utm' => 'nullable|boolean',
            'include_location' => 'nullable|boolean'
        ]);

        $websiteId = Auth::user()->website_id ?? 1;
        
        $query = DB::table('analytics_events')
            ->where('website_id', $websiteId)
            ->whereBetween('created_at', [$validated['start_date'], $validated['end_date']]);

        if (!empty($validated['event_types'])) {
            $query->whereIn('event_type', $validated['event_types']);
        }

        $selectFields = [
            'id',
            'event_type',
            'page_url',
            'user_id',
            'session_id',
            'created_at'
        ];

        if ($validated['include_utm'] ?? true) {
            $selectFields = array_merge($selectFields, [
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content'
            ]);
        }

        if ($validated['include_location'] ?? true) {
            $selectFields = array_merge($selectFields, [
                'country',
                'city',
                'ip_address'
            ]);
        }

        $data = $query->select($selectFields)->get();

        return $this->export($data, 'analytics_export', $validated['format']);
    }

    /**
     * Export donations data
     */
    public function donations(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,json,excel',
            'status' => 'nullable|array',
            'min_amount' => 'nullable|numeric',
            'max_amount' => 'nullable|numeric'
        ]);

        $query = Donation::with('user')
            ->whereBetween('created_at', [$validated['start_date'], $validated['end_date']]);

        if (!empty($validated['status'])) {
            $query->whereIn('status', $validated['status']);
        }

        if (isset($validated['min_amount'])) {
            $query->where('amount', '>=', $validated['min_amount']);
        }

        if (isset($validated['max_amount'])) {
            $query->where('amount', '<=', $validated['max_amount']);
        }

        $donations = $query->get();

        $data = $donations->map(function($donation) {
            return [
                'ID' => $donation->id,
                'Donor Name' => $donation->user->name ?? 'Anonymous',
                'Donor Email' => $donation->user->email ?? 'N/A',
                'Amount' => $donation->amount,
                'Payment Method' => $donation->payment_method,
                'Status' => $donation->status,
                'Date' => $donation->created_at->format('Y-m-d H:i:s'),
                'Tax Receipt' => $donation->tax_receipt_number ?? 'N/A'
            ];
        });

        return $this->export($data, 'donations_export', $validated['format']);
    }

    /**
     * Export transactions data
     */
    public function transactions(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,json,excel',
            'payment_method' => 'nullable|array',
            'status' => 'nullable|array'
        ]);

        $query = Transaction::with('user')
            ->whereBetween('created_at', [$validated['start_date'], $validated['end_date']]);

        if (!empty($validated['payment_method'])) {
            $query->whereIn('payment_method', $validated['payment_method']);
        }

        if (!empty($validated['status'])) {
            $query->whereIn('status', $validated['status']);
        }

        $transactions = $query->get();

        $data = $transactions->map(function($transaction) {
            return [
                'Transaction ID' => $transaction->id,
                'User' => $transaction->user->name ?? 'Guest',
                'Amount' => $transaction->amount,
                'Payment Method' => $transaction->payment_method,
                'Status' => $transaction->status,
                'Transaction Date' => $transaction->created_at->format('Y-m-d H:i:s'),
                'Gateway' => $transaction->gateway ?? 'N/A',
                'Reference' => $transaction->transaction_id ?? 'N/A'
            ];
        });

        return $this->export($data, 'transactions_export', $validated['format']);
    }

    /**
     * Export users data
     */
    public function users(Request $request)
    {
        $validated = $request->validate([
            'format' => 'required|in:csv,json,excel',
            'include_donations' => 'nullable|boolean',
            'user_type' => 'nullable|in:all,donors,non_donors'
        ]);

        $query = User::query();

        if ($validated['user_type'] === 'donors') {
            $query->has('donations');
        } elseif ($validated['user_type'] === 'non_donors') {
            $query->doesntHave('donations');
        }

        if ($validated['include_donations'] ?? false) {
            $query->withCount('donations')
                  ->withSum('donations', 'amount');
        }

        $users = $query->get();

        $data = $users->map(function($user) use ($validated) {
            $row = [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Joined' => $user->created_at->format('Y-m-d'),
                'Last Login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never'
            ];

            if ($validated['include_donations'] ?? false) {
                $row['Total Donations'] = $user->donations_count ?? 0;
                $row['Total Amount'] = $user->donations_sum_amount ?? 0;
            }

            return $row;
        });

        return $this->export($data, 'users_export', $validated['format']);
    }

    /**
     * Custom export with SQL query
     */
    public function custom(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|string',
            'columns' => 'required|array',
            'filters' => 'nullable|array',
            'format' => 'required|in:csv,json,excel'
        ]);

        // Whitelist allowed tables for security
        $allowedTables = [
            'analytics_events',
            'donations',
            'transactions',
            'users',
            'cohorts',
            'cohort_members',
            'fraud_detections',
            'ab_tests',
            'ab_test_results'
        ];

        if (!in_array($validated['table'], $allowedTables)) {
            return response()->json([
                'error' => 'Invalid table name'
            ], 400);
        }

        $query = DB::table($validated['table'])
            ->select($validated['columns']);

        // Apply filters
        if (!empty($validated['filters'])) {
            foreach ($validated['filters'] as $filter) {
                if (isset($filter['column'], $filter['operator'], $filter['value'])) {
                    $query->where($filter['column'], $filter['operator'], $filter['value']);
                }
            }
        }

        $data = $query->get();

        return $this->export($data, "{$validated['table']}_export", $validated['format']);
    }

    /**
     * Export data to specified format
     */
    private function export($data, $filename, $format)
    {
        $timestamp = Carbon::now()->format('Y-m-d_His');
        $filename = "{$filename}_{$timestamp}";

        switch ($format) {
            case 'csv':
                return $this->exportCSV($data, "{$filename}.csv");
            
            case 'json':
                return $this->exportJSON($data, "{$filename}.json");
            
            case 'excel':
                // For Excel, we'll use CSV format for now
                // In production, use a library like PhpSpreadsheet
                return $this->exportCSV($data, "{$filename}.csv");
            
            default:
                return $this->exportCSV($data, "{$filename}.csv");
        }
    }

    /**
     * Export to CSV
     */
    private function exportCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                // Add BOM for Excel UTF-8 compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($file, array_keys((array)$data->first()));
                
                // Data
                foreach ($data as $row) {
                    fputcsv($file, (array)$row);
                }
            } else {
                fputcsv($file, ['No data available']);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to JSON
     */
    private function exportJSON($data, $filename)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->json($data, 200, $headers);
    }
}
