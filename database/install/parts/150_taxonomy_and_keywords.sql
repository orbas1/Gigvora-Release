-- Shared taxonomy, placements, and keyword intelligence tables
CREATE TABLE IF NOT EXISTS `placements` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(255) not null,
    `channel` varchar(255) not null,
    `description` text,
    `is_active` tinyint(1) not null default '1',
    `created_at` datetime,
    `updated_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO placements VALUES(1,'newsfeed','web','Feed hero placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(2,'sidebar','sidebar','Sidebar placement imported from Sngine baseline.',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(3,'profile','web','Profile rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(4,'search','web','Search result placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(5,'gigs','gigs','Gigs placement imported from Sngine baseline.',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(6,'jobs','web','Jobs listing rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(7,'projects','projects','Projects placement imported from Sngine baseline.',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(8,'podcasts','podcasts','Podcasts placement imported from Sngine baseline.',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(9,'webinars','webinars','Webinars placement imported from Sngine baseline.',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(10,'networking','networking','Networking placement imported from Sngine baseline.',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(11,'newsfeed_inline','web','Feed inline placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(12,'newsfeed_lane','web','Feed recommendation rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(13,'jobs_detail','web','Job detail CTA placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(14,'freelance','web','Freelance listings placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(15,'freelance_detail','web','Freelance detail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(16,'freelance_dashboard','web','Freelance dashboard rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(17,'freelance_search','web','Freelance search result placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(18,'marketplace','web','Marketplace shelf placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(19,'marketplace_manager','web','Marketplace manager rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(20,'groups','web','Groups rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(21,'pages','web','Pages rail placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(22,'live_overlay','web','Live & events overlay placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(23,'story_interstitial','web','Stories interstitial placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO placements VALUES(24,'video_swipe','mobile','Video swipe (mobile) placement',1,'2025-12-03 05:54:50','2025-12-03 05:54:50');

CREATE TABLE IF NOT EXISTS `keyword_prices` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `keyword` varchar(255) not null,
    `cpc` numeric not null default '0',
    `cpa` numeric not null default '0',
    `cpm` numeric not null default '0',
    `created_at` datetime,
    `updated_at` datetime,
    `search_volume` bigint unsigned not null default '0',
    `competition_score` numeric not null default '0',
    `quality_score` numeric not null default '0.5',
    `ctr` numeric not null default '0',
    `conversion_rate` numeric not null default '0',
    `placement_multiplier` numeric not null default '1',
    `currency` varchar(255) not null default 'USD',
    `last_synced_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO keyword_prices VALUES(1,'networking',1.19999999999999995,6.5,3.29999999999999982,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(2,'jobs',1,7.5,3.89999999999999991,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(3,'freelance',0.900000000000000022,5.79999999999999982,3.10000000000000008,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(4,'podcast',0.800000000000000044,5.40000000000000035,2.89999999999999991,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(5,'webinar',1.10000000000000008,6.90000000000000035,3.60000000000000008,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);

CREATE TABLE IF NOT EXISTS `keyword_registry` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `keyword` varchar(255) not null,
    `normalized` varchar(255) not null,
    `source_type` varchar(255),
    `source_id` bigint unsigned,
    `country` varchar(255),
    `frequency` bigint unsigned not null default '1',
    `last_seen_at` datetime,
    `created_at` datetime,
    `updated_at` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO keyword_registry VALUES(1,'networking','networking','ads_keyword_price',1,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(2,'jobs','jobs','ads_keyword_price',2,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(3,'freelance','freelance','ads_keyword_price',3,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(4,'podcast','podcast','ads_keyword_price',4,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(5,'webinar','webinar','ads_keyword_price',5,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');

INSERT INTO sqlite_sequence VALUES('placements',24);
INSERT INTO sqlite_sequence VALUES('keyword_prices',5);
INSERT INTO sqlite_sequence VALUES('keyword_registry',5);

CREATE UNIQUE INDEX `keyword_prices_keyword_unique` on `keyword_prices` (`keyword`);
CREATE UNIQUE INDEX `keyword_registry_normalized_source_type_source_id_unique` on `keyword_registry` (`normalized`, `source_type`, `source_id`);
CREATE INDEX `keyword_registry_normalized_index` on `keyword_registry` (`normalized`);
CREATE INDEX `keyword_registry_source_type_index` on `keyword_registry` (`source_type`);
CREATE INDEX `keyword_registry_country_index` on `keyword_registry` (`country`);
