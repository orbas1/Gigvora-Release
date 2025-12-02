<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $this->addJobIndexes();
        $this->addInteractiveIndexes();
        $this->addAuditIndexes();
        $this->addAdvertisementIndexes();
    }

    public function down(): void
    {
        Schema::table('job_bookmarks', function (Blueprint $table) {
            if (Schema::hasColumn('job_bookmarks', 'job_id') && Schema::hasColumn('job_bookmarks', 'user_id')) {
                $table->dropUnique('job_bookmarks_job_user_unique');
            }
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'job_applications_job_id_index');
            $this->dropIndexIfExists($table, 'job_applications_candidate_id_index');
            $this->dropIndexIfExists($table, 'job_applications_status_index');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'jobs_company_id_index');
            $this->dropIndexIfExists($table, 'jobs_status_index');
            $this->dropIndexIfExists($table, 'jobs_status_published_at_index');
        });

        Schema::table('webinars', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'webinars_host_id_index');
            $this->dropIndexIfExists($table, 'webinars_starts_at_index');
            $this->dropIndexIfExists($table, 'webinars_status_index');
        });

        Schema::table('networking_sessions', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'networking_sessions_host_id_index');
            $this->dropIndexIfExists($table, 'networking_sessions_starts_at_index');
        });

        Schema::table('podcast_series', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'podcast_series_host_id_index');
            $this->dropIndexIfExists($table, 'podcast_series_is_public_index');
        });

        Schema::table('interviews', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'interviews_host_id_index');
            $this->dropIndexIfExists($table, 'interviews_scheduled_at_index');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'audit_logs_target_index');
        });

        Schema::table('advertisers', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'advertisers_user_id_status_index');
        });

        Schema::table('ad_groups', function (Blueprint $table) {
            $this->dropIndexIfExists($table, 'ad_groups_campaign_id_status_index');
        });
    }

    protected function addJobIndexes(): void
    {
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->index('company_id');
                $table->index('status');
                $table->index(['status', 'published_at']);
            });
        }

        if (Schema::hasTable('job_applications')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $table->index('job_id');
                $table->index('candidate_id');
                $table->index('status');
            });
        }

        if (Schema::hasTable('job_bookmarks')) {
            Schema::table('job_bookmarks', function (Blueprint $table) {
                $table->unique(['job_id', 'user_id'], 'job_bookmarks_job_user_unique');
            });
        }
    }

    protected function addInteractiveIndexes(): void
    {
        if (Schema::hasTable('webinars')) {
            Schema::table('webinars', function (Blueprint $table) {
                $table->index('host_id');
                $table->index('starts_at');
                $table->index('status');
            });
        }

        if (Schema::hasTable('networking_sessions')) {
            Schema::table('networking_sessions', function (Blueprint $table) {
                $table->index('host_id');
                $table->index('starts_at');
            });
        }

        if (Schema::hasTable('podcast_series')) {
            Schema::table('podcast_series', function (Blueprint $table) {
                $table->index('host_id');
                $table->index('is_public');
            });
        }

        if (Schema::hasTable('interviews')) {
            Schema::table('interviews', function (Blueprint $table) {
                $table->index('host_id');
                $table->index('scheduled_at');
            });
        }
    }

    protected function addAuditIndexes(): void
    {
        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->index(['target_type', 'target_id'], 'audit_logs_target_index');
            });
        }
    }

    protected function addAdvertisementIndexes(): void
    {
        if (Schema::hasTable('advertisers')) {
            Schema::table('advertisers', function (Blueprint $table) {
                $table->index(['user_id', 'status']);
            });
        }

        if (Schema::hasTable('ad_groups')) {
            Schema::table('ad_groups', function (Blueprint $table) {
                $table->index(['campaign_id', 'status']);
            });
        }
    }

    protected function dropIndexIfExists(Blueprint $table, string $index): void
    {
        try {
            $table->dropIndex($index);
        } catch (\Throwable) {
            // ignore missing indexes for idempotence
        }
    }
};

