CREATE TABLE IF NOT EXISTS `account_active_requests` (
    `id` int NOT NULL,
    `user_id` int NOT NULL,
    `status` varchar(100) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `activities` (
    `activity_id` int(11) NOT NULL,
    `activity_type` varchar(255) DEFAULT NULL,
    `title` int(11) DEFAULT NULL,
    `icon` int(11) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`activity_id`)
);

CREATE TABLE IF NOT EXISTS `addons` (
    `id` int(11) NOT NULL,
    `title` varchar(255) DEFAULT NULL,
    `parent_id` int(11) DEFAULT NULL,
    `features` varchar(255) DEFAULT NULL,
    `version` float DEFAULT NULL,
    `unique_identifier` varchar(255) DEFAULT NULL,
    `status` int(11) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `albums` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `page_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `sub_title` varchar(500) DEFAULT NULL,
    `thumbnail` varchar(255) DEFAULT NULL,
    `privacy` varchar(255) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `album_images` (
    `id` int(11) NOT NULL,
    `album_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `page_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `image` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `batchs` (
    `id` bigint(20)  NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` longtext,
    `icon` varchar(255) DEFAULT NULL,
    `status` int(11) DEFAULT NULL,
    `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `block_users` (
    `id` bigint(20)  NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `block_user` int(11) DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `blogcategories` (
    `id` int(11) NOT NULL,
    `name` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `blogs` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `category_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` longtext,
    `thumbnail` varchar(255) DEFAULT NULL,
    `banner` varchar(255) DEFAULT NULL,
    `status` varchar(100) DEFAULT NULL,
    `tag` text,
    `view` text,
    `created_at` varchar(100) NOT NULL,
    `updated_at` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `brands` (
    `id` int(11) NOT NULL,
    `name` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `categories` (
    `id` int(11) NOT NULL,
    `name` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `chats` (
    `id` int(11) NOT NULL,
    `message_thrade` int(11) DEFAULT NULL,
    `reciver_id` int(11) DEFAULT NULL,
    `sender_id` int(11) DEFAULT NULL,
    `message` text,
    `thumbsup` tinyint(1) NOT NULL DEFAULT '0',
    `file` text,
    `react` text,
    `reply_id` int(11) DEFAULT NULL,
    `chatcenter` text,
    `read_status` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `comments` (
    `comment_id` int(11) NOT NULL,
    `parent_id` int(11) NOT NULL DEFAULT '0',
    `user_id` int(11) DEFAULT NULL,
    `is_type` varchar(100) DEFAULT NULL,
    `id_of_type` int(11) DEFAULT NULL,
    `description` longtext,
    `user_reacts` longtext,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`comment_id`)
);

CREATE TABLE IF NOT EXISTS `currencies` (
    `id` int(11) NOT NULL,
    `name` varchar(255) DEFAULT NULL,
    `code` varchar(255) DEFAULT NULL,
    `symbol` varchar(255) DEFAULT NULL,
    `paypal_supported` int(11) DEFAULT NULL,
    `stripe_supported` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `events` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `publisher` varchar(100) DEFAULT NULL,
    `publisher_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` longtext,
    `event_date` varchar(100) DEFAULT NULL,
    `event_time` varchar(255) DEFAULT NULL,
    `location` text,
    `going_users_id` longtext,
    `interested_users_id` longtext,
    `thumbnail` varchar(255) DEFAULT NULL,
    `banner` varchar(255) DEFAULT NULL,
    `privacy` varchar(50) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `failed_jobs` (
    `id` bigint(20)  NOT NULL,
    `uuid` varchar(255) DEFAULT NULL,
    `connection` text,
    `queue` text,
    `payload` longtext,
    `exception` longtext,
    `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `feeling_and_activities` (
    `feeling_and_activity_id` int(11) NOT NULL,
    `type` varchar(255) NOT NULL,
    `title` varchar(255) NOT NULL,
    `icon` varchar(255) NOT NULL,
    `created_at` varchar(100) NOT NULL,
    `updated_at` varchar(100) NOT NULL,
    PRIMARY KEY (`feeling_and_activity_id`)
);

CREATE TABLE IF NOT EXISTS `followers` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `follow_id` int(11) DEFAULT NULL,
    `page_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `friendships` (
    `id` int(11) NOT NULL,
    `requester` int(11) DEFAULT NULL,
    `accepter` int(11) DEFAULT NULL,
    `importance` int(11) DEFAULT NULL,
    `is_accepted` int(11) DEFAULT NULL,
    `accepted_at` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `groups` (
    `id` int(11) NOT NULL,
    `user_id` text,
    `title` varchar(255) DEFAULT NULL,
    `subtitle` varchar(300) DEFAULT NULL,
    `privacy` varchar(255) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL,
    `group_type` varchar(300) DEFAULT NULL,
    `logo` varchar(255) DEFAULT NULL,
    `banner` varchar(255) DEFAULT NULL,
    `about` longtext,
    `status` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `group_members` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `is_accepted` varchar(10) DEFAULT NULL,
    `role` varchar(100) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `invites` (
    `id` bigint(20) NOT NULL,
    `invite_sender_id` int(11) DEFAULT NULL,
    `invite_reciver_id` int(11) DEFAULT NULL,
    `is_accepted` int(11) NOT NULL DEFAULT '0',
    `event_id` int(11) DEFAULT NULL,
    `page_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `post_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `live_streamings` (
    `streaming_id` int(11) NOT NULL,
    `publisher` varchar(255) DEFAULT NULL,
    `publisher_id` int(11) DEFAULT NULL,
    `user_id` int(11) DEFAULT NULL,
    `details` longtext,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`streaming_id`)
);

CREATE TABLE IF NOT EXISTS `marketplaces` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `currency_id` int(11) DEFAULT NULL,
    `price` varchar(15) DEFAULT NULL,
    `location` varchar(255) DEFAULT NULL,
    `category` text,
    `status` varchar(250) DEFAULT NULL,
    `condition` text,
    `brand` varchar(250) DEFAULT NULL,
    `buy_link` varchar(300) DEFAULT NULL,
    `description` text,
    `image` varchar(250) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `media_files` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `post_id` int(11) DEFAULT NULL,
    `story_id` int(11) DEFAULT NULL,
    `album_id` int(11) DEFAULT NULL,
    `product_id` int(11) DEFAULT NULL,
    `page_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `chat_id` int(11) DEFAULT NULL,
    `album_image_id` int(11) DEFAULT NULL,
    `file_name` varchar(255) DEFAULT NULL,
    `file_type` varchar(255) DEFAULT NULL,
    `privacy` varchar(200) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `message_thrades` (
    `id` int(11) NOT NULL,
    `reciver_id` int(11) DEFAULT NULL,
    `sender_id` int(11) DEFAULT NULL,
    `chatcenter` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `migrations` (
    `id` int(10)  NOT NULL,
    `migration` varchar(255) NOT NULL,
    `batch` int(11) NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `pagecategories` (
    `id` int(11) NOT NULL,
    `name` varchar(255) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `pages` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `subtitle` varchar(300) DEFAULT NULL,
    `page_type` varchar(300) DEFAULT NULL,
    `category_id` int(11) DEFAULT NULL,
    `logo` text,
    `coverphoto` varchar(255) DEFAULT NULL,
    `description` longtext,
    `job` text,
    `lifestyle` text,
    `location` text,
    `status` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `page_likes` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `page_id` int(11) DEFAULT NULL,
    `role` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `password_resets` (
    `email` varchar(255) NOT NULL,
    `token` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `payment_gateways` (
    `id` int(11) NOT NULL,
    `identifier` varchar(255) DEFAULT NULL,
    `currency` varchar(100) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `description` text,
    `keys` text,
    `model_name` varchar(255) DEFAULT NULL,
    `test_mode` int(11) DEFAULT NULL,
    `status` int(11) DEFAULT NULL,
    `is_addon` int(11) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `payment_histories` (
    `id` bigint(20) NOT NULL,
    `item_type` varchar(255) DEFAULT NULL,
    `item_id` bigint(20) DEFAULT NULL,
    `user_id` bigint(20) DEFAULT NULL,
    `amount` double DEFAULT NULL,
    `currency` varchar(100) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `identifier` varchar(255) DEFAULT NULL,
    `transaction_keys` longtext,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
    `id` bigint(20)  NOT NULL,
    `tokenable_type` varchar(255) NOT NULL,
    `tokenable_id` bigint(20)  NOT NULL,
    `name` varchar(255) NOT NULL,
    `token` varchar(64) NOT NULL,
    `abilities` text,
    `last_used_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    `expires_at` varchar(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS `posts` (
    `post_id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `publisher` varchar(100) DEFAULT NULL,
    `publisher_id` int(11) DEFAULT NULL,
    `post_type` varchar(255) DEFAULT NULL,
    `privacy` varchar(100) DEFAULT NULL,
    `tagged_user_ids` longtext,
    `activity_id` int(11) DEFAULT NULL,
    `location` varchar(300) DEFAULT NULL,
    `description` longtext,
    `status` varchar(100) DEFAULT NULL,
    `report_status` tinyint(1) NOT NULL DEFAULT '0',
    `user_reacts` longtext,
    `shared_user` text,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    `posted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `hashtag` varchar(255) DEFAULT NULL,
    `album_image_id` varchar(255) DEFAULT NULL,
    `mobile_app_image` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`post_id`)
);

CREATE TABLE IF NOT EXISTS `post_shares` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `post_id` int(11) DEFAULT NULL,
    `shared_on` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `reports` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `post_id` int(11) DEFAULT NULL,
    `report` text,
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `saved_products` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `product_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `saveforlaters` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `video_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `post_id` int(11) DEFAULT NULL,
    `marketplace_id` int(11) DEFAULT NULL,
    `event_id` int(11) DEFAULT NULL,
    `blog_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `shares` (
    `id` bigint(20) NOT NULL,
    `share_user_id` text,
    `event_id` int(11) DEFAULT NULL,
    `page_id` int(11) DEFAULT NULL,
    `group_id` int(11) DEFAULT NULL,
    `url` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sponsors` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `name` text,
    `description` text,
    `ext_url` text,
    `image` varchar(255) DEFAULT NULL,
    `paid_amount` double DEFAULT NULL,
    `status` int(11) DEFAULT NULL,
    `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `end_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `stories` (
    `story_id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `publisher` varchar(100) DEFAULT NULL,
    `publisher_id` int(11) DEFAULT NULL,
    `privacy` varchar(255) DEFAULT NULL,
    `content_type` varchar(255) DEFAULT NULL,
    `media_files` longtext,
    `description` longtext,
    `status` varchar(100) DEFAULT NULL,
    `created_at` varchar(100) DEFAULT NULL,
    `updated_at` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`story_id`)
);

CREATE TABLE IF NOT EXISTS `videos` (
    `id` int(11) NOT NULL,
    `user_id` int(11) DEFAULT NULL,
    `title` varchar(255) DEFAULT NULL,
    `category` text,
    `privacy` text,
    `file` text,
    `view` text,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `mobile_app_image` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
);
