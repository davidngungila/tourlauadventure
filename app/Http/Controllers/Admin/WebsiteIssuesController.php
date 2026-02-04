<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerQuery;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WebsiteIssuesController extends Controller
{
    /**
     * Display unified website issues dashboard
     */
    public function index(Request $request)
    {
        // Get statistics for all issue types
        $stats = [
            'customer_queries' => [
                'total' => CustomerQuery::count(),
                'new' => CustomerQuery::where('status', 'new')->count(),
                'replied' => CustomerQuery::where('status', 'replied')->count(),
                'resolved' => CustomerQuery::where('status', 'resolved')->count(),
                'urgent' => CustomerQuery::where('priority', 'urgent')->whereIn('status', ['new', 'read'])->count(),
            ],
            'support_tickets' => [
                'total' => SupportTicket::count(),
                'open' => SupportTicket::where('status', 'open')->count(),
                'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
                'resolved' => SupportTicket::where('status', 'resolved')->count(),
                'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
            ],
            'system_health' => $this->getSystemHealth(),
        ];

        // Get recent issues
        $recentQueries = CustomerQuery::latest()->limit(5)->get();
        $recentTickets = SupportTicket::latest()->limit(5)->get();
        $systemIssues = $this->getSystemIssues();

        // Get active issues count
        $activeIssues = 
            $stats['customer_queries']['new'] + 
            $stats['customer_queries']['urgent'] +
            $stats['support_tickets']['open'] + 
            $stats['support_tickets']['urgent'] +
            count($systemIssues);

        return view('admin.issues.index', compact('stats', 'recentQueries', 'recentTickets', 'systemIssues', 'activeIssues'));
    }

    /**
     * Get system health status
     */
    protected function getSystemHealth()
    {
        try {
            $dbStatus = DB::connection()->getPdo() ? 'healthy' : 'unhealthy';
        } catch (\Exception $e) {
            $dbStatus = 'unhealthy';
        }

        $logPath = storage_path('logs/laravel.log');
        $logSize = file_exists($logPath) ? filesize($logPath) : 0;
        $logSizeMB = round($logSize / 1024 / 1024, 2);

        return [
            'database' => $dbStatus,
            'log_size_mb' => $logSizeMB,
            'log_warning' => $logSizeMB > 100, // Warning if log > 100MB
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];
    }

    /**
     * Get system issues
     */
    protected function getSystemIssues()
    {
        $issues = [];

        // Check database connection
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $issues[] = [
                'type' => 'critical',
                'title' => 'Database Connection Failed',
                'message' => 'Cannot connect to database',
                'icon' => 'ri-database-2-line',
            ];
        }

        // Check log file size
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $logSizeMB = round(filesize($logPath) / 1024 / 1024, 2);
            if ($logSizeMB > 100) {
                $issues[] = [
                    'type' => 'warning',
                    'title' => 'Large Log File',
                    'message' => "Log file is {$logSizeMB}MB. Consider clearing it.",
                    'icon' => 'ri-file-list-3-line',
                ];
            }
        }

        // Check storage permissions
        $storagePath = storage_path('app');
        if (!is_writable($storagePath)) {
            $issues[] = [
                'type' => 'warning',
                'title' => 'Storage Not Writable',
                'message' => 'Storage directory is not writable',
                'icon' => 'ri-folder-warning-line',
            ];
        }

        // Check .env file
        if (!file_exists(base_path('.env'))) {
            $issues[] = [
                'type' => 'critical',
                'title' => '.env File Missing',
                'message' => 'Environment configuration file not found',
                'icon' => 'ri-file-warning-line',
            ];
        }

        return $issues;
    }
}
