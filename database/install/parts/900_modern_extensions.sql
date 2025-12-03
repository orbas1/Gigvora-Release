-- Modern extensions to align Sociopro core with latest migrations and addons

-- Extend users with moderation controls if not already present
ALTER TABLE `users`
  ADD COLUMN `moderation_strikes` int unsigned DEFAULT 0 AFTER `profile_status`,
  ADD COLUMN `shadow_banned_until` timestamp NULL AFTER `moderation_strikes`,
  ADD COLUMN `banned_reason` varchar(255) NULL AFTER `shadow_banned_until`;

-- Enhance notifications to carry resource metadata
ALTER TABLE `notifications`
  ADD COLUMN `resource_type` varchar(50) NULL AFTER `type`,
  ADD COLUMN `resource_id` varchar(100) NULL AFTER `resource_type`,
  ADD COLUMN `title` varchar(255) NULL AFTER `view`,
  ADD COLUMN `message` text NULL AFTER `title`,
  ADD COLUMN `action_url` varchar(255) NULL AFTER `message`,
  ADD COLUMN `data` json NULL AFTER `action_url`;
ALTER TABLE `notifications` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Ads keyword pricing with intelligence columns
CREATE TABLE IF NOT EXISTS `keyword_prices` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `keyword` varchar(255) NOT NULL UNIQUE,
    `cpc` decimal(8,2) NOT NULL DEFAULT 0,
    `cpa` decimal(8,2) NOT NULL DEFAULT 0,
    `cpm` decimal(8,2) NOT NULL DEFAULT 0,
    `search_volume` int unsigned NOT NULL DEFAULT 0,
    `competition_score` decimal(6,4) NOT NULL DEFAULT 0,
    `quality_score` decimal(6,4) NOT NULL DEFAULT 0.5,
    `ctr` decimal(6,4) NOT NULL DEFAULT 0,
    `conversion_rate` decimal(6,4) NOT NULL DEFAULT 0,
    `placement_multiplier` decimal(6,4) NOT NULL DEFAULT 1,
    `currency` char(3) NOT NULL DEFAULT 'USD',
    `last_synced_at` timestamp NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `keyword_registry` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `keyword` varchar(255) NOT NULL,
    `normalized` varchar(255) NOT NULL,
    `source_type` varchar(255) NULL,
    `source_id` bigint unsigned NULL,
    `country` varchar(10) NULL,
    `frequency` int unsigned NOT NULL DEFAULT 1,
    `last_seen_at` timestamp NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    UNIQUE KEY `keyword_registry_unique` (`normalized`, `source_type`, `source_id`),
    INDEX `keyword_registry_normalized_index` (`normalized`),
    INDEX `keyword_registry_source_type_index` (`source_type`),
    INDEX `keyword_registry_country_index` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `actor_id` bigint unsigned NULL,
    `target_type` varchar(255) NULL,
    `target_id` bigint unsigned NULL,
    `action` varchar(255) NOT NULL,
    `changes` json NULL,
    `source` varchar(255) NOT NULL DEFAULT 'web',
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `audit_logs_target_index` (`target_type`, `target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `live_streaming_engagements` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `live_streaming_id` bigint unsigned NOT NULL,
    `user_id` bigint unsigned NULL,
    `type` varchar(32) NOT NULL,
    `amount` decimal(12,2) NOT NULL DEFAULT 0,
    `payload` json NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `live_streaming_engagements_stream_type_index` (`live_streaming_id`,`type`),
    CONSTRAINT `live_streaming_engagements_stream_fk` FOREIGN KEY (`live_streaming_id`) REFERENCES `live_streamings`(`streaming_id`) ON DELETE CASCADE,
    CONSTRAINT `live_streaming_engagements_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index overlays for MySQL readiness
ALTER TABLE `jobs` ADD INDEX `jobs_company_id_index` (`company_id`), ADD INDEX `jobs_status_index` (`status`), ADD INDEX `jobs_status_published_at_index` (`status`, `published_at`);
ALTER TABLE `job_applications` ADD INDEX `job_applications_job_id_index` (`job_id`), ADD INDEX `job_applications_candidate_id_index` (`candidate_id`), ADD INDEX `job_applications_status_index` (`status`);
ALTER TABLE `job_bookmarks` ADD UNIQUE KEY `job_bookmarks_job_user_unique` (`job_id`, `user_id`);
ALTER TABLE `webinars` ADD INDEX `webinars_host_id_index` (`host_id`), ADD INDEX `webinars_starts_at_index` (`starts_at`), ADD INDEX `webinars_status_index` (`status`);
ALTER TABLE `networking_sessions` ADD INDEX `networking_sessions_host_id_index` (`host_id`), ADD INDEX `networking_sessions_starts_at_index` (`starts_at`);
ALTER TABLE `podcast_series` ADD INDEX `podcast_series_host_id_index` (`host_id`), ADD INDEX `podcast_series_is_public_index` (`is_public`);
ALTER TABLE `interviews` ADD INDEX `interviews_host_id_index` (`host_id`), ADD INDEX `interviews_scheduled_at_index` (`scheduled_at`);
ALTER TABLE `advertisers` ADD INDEX `advertisers_user_id_status_index` (`user_id`, `status`);
ALTER TABLE `ad_groups` ADD INDEX `ad_groups_campaign_id_status_index` (`campaign_id`, `status`);
