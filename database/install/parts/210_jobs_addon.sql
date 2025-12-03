-- Jobs addon tables
CREATE TABLE IF NOT EXISTS `company_profiles` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` bigint unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `headline` varchar(255),
    `description` text,
    `website` varchar(255),
    `location` varchar(255),
    `logo_path` varchar(255),
    `cover_path` varchar(255),
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `company_profiles_user_id_index` (`user_id`),
    CONSTRAINT `company_profiles_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `candidate_profiles` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` bigint unsigned NOT NULL,
    `headline` varchar(255),
    `bio` text,
    `location` varchar(255),
    `skills` json,
    `experience_years` int unsigned,
    `resume_path` varchar(255),
    `portfolio_url` varchar(255),
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `candidate_profiles_user_id_index` (`user_id`),
    CONSTRAINT `candidate_profiles_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `company_id` bigint unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `description` text NOT NULL,
    `location` varchar(255),
    `workplace_type` varchar(255),
    `employment_type` varchar(255),
    `salary_min` decimal(12,2),
    `salary_max` decimal(12,2),
    `currency` char(3),
    `status` varchar(255) NOT NULL DEFAULT 'draft',
    `published_at` timestamp NULL,
    `expires_at` timestamp NULL,
    `is_featured` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `jobs_company_id_index` (`company_id`),
    INDEX `jobs_status_index_overlay` (`status`),
    INDEX `jobs_status_published_at_index_overlay` (`status`,`published_at`),
    CONSTRAINT `jobs_company_fk` FOREIGN KEY (`company_id`) REFERENCES `company_profiles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs_categories` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(255) NOT NULL,
    `slug` varchar(255) NOT NULL UNIQUE,
    `created_at` datetime NULL,
    `updated_at` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO jobs_categories VALUES(1,'Engineering','engineering',NULL,NULL);
INSERT INTO jobs_categories VALUES(2,'Design','design',NULL,NULL);
INSERT INTO jobs_categories VALUES(3,'Marketing','marketing',NULL,NULL);
INSERT INTO jobs_categories VALUES(4,'Sales','sales',NULL,NULL);

