<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\LiveStreamingEngagement;
use App\Models\Live_streamings;
use App\Models\Media_files;
use App\Models\Notification;
use App\Models\Posts;
use App\Models\UtilitiesCalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminMetricsService
{
    public function overview(int $days = 30): array
    {
        $since = Carbon::now()->subDays($days);

        return [
            'timeframe_days' => $days,
            'posts' => [
                'total' => Posts::count(),
                'recent' => Posts::where('created_at', '>=', $since)->count(),
                'composer_modes' => $this->composerModeCounts($since),
            ],
            'media' => [
                'reels' => Media_files::where('is_reel', true)->count(),
                'long_video' => Media_files::longVideos()->count(),
                'with_processing_manifest' => Media_files::whereNotNull('processing_manifest')->count(),
            ],
            'live' => [
                'sessions' => Live_streamings::where('created_at', '>=', $since)->count(),
                'avg_viewer_peak' => Live_streamings::whereNotNull('viewer_peak')->avg('viewer_peak'),
                'engagements' => LiveStreamingEngagement::where('created_at', '>=', $since)->count(),
            ],
            'utilities' => [
                'calendar_events' => UtilitiesCalendarEvent::where('created_at', '>=', $since)->count(),
                'notifications' => Notification::where('created_at', '>=', $since)->count(),
            ],
            'audit' => [
                'logs' => AuditLog::where('created_at', '>=', $since)->count(),
                'recent_security' => AuditLog::where('created_at', '>=', $since)
                    ->whereIn('action', ['admin.login', 'role.change', 'gdpr.export', 'gdpr.erase', 'incident.report'])
                    ->count(),
            ],
            'queues' => $this->queueHealth(),
            'integrations' => $this->integrationHealth($since),
        ];
    }

    public function addonDashboard(int $days = 30): array
    {
        $since = Carbon::now()->subDays($days);

        return [
            'jobs' => $this->addonMetrics('job', $since),
            'freelance' => $this->addonMetrics('freelance', $since),
            'ads' => $this->addonMetrics('ads', $since),
            'interactive' => $this->addonMetrics('interactive', $since),
            'ai' => $this->addonMetrics('ai', $since),
            'utilities' => $this->addonMetrics('utilities', $since),
        ];
    }

    protected function composerModeCounts(Carbon $since): array
    {
        $modes = ['job', 'freelance', 'ads', 'interactive', 'ai', 'utilities', 'standard'];

        $counts = [];
        foreach ($modes as $mode) {
            $counts[$mode] = Posts::where('composer_mode', $mode)
                ->where('created_at', '>=', $since)
                ->count();
        }

        return $counts;
    }

    protected function queueHealth(): array
    {
        $health = [
            'jobs' => 0,
            'failed' => 0,
            'latest_failed_at' => null,
        ];

        if (Schema::hasTable('jobs')) {
            $health['jobs'] = DB::table('jobs')->count();
        }

        if (Schema::hasTable('failed_jobs')) {
            $health['failed'] = DB::table('failed_jobs')->count();
            $health['latest_failed_at'] = DB::table('failed_jobs')->max('failed_at');
        }

        return $health;
    }

    protected function integrationHealth(Carbon $since): array
    {
        $actions = ['integration.error', 'payment.error', 'streaming.error', 'ai.error'];

        $recentErrors = AuditLog::where('created_at', '>=', $since)
            ->whereIn('action', $actions)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return [
            'recent_errors' => $recentErrors,
            'has_errors' => $recentErrors->isNotEmpty(),
        ];
    }

    protected function addonMetrics(string $mode, Carbon $since): array
    {
        $posts = Posts::where('composer_mode', $mode);

        return [
            'total' => $posts->count(),
            'recent' => (clone $posts)->where('created_at', '>=', $since)->count(),
            'flagged' => AuditLog::where('target_type', Posts::class)
                ->where('action', 'content.flagged')
                ->whereJsonContains('changes->composer_mode', $mode)
                ->count(),
            'reports' => AuditLog::where('target_type', Posts::class)
                ->where('action', 'report.received')
                ->whereJsonContains('changes->composer_mode', $mode)
                ->count(),
        ];
    }
}