CREATE TABLE IF NOT EXISTS `job_bookmarks` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `job_id` bigint unsigned NOT NULL,
    `user_id` bigint unsigned NOT NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    UNIQUE KEY `job_bookmarks_job_user_unique` (`job_id`,`user_id`),
    CONSTRAINT `job_bookmarks_job_fk` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE,
    CONSTRAINT `job_bookmarks_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cover_letters` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `candidate_id` bigint unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `body` longtext NOT NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `cover_letters_candidate_id_index` (`candidate_id`),
    CONSTRAINT `cover_letters_candidate_fk` FOREIGN KEY (`candidate_id`) REFERENCES `candidate_profiles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cv_templates` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `candidate_id` bigint unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` json NOT NULL,
    `is_default` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `cv_templates_candidate_id_index` (`candidate_id`),
    CONSTRAINT `cv_templates_candidate_fk` FOREIGN KEY (`candidate_id`) REFERENCES `candidate_profiles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_applications` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `job_id` bigint unsigned NOT NULL,
    `candidate_id` bigint unsigned NOT NULL,
    `cover_letter_id` bigint unsigned NULL,
    `cv_template_id` bigint unsigned NULL,
    `screening_score` int unsigned NULL,
    `status` varchar(255) NOT NULL DEFAULT 'applied',
    `notes` text,
    `resume_path` varchar(255),
    `applied_at` timestamp NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `job_applications_job_id_index` (`job_id`),
    INDEX `job_applications_candidate_id_index` (`candidate_id`),
    INDEX `job_applications_status_index` (`status`),
    CONSTRAINT `job_applications_job_fk` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE,
    CONSTRAINT `job_applications_candidate_fk` FOREIGN KEY (`candidate_id`) REFERENCES `candidate_profiles`(`id`) ON DELETE CASCADE,
    CONSTRAINT `job_applications_cover_letter_fk` FOREIGN KEY (`cover_letter_id`) REFERENCES `cover_letters`(`id`) ON DELETE SET NULL,
    CONSTRAINT `job_applications_cv_template_fk` FOREIGN KEY (`cv_template_id`) REFERENCES `cv_templates`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ats_pipelines` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `company_id` bigint unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `is_default` tinyint(1) NOT NULL DEFAULT 0,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `ats_pipelines_company_id_index` (`company_id`),
    CONSTRAINT `ats_pipelines_company_fk` FOREIGN KEY (`company_id`) REFERENCES `company_profiles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO ats_pipelines VALUES(1,0,'Default',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');

CREATE TABLE IF NOT EXISTS `ats_stages` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `ats_pipeline_id` bigint unsigned NOT NULL,
    `name` varchar(255) NOT NULL,
    `position` int unsigned NOT NULL DEFAULT 0,
    `color` varchar(255),
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `ats_stages_pipeline_id_index` (`ats_pipeline_id`),
    CONSTRAINT `ats_stages_pipeline_fk` FOREIGN KEY (`ats_pipeline_id`) REFERENCES `ats_pipelines`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO ats_stages VALUES(1,1,'Applied',1,'#2f855a','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO ats_stages VALUES(2,1,'Phone Screen',2,'#3182ce','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO ats_stages VALUES(3,1,'Interview',3,'#805ad5','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO ats_stages VALUES(4,1,'Offer',4,'#dd6b20','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO ats_stages VALUES(5,1,'Hired',5,'#38a169','2025-12-03 05:54:50','2025-12-03 05:54:50');

CREATE TABLE IF NOT EXISTS `ats_stage_assignments` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `job_application_id` bigint unsigned NOT NULL,
    `ats_stage_id` bigint unsigned NOT NULL,
    `moved_by` bigint unsigned NULL,
    `notes` text,
    `moved_at` timestamp NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `ats_stage_assignments_application_index` (`job_application_id`),
    INDEX `ats_stage_assignments_stage_index` (`ats_stage_id`),
    CONSTRAINT `ats_stage_assignments_application_fk` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE,
    CONSTRAINT `ats_stage_assignments_stage_fk` FOREIGN KEY (`ats_stage_id`) REFERENCES `ats_stages`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `screening_questions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `job_id` bigint unsigned NOT NULL,
    `question` varchar(255) NOT NULL,
    `type` varchar(255) NOT NULL DEFAULT 'text',
    `options` json,
    `is_required` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `screening_questions_job_id_index` (`job_id`),
    CONSTRAINT `screening_questions_job_fk` FOREIGN KEY (`job_id`) REFERENCES `jobs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `screening_answers` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `job_application_id` bigint unsigned NOT NULL,
    `screening_question_id` bigint unsigned NOT NULL,
    `answer` text NOT NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `screening_answers_application_index` (`job_application_id`),
    INDEX `screening_answers_question_index` (`screening_question_id`),
    CONSTRAINT `screening_answers_application_fk` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE,
    CONSTRAINT `screening_answers_question_fk` FOREIGN KEY (`screening_question_id`) REFERENCES `screening_questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `subscriptions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `company_id` bigint unsigned NOT NULL,
    `plan` varchar(255) NOT NULL,
    `job_credits` int unsigned NOT NULL DEFAULT 0,
    `renews_at` timestamp NULL,
    `status` varchar(255) NOT NULL DEFAULT 'active',
    `payment_reference` varchar(255),
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `subscriptions_company_id_index` (`company_id`),
    CONSTRAINT `subscriptions_company_fk` FOREIGN KEY (`company_id`) REFERENCES `company_profiles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `interview_schedules` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `job_application_id` bigint unsigned NOT NULL,
    `scheduled_at` timestamp NOT NULL,
    `location` varchar(255),
    `instructions` text,
    `meeting_link` varchar(255),
    `status` varchar(255) NOT NULL DEFAULT 'pending',
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    INDEX `interview_schedules_application_index` (`job_application_id`),
    CONSTRAINT `interview_schedules_application_fk` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
