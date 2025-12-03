-- Gigvora master install preamble
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;
DELETE FROM sqlite_sequence;
-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 25, 2024 at 10:07 AM
-- Server version: 5.7.24
-- PHP Version: 8.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tmain`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_active_requests`
--

CREATE TABLE `account_active_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `status` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------


--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` int(11) NOT NULL,
  `activity_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `features` varchar(255) DEFAULT NULL,
  `version` float DEFAULT NULL,
  `unique_identifier` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_title` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `album_images`
--

CREATE TABLE `album_images` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `image` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `batchs`
--

CREATE TABLE `batchs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `block_users`
--

CREATE TABLE `block_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `block_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blogcategories`
--

CREATE TABLE `blogcategories` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag` text COLLATE utf8_unicode_ci,
  `view` text COLLATE utf8_unicode_ci,
  `created_at` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `message_thrade` int(11) DEFAULT NULL,
  `reciver_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `thumbsup` tinyint(1) NOT NULL DEFAULT '0',
  `file` text,
  `react` text,
  `reply_id` int(11) DEFAULT NULL,
  `chatcenter` text,
  `read_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `is_type` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'post, event, any other type post''s comment',
  `id_of_type` int(11) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `user_reacts` longtext COLLATE utf8_unicode_ci,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `symbol` varchar(255) DEFAULT NULL,
  `paypal_supported` int(11) DEFAULT NULL,
  `stripe_supported` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `paypal_supported`, `stripe_supported`) VALUES
(1, 'Leke', 'ALL', 'Lek', 0, 1),
(2, 'Dollars', 'USD', '$', 1, 1),
(3, 'Afghanis', 'AFN', '؋', 0, 1),
(4, 'Pesos', 'ARS', '$', 0, 1),
(5, 'Guilders', 'AWG', 'ƒ', 0, 1),
(6, 'Dollars', 'AUD', '$', 1, 1),
(7, 'New Manats', 'AZN', 'ман', 0, 1),
(8, 'Dollars', 'BSD', '$', 0, 1),
(9, 'Dollars', 'BBD', '$', 0, 1),
(10, 'Rubles', 'BYR', 'p.', 0, 0),
(11, 'Euro', 'EUR', '€', 1, 1),
(12, 'Dollars', 'BZD', 'BZ$', 0, 1),
(13, 'Dollars', 'BMD', '$', 0, 1),
(14, 'Bolivianos', 'BOB', '$b', 0, 1),
(15, 'Convertible Marka', 'BAM', 'KM', 0, 1),
(16, 'Pula', 'BWP', 'P', 0, 1),
(17, 'Leva', 'BGN', 'лв', 0, 1),
(18, 'Reais', 'BRL', 'R$', 1, 1),
(19, 'Pounds', 'GBP', '£', 1, 1),
(20, 'Dollars', 'BND', '$', 0, 1),
(21, 'Riels', 'KHR', '៛', 0, 1),
(22, 'Dollars', 'CAD', '$', 1, 1),
(23, 'Dollars', 'KYD', '$', 0, 1),
(24, 'Pesos', 'CLP', '$', 0, 1),
(25, 'Yuan Renminbi', 'CNY', '¥', 0, 1),
(26, 'Pesos', 'COP', '$', 0, 1),
(27, 'Colón', 'CRC', '₡', 0, 1),
(28, 'Kuna', 'HRK', 'kn', 0, 1),
(29, 'Pesos', 'CUP', '₱', 0, 0),
(30, 'Koruny', 'CZK', 'Kč', 1, 1),
(31, 'Kroner', 'DKK', 'kr', 1, 1),
(32, 'Pesos', 'DOP ', 'RD$', 0, 1),
(33, 'Dollars', 'XCD', '$', 0, 1),
(34, 'Pounds', 'EGP', '£', 0, 1),
(35, 'Colones', 'SVC', '$', 0, 0),
(36, 'Pounds', 'FKP', '£', 0, 1),
(37, 'Dollars', 'FJD', '$', 0, 1),
(38, 'Cedis', 'GHC', '¢', 0, 0),
(39, 'Pounds', 'GIP', '£', 0, 1),
(40, 'Quetzales', 'GTQ', 'Q', 0, 1),
(41, 'Pounds', 'GGP', '£', 0, 0),
(42, 'Dollars', 'GYD', '$', 0, 1),
(43, 'Lempiras', 'HNL', 'L', 0, 1),
(44, 'Dollars', 'HKD', '$', 1, 1),
(45, 'Forint', 'HUF', 'Ft', 1, 1),
(46, 'Kronur', 'ISK', 'kr', 0, 1),
(47, 'Rupees', 'INR', 'Rp', 1, 1),
(48, 'Rupiahs', 'IDR', 'Rp', 0, 1),
(49, 'Rials', 'IRR', '﷼', 0, 0),
(50, 'Pounds', 'IMP', '£', 0, 0),
(51, 'New Shekels', 'ILS', '₪', 1, 1),
(52, 'Dollars', 'JMD', 'J$', 0, 1),
(53, 'Yen', 'JPY', '¥', 1, 1),
(54, 'Pounds', 'JEP', '£', 0, 0),
(55, 'Tenge', 'KZT', 'лв', 0, 1),
(56, 'Won', 'KPW', '₩', 0, 0),
(57, 'Won', 'KRW', '₩', 0, 1),
(58, 'Soms', 'KGS', 'лв', 0, 1),
(59, 'Kips', 'LAK', '₭', 0, 1),
(60, 'Lati', 'LVL', 'Ls', 0, 0),
(61, 'Pounds', 'LBP', '£', 0, 1),
(62, 'Dollars', 'LRD', '$', 0, 1),
(63, 'Switzerland Francs', 'CHF', 'CHF', 1, 1),
(64, 'Litai', 'LTL', 'Lt', 0, 0),
(65, 'Denars', 'MKD', 'ден', 0, 1),
(66, 'Ringgits', 'MYR', 'RM', 1, 1),
(67, 'Rupees', 'MUR', '₨', 0, 1),
(68, 'Pesos', 'MXN', '$', 1, 1),
(69, 'Tugriks', 'MNT', '₮', 0, 1),
(70, 'Meticais', 'MZN', 'MT', 0, 1),
(71, 'Dollars', 'NAD', '$', 0, 1),
(72, 'Rupees', 'NPR', '₨', 0, 1),
(73, 'Guilders', 'ANG', 'ƒ', 0, 1),
(74, 'Dollars', 'NZD', '$', 1, 1),
(75, 'Cordobas', 'NIO', 'C$', 0, 1),
(76, 'Nairas', 'NGN', '₦', 0, 1),
(77, 'Krone', 'NOK', 'kr', 1, 1),
(78, 'Rials', 'OMR', '﷼', 0, 0),
(79, 'Rupees', 'PKR', '₨', 0, 1),
(80, 'Balboa', 'PAB', 'B/.', 0, 1),
(81, 'Guarani', 'PYG', 'Gs', 0, 1),
(82, 'Nuevos Soles', 'PEN', 'S/.', 0, 1),
(83, 'Pesos', 'PHP', 'Php', 1, 1),
(84, 'Zlotych', 'PLN', 'zł', 1, 1),
(85, 'Rials', 'QAR', '﷼', 0, 1),
(86, 'New Lei', 'RON', 'lei', 0, 1),
(87, 'Rubles', 'RUB', 'руб', 1, 1),
(88, 'Pounds', 'SHP', '£', 0, 1),
(89, 'Riyals', 'SAR', '﷼', 0, 1),
(90, 'Dinars', 'RSD', 'Дин.', 0, 1),
(91, 'Rupees', 'SCR', '₨', 0, 1),
(92, 'Dollars', 'SGD', '$', 1, 1),
(93, 'Dollars', 'SBD', '$', 0, 1),
(94, 'Shillings', 'SOS', 'S', 0, 1),
(95, 'Rand', 'ZAR', 'R', 0, 1),
(96, 'Rupees', 'LKR', '₨', 0, 1),
(97, 'Kronor', 'SEK', 'kr', 1, 1),
(98, 'Dollars', 'SRD', '$', 0, 1),
(99, 'Pounds', 'SYP', '£', 0, 0),
(100, 'New Dollars', 'TWD', 'NT$', 1, 1),
(101, 'Baht', 'THB', '฿', 1, 1),
(102, 'Dollars', 'TTD', 'TT$', 0, 1),
(103, 'Lira', 'TRY', 'TL', 0, 1),
(104, 'Liras', 'TRL', '£', 0, 0),
(105, 'Dollars', 'TVD', '$', 0, 0),
(106, 'Hryvnia', 'UAH', '₴', 0, 1),
(107, 'Pesos', 'UYU', '$U', 0, 1),
(108, 'Sums', 'UZS', 'лв', 0, 1),
(109, 'Bolivares Fuertes', 'VEF', 'Bs', 0, 0),
(110, 'Dong', 'VND', '₫', 0, 1),
(111, 'Rials', 'YER', '﷼', 0, 1),
(112, 'Zimbabwe Dollars', 'ZWD', 'Z$', 0, 0),
(113, 'Taka', 'BDT', '৳', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `publisher` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `event_date` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `event_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` text COLLATE utf8_unicode_ci,
  `going_users_id` longtext COLLATE utf8_unicode_ci,
  `interested_users_id` longtext COLLATE utf8_unicode_ci,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci,
  `queue` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci,
  `exception` longtext COLLATE utf8mb4_unicode_ci,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeling_and_activities`
--

CREATE TABLE `feeling_and_activities` (
  `feeling_and_activity_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `feeling_and_activities`
--

INSERT INTO `feeling_and_activities` (`feeling_and_activity_id`, `type`, `title`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'activity', 'Traveling', 'travelling.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(2, 'activity', 'Watching', 'watch.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(3, 'activity', 'Listening', 'listen.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(4, 'activity', 'Playing', 'playing.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(5, 'activity', 'Relaxed', 'relax.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(6, 'feeling', 'Happy', 'happy.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(7, 'feeling', 'Lovely', 'lovely.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(8, 'feeling', 'Loved', 'loved.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(9, 'feeling', 'Fun', 'fun.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(10, 'feeling', 'Crazy', 'crazy.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(11, 'feeling', 'Relaxed', 'relax.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(12, 'feeling', 'Happy blessed', 'blessed.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(13, 'feeling', 'Lovely Sad', 'r-cry1.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(14, 'feeling', 'Loved Thankful', 'r-care.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(15, 'feeling', 'Fun Cool', 'cool.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(16, 'feeling', 'Crazy Surprised', 'amused.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(17, 'feeling', 'Relaxed Angry', 'angry.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49'),
(18, 'feeling', 'Relaxed Heartbroken', 'surprise.png', '2023-04-05 14:11:49', '2023-04-05 14:11:49');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `follow_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `id` int(11) NOT NULL,
  `requester` int(11) DEFAULT NULL,
  `accepter` int(11) DEFAULT NULL,
  `importance` int(11) DEFAULT NULL,
  `is_accepted` int(11) DEFAULT NULL,
  `accepted_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `user_id` text COLLATE utf8_unicode_ci,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtitle` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group_type` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banner` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `about` longtext COLLATE utf8_unicode_ci,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `is_accepted` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE `invites` (
  `id` bigint(20) NOT NULL,
  `invite_sender_id` int(11) DEFAULT NULL,
  `invite_reciver_id` int(11) DEFAULT NULL,
  `is_accepted` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phrase` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translated` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `phrase`, `translated`, `created_at`, `updated_at`) VALUES
(1, 'english', 'English', 'English', '2023-04-05 11:34:21', '2023-04-05 11:34:21'),
(2, 'english', 'Login', 'Login', '2023-06-19 11:39:31', '2023-06-19 11:39:31'),
(3, 'english', 'Activate', 'Activate', '2023-10-09 10:35:35', '2023-10-09 10:35:35'),
(784, 'english', 'Email', 'Email', '2024-01-08 10:57:23', '2024-01-08 10:57:23'),
(785, 'english', 'Enter your email address', 'Enter your email address', '2024-01-08 10:57:23', '2024-01-08 10:57:23'),
(786, 'english', 'Password', 'Password', '2024-01-08 10:57:23', '2024-01-08 10:57:23'),
(787, 'english', 'Your password', 'Your password', '2024-01-08 10:57:23', '2024-01-08 10:57:23'),
(788, 'english', 'Remember me', 'Remember me', '2024-01-08 10:57:23', '2024-01-08 10:57:23'),
(789, 'english', 'Forgot your password?', 'Forgot your password?', '2024-01-08 10:57:23', '2024-01-08 10:57:23'),
(790, 'english', 'My Profile', 'My Profile', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(791, 'english', 'Go to admin panel', 'Go to admin panel', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(792, 'english', 'Addons', 'Addons', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(793, 'english', 'Change Password', 'Change Password', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(794, 'english', 'Log Out', 'Log Out', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(795, 'english', 'Timeline', 'Timeline', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(796, 'english', 'Profile', 'Profile', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(797, 'english', 'Group', 'Group', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(798, 'english', 'Page', 'Page', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(799, 'english', 'Marketplace', 'Marketplace', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(800, 'english', 'Video and Shorts', 'Video and Shorts', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(801, 'english', 'Event', 'Event', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(802, 'english', 'Blog', 'Blog', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(803, 'english', 'About', 'About', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(804, 'english', 'Privacy Policy', 'Privacy Policy', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(805, 'english', 'Create story', 'Create story', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(806, 'english', 'What\'s on your mind ____', 'What\'s on your mind ____', '2024-01-08 10:57:33', '2024-01-08 10:57:33'),
(807, 'english', 'Create Post', 'Create Post', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(808, 'english', 'Public', 'Public', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(809, 'english', 'Only Me', 'Only Me', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(810, 'english', 'Friends', 'Friends', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(811, 'english', 'Click to browse', 'Click to browse', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(812, 'english', 'Tag People', 'Tag People', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(813, 'english', 'Tagged', 'Tagged', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(814, 'english', 'Search more peoples', 'Search more peoples', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(815, 'english', 'Suggestions', 'Suggestions', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(816, 'english', 'What are you doing', 'What are you doing', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(817, 'english', 'Activities', 'Activities', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(818, 'english', 'How are you feeling', 'How are you feeling', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(819, 'english', 'Feelings', 'Feelings', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(820, 'english', 'Search for location', 'Search for location', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(821, 'english', 'Determine your location', 'Determine your location', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(822, 'english', 'Add to your post', 'Add to your post', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(823, 'english', 'Publish Now', 'Publish Now', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(824, 'english', 'Processing', 'Processing', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(825, 'english', 'Uploading', 'Uploading', '2024-01-08 10:57:34', '2024-01-08 10:57:34'),
(826, 'english', 'Link Copied', 'Link Copied', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(827, 'english', 'Hi', 'Hi', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(828, 'english', 'Good Afternoon', 'Good Afternoon', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(829, 'english', 'Sponsored', 'Sponsored', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(830, 'english', 'Active users', 'Active users', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(831, 'english', 'Loading...', 'Loading...', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(832, 'english', 'Create new story', 'Create new story', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(833, 'english', 'Stories', 'Stories', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(834, 'english', 'Confirmation', 'Confirmation', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(835, 'english', 'Are you sure', 'Are you sure', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(836, 'english', 'Cancel', 'Cancel', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(837, 'english', 'Continue', 'Continue', '2024-01-08 10:57:35', '2024-01-08 10:57:35'),
(838, 'english', '404 page not found', '404 page not found', '2024-01-08 10:57:37', '2024-01-08 10:57:37'),
(839, 'english', '404 page not found', '404 page not found', '2024-01-08 10:57:37', '2024-01-08 10:57:37'),
(840, 'english', 'This page is not available, please provide a valid URL', 'This page is not available, please provide a valid URL', '2024-01-08 10:57:37', '2024-01-08 10:57:37'),
(841, 'english', 'Back', 'Back', '2024-01-08 10:57:37', '2024-01-08 10:57:37'),
(842, 'english', 'Dashboard', 'Dashboard', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(843, 'english', 'User', 'User', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(844, 'english', 'Users', 'Users', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(845, 'english', 'Create new user', 'Create new user', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(846, 'english', 'Pages', 'Pages', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(847, 'english', 'Create Page', 'Create Page', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(848, 'english', 'Category', 'Category', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(849, 'english', 'Create Category', 'Create Category', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(850, 'english', 'Brand', 'Brand', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(851, 'english', 'Blogs', 'Blogs', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(852, 'english', 'Create Blog', 'Create Blog', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(853, 'english', 'Sponsored Post', 'Sponsored Post', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(854, 'english', 'Ads', 'Ads', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(855, 'english', 'Create Ad', 'Create Ad', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(856, 'english', 'Reported Post', 'Reported Post', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(857, 'english', 'Payment history', 'Payment history', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(858, 'english', 'Settings', 'Settings', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(859, 'english', 'System Setting', 'System Setting', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(860, 'english', 'Amazon s3 settings', 'Amazon s3 settings', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(861, 'english', 'Custom Pages', 'Custom Pages', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(862, 'english', 'Payment Setting', 'Payment Setting', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(863, 'english', 'Language Setting', 'Language Setting', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(864, 'english', 'SMTP Setting', 'SMTP Setting', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(865, 'english', 'Visit Website', 'Visit Website', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(866, 'english', 'My Account', 'My Account', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(867, 'english', 'Total Users', 'Total Users', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(868, 'english', 'Post', 'Post', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(869, 'english', 'Total Posts', 'Total Posts', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(870, 'english', 'Total Pages', 'Total Pages', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(871, 'english', 'Total Blogs', 'Total Blogs', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(872, 'english', 'Ad', 'Ad', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(873, 'english', 'Total Sponsored Posts', 'Total Sponsored Posts', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(874, 'english', 'Marketplace Products', 'Marketplace Products', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(875, 'english', 'Total Products', 'Total Products', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(876, 'english', 'By ____', 'By ____', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(877, 'english', 'Number of user', 'Number of user', '2024-01-08 10:57:42', '2024-01-08 10:57:42'),
(878, 'english', 'System Settings', 'System Settings', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(879, 'english', 'System Name', 'System Name', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(880, 'english', 'System Title', 'System Title', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(881, 'english', 'System Email', 'System Email', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(882, 'english', 'System Phone', 'System Phone', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(883, 'english', 'System Fax', 'System Fax', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(884, 'english', 'Address', 'Address', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(885, 'english', 'System currency', 'System currency', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(886, 'english', 'System language', 'System language', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(887, 'english', 'Public signup', 'Public signup', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(888, 'english', 'enabled', 'enabled', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(889, 'english', 'disabled', 'disabled', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(890, 'english', 'Ad charge per day', 'Ad charge per day', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(891, 'english', 'Footer', 'Footer', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(892, 'english', 'Footer Link', 'Footer Link', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(893, 'english', 'Aoogle Analytics Id', 'Aoogle Analytics Id', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(894, 'english', 'Commission on Paid content', 'Commission on Paid content', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(895, 'english', 'Update', 'Update', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(896, 'english', 'Product Update', 'Product Update', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(897, 'english', 'SYSTEM LOGO', 'SYSTEM LOGO', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(898, 'english', 'Dark logo', 'Dark logo', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(899, 'english', 'Light logo', 'Light logo', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(900, 'english', 'Favicon', 'Favicon', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(901, 'english', 'Update Logo', 'Update Logo', '2024-01-08 10:57:47', '2024-01-08 10:57:47'),
(902, 'english', 'Version updated successfully', 'Version updated successfully', '2024-01-08 10:58:12', '2024-01-08 10:58:12'),
(903, 'english', 'Not found', 'Not found', '2024-01-08 10:58:17', '2024-01-08 10:58:17'),
(904, 'english', 'About this application', 'About this application', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(905, 'english', 'Software version', 'Software version', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(906, 'english', 'Check update', 'Check update', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(907, 'english', 'PHP version', 'PHP version', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(908, 'english', 'Curl enable', 'Curl enable', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(909, 'english', 'Purchase code', 'Purchase code', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(910, 'english', 'Product license', 'Product license', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(911, 'english', 'invalid', 'invalid', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(912, 'english', 'Enter valid purchase code', 'Enter valid purchase code', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(913, 'english', 'Customer support status', 'Customer support status', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(914, 'english', 'Support expiry date', 'Support expiry date', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(915, 'english', 'Customer name', 'Customer name', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(916, 'english', 'Get customer support', 'Get customer support', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(917, 'english', 'Customer support', 'Customer support', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(918, 'english', 'Enter your purchase code', 'Enter your purchase code', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(919, 'english', 'Invalid purchase code', 'Invalid purchase code', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(920, 'english', 'Submit', 'Submit', '2024-01-08 10:58:19', '2024-01-08 10:58:19'),
(921, 'english', 'My articles', 'My articles', '2024-01-08 10:59:07', '2024-01-08 10:59:07'),
(922, 'english', 'Create New Article', 'Create New Article', '2024-01-08 10:59:09', '2024-01-08 10:59:09'),
(923, 'english', 'Title', 'Title', '2024-01-08 10:59:09', '2024-01-08 10:59:09'),
(924, 'english', 'Select Category', 'Select Category', '2024-01-08 10:59:09', '2024-01-08 10:59:09'),
(925, 'english', 'Tags', 'Tags', '2024-01-08 10:59:09', '2024-01-08 10:59:09'),
(926, 'english', 'Description', 'Description', '2024-01-08 10:59:09', '2024-01-08 10:59:09'),
(927, 'english', 'Image', 'Image', '2024-01-08 10:59:09', '2024-01-08 10:59:09'),
(928, 'english', 'Watch', 'Watch', '2024-01-08 11:04:41', '2024-01-08 11:04:41'),
(929, 'english', 'Create Video & Shorts ', 'Create Video & Shorts ', '2024-01-08 11:04:41', '2024-01-08 11:04:41'),
(930, 'english', 'Create', 'Create', '2024-01-08 11:04:41', '2024-01-08 11:04:41'),
(931, 'english', 'Shorts', 'Shorts', '2024-01-08 11:04:41', '2024-01-08 11:04:41'),
(932, 'english', 'Videos', 'Videos', '2024-01-08 11:04:41', '2024-01-08 11:04:41'),
(933, 'english', 'Good Evening', 'Good Evening', '2024-01-08 11:04:41', '2024-01-08 11:04:41'),
(934, 'english', 'Private', 'Private', '2024-01-08 11:04:46', '2024-01-08 11:04:46'),
(935, 'english', 'Video', 'Video', '2024-01-08 11:04:46', '2024-01-08 11:04:46'),
(936, 'english', 'Video/Shorts', 'Video/Shorts', '2024-01-08 11:04:46', '2024-01-08 11:04:46'),
(937, 'english', 'Install addon', 'Install addon', '2024-01-08 11:08:36', '2024-01-08 11:08:36'),
(938, 'english', 'Sl No', 'Sl No', '2024-01-08 11:08:36', '2024-01-08 11:08:36'),
(939, 'english', 'Name', 'Name', '2024-01-08 11:08:36', '2024-01-08 11:08:36'),
(940, 'english', 'Version', 'Version', '2024-01-08 11:08:36', '2024-01-08 11:08:36'),
(941, 'english', 'Status', 'Status', '2024-01-08 11:08:36', '2024-01-08 11:08:36'),
(942, 'english', 'Action', 'Action', '2024-01-08 11:08:36', '2024-01-08 11:08:36'),
(943, 'english', 'All Blogs', 'All Blogs', '2024-01-08 11:08:39', '2024-01-08 11:08:39'),
(944, 'english', 'Blog owner', 'Blog owner', '2024-01-08 11:08:39', '2024-01-08 11:08:39'),
(945, 'english', 'Events', 'Events', '2024-01-08 11:08:44', '2024-01-08 11:08:44'),
(946, 'english', 'Create Event', 'Create Event', '2024-01-08 11:08:44', '2024-01-08 11:08:44'),
(947, 'english', 'My Event', 'My Event', '2024-01-08 11:08:44', '2024-01-08 11:08:44'),
(948, 'english', 'Event Name', 'Event Name', '2024-01-08 11:08:46', '2024-01-08 11:08:46'),
(949, 'english', 'Event Date', 'Event Date', '2024-01-08 11:08:46', '2024-01-08 11:08:46'),
(950, 'english', 'Event Time', 'Event Time', '2024-01-08 11:08:46', '2024-01-08 11:08:46'),
(951, 'english', 'Location', 'Location', '2024-01-08 11:08:46', '2024-01-08 11:08:46'),
(952, 'english', 'Event Description', 'Event Description', '2024-01-08 11:08:46', '2024-01-08 11:08:46'),
(953, 'english', 'Cover Photo', 'Cover Photo', '2024-01-08 11:08:46', '2024-01-08 11:08:46'),
(954, 'english', 'Memories', 'Memories', '2024-02-01 07:47:19', '2024-02-01 07:47:19'),
(955, 'english', 'No memories to view or share today.', 'No memories to view or share today.', '2024-02-01 07:47:26', '2024-02-01 07:47:26'),
(956, 'english', 'We\'ll notify you when there are some to reminisce about', 'We\'ll notify you when there are some to reminisce about', '2024-02-01 07:47:26', '2024-02-01 07:47:26'),
(957, 'english', 'Badge', 'Badge', '2024-02-19 09:18:45', '2024-02-19 09:18:45'),
(958, 'english', 'Build trust with Gigvora Verified', 'Build trust with Gigvora Verified', '2024-02-19 09:25:58', '2024-02-19 09:25:58'),
(959, 'english', 'A verified badge', 'A verified badge', '2024-02-19 09:25:58', '2024-02-19 09:25:58'),
(960, 'english', 'Your audience can trust that you\"re a real person sharing your real stories.', 'Your audience can trust that you\"re a real person sharing your real stories.', '2024-02-19 09:25:58', '2024-02-19 09:25:58'),
(961, 'english', 'Increased account protection', 'Increased account protection', '2024-02-19 09:25:58', '2024-02-19 09:25:58'),
(962, 'english', 'Worry less about impersonation with proactive identity monitoring.', 'Worry less about impersonation with proactive identity monitoring.', '2024-02-19 09:25:58', '2024-02-19 09:25:58'),
(963, 'english', 'Next', 'Next', '2024-02-19 09:25:58', '2024-02-19 09:25:58'),
(964, 'english', 'Confirm and pay', 'Confirm and pay', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(965, 'english', 'You are subscribing to Meta Verified on Gigvora.', 'You are subscribing to Meta Verified on Gigvora.', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(966, 'english', 'Gigvora', 'Gigvora', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(967, 'english', 'You\'ll be billed', 'You\'ll be billed', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(968, 'english', 'per month.', 'per month.', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(969, 'english', 'What you get with your subscription.', 'What you get with your subscription.', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(970, 'english', 'Pay Now', 'Pay Now', '2024-02-19 09:26:03', '2024-02-19 09:26:03'),
(971, 'english', 'Good Morning', 'Good Morning', '2024-03-17 05:23:55', '2024-03-17 05:23:55'),
(972, 'english', 'Edit your profile', 'Edit your profile', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(973, 'english', 'Edit Profile', 'Edit Profile', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(974, 'english', 'Update your cover photo', 'Update your cover photo', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(975, 'english', 'Edit Cover Photo', 'Edit Cover Photo', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(976, 'english', 'Photo', 'Photo', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(977, 'english', 'Intro', 'Intro', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(978, 'english', 'Edit Bio', 'Edit Bio', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(979, 'english', 'Save Bio', 'Save Bio', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(980, 'english', 'Info', 'Info', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(981, 'english', 'Studied at', 'Studied at', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(982, 'english', 'From', 'From', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(983, 'english', 'male', 'male', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(984, 'english', 'Joined', 'Joined', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(985, 'english', 'Edit info', 'Edit info', '2024-03-17 05:24:10', '2024-03-17 05:24:10'),
(986, 'english', 'See All', 'See All', '2024-03-17 05:24:11', '2024-03-17 05:24:11'),
(987, 'english', 'All Groups', 'All Groups', '2024-03-17 05:24:12', '2024-03-17 05:24:12'),
(988, 'english', ' Create New Group', ' Create New Group', '2024-03-17 05:24:12', '2024-03-17 05:24:12'),
(989, 'english', 'Groups', 'Groups', '2024-03-17 05:24:12', '2024-03-17 05:24:12'),
(990, 'english', 'Group you Manage', 'Group you Manage', '2024-03-17 05:24:12', '2024-03-17 05:24:12'),
(991, 'english', 'Group you Joined', 'Group you Joined', '2024-03-17 05:24:12', '2024-03-17 05:24:12'),
(992, 'english', 'My Pages', 'My Pages', '2024-03-17 05:24:13', '2024-03-17 05:24:13'),
(993, 'english', 'Suggested Pages', 'Suggested Pages', '2024-03-17 05:24:13', '2024-03-17 05:24:13'),
(994, 'english', 'Liked Pages', 'Liked Pages', '2024-03-17 05:24:13', '2024-03-17 05:24:13'),
(995, 'english', 'Create Product', 'Create Product', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(996, 'english', 'My Products', 'My Products', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(997, 'english', 'Saved Product', 'Saved Product', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(998, 'english', 'Saved', 'Saved', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(999, 'english', 'Filters', 'Filters', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(1000, 'english', 'Condition', 'Condition', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(1001, 'english', 'Used', 'Used', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(1002, 'english', 'New', 'New', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(1003, 'english', 'Select Brand', 'Select Brand', '2024-03-17 05:24:14', '2024-03-17 05:24:14'),
(1004, 'english', 'All Users', 'All Users', '2024-03-17 05:25:36', '2024-03-17 05:25:36'),
(1005, 'english', 'Create user', 'Create user', '2024-03-17 05:25:36', '2024-03-17 05:25:36'),
(1006, 'english', 'Create a new user', 'Create a new user', '2024-03-17 05:25:36', '2024-03-17 05:25:36'),
(1007, 'english', 'Actions', 'Actions', '2024-03-17 05:25:36', '2024-03-17 05:25:36'),
(1008, 'english', 'All Sponsors', 'All Sponsors', '2024-03-17 05:25:45', '2024-03-17 05:25:45'),
(1009, 'english', 'Start Date', 'Start Date', '2024-03-17 05:25:45', '2024-03-17 05:25:45'),
(1010, 'english', 'Payment Settings', 'Payment Settings', '2024-03-17 05:25:49', '2024-03-17 05:25:49'),
(1011, 'english', 'Profile Picture', 'Profile Picture', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1012, 'english', 'Enter your name', 'Enter your name', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1013, 'english', 'Nickname', 'Nickname', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1014, 'english', 'Enter your nickname name', 'Enter your nickname name', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1015, 'english', 'Marital status', 'Marital status', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1016, 'english', 'Enter your marital status', 'Enter your marital status', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1017, 'english', 'Phone', 'Phone', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1018, 'english', 'Enter your phone number', 'Enter your phone number', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1019, 'english', 'Date of birth', 'Date of birth', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1020, 'english', 'Your date of birth', 'Your date of birth', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1021, 'english', 'Update Profile', 'Update Profile', '2024-03-31 05:02:17', '2024-03-31 05:02:17'),
(1022, 'english', 'Profile updated successfully', 'Profile updated successfully', '2024-03-31 05:02:31', '2024-03-31 05:02:31'),
(1023, 'english', 'Just now', 'Just now', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1024, 'english', 'Copy Link', 'Copy Link', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1025, 'english', 'Edit post', 'Edit post', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1026, 'english', 'Edit', 'Edit', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1027, 'english', 'Delete', 'Delete', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1028, 'english', 'Report Post', 'Report Post', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1029, 'english', 'Report', 'Report', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1030, 'english', 'Preview', 'Preview', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1031, 'english', 'Like', 'Like', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1032, 'english', 'Comments', 'Comments', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1033, 'english', 'Share post', 'Share post', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1034, 'english', 'Share', 'Share', '2024-03-31 05:02:32', '2024-03-31 05:02:32'),
(1035, 'english', 'No data found!', 'No data found!', '2024-03-31 05:02:42', '2024-03-31 05:02:42'),
(1036, 'english', 'Please go back', 'Please go back', '2024-03-31 05:02:42', '2024-03-31 05:02:42'),
(1037, 'english', 'My Blog', 'My Blog', '2024-03-31 05:02:44', '2024-03-31 05:02:44'),
(1038, 'english', 'year', 'year', '2024-03-31 05:02:53', '2024-03-31 05:02:53'),
(1039, 'english', 'month', 'month', '2024-03-31 05:02:53', '2024-03-31 05:02:53'),
(1040, 'english', 'day', 'day', '2024-03-31 05:02:53', '2024-03-31 05:02:53'),
(1041, 'english', 'ago', 'ago', '2024-03-31 05:02:53', '2024-03-31 05:02:53'),
(1042, 'english', 'Page Name', 'Page Name', '2024-04-03 07:27:47', '2024-04-03 07:27:47'),
(1043, 'english', 'Page BIO', 'Page BIO', '2024-04-03 07:27:47', '2024-04-03 07:27:47'),
(1044, 'english', 'Profile Photo', 'Profile Photo', '2024-04-03 07:27:47', '2024-04-03 07:27:47'),
(1045, 'english', 'Page Category', 'Page Category', '2024-04-03 07:27:47', '2024-04-03 07:27:47'),
(1046, 'english', 'Currency', 'Currency', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1047, 'english', 'Select Currency', 'Select Currency', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1048, 'english', 'Price', 'Price', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1049, 'english', 'Select Condition', 'Select Condition', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1050, 'english', 'Select Status', 'Select Status', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1051, 'english', 'In Stock', 'In Stock', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1052, 'english', 'Out Of Stock', 'Out Of Stock', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1053, 'english', 'Buy link', 'Buy link', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1054, 'english', 'Enter the buy link', 'Enter the buy link', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1055, 'english', 'Product Image', 'Product Image', '2024-04-03 07:27:57', '2024-04-03 07:27:57'),
(1056, 'english', 'Group Title', 'Group Title', '2024-04-03 07:29:34', '2024-04-03 07:29:34'),
(1057, 'english', 'Group Sub Title', 'Group Sub Title', '2024-04-03 07:29:34', '2024-04-03 07:29:34'),
(1058, 'english', 'Update Profile Photo', 'Update Profile Photo', '2024-04-03 07:29:34', '2024-04-03 07:29:34'),
(1059, 'english', 'Active', 'Active', '2024-04-03 07:29:34', '2024-04-03 07:29:34'),
(1060, 'english', 'Deactive', 'Deactive', '2024-04-03 07:29:34', '2024-04-03 07:29:34'),
(1061, 'english', 'Create Group', 'Create Group', '2024-04-03 07:29:34', '2024-04-03 07:29:34'),
(1062, 'english', 'My Friends', 'My Friends', '2024-04-03 07:29:44', '2024-04-03 07:29:44'),
(1063, 'english', 'Friend Requests', 'Friend Requests', '2024-04-03 07:29:44', '2024-04-03 07:29:44'),
(1064, 'english', 'Add Photo To Album', 'Add Photo To Album', '2024-04-03 07:29:46', '2024-04-03 07:29:46'),
(1065, 'english', 'Add Photo/Album', 'Add Photo/Album', '2024-04-03 07:29:46', '2024-04-03 07:29:46'),
(1066, 'english', 'Your Photos', 'Your Photos', '2024-04-03 07:29:46', '2024-04-03 07:29:46'),
(1067, 'english', 'Album', 'Album', '2024-04-03 07:29:46', '2024-04-03 07:29:46'),
(1068, 'english', 'Create Album', 'Create Album', '2024-04-03 07:29:46', '2024-04-03 07:29:46'),
(1069, 'english', 'Album title', 'Album title', '2024-04-03 07:29:52', '2024-04-03 07:29:52'),
(1070, 'english', 'Album subtitle', 'Album subtitle', '2024-04-03 07:29:52', '2024-04-03 07:29:52'),
(1071, 'english', 'Thumbnail', 'Thumbnail', '2024-04-03 07:29:52', '2024-04-03 07:29:52'),
(1072, 'english', 'Share the post on', 'Share the post on', '2024-04-03 07:30:17', '2024-04-03 07:30:17'),
(1073, 'english', 'My Timeline', 'My Timeline', '2024-04-03 07:30:17', '2024-04-03 07:30:17'),
(1074, 'english', 'Send in Message', 'Send in Message', '2024-04-03 07:30:17', '2024-04-03 07:30:17'),
(1075, 'english', 'Share to a Group', 'Share to a Group', '2024-04-03 07:30:17', '2024-04-03 07:30:17'),
(1076, 'english', 'Razorpay', 'Razorpay', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1077, 'english', 'Key id', 'Key id', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1078, 'english', 'Secret\r\n                                            key', 'Secret\r\n                                            key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1079, 'english', 'Theme\r\n                                            color', 'Theme\r\n                                            color', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1080, 'english', '*Please enter HEX color\r\n                                            code.', '*Please enter HEX color\r\n                                            code.', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1081, 'english', 'Stripe', 'Stripe', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1082, 'english', 'Live mode', 'Live mode', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1083, 'english', 'Public key', 'Public key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1084, 'english', 'Secret key', 'Secret key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1085, 'english', 'Public live key', 'Public live key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1086, 'english', 'Secret live key', 'Secret live key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1087, 'english', 'Paypal', 'Paypal', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1088, 'english', 'Sandbox\r\n                                                client id', 'Sandbox\r\n                                                client id', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1089, 'english', 'Sandbox secret key', 'Sandbox secret key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1090, 'english', 'Production client id', 'Production client id', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1091, 'english', 'Flutterwave', 'Flutterwave', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1092, 'english', 'Encryption key', 'Encryption key', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1093, 'english', 'Save settings', 'Save settings', '2024-04-03 07:37:02', '2024-04-03 07:37:02'),
(1094, 'english', 'SYSTEM Theme Color', 'SYSTEM Theme Color', '2024-04-03 07:40:43', '2024-04-03 07:40:43'),
(1095, 'english', 'Default', 'Default', '2024-04-03 07:40:43', '2024-04-03 07:40:43'),
(1096, 'english', 'Create Products', 'Create Products', '2024-04-03 07:57:45', '2024-04-03 07:57:45'),
(1097, 'english', 'Your videos', 'Your videos', '2024-04-03 08:10:01', '2024-04-03 08:10:01'),
(1098, 'english', 'All Product Categories', 'All Product Categories', '2024-04-03 09:04:08', '2024-04-03 09:04:08'),
(1099, 'english', 'Category Name', 'Category Name', '2024-04-03 09:04:08', '2024-04-03 09:04:08'),
(1100, 'english', 'DATE', 'DATE', '2024-04-03 09:04:08', '2024-04-03 09:04:08'),
(1101, 'english', 'View', 'View', '2024-04-03 09:04:09', '2024-04-03 09:04:09'),
(1102, 'english', 'Product Category', 'Product Category', '2024-04-03 09:04:09', '2024-04-03 09:04:09'),
(1103, 'english', 'All Product Brand ', 'All Product Brand ', '2024-04-03 09:04:21', '2024-04-03 09:04:21'),
(1104, 'english', 'Brand Name', 'Brand Name', '2024-04-03 09:04:21', '2024-04-03 09:04:21'),
(1105, 'english', 'Product Brand', 'Product Brand', '2024-04-03 09:04:23', '2024-04-03 09:04:23'),
(1106, 'english', 'Marketplace Product Added Successfully', 'Marketplace Product Added Successfully', '2024-04-03 09:07:48', '2024-04-03 09:07:48'),
(1107, 'english', 'Previous', 'Previous', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1108, 'english', 'Details', 'Details', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1109, 'english', 'Buy Now', 'Buy Now', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1110, 'english', 'Listed', 'Listed', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1111, 'english', 'Published By', 'Published By', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1112, 'english', 'Share Product', 'Share Product', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1113, 'english', 'Related Product', 'Related Product', '2024-04-03 09:08:05', '2024-04-03 09:08:05'),
(1114, 'english', 'Saved Successfully', 'Saved Successfully', '2024-04-03 09:11:19', '2024-04-03 09:11:19'),
(1115, 'english', 'Unsaved Successfully', 'Unsaved Successfully', '2024-04-03 09:11:22', '2024-04-03 09:11:22'),
(1116, 'english', 'Listed by', 'Listed by', '2024-04-03 09:12:19', '2024-04-03 09:12:19'),
(1117, 'english', 'Message', 'Message', '2024-04-03 09:17:11', '2024-04-03 09:17:11'),
(1118, 'english', 'Edit Product', 'Edit Product', '2024-04-03 09:29:18', '2024-04-03 09:29:18'),
(1119, 'english', 'Previous Uploaded Image', 'Previous Uploaded Image', '2024-04-03 09:31:43', '2024-04-03 09:31:43'),
(1120, 'english', 'Marketplace Product Updated Successfully', 'Marketplace Product Updated Successfully', '2024-04-03 09:31:58', '2024-04-03 09:31:58'),
(1121, 'english', 'Sold', 'Sold', '2024-04-03 10:11:04', '2024-04-03 10:11:04'),
(1122, 'english', 'Add a new user', 'Add a new user', '2024-04-03 10:14:40', '2024-04-03 10:14:40'),
(1123, 'english', 'Email address', 'Email address', '2024-04-03 10:14:40', '2024-04-03 10:14:40'),
(1124, 'english', 'Gender', 'Gender', '2024-04-03 10:14:40', '2024-04-03 10:14:40'),
(1125, 'english', 'Female', 'Female', '2024-04-03 10:14:40', '2024-04-03 10:14:40'),
(1126, 'english', 'Bio', 'Bio', '2024-04-03 10:14:40', '2024-04-03 10:14:40'),
(1127, 'english', 'Are You Sure Want To Delete', 'Are You Sure Want To Delete', '2024-04-03 10:15:30', '2024-04-03 10:15:30'),
(1128, 'english', 'Follow', 'Follow', '2024-04-03 10:15:44', '2024-04-03 10:15:44'),
(1129, 'english', 'Chats', 'Chats', '2024-04-03 10:17:03', '2024-04-03 10:17:03'),
(1130, 'english', 'Active now', 'Active now', '2024-04-03 10:17:04', '2024-04-03 10:17:04'),
(1131, 'english', 'View Profile', 'View Profile', '2024-04-03 10:17:04', '2024-04-03 10:17:04'),
(1132, 'english', 'Reset', 'Reset', '2024-04-03 10:17:04', '2024-04-03 10:17:04'),
(1133, 'english', 'Stadied at', 'Stadied at', '2024-04-03 10:23:15', '2024-04-03 10:23:15'),
(1134, 'english', 'See More', 'See More', '2024-04-03 10:23:15', '2024-04-03 10:23:15'),
(1135, 'english', 'Add Friend', 'Add Friend', '2024-04-03 10:23:35', '2024-04-03 10:23:35'),
(1136, 'english', 'Unsave', 'Unsave', '2024-04-03 11:03:58', '2024-04-03 11:03:58'),
(1137, 'english', 'Your post has been published', 'Your post has been published', '2024-04-04 06:29:21', '2024-04-04 06:29:21'),
(1138, 'english', 'Create Photo / Video Story', 'Create Photo / Video Story', '2024-04-04 06:46:19', '2024-04-04 06:46:19'),
(1139, 'english', 'Create a Text Story', 'Create a Text Story', '2024-04-04 06:46:19', '2024-04-04 06:46:19'),
(1140, 'english', 'Share to story', 'Share to story', '2024-04-04 06:46:19', '2024-04-04 06:46:19'),
(1141, 'english', 'Discard', 'Discard', '2024-04-04 06:46:19', '2024-04-04 06:46:19'),
(1142, 'english', 'Friend Request Sent Successfully', 'Friend Request Sent Successfully', '2024-04-04 06:57:04', '2024-04-04 06:57:04'),
(1143, 'english', 'Cancle Friend Request', 'Cancle Friend Request', '2024-04-04 06:57:05', '2024-04-04 06:57:05'),
(1144, 'english', 'Friend request', 'Friend request', '2024-04-04 06:57:05', '2024-04-04 06:57:05'),
(1145, 'english', 'Requested', 'Requested', '2024-04-04 06:57:05', '2024-04-04 06:57:05'),
(1146, 'english', 'Accepted', 'Accepted', '2024-04-04 06:57:05', '2024-04-04 06:57:05'),
(1147, 'english', '1', '1', '2024-04-04 06:57:10', '2024-04-04 06:57:10'),
(1148, 'english', 'Notifications', 'Notifications', '2024-04-04 06:57:15', '2024-04-04 06:57:15'),
(1149, 'english', 'sent you Friend Request', 'sent you Friend Request', '2024-04-04 06:57:15', '2024-04-04 06:57:15'),
(1150, 'english', 'Accept', 'Accept', '2024-04-04 06:57:15', '2024-04-04 06:57:15'),
(1151, 'english', 'Decline', 'Decline', '2024-04-04 06:57:15', '2024-04-04 06:57:15'),
(1152, 'english', 'Friend Request Accepted', 'Friend Request Accepted', '2024-04-04 06:57:23', '2024-04-04 06:57:23'),
(1153, 'english', 'feeling', 'feeling', '2024-04-04 07:13:20', '2024-04-04 07:13:20'),
(1154, 'english', 'Post has been added to your timeline', 'Post has been added to your timeline', '2024-04-04 07:22:32', '2024-04-04 07:22:32'),
(1155, 'english', 'is live now', 'is live now', '2024-04-04 07:22:43', '2024-04-04 07:22:43'),
(1156, 'english', 'Join now', 'Join now', '2024-04-04 07:22:43', '2024-04-04 07:22:43'),
(1157, 'english', 'Post Deleted Successfully', 'Post Deleted Successfully', '2024-04-04 07:23:17', '2024-04-04 07:23:17'),
(1158, 'english', 'Send', 'Send', '2024-04-04 07:32:06', '2024-04-04 07:32:06'),
(1159, 'english', 'at', 'at', '2024-04-04 07:37:29', '2024-04-04 07:37:29'),
(1160, 'english', 'We hope you enjoy revisiting and sharing your memories on Gigvora from the most recent moments to those from days gone by.', 'We hope you enjoy revisiting and sharing your memories on Gigvora from the most recent moments to those from days gone by.', '2024-04-04 07:37:33', '2024-04-04 07:37:33'),
(1161, 'english', 'On this day', 'On this day', '2024-04-04 07:37:33', '2024-04-04 07:37:33'),
(1162, 'english', 'You have a memory ____ ____ ago', 'You have a memory ____ ____ ago', '2024-04-04 07:37:33', '2024-04-04 07:37:33'),
(1163, 'english', 'posts', 'posts', '2024-04-04 07:42:07', '2024-04-04 07:42:07'),
(1164, 'english', 'Search Results', 'Search Results', '2024-04-04 07:42:07', '2024-04-04 07:42:07'),
(1165, 'english', 'All', 'All', '2024-04-04 07:42:08', '2024-04-04 07:42:08'),
(1166, 'english', 'Peoples', 'Peoples', '2024-04-04 07:42:08', '2024-04-04 07:42:08'),
(1167, 'english', 'People', 'People', '2024-04-04 07:42:08', '2024-04-04 07:42:08'),
(1168, 'english', 'Friend', 'Friend', '2024-04-04 07:42:23', '2024-04-04 07:42:23'),
(1169, 'english', 'Mutual Friends', 'Mutual Friends', '2024-04-04 07:57:38', '2024-04-04 07:57:38'),
(1170, 'english', 'Add Sponsors Post', 'Add Sponsors Post', '2024-04-04 07:58:34', '2024-04-04 07:58:34'),
(1171, 'english', 'URL', 'URL', '2024-04-04 07:58:34', '2024-04-04 07:58:34'),
(1172, 'english', '(50 Character Show In Front End)', '(50 Character Show In Front End)', '2024-04-04 07:58:34', '2024-04-04 07:58:34'),
(1173, 'english', 'Not yet published', 'Not yet published', '2024-04-04 07:59:55', '2024-04-04 07:59:55'),
(1174, 'english', 'Are You Sure Want To Delete?', 'Are You Sure Want To Delete?', '2024-04-04 07:59:55', '2024-04-04 07:59:55'),
(1175, 'english', 'Edit Sponsor Post', 'Edit Sponsor Post', '2024-04-04 08:02:10', '2024-04-04 08:02:10'),
(1176, 'english', 'Previous Uploaded File', 'Previous Uploaded File', '2024-04-04 08:02:10', '2024-04-04 08:02:10'),
(1177, 'english', 'End date', 'End date', '2024-04-04 08:02:10', '2024-04-04 08:02:10'),
(1178, 'english', 'Reply', 'Reply', '2024-04-04 08:04:52', '2024-04-04 08:04:52'),
(1179, 'english', 'Delete Comment', 'Delete Comment', '2024-04-04 08:04:52', '2024-04-04 08:04:52'),
(1180, 'english', 'Loved', 'Loved', '2024-04-04 08:04:55', '2024-04-04 08:04:55'),
(1181, 'english', 'Posted On My Timeline Successfully', 'Posted On My Timeline Successfully', '2024-04-04 08:05:05', '2024-04-04 08:05:05'),
(1182, 'english', 'shared post', 'shared post', '2024-04-04 08:05:06', '2024-04-04 08:05:06'),
(1183, 'english', 'Upload', 'Upload', '2024-04-04 09:50:59', '2024-04-04 09:50:59'),
(1184, 'english', 'Cover photo updated', 'Cover photo updated', '2024-04-04 09:53:35', '2024-04-04 09:53:35'),
(1185, 'english', 'Unfriend', 'Unfriend', '2024-04-04 09:53:43', '2024-04-04 09:53:43'),
(1186, 'english', 'View Album', 'View Album', '2024-04-04 09:54:25', '2024-04-04 09:54:25'),
(1187, 'english', 'Delete Album', 'Delete Album', '2024-04-04 09:54:25', '2024-04-04 09:54:25'),
(1188, 'english', 'Items', 'Items', '2024-04-04 09:54:25', '2024-04-04 09:54:25'),
(1189, 'english', 'Confirm', 'Confirm', '2024-04-04 10:24:23', '2024-04-04 10:24:23'),
(1190, 'english', 'Find Friend', 'Find Friend', '2024-04-04 10:26:02', '2024-04-04 10:26:02'),
(1191, 'english', 'Find Friends', 'Find Friends', '2024-04-04 10:26:15', '2024-04-04 10:26:15'),
(1192, 'english', 'Friend request deleted', 'Friend request deleted', '2024-04-04 12:12:31', '2024-04-04 12:12:31'),
(1193, 'english', '2', '2', '2024-04-04 12:13:23', '2024-04-04 12:13:23'),
(1194, 'english', 'accepted Your Friend Request', 'accepted Your Friend Request', '2024-04-06 16:23:03', '2024-04-06 16:23:03'),
(1195, 'english', 'Mark As Read', 'Mark As Read', '2024-04-06 16:23:03', '2024-04-06 16:23:03'),
(1196, 'english', 'Accept Friend Request', 'Accept Friend Request', '2024-04-07 05:22:22', '2024-04-07 05:22:22'),
(1197, 'english', 'Removed from friend list', 'Removed from friend list', '2024-04-07 05:30:57', '2024-04-07 05:30:57'),
(1198, 'english', '3', '3', '2024-04-07 05:36:18', '2024-04-07 05:36:18'),
(1199, 'english', '4', '4', '2024-04-07 05:55:09', '2024-04-07 05:55:09'),
(1200, 'english', '5', '5', '2024-04-07 05:59:26', '2024-04-07 05:59:26'),
(1201, 'english', 'Edit user profile', 'Edit user profile', '2024-04-07 06:07:28', '2024-04-07 06:07:28'),
(1202, 'english', 'Verified', 'Verified', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1203, 'english', 'Details info', 'Details info', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1204, 'english', 'Phone Number', 'Phone Number', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1205, 'english', 'Your name', 'Your name', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1206, 'english', 'Profession', 'Profession', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1207, 'english', 'Enter your Profession', 'Enter your Profession', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1208, 'english', 'Birthday', 'Birthday', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1209, 'english', 'Your address', 'Your address', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1210, 'english', 'Save Changes', 'Save Changes', '2024-04-07 06:08:30', '2024-04-07 06:08:30'),
(1211, 'english', 'Select Album', 'Select Album', '2024-04-07 06:13:18', '2024-04-07 06:13:18'),
(1212, 'english', 'Album Images', 'Album Images', '2024-04-07 06:13:18', '2024-04-07 06:13:18'),
(1213, 'english', 'Your images is added to album', 'Your images is added to album', '2024-04-07 06:13:29', '2024-04-07 06:13:29'),
(1214, 'english', 'Album deleted successfully', 'Album deleted successfully', '2024-04-07 07:26:09', '2024-04-07 07:26:09'),
(1215, 'english', 'This content isn\'t available right now', 'This content isn\'t available right now', '2024-04-07 09:39:25', '2024-04-07 09:39:25'),
(1216, 'english', 'When this happens, it\'s usually because the owner only shared it with a small group of people, changed who can see it or it\'s been deleted.', 'When this happens, it\'s usually because the owner only shared it with a small group of people, changed who can see it or it\'s been deleted.', '2024-04-07 09:39:25', '2024-04-07 09:39:25'),
(1217, 'english', 'All Pages', 'All Pages', '2024-04-08 04:35:33', '2024-04-08 04:35:33'),
(1218, 'english', 'Page owner', 'Page owner', '2024-04-08 04:35:33', '2024-04-08 04:35:33'),
(1219, 'english', 'Activity', 'Activity', '2024-04-08 04:40:27', '2024-04-08 04:40:27'),
(1220, 'english', 'Your bio updated', 'Your bio updated', '2024-04-08 04:41:21', '2024-04-08 04:41:21'),
(1221, 'english', 'Earlier', 'Earlier', '2024-04-08 06:02:36', '2024-04-08 06:02:36'),
(1222, 'english', 'No Conversion Start!', 'No Conversion Start!', '2024-04-08 06:06:09', '2024-04-08 06:06:09'),
(1223, 'english', 'FRONTEND BADGE PRICING SETTINGS', 'FRONTEND BADGE PRICING SETTINGS', '2024-04-08 06:28:26', '2024-04-08 06:28:26'),
(1224, 'english', 'Badge Price', 'Badge Price', '2024-04-08 06:28:26', '2024-04-08 06:28:26'),
(1225, 'english', 'Order summary', 'Order summary', '2024-04-08 06:28:39', '2024-04-08 06:28:39'),
(1226, 'english', 'Select payment gateway', 'Select payment gateway', '2024-04-08 06:28:39', '2024-04-08 06:28:39'),
(1227, 'english', 'Item List', 'Item List', '2024-04-08 06:28:39', '2024-04-08 06:28:39'),
(1228, 'english', 'Grand Total', 'Grand Total', '2024-04-08 06:28:39', '2024-04-08 06:28:39'),
(1229, 'english', 'Payment successfully done!', 'Payment successfully done!', '2024-04-08 06:29:07', '2024-04-08 06:29:07'),
(1230, 'english', 'Already purchased', 'Already purchased', '2024-04-08 06:29:07', '2024-04-08 06:29:07'),
(1231, 'english', 'Badge Purchased History', 'Badge Purchased History', '2024-04-08 06:29:07', '2024-04-08 06:29:07'),
(1232, 'english', 'ID', 'ID', '2024-04-08 06:29:07', '2024-04-08 06:29:07'),
(1233, 'english', 'LIVE', 'LIVE', '2024-04-08 11:33:02', '2024-04-08 11:33:02'),
(1234, 'english', 'Watch now', 'Watch now', '2024-04-08 11:33:02', '2024-04-08 11:33:02'),
(1235, 'english', 'Liked', 'Liked', '2024-04-08 12:00:34', '2024-04-08 12:00:34'),
(1236, 'english', 'Likedss', 'Likedss', '2024-04-08 12:22:07', '2024-04-08 12:22:07'),
(1237, 'english', 'Haha', 'Haha', '2024-04-08 12:27:43', '2024-04-08 12:27:43'),
(1238, 'english', 'Angry', 'Angry', '2024-04-08 12:34:06', '2024-04-08 12:34:06'),
(1239, 'english', 'Deleted successfully', 'Deleted successfully', '2024-04-09 06:43:06', '2024-04-09 06:43:06'),
(1240, 'english', 'All Page Categories', 'All Page Categories', '2024-04-09 06:50:04', '2024-04-09 06:50:04'),
(1241, 'english', 'Page Created Successfully', 'Page Created Successfully', '2024-04-09 06:53:36', '2024-04-09 06:53:36'),
(1242, 'english', 'People like this', 'People like this', '2024-04-09 06:53:37', '2024-04-09 06:53:37'),
(1243, 'english', 'Edit Page', 'Edit Page', '2024-04-09 06:53:37', '2024-04-09 06:53:37'),
(1244, 'english', '____ likes', '____ likes', '2024-04-09 06:53:37', '2024-04-09 06:53:37'),
(1245, 'english', 'Previous Profile Photo', 'Previous Profile Photo', '2024-04-09 06:58:18', '2024-04-09 06:58:18'),
(1246, 'english', 'Page Updated Successfully', 'Page Updated Successfully', '2024-04-09 06:58:25', '2024-04-09 06:58:25'),
(1247, 'english', 'like this', 'like this', '2024-04-09 06:59:04', '2024-04-09 06:59:04'),
(1248, 'english', 'Update Page Info', 'Update Page Info', '2024-04-09 06:59:04', '2024-04-09 06:59:04'),
(1249, 'english', 'Page you may like', 'Page you may like', '2024-04-09 06:59:04', '2024-04-09 06:59:04'),
(1250, 'english', 'Photo/Video', 'Photo/Video', '2024-04-09 06:59:04', '2024-04-09 06:59:04'),
(1251, 'english', 'Photos', 'Photos', '2024-04-09 07:11:00', '2024-04-09 07:11:00'),
(1252, 'english', 'Group Created Successfully', 'Group Created Successfully', '2024-04-09 07:39:09', '2024-04-09 07:39:09'),
(1253, 'english', 'Member', 'Member', '2024-04-09 07:39:10', '2024-04-09 07:39:10'),
(1254, 'english', 'Admin', 'Admin', '2024-04-09 07:39:10', '2024-04-09 07:39:10'),
(1255, 'english', 'Edit Group', 'Edit Group', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1256, 'english', 'Invite', 'Invite', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1257, 'english', 'Discussion', 'Discussion', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1258, 'english', 'Media', 'Media', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1259, 'english', 'Invite Group', 'Invite Group', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1260, 'english', 'Invite Friends', 'Invite Friends', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1261, 'english', 'Optional', 'Optional', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1262, 'english', 'Suggestion', 'Suggestion', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1263, 'english', 'Recent Media', 'Recent Media', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1264, 'english', 'Recent Members', 'Recent Members', '2024-04-09 07:39:13', '2024-04-09 07:39:13'),
(1265, 'english', 'Members', 'Members', '2024-04-09 07:39:22', '2024-04-09 07:39:22'),
(1266, 'english', 'New people and Pages who join this group will appear here', 'New people and Pages who join this group will appear here', '2024-04-09 07:39:22', '2024-04-09 07:39:22'),
(1267, 'english', 'Leave Group', 'Leave Group', '2024-04-09 07:39:22', '2024-04-09 07:39:22'),
(1268, 'english', 'Members With Things in Common', 'Members With Things in Common', '2024-04-09 07:39:22', '2024-04-09 07:39:22'),
(1269, 'english', 'Photos of you', 'Photos of you', '2024-04-09 07:39:26', '2024-04-09 07:39:26'),
(1270, 'english', 'Albums', 'Albums', '2024-04-09 07:39:26', '2024-04-09 07:39:26'),
(1271, 'english', 'Upcoming Events', 'Upcoming Events', '2024-04-09 07:54:48', '2024-04-09 07:54:48'),
(1272, 'english', 'No upcoming events', 'No upcoming events', '2024-04-09 07:54:48', '2024-04-09 07:54:48'),
(1273, 'english', 'Post Events', 'Post Events', '2024-04-09 07:54:48', '2024-04-09 07:54:48'),
(1274, 'english', 'All  people and  who join this group will appear here', 'All  people and  who join this group will appear here', '2024-04-09 07:55:57', '2024-04-09 07:55:57'),
(1275, 'english', 'Event Created Successfully', 'Event Created Successfully', '2024-04-09 07:56:46', '2024-04-09 07:56:46'),
(1276, 'english', 'Nearest event', 'Nearest event', '2024-04-09 07:56:46', '2024-04-09 07:56:46'),
(1277, 'english', 'Total ____ Upcoming events', 'Total ____ Upcoming events', '2024-04-09 07:56:46', '2024-04-09 07:56:46'),
(1278, 'english', 'Edit Event', 'Edit Event', '2024-04-09 07:56:46', '2024-04-09 07:56:46'),
(1279, 'english', 'Delete Event', 'Delete Event', '2024-04-09 07:56:46', '2024-04-09 07:56:46'),
(1280, 'english', 'Created by', 'Created by', '2024-04-09 07:56:46', '2024-04-09 07:56:46'),
(1281, 'english', 'Share Event', 'Share Event', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1282, 'english', 'Recent Activity', 'Recent Activity', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1283, 'english', 'Selected users', 'Selected users', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1284, 'english', 'Invite Event', 'Invite Event', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1285, 'english', 'Guests', 'Guests', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1286, 'english', 'All Going And Interested User', 'All Going And Interested User', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1287, 'english', 'View All', 'View All', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1288, 'english', 'Go With Friends', 'Go With Friends', '2024-04-09 07:56:55', '2024-04-09 07:56:55');
INSERT INTO `languages` (`id`, `name`, `phrase`, `translated`, `created_at`, `updated_at`) VALUES
(1289, 'english', 'Send invitations', 'Send invitations', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1290, 'english', 'Popular Events', 'Popular Events', '2024-04-09 07:56:55', '2024-04-09 07:56:55'),
(1291, 'english', 'Group Invited Done Successfully', 'Group Invited Done Successfully', '2024-04-09 08:07:54', '2024-04-09 08:07:54'),
(1292, 'english', 'invited you to like', 'invited you to like', '2024-04-09 08:07:59', '2024-04-09 08:07:59'),
(1293, 'english', 'Group Invitation Canceled', 'Group Invitation Canceled', '2024-04-09 08:09:29', '2024-04-09 08:09:29'),
(1294, 'english', 'Update Event', 'Update Event', '2024-04-09 09:29:23', '2024-04-09 09:29:23'),
(1295, 'english', 'Enter your group title', 'Enter your group title', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1296, 'english', 'Enter your group sub title', 'Enter your group sub title', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1297, 'english', 'Group Location', 'Group Location', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1298, 'english', 'Enter your group location', 'Enter your group location', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1299, 'english', 'Group Type', 'Group Type', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1300, 'english', 'Enter your group type', 'Enter your group type', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1301, 'english', 'Privacy', 'Privacy', '2024-04-09 09:34:23', '2024-04-09 09:34:23'),
(1302, 'english', 'Cover Photo Updated Successfully', 'Cover Photo Updated Successfully', '2024-04-09 09:34:34', '2024-04-09 09:34:34'),
(1303, 'english', 'Update Custom Pages Information', 'Update Custom Pages Information', '2024-04-09 09:57:38', '2024-04-09 09:57:38'),
(1304, 'english', 'About Page Description', 'About Page Description', '2024-04-09 09:57:38', '2024-04-09 09:57:38'),
(1305, 'english', 'Privacy Page Description', 'Privacy Page Description', '2024-04-09 09:57:38', '2024-04-09 09:57:38'),
(1306, 'english', 'Term and Condition Page Description', 'Term and Condition Page Description', '2024-04-09 09:57:38', '2024-04-09 09:57:38'),
(1307, 'english', 'Create New Blog', 'Create New Blog', '2024-04-09 10:16:58', '2024-04-09 10:16:58'),
(1308, 'english', 'Create post to share', 'Create post to share', '2024-04-20 09:21:24', '2024-04-20 09:21:24'),
(1309, 'english', 'See Moress', 'See Moress', '2024-04-20 12:44:30', '2024-04-20 12:44:30'),
(1310, 'english', 'You are now following', 'You are now following', '2024-04-20 12:48:46', '2024-04-20 12:48:46'),
(1311, 'english', 'Unfollow', 'Unfollow', '2024-04-20 12:48:47', '2024-04-20 12:48:47'),
(1312, 'english', 'likes', 'likes', '2024-04-21 04:44:02', '2024-04-21 04:44:02'),
(1313, 'english', 'Interested', 'Interested', '2024-04-21 04:44:02', '2024-04-21 04:44:02'),
(1314, 'english', 'Going', 'Going', '2024-04-21 04:44:02', '2024-04-21 04:44:02'),
(1315, 'english', 'Interest', 'Interest', '2024-04-21 04:44:02', '2024-04-21 04:44:02'),
(1316, 'english', 'Event has been Canceled', 'Event has been Canceled', '2024-04-21 05:33:23', '2024-04-21 05:33:23'),
(1317, 'english', 'Interested to Event', 'Interested to Event', '2024-04-21 05:35:07', '2024-04-21 05:35:07'),
(1318, 'english', 'Going to Event', 'Going to Event', '2024-04-21 05:38:09', '2024-04-21 05:38:09'),
(1319, 'english', 'Share On Group', 'Share On Group', '2024-04-21 05:47:31', '2024-04-21 05:47:31'),
(1320, 'english', 'Payment gateways', 'Payment gateways', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1321, 'english', 'Payment Gateway', 'Payment Gateway', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1322, 'english', 'Environment', 'Environment', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1323, 'english', 'Test Mode', 'Test Mode', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1324, 'english', 'Are you sure want to change status?', 'Are you sure want to change status?', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1325, 'english', 'Are you sure want to change environment?', 'Are you sure want to change environment?', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1326, 'english', 'Activate live mode', 'Activate live mode', '2024-04-21 06:54:20', '2024-04-21 06:54:20'),
(1327, 'english', 'Live video', 'Live video', '2024-04-21 06:55:05', '2024-04-21 06:55:05'),
(1328, 'english', 'Update zoom api keys', 'Update zoom api keys', '2024-04-21 06:55:09', '2024-04-21 06:55:09'),
(1329, 'english', 'API key', 'API key', '2024-04-21 06:55:09', '2024-04-21 06:55:09'),
(1330, 'english', 'API secret', 'API secret', '2024-04-21 06:55:09', '2024-04-21 06:55:09'),
(1331, 'english', 'Save', 'Save', '2024-04-21 06:55:09', '2024-04-21 06:55:09'),
(1332, 'english', 'Update Zitsi api keys', 'Update Zitsi api keys', '2024-04-21 06:58:51', '2024-04-21 06:58:51'),
(1333, 'english', 'Zitsi Live Settings', 'Zitsi Live Settings', '2024-04-21 08:04:35', '2024-04-21 08:04:35'),
(1334, 'english', 'Jitsi live class settings', 'Jitsi live class settings', '2024-04-21 08:13:58', '2024-04-21 08:13:58'),
(1335, 'english', 'Jitsi API Configuration', 'Jitsi API Configuration', '2024-04-21 08:13:58', '2024-04-21 08:13:58'),
(1336, 'english', 'How to configure Jitsi API?', 'How to configure Jitsi API?', '2024-04-21 08:13:58', '2024-04-21 08:13:58'),
(1337, 'english', 'Account email*', 'Account email*', '2024-04-21 09:25:30', '2024-04-21 09:25:30'),
(1338, 'english', 'Jitsi app id*', 'Jitsi app id*', '2024-04-21 09:25:30', '2024-04-21 09:25:30'),
(1339, 'english', 'Jwt token*', 'Jwt token*', '2024-04-21 09:25:30', '2024-04-21 09:25:30'),
(1340, 'english', 'sandbox client id', 'sandbox client id', '2024-04-21 11:08:09', '2024-04-21 11:08:09'),
(1341, 'english', 'production secret key', 'production secret key', '2024-04-21 11:08:09', '2024-04-21 11:08:09'),
(1342, 'english', 'Your post has been updated', 'Your post has been updated', '2024-04-22 05:43:16', '2024-04-22 05:43:16'),
(1343, 'english', 'Video/Shorts Created Successfully', 'Video/Shorts Created Successfully', '2024-04-22 09:46:25', '2024-04-22 09:46:25'),
(1344, 'english', 'Save Video', 'Save Video', '2024-04-22 09:46:26', '2024-04-22 09:46:26'),
(1345, 'english', 'Delete Video', 'Delete Video', '2024-04-22 09:46:26', '2024-04-22 09:46:26'),
(1346, 'english', 'Latest Short', 'Latest Short', '2024-04-22 09:46:48', '2024-04-22 09:46:48'),
(1347, 'english', 'Removed from followed list', 'Removed from followed list', '2024-04-22 10:02:41', '2024-04-22 10:02:41'),
(1348, 'english', 'View more', 'View more', '2024-04-22 10:17:36', '2024-04-22 10:17:36'),
(1349, 'english', 'Likeds', 'Likeds', '2024-04-22 10:20:54', '2024-04-22 10:20:54'),
(1350, 'english', 'Unsave Video', 'Unsave Video', '2024-04-22 10:25:49', '2024-04-22 10:25:49'),
(1351, 'english', 'Views', 'Views', '2024-04-22 10:25:52', '2024-04-22 10:25:52'),
(1352, 'english', 'All Blog Categories', 'All Blog Categories', '2024-04-22 10:36:19', '2024-04-22 10:36:19'),
(1353, 'english', 'Blog Category', 'Blog Category', '2024-04-22 10:36:25', '2024-04-22 10:36:25'),
(1354, 'english', 'Blog Created Successfully', 'Blog Created Successfully', '2024-04-22 10:37:46', '2024-04-22 10:37:46'),
(1355, 'english', 'Edit Article', 'Edit Article', '2024-04-22 10:40:09', '2024-04-22 10:40:09'),
(1356, 'english', 'Delete Article', 'Delete Article', '2024-04-22 10:40:09', '2024-04-22 10:40:09'),
(1357, 'english', 'Search Resultsbgf', 'Search Resultsbgf', '2024-04-22 10:48:55', '2024-04-22 10:48:55'),
(1358, 'english', 'Remove', 'Remove', '2024-04-22 11:20:26', '2024-04-22 11:20:26'),
(1359, 'english', 'Reset password', 'Reset password', '2024-04-22 12:32:03', '2024-04-22 12:32:03'),
(1360, 'english', 'Current Password', 'Current Password', '2024-04-22 12:32:03', '2024-04-22 12:32:03'),
(1361, 'english', '8 Symbols at least', '8 Symbols at least', '2024-04-22 12:32:03', '2024-04-22 12:32:03'),
(1362, 'english', 'New Password', 'New Password', '2024-04-22 12:32:03', '2024-04-22 12:32:03'),
(1363, 'english', 'Confirm Password', 'Confirm Password', '2024-04-22 12:32:03', '2024-04-22 12:32:03'),
(1364, 'english', 'Update Password', 'Update Password', '2024-04-22 12:32:03', '2024-04-22 12:32:03'),
(1365, 'english', 'Install', 'Install', '2024-04-23 05:58:25', '2024-04-23 05:58:25'),
(1366, 'english', 'Addon updated successfully', 'Addon updated successfully', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1367, 'english', 'Job', 'Job', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1368, 'english', 'Job List', 'Job List', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1369, 'english', 'Create Job', 'Create Job', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1370, 'english', 'Pending Job', 'Pending Job', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1371, 'english', 'All Apply List', 'All Apply List', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1372, 'english', 'Job Price', 'Job Price', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1373, 'english', 'Deactivate', 'Deactivate', '2024-04-23 05:58:53', '2024-04-23 05:58:53'),
(1374, 'english', 'Jobs', 'Jobs', '2024-04-23 05:58:57', '2024-04-23 05:58:57'),
(1375, 'english', 'Explore Jobs', 'Explore Jobs', '2024-04-23 05:59:00', '2024-04-23 05:59:00'),
(1376, 'english', 'My Job', 'My Job', '2024-04-23 05:59:00', '2024-04-23 05:59:00'),
(1377, 'english', 'Saved Job', 'Saved Job', '2024-04-23 05:59:00', '2024-04-23 05:59:00'),
(1378, 'english', 'My Application', 'My Application', '2024-04-23 05:59:00', '2024-04-23 05:59:00'),
(1379, 'english', 'History', 'History', '2024-04-23 06:03:40', '2024-04-23 06:03:40'),
(1380, 'english', 'My Jobs', 'My Jobs', '2024-04-23 06:03:51', '2024-04-23 06:03:51'),
(1381, 'english', 'Saved Jobs', 'Saved Jobs', '2024-04-23 06:03:51', '2024-04-23 06:03:51'),
(1382, 'english', 'Company Name', 'Company Name', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1383, 'english', 'Starting Salary Range', 'Starting Salary Range', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1384, 'english', 'Ending Salary Range', 'Ending Salary Range', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1385, 'english', 'Type', 'Type', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1386, 'english', 'Full Time', 'Full Time', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1387, 'english', 'Part Time', 'Part Time', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1388, 'english', 'Create Job Post', 'Create Job Post', '2024-04-23 06:06:01', '2024-04-23 06:06:01'),
(1389, 'english', 'All Job Categories', 'All Job Categories', '2024-04-23 06:18:14', '2024-04-23 06:18:14'),
(1390, 'english', 'All Jobs Categories', 'All Jobs Categories', '2024-04-23 06:18:22', '2024-04-23 06:18:22'),
(1391, 'english', 'Jobs Category', 'Jobs Category', '2024-04-23 06:18:22', '2024-04-23 06:18:22'),
(1392, 'english', 'Job Created Successfully', 'Job Created Successfully', '2024-04-23 06:22:11', '2024-04-23 06:22:11'),
(1393, 'english', 'Please pay for your created job post.', 'Please pay for your created job post.', '2024-04-23 06:22:12', '2024-04-23 06:22:12'),
(1394, 'english', 'Job Start Date', 'Job Start Date', '2024-04-23 06:22:12', '2024-04-23 06:22:12'),
(1395, 'english', 'Your job on our website expires on date', 'Your job on our website expires on date', '2024-04-23 06:22:12', '2024-04-23 06:22:12'),
(1396, 'english', 'Cancle', 'Cancle', '2024-04-23 06:22:12', '2024-04-23 06:22:12'),
(1397, 'english', 'Add Days', 'Add Days', '2024-04-23 06:22:21', '2024-04-23 06:22:21'),
(1398, 'english', 'After the Administrator sets this day. The job will be visible as of this day.', 'After the Administrator sets this day. The job will be visible as of this day.', '2024-04-23 06:22:21', '2024-04-23 06:22:21'),
(1399, 'english', 'Pay', 'Pay', '2024-04-23 06:26:26', '2024-04-23 06:26:26'),
(1400, 'english', 'Pending', 'Pending', '2024-04-23 06:26:26', '2024-04-23 06:26:26'),
(1401, 'english', 'Job title', 'Job title', '2024-04-23 06:43:52', '2024-04-23 06:43:52'),
(1402, 'english', 'Select a category', 'Select a category', '2024-04-23 06:43:52', '2024-04-23 06:43:52'),
(1403, 'english', 'Salary', 'Salary', '2024-04-23 06:43:52', '2024-04-23 06:43:52'),
(1404, 'english', 'Job Description', 'Job Description', '2024-04-23 06:43:52', '2024-04-23 06:43:52'),
(1405, 'english', 'View Details', 'View Details', '2024-04-23 06:51:03', '2024-04-23 06:51:03'),
(1406, 'english', 'added a job', 'added a job', '2024-04-23 06:51:06', '2024-04-23 06:51:06'),
(1407, 'english', '/ Monthly', '/ Monthly', '2024-04-23 06:51:06', '2024-04-23 06:51:06'),
(1408, 'english', 'See Applicant', 'See Applicant', '2024-04-23 06:51:06', '2024-04-23 06:51:06'),
(1409, 'english', 'Print', 'Print', '2024-04-23 07:10:22', '2024-04-23 07:10:22'),
(1410, 'english', 'Job Name', 'Job Name', '2024-04-23 07:10:22', '2024-04-23 07:10:22'),
(1411, 'english', 'Reference', 'Reference', '2024-04-23 07:10:22', '2024-04-23 07:10:22'),
(1412, 'english', 'Amount', 'Amount', '2024-04-23 07:10:22', '2024-04-23 07:10:22'),
(1413, 'english', 'Paid', 'Paid', '2024-04-23 07:18:39', '2024-04-23 07:18:39'),
(1414, 'english', 'All Pending Jobs', 'All Pending Jobs', '2024-04-23 07:18:46', '2024-04-23 07:18:46'),
(1415, 'english', 'Company', 'Company', '2024-04-23 07:18:46', '2024-04-23 07:18:46'),
(1416, 'english', 'Payment', 'Payment', '2024-04-23 07:18:46', '2024-04-23 07:18:46'),
(1417, 'english', 'Update Job', 'Update Job', '2024-04-23 07:18:51', '2024-04-23 07:18:51'),
(1418, 'english', 'Expires date', 'Expires date', '2024-04-23 07:18:51', '2024-04-23 07:18:51'),
(1419, 'english', 'Are you sure you want to publish this job!', 'Are you sure you want to publish this job!', '2024-04-23 07:18:51', '2024-04-23 07:18:51'),
(1420, 'english', 'All Jobs', 'All Jobs', '2024-04-23 07:19:11', '2024-04-23 07:19:11'),
(1421, 'english', 'Expire Date', 'Expire Date', '2024-04-23 07:19:11', '2024-04-23 07:19:11'),
(1422, 'english', 'Published', 'Published', '2024-04-23 07:19:11', '2024-04-23 07:19:11'),
(1423, 'english', 'Hold', 'Hold', '2024-04-23 07:19:11', '2024-04-23 07:19:11'),
(1424, 'english', 'Holding', 'Holding', '2024-04-23 07:19:19', '2024-04-23 07:19:19'),
(1425, 'english', 'Job Details', 'Job Details', '2024-04-23 07:20:50', '2024-04-23 07:20:50'),
(1426, 'english', 'Open', 'Open', '2024-04-23 07:21:59', '2024-04-23 07:21:59'),
(1427, 'english', 'You Can\"t Apply Your Own Job!', 'You Can\"t Apply Your Own Job!', '2024-04-23 07:22:00', '2024-04-23 07:22:00'),
(1428, 'english', 'Apply', 'Apply', '2024-04-23 07:42:11', '2024-04-23 07:42:11'),
(1429, 'english', 'Apply Now', 'Apply Now', '2024-04-23 07:42:17', '2024-04-23 07:42:17'),
(1430, 'english', 'Email*', 'Email*', '2024-04-23 07:42:18', '2024-04-23 07:42:18'),
(1431, 'english', 'Phone*', 'Phone*', '2024-04-23 07:42:18', '2024-04-23 07:42:18'),
(1432, 'english', 'CV*', 'CV*', '2024-04-23 07:42:18', '2024-04-23 07:42:18'),
(1433, 'english', 'Please upload your CV!', 'Please upload your CV!', '2024-04-23 07:42:18', '2024-04-23 07:42:18'),
(1434, 'english', 'Job Apply Successfully', 'Job Apply Successfully', '2024-04-23 07:44:21', '2024-04-23 07:44:21'),
(1435, 'english', 'Applied', 'Applied', '2024-04-23 07:44:21', '2024-04-23 07:44:21'),
(1436, 'english', 'Applicant', 'Applicant', '2024-04-23 07:44:29', '2024-04-23 07:44:29'),
(1437, 'english', 'Download', 'Download', '2024-04-23 07:44:29', '2024-04-23 07:44:29'),
(1438, 'english', 'Job Owner', 'Job Owner', '2024-04-23 07:50:42', '2024-04-23 07:50:42'),
(1439, 'english', 'Related Jobs', 'Related Jobs', '2024-04-23 09:11:31', '2024-04-23 09:11:31'),
(1440, 'english', 'Upload a preview(for mobile application )', 'Upload a preview(for mobile application )', '2024-05-31 17:52:46', '2024-05-31 17:52:46'),
(1441, 'english', 'All Group', 'All Group', '2024-06-25 09:54:02', '2024-06-25 09:54:02'),
(1442, 'english', 'Group owner', 'Group owner', '2024-06-25 09:54:02', '2024-06-25 09:54:02'),
(1443, 'english', 'Add a new Group', 'Add a new Group', '2024-06-25 09:54:04', '2024-06-25 09:54:04'),
(1444, 'english', 'Group details', 'Group details', '2024-06-25 09:54:04', '2024-06-25 09:54:04'),
(1445, 'english', 'Group Logo', 'Group Logo', '2024-06-25 09:54:04', '2024-06-25 09:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `live_streamings`
--

CREATE TABLE `live_streamings` (
  `streaming_id` int(11) NOT NULL,
  `publisher` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `details` longtext COLLATE utf8_unicode_ci,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketplaces`
--

CREATE TABLE `marketplaces` (
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
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_files`
--

CREATE TABLE `media_files` (
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
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_thrades`
--

CREATE TABLE `message_thrades` (
  `id` int(11) NOT NULL,
  `reciver_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `chatcenter` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `sender_user_id` int(11) DEFAULT NULL,
  `reciver_user_id` int(11) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `view` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pagecategories`
--

CREATE TABLE `pagecategories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subtitle` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_type` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `logo` text COLLATE utf8_unicode_ci,
  `coverphoto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `job` text COLLATE utf8_unicode_ci,
  `lifestyle` text COLLATE utf8_unicode_ci,
  `location` text COLLATE utf8_unicode_ci,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_likes`
--

CREATE TABLE `page_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `role` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` int(11) NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `keys` text COLLATE utf8_unicode_ci,
  `model_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `test_mode` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `is_addon` int(11) DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `identifier`, `currency`, `title`, `description`, `keys`, `model_name`, `test_mode`, `status`, `is_addon`, `created_at`, `updated_at`) VALUES
(1, 'paypal', 'USD', 'Paypal', '', '{\"sandbox_client_id\":\"\",\"sandbox_secret_key\":\"\",\"production_client_id\":\"\",\"production_secret_key\":\"\"}', 'Paypal', 1, 1, 0, '', '2023-03-15 06:55:21'),
(2, 'stripe', 'USD', 'Stripe', '', '{\"public_key\":\"\",\"secret_key\":\"\",\"public_live_key\":\"\",\"secret_live_key\":\"\"}', 'StripePay', 1, 1, 0, '', '2023-03-15 06:54:23'),
(3, 'razorpay', 'USD', 'Razorpay', '', '{\"public_key\":\"rzp_test_J60bqBOi1z1aF5\",\"secret_key\":\"uk935K7p4j96UCJgHK8kAU4q\"}', 'Razorpay', 1, 1, 0, NULL, NULL),
(4, 'flutterwave', 'USD', 'Flutterwave', '', '{\"public_key\":\"FLWPUBK_TEST-48dfbeb50344ecd8bc075b4ffe9ba266-X\",\"secret_key\":\"FLWSECK_TEST-1691582e23bd6ee4fb04213ec0b862dd-X\"}', 'Flutterwave', 1, 1, 0, NULL, NULL),
(5, 'paytm', 'USD', 'Paytm', '', '{\"public_key\":\"ApLWOX88722132489587\",\"secret_key\":\"#iFa7&W_C50VL@aT\"}', 'Paytm', 1, 1, 0, NULL, NULL),
(6, 'paystack', 'NGN', 'Paystack', '', '{\"secret_test_key\":\"sk_test_c746060e693dd50c6f397dffc6c3b2f655217c94\",\"public_test_key\":\"pk_test_0816abbed3c339b8473ff22f970c7da1c78cbe1b\",\"secret_live_key\":\"sk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\",\"public_live_key\":\"pk_live_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\"}', 'Paystack', 1, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_histories`
--

CREATE TABLE `payment_histories` (
  `id` bigint(20) NOT NULL,
  `item_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `currency` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `identifier` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_keys` longtext COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `publisher` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `post_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `privacy` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tagged_user_ids` longtext COLLATE utf8_unicode_ci,
  `activity_id` int(11) DEFAULT NULL,
  `location` varchar(300) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report_status` tinyint(1) NOT NULL DEFAULT '0',
  `user_reacts` longtext COLLATE utf8_unicode_ci,
  `shared_user` text COLLATE utf8_unicode_ci,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `posted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `hashtag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `album_image_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile_app_image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_shares`
--

CREATE TABLE `post_shares` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `shared_on` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `report` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `saved_products`
--

CREATE TABLE `saved_products` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `saveforlaters`
--

CREATE TABLE `saveforlaters` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `marketplace_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `blog_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'zoom_configuration', '{\"api_key\":null,\"api_secret\":null}', '2022-09-07 06:07:09', '2024-04-21 09:32:14'),
(2, 'about', '<h2 style=\"font-style:italic;\">What is Lorem Ipsum?</h2>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<h2>Why do we use it?</h2>\r\n\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Where does it come from?</h2>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '2022-09-07 06:07:09', '2022-09-10 23:07:33'),
(3, 'policy', '<h2>What is Lorem Ipsum?</h2>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<h2>Why do we use it?</h2>\r\n\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using &#39;Content here, content here&#39;, making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for &#39;lorem ipsum&#39; will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<h2>Where does it come from?</h2>\r\n\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>\r\n\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from &quot;de Finibus Bonorum et Malorum&quot; by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>', '2022-09-07 06:07:09', '2022-09-07 00:12:27'),
(4, 'term', '<p>Welcome to the University of Dhaka&rsquo;s website, featuring the oldest, largest and the premier multidisciplinary university of Bangladesh!&nbsp;</p>\r\n\r\n<p>Founded in 1921, The University of Dhaka has always had the mission of uplifting the educational standards of the people of the region. It was initially meant to provide tertiary education to people who didn&rsquo;t have access to higher studies till then. Subsequently, it has contributed significantly to the socio-cultural and political development of what was once East Bengal and then East Pakistan, and is now Bangladesh.</p>\r\n\r\n<p>Since its establishment, the university has been fulfilling the hopes and aspirations of its students and their parents. It has, of course, not only been a lighthouse of learning, but has also acted as a torch-bearer of the people of the region in issues such as democracy, freedom of expression, and the need for a just and equitable society. It has always been associated with high quality education and research in which students as well as faculty members and alumni have played their parts. The University of Dhaka&rsquo;s role has expanded beyond its classrooms and research labs during different crises the country has had to face since 1947. In many ways, thus, the university is unique, for it has played a major role in the creation of the independent nation-state of Bangladesh in 1971.</p>\r\n\r\n<p>Writing at this time, I am proud to note that the University of Dhaka has not only fulfilled but also exceeded the aspirations of those who set it up. It has been acclaimed as the best educational institution of the region, and one of the leading universities of the subcontinent. It is an incubator of ideas and has nurtured renowned scientists and academicians, great leaders, administrative and business officials, and entrepreneurs. Its proud alumni include the Father of the Nation Bangabandhu Sheikh Mujibur Rahman, and the incumbent Prime Minister, Sheikh Hasina, his august daughter. Most of the country&rsquo;s presidents, prime ministers, policymakers, politicians and CEOs of leading organizations, researchers and activists have been products of this institution.</p>\r\n\r\n<p>The University of Dhaka&rsquo;s doors are open for people from all classes, religions and parts of the country, and, indeed, also for international students. Its campus, too, regularly hosts different national and international events and festivals where people from every corner can and do participate. It is a hub, for breeding and nourishing liberal, secular and humanistics values.</p>\r\n\r\n<p>At the time when our country is dreaming to become a developed nation by 2041 and has made a firm commitment to achieve the Sustainable Development Goals (SDGs) by 2030, and in an age when we are witnessing the emergence of spirited youths all set to participate in the Fourth Industrial Revolution (4IR), I can affirm that the University of Dhaka is well prepared to meet all future challenges and is ready to lead the nation once again with its acquired experience, available resources, dedicated administrators, experienced faculty members and talented students and employees.</p>\r\n\r\n<p>Having been associated with the university for almost 40 years, first as a student, then as a faculty member, and later in various administrative capacities, it is a great honor and proud privilege for me to be here to not only witness but also to contribute to its centenary celebrations from where I am. Let me emphasize too that it is the singular privilege of all of us at the University of Dhaka to be celebrating its centenary in the year that Bangladesh is celebrating its golden jubilee of independence.</p>\r\n\r\n<p>I would like to request you all to explore the legacy, beauty, and history of this great institution of national, regional and international importance through this website. I hope it will be of use to you as you venture into the knowledge network of the university and acquaint yourself with its history, achievements, centers of learning, residential facilities and other attributes. My office staff and I will be more than happy to listen to you in case you want to visit us in person at a mutually convenient time.&nbsp;</p>\r\n\r\n<p>&nbsp;</p>', '2022-09-07 06:07:09', '2022-09-07 00:19:02'),
(5, 'smtp', '{\"smtp_protocol\":\"smtp\",\"smtp_crypto\":\"tls\",\"smtp_host\":\"smtp.gmail.com\",\"smtp_port\":\"587\",\"smtp_user\":\"your-email\",\"smtp_pass\":\"Email-password\"}', '2022-09-11 04:36:29', '2022-09-10 23:06:38'),
(6, 'about', 'about us', '2022-09-20 03:45:06', '2022-09-20 03:45:06'),
(7, 'policy', 'policy page', '2022-09-20 03:45:06', '2022-09-20 03:45:06'),
(8, 'term', 'term c', '2022-09-20 03:50:51', '2022-09-20 03:50:51'),
(10, 'system_name', 'sociopro', '2022-09-20 03:52:50', '2023-06-21 14:43:29'),
(11, 'system_title', 'Our private social platform', '2022-09-20 03:53:27', '2023-06-21 14:43:29'),
(12, 'system_email', 'admin@example.com', '2022-09-20 03:53:27', '2023-06-21 14:43:29'),
(13, 'system_phone', '236423625746', '2022-09-20 03:53:57', '2023-06-21 14:43:29'),
(14, 'system_fax', '555-123-4567', '2022-09-20 03:53:57', '2023-06-21 14:43:29'),
(15, 'system_address', 'New York, USA', '2022-09-20 03:54:41', '2023-06-21 14:43:29'),
(16, 'system_footer', 'Creativeitem', '2022-09-20 03:54:41', '2023-06-21 14:43:29'),
(17, 'system_footer_link', 'https://creativeitem.com', '2022-09-20 03:55:08', '2023-06-21 14:43:29'),
(18, 'system_dark_logo', '623.png', '2022-09-20 03:55:08', '2022-09-19 21:10:00'),
(19, 'system_light_logo', '727.png', '2022-09-20 03:55:27', '2022-09-19 21:10:00'),
(20, 'system_fav_icon', '450.png', '2022-09-20 03:55:27', '2022-09-19 20:39:06'),
(21, 'version', '2.6.1', '2022-09-20 03:55:27', '2022-09-19 20:39:06'),
(22, 'language', 'english', '2022-09-20 03:55:27', '2022-09-19 20:39:06'),
(23, 'public_signup', '1', '2022-09-20 03:55:27', '2023-06-21 14:43:29'),
(24, 'amazon_s3', '{\"active\":\"0\",\"AWS_ACCESS_KEY_ID\":\"\",\"AWS_SECRET_ACCESS_KEY\":\"\",\"AWS_DEFAULT_REGION\":\"\",\"AWS_BUCKET\":\"\"}', '2022-09-20 03:55:27', '2023-03-29 09:34:49'),
(25, 'ad_charge_per_day', '0.1', '2022-09-20 03:55:27', '2023-06-21 14:43:29'),
(26, 'system_currency', 'USD', '2022-09-07 06:07:09', '2023-06-21 14:43:29'),
(27, 'system_language', 'english', '2022-09-07 06:07:09', '2023-06-21 14:43:29'),
(28, 'purchase_code', 'Enter-your-valid-purchase-code', '2022-09-07 06:07:09', '2023-03-30 09:52:44'),
(29, 'google_analytics_id', NULL, '2022-09-07 06:07:09', '2023-06-21 14:43:29'),
(30, 'meta_pixel_id', NULL, '2022-09-07 06:07:09', '2023-06-21 14:43:29'),
(31, 'commission_rate', '', '2023-08-18 18:21:32', '2023-08-18 18:21:32'),
(32, 'job_price', NULL, '2024-01-08 10:58:12', '2024-04-23 06:22:29'),
(33, 'day', NULL, '2024-01-08 10:58:12', '2024-04-23 06:22:29'),
(34, 'badge_price', NULL, '2024-02-19 09:25:43', '2024-04-08 06:28:30'),
(35, 'theme_color', 'default', '2024-02-19 09:25:43', '2024-04-09 10:01:33'),
(36, 'zitsi_configuration', '{\"account_email\":\"admin@gmail.com\",\"jitsi_app_id\":\"xxxxxxxxxxxxxxxxxxxxxxxxxxx\",\"jitsi_jwt\":\"xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\"}', '2024-02-19 09:25:43', '2024-04-22 09:13:49'),
(37, 'hugging_face_auth_key', 'Auth Key', '2024-02-19 09:25:43', '2024-11-24 06:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `shares`
--

CREATE TABLE `shares` (
  `id` bigint(20) NOT NULL,
  `share_user_id` text,
  `event_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `url` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE `sponsors` (
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
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `story_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `publisher` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `publisher_id` int(11) DEFAULT NULL,
  `privacy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `media_files` longtext COLLATE utf8_unicode_ci,
  `description` longtext COLLATE utf8_unicode_ci,
  `status` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated_at` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `friends` longtext COLLATE utf8mb4_unicode_ci,
  `followers` longtext COLLATE utf8mb4_unicode_ci,
  `gender` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `studied_at` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci,
  `save_post` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastActive` timestamp NULL DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_settings` longtext COLLATE utf8mb4_unicode_ci,
  `profile_status` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` text,
  `privacy` text,
  `file` text,
  `view` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mobile_app_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_active_requests`
--
ALTER TABLE `account_active_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `album_images`
--
ALTER TABLE `album_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batchs`
--
ALTER TABLE `batchs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `block_users`
--
ALTER TABLE `block_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogcategories`
--
ALTER TABLE `blogcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feeling_and_activities`
--
ALTER TABLE `feeling_and_activities`
  ADD PRIMARY KEY (`feeling_and_activity_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invites`
--
ALTER TABLE `invites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_streamings`
--
ALTER TABLE `live_streamings`
  ADD PRIMARY KEY (`streaming_id`);

--
-- Indexes for table `marketplaces`
--
ALTER TABLE `marketplaces`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_files`
--
ALTER TABLE `media_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_thrades`
--
ALTER TABLE `message_thrades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagecategories`
--
ALTER TABLE `pagecategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_likes`
--
ALTER TABLE `page_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_histories`
--
ALTER TABLE `payment_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `post_shares`
--
ALTER TABLE `post_shares`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_products`
--
ALTER TABLE `saved_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saveforlaters`
--
ALTER TABLE `saveforlaters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `shares`
--
ALTER TABLE `shares`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`story_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_active_requests`
--
ALTER TABLE `account_active_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `album_images`
--
ALTER TABLE `album_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `batchs`
--
ALTER TABLE `batchs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `block_users`
--
ALTER TABLE `block_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogcategories`
--
ALTER TABLE `blogcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feeling_and_activities`
--
ALTER TABLE `feeling_and_activities`
  MODIFY `feeling_and_activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friendships`
--
ALTER TABLE `friendships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invites`
--
ALTER TABLE `invites`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1446;

--
-- AUTO_INCREMENT for table `live_streamings`
--
ALTER TABLE `live_streamings`
  MODIFY `streaming_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplaces`
--
ALTER TABLE `marketplaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_files`
--
ALTER TABLE `media_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message_thrades`
--
ALTER TABLE `message_thrades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagecategories`
--
ALTER TABLE `pagecategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `page_likes`
--
ALTER TABLE `page_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment_histories`
--
ALTER TABLE `payment_histories`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_shares`
--
ALTER TABLE `post_shares`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_products`
--
ALTER TABLE `saved_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saveforlaters`
--
ALTER TABLE `saveforlaters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `shares`
--
ALTER TABLE `shares`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
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
CREATE TABLE IF NOT EXISTS `projects` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `author_id` bigint unsigned, `project_category` bigint unsigned, `project_title` varchar(255) not null, `slug` varchar(255) not null, `project_type` varchar(255) check (`project_type` in ('hourly', 'fixed')), `project_payout_type` varchar(255) check (`project_payout_type` in ('fixed', 'both', 'milestone', 'hourly')), `attachments` text, `project_description` text, `project_payment_mode` varchar(255), `project_max_hours` varchar(255), `project_min_price` float not null default '0', `project_max_price` float not null default '0', `project_country` varchar(255), `country_zipcode` varchar(255), `address` text, `project_duration` bigint unsigned, `project_hiring_seller` bigint unsigned, `project_expert_level` bigint unsigned, `project_location` bigint unsigned, `is_featured` bigint unsigned not null default '0', `featured_expiry` datetime, `status` varchar(255) check (`status` in ('draft', 'pending', 'publish', 'hired', 'completed', 'refunded', 'cancelled')) not null default 'draft', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_orders` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `author_id` bigint unsigned, `gig_id` bigint unsigned, `plan_type` varchar(255) not null, `plan_amount` float not null default '0', `gig_features` text, `gig_addons` text, `downloadable` text, `gig_delivery_days` bigint unsigned not null, `gig_start_time` datetime, `commission_type` bigint unsigned not null default '0', `commission_amount` float not null default '0', `status` varchar(255) check (`status` in ('draft', 'hired', 'queued', 'completed', 'disputed', 'refunded', 'rejected')) not null default 'draft', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_durations` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `status` varchar(255) check (`status` in ('active', 'deactive')) not null default 'active', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `seller_project_invites` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `seller_id` bigint unsigned not null, `project_id` bigint unsigned not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_locations` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `status` varchar(255) check (`status` in ('active', 'deactive')) not null default 'active', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `escrow_disburse_methods` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `seller_id` bigint unsigned not null, `project_id` bigint unsigned not null, `disburse_methods_id` bigint unsigned not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_order_activities` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `sender_id` bigint unsigned not null, `receiver_id` bigint unsigned not null, `gig_id` bigint unsigned not null, `order_id` bigint unsigned not null, `type` varchar(255) check (`type` in ('revision', 'final')) not null default 'revision', `attachments` text, `description` text not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_activities` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `sender_id` bigint unsigned not null, `receiver_id` bigint unsigned not null, `project_id` bigint unsigned not null, `attachments` text, `description` text not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `disputes` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `created_by` bigint unsigned, `created_to` bigint unsigned, `proposal_id` bigint unsigned, `gig_order_id` bigint unsigned, `price` float not null default '0', `dispute_issue` varchar(255), `dispute_detail` text, `dispute_log` text, `resolved_by` varchar(255) check (`resolved_by` in ('admin', 'seller')) not null default 'seller', `favour_to` varchar(255) check (`favour_to` in ('seller', 'buyer')), `status` varchar(255) check (`status` in ('publish', 'declined', 'refunded', 'resolved', 'disputed', 'processing', 'cancelled')) not null default 'publish', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `dispute_conversations` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `sender_id` bigint unsigned not null, `dispute_id` bigint unsigned not null, `message_id` bigint unsigned, `message` text, `attachments` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_categories` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `parent_id` bigint unsigned, `name` varchar(255) not null, `image` text, `slug` varchar(255) not null, `description` text, `status` varchar(255) check (`status` in ('active', 'deactive')) not null default 'active', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_categories` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `parent_id` bigint unsigned, `name` varchar(255) not null, `image` text, `slug` varchar(255) not null, `description` text, `status` varchar(255) check (`status` in ('active', 'deactive')) not null default 'active', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_category_features` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `category_id` bigint unsigned, `label` varchar(255) not null, `options` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gigs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `author_id` bigint unsigned not null, `title` varchar(255) not null, `slug` varchar(255) not null, `country` varchar(255) not null, `zipcode` varchar(255) not null, `address` text, `description` text, `attachments` text, `downloadable` text, `is_featured` bigint unsigned not null default '0', `featured_expiry` datetime, `status` varchar(255) check (`status` in ('publish', 'draft')) not null default 'publish', `deleted_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_plans` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned, `title` varchar(255) not null, `description` text, `price` float not null default '0', `delivery_time` bigint unsigned not null, `is_featured` bigint unsigned not null default '0', `options` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_addons` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `addon_id` bigint unsigned not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_faqs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `question` text not null, `answer` text not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_tags` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `tag_name` varchar(255) not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_category_link` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `category_id` bigint unsigned not null, `category_level` bigint unsigned not null, `created_at` datetime, `updated_at` datetime, foreign key(`gig_id`) references `gigs`(`id`), foreign key(`category_id`) references `gig_categories`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `advertisers` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `name` varchar(255) not null, `billing_email` varchar(255) not null, `daily_spend_limit` numeric, `lifetime_spend_limit` numeric, `wallet_balance` numeric not null default '0', `status` varchar(255) not null default 'active', `affiliate_id` bigint unsigned, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`affiliate_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_freelancers` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `freelancer` varchar(255) not null, `role` varchar(255) not null default 'contributor', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_tasks` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `title` varchar(255) not null, `assignee` varchar(255), `status` varchar(255) not null default 'pending', `due_date` datetime, `hours_logged` numeric not null default '0', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_milestones` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `title` varchar(255) not null, `amount` numeric not null, `status` varchar(255) not null default 'pending', `due_date` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_submissions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `milestone_id` bigint unsigned, `notes` text not null, `attachment_url` varchar(255), `status` varchar(255) not null default 'submitted', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_invitations` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `freelancer` varchar(255) not null, `message` text, `status` varchar(255) not null default 'pending', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_time_logs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `freelancer` varchar(255) not null, `hours` numeric not null, `note` text, `logged_at` datetime not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `project_reviews` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `project_id` bigint unsigned not null, `rating` numeric not null, `comment` text, `author` varchar(255) not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_timeline_items` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `occurred_at` datetime not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_packages` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `name` varchar(255) not null, `price` numeric not null, `delivery_time` bigint unsigned not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_requirements` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `prompt` text not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_change_requests` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `requester` varchar(255) not null, `notes` text not null, `status` varchar(255) not null default 'pending', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `gig_reviews` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `gig_id` bigint unsigned not null, `rating` numeric not null, `comment` text, `author` varchar(255) not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `profile_portfolios` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `link` varchar(255), `thumbnail_url` varchar(255), `featured` tinyint(1) not null default '0', `completed_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `profile_reviews` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `reviewer` varchar(255), `rating` numeric not null, `comment` text, `reference` varchar(255), `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `profile_educations` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `institution` varchar(255) not null, `degree` varchar(255), `field` varchar(255), `start_year` bigint unsigned, `end_year` bigint unsigned, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `profile_certifications` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `name` varchar(255) not null, `issuer` varchar(255), `credential_url` varchar(255), `issued_at` datetime, `expires_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `custom_gigs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) not null, `buyer` varchar(255) not null, `scope` text, `status` varchar(255) not null default 'draft', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `dispute_stages` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `dispute_id` bigint unsigned not null, `stage` varchar(255) not null, `notes` text, `decision` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `freelance_tags` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `slug` varchar(255) not null, `type` varchar(255) not null default 'freelancer', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `freelance_tag_assignments` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `tag_id` bigint unsigned not null, `assignable_id` bigint unsigned not null, `assignable_type` varchar(255) not null, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `escrow_actions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `escrow_id` bigint unsigned not null, `type` varchar(255) not null, `amount` numeric, `actor` varchar(255), `decision` varchar(255), `notes` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `headhunter_profiles` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `status` varchar(255) not null default 'pending', `bio` text, `industries` text, `skills` text, `approved_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `headhunter_mandates` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `headhunter_profile_id` bigint unsigned not null, `organisation_id` bigint unsigned, `title` varchar(255) not null, `location` varchar(255), `fee_model` varchar(255), `fee_amount` numeric, `status` varchar(255) not null default 'open', `requirements` text, `created_at` datetime, `updated_at` datetime, foreign key(`headhunter_profile_id`) references `headhunter_profiles`(`id`), foreign key(`organisation_id`) references `organizations`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `headhunter_candidates` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `headhunter_profile_id` bigint unsigned not null, `user_id` bigint unsigned, `name` varchar(255) not null, `email` varchar(255), `phone` varchar(255), `skills` text, `experience` text, `created_at` datetime, `updated_at` datetime, foreign key(`headhunter_profile_id`) references `headhunter_profiles`(`id`), foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `headhunter_pipeline_items` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `headhunter_mandate_id` bigint unsigned not null, `headhunter_candidate_id` bigint unsigned not null, `stage` varchar(255) not null default 'sourced', `notes` text, `moved_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`headhunter_mandate_id`) references `headhunter_mandates`(`id`), foreign key(`headhunter_candidate_id`) references `headhunter_candidates`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `headhunter_interviews` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `headhunter_pipeline_item_id` bigint unsigned not null, `scheduled_by` bigint unsigned not null, `scheduled_at` datetime not null, `status` varchar(255) not null default 'scheduled', `summary` text, `created_at` datetime, `updated_at` datetime, foreign key(`headhunter_pipeline_item_id`) references `headhunter_pipeline_items`(`id`), foreign key(`scheduled_by`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_connection_caches` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `connection_id` bigint unsigned not null, `degree` bigint unsigned not null, `connection_path` text, `mutual_count` bigint unsigned not null default '0', `strength` bigint unsigned not null default '0', `calculated_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`connection_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_mutual_connections` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `target_user_id` bigint unsigned not null, `mutual_user_ids` text not null, `mutual_count` bigint unsigned not null, `calculated_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`target_user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_network_metrics` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `first_degree_count` bigint unsigned not null default '0', `second_degree_count` bigint unsigned not null default '0', `third_degree_count` bigint unsigned not null default '0', `mutual_count` bigint unsigned not null default '0', `suggestions` text, `calculated_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_professional_profiles` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `headline` varchar(255), `tagline` varchar(255), `location` varchar(255), `top_skills` text, `available_for_work` tinyint(1) not null default '0', `public_url` varchar(255), `share_hash` varchar(255), `connections_count` bigint unsigned not null default '0', `activity_summary` text, `interests` text, `visibility` varchar(255) not null default 'public', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_skills` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `name` varchar(255) not null, `proficiency` varchar(255), `is_top_five` tinyint(1) not null default '0', `weight` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_certifications` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `name` varchar(255) not null, `authority` varchar(255), `license_number` varchar(255), `verification_url` varchar(255), `issued_at` date, `expires_at` date, `description` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_work_histories` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `title` varchar(255) not null, `company_name` varchar(255) not null, `employment_type` varchar(255), `location` varchar(255), `started_at` date, `ended_at` date, `is_current` tinyint(1) not null default '0', `description` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_education_histories` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `institution` varchar(255) not null, `degree` varchar(255), `field` varchar(255), `started_at` date, `ended_at` date, `description` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_references` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `reference_user_id` bigint unsigned, `name` varchar(255) not null, `relationship` varchar(255), `contact_email` varchar(255), `contact_phone` varchar(255), `statement` text, `verified_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`reference_user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_background_checks` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `status` varchar(255) not null default 'pending', `provider` varchar(255), `reference` varchar(255), `checked_at` datetime, `expires_at` datetime, `notes` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_interests` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `interest` varchar(255) not null, `weight` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_opportunities` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `type` varchar(255) not null, `title` varchar(255) not null, `description` text, `rate` numeric, `currency` varchar(255), `status` varchar(255) not null default 'open', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_company_profiles` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `page_id` bigint unsigned not null, `headline` varchar(255), `industry` varchar(255), `location` varchar(255), `website` varchar(255), `metadata` text, `employee_count` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`page_id`) references `pages`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_company_employees` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `company_profile_id` bigint unsigned not null, `user_id` bigint unsigned not null, `role_title` varchar(255), `started_at` date, `ended_at` date, `is_current` tinyint(1) not null default '1', `created_at` datetime, `updated_at` datetime, foreign key(`company_profile_id`) references `pro_network_company_profiles`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_marketplace_escrows` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `order_id` bigint unsigned not null, `status` varchar(255) not null default 'pending', `amount` numeric not null, `currency` varchar(255) not null default 'USD', `delivery_method` varchar(255) not null default 'delivery', `delivery_notes` varchar(255), `escrow_reference` varchar(255), `held_at` datetime, `released_at` datetime, `refunded_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`order_id`) references `marketplace_orders`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_marketplace_milestones` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `escrow_id` bigint unsigned not null, `title` varchar(255) not null, `amount` numeric not null, `status` varchar(255) not null default 'pending', `due_at` datetime, `released_at` datetime, `refunded_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`escrow_id`) references `pro_network_marketplace_escrows`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_marketplace_transactions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `escrow_id` bigint unsigned not null, `user_id` bigint unsigned, `type` varchar(255) not null, `amount` numeric not null, `currency` varchar(255) not null default 'USD', `notes` text, `created_at` datetime, `updated_at` datetime, foreign key(`escrow_id`) references `pro_network_marketplace_escrows`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_marketplace_disputes` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `escrow_id` bigint unsigned not null, `raised_by` bigint unsigned not null, `reason` text not null, `status` varchar(255) not null default 'open', `resolution_notes` text, `resolved_by` bigint unsigned, `resolved_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`escrow_id`) references `pro_network_marketplace_escrows`(`id`) on delete cascade, foreign key(`raised_by`) references `users`(`id`) on delete cascade, foreign key(`resolved_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_marketplace_dispute_messages` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `dispute_id` bigint unsigned not null, `user_id` bigint unsigned not null, `message` text not null, `attachments` text, `created_at` datetime, `updated_at` datetime, foreign key(`dispute_id`) references `pro_network_marketplace_disputes`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_live_sessions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `host_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `status` varchar(255) not null default 'scheduled', `guest_user_ids` text, `likes_count` bigint unsigned not null default '0', `donations_total` numeric not null default '0', `chat_channel` varchar(255), `started_at` datetime, `ended_at` datetime, `recording_path` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`host_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_live_session_participants` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `live_session_id` bigint unsigned not null, `user_id` bigint unsigned not null, `role` varchar(255) not null default 'guest', `joined_at` datetime, `left_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`live_session_id`) references `pro_network_live_sessions`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_reactions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `reactable_type` varchar(255) not null, `reactable_id` bigint unsigned not null, `user_id` bigint unsigned not null, `type` varchar(255) not null, `weight` bigint unsigned not null default '1', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_reaction_aggregates` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `reactable_type` varchar(255) not null, `reactable_id` bigint unsigned not null, `counts` text, `dislikes` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_profile_reaction_scores` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `like_score` bigint unsigned not null default '0', `dislike_count` bigint unsigned not null default '0', `reaction_breakdown` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_hashtags` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `tag` varchar(255) not null, `normalized` varchar(255) not null, `usage_count` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_hashtaggables` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `hashtag_id` bigint unsigned not null, `hashtaggable_type` varchar(255) not null, `hashtaggable_id` bigint unsigned not null, `created_at` datetime, `updated_at` datetime, foreign key(`hashtag_id`) references `pro_network_hashtags`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_music_tracks` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `title` varchar(255) not null, `artist` varchar(255), `duration_seconds` bigint unsigned not null default '0', `license` varchar(255), `storage_disk` varchar(255), `storage_path` varchar(255), `genre` varchar(255), `mood` varchar(255), `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_story_metadata` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `story_id` bigint unsigned not null, `overlays` text, `filters` text, `stickers` text, `links` text, `music_track_id` bigint unsigned, `live_session_id` bigint unsigned, `created_at` datetime, `updated_at` datetime, foreign key(`story_id`) references `stories`(`id`) on delete cascade, foreign key(`music_track_id`) references `pro_network_music_tracks`(`id`) on delete set null, foreign key(`live_session_id`) references `pro_network_live_sessions`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_security_events` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned, `type` varchar(255) not null, `ip` varchar(255), `user_agent` varchar(255), `severity` varchar(255) not null default 'info', `context` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_moderation_queue` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `moderatable_type` varchar(255) not null, `moderatable_id` bigint unsigned not null, `reason` varchar(255) not null, `status` varchar(255) not null default 'pending', `flags` text, `actioned_by` bigint unsigned, `resolved_at` datetime, `notes` text, `created_at` datetime, `updated_at` datetime, foreign key(`actioned_by`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_bad_words` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `phrase` varchar(255) not null, `severity` varchar(255) not null default 'medium', `replacement` varchar(255), `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_bad_word_rules` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `action` varchar(255) not null, `applies_to` text, `active` tinyint(1) not null default '1', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_file_scans` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `path` varchar(255) not null, `file_hash` varchar(255), `scanner_name` varchar(255), `status` varchar(255) not null default 'pending', `details` text, `scanned_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_analytics_events` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `event` varchar(255) not null, `user_id` bigint unsigned, `properties` text, `ip` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_analytics_metrics` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `entity_type` varchar(255) not null, `entity_id` bigint unsigned not null, `metric` varchar(255) not null, `value` bigint unsigned not null default '0', `meta` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_account_types` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `slug` varchar(255) not null, `name` varchar(255) not null, `description` text, `features` text, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_user_account_types` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `account_type_id` bigint unsigned not null, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade, foreign key(`account_type_id`) references `pro_network_account_types`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_user_feature_flags` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `feature` varchar(255) not null, `enabled` tinyint(1) not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_age_verifications` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `status` varchar(255) not null default 'pending', `provider` varchar(255), `provider_reference` varchar(255), `verified_at` datetime, `rejected_at` datetime, `payload` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_age_verification_logs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `age_verification_id` bigint unsigned not null, `event` varchar(255) not null, `meta` text, `created_at` datetime, `updated_at` datetime, foreign key(`age_verification_id`) references `pro_network_age_verifications`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_newsletter_subscriptions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned, `email` varchar(255) not null, `subscribed` tinyint(1) not null default '1', `source` varchar(255), `locale` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_invite_contributions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `inviter_id` bigint unsigned not null, `invitee_id` bigint unsigned, `post_id` bigint unsigned, `role` varchar(255), `status` varchar(255) not null default 'pending', `message` text, `created_at` datetime, `updated_at` datetime, foreign key(`inviter_id`) references `users`(`id`) on delete cascade, foreign key(`invitee_id`) references `users`(`id`) on delete set null, foreign key(`post_id`) references `posts`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `pro_network_post_enhancements` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `post_id` bigint unsigned not null, `type` varchar(255) not null, `payload` text, `created_at` datetime, `updated_at` datetime, foreign key(`post_id`) references `posts`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `webinars` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `host_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `starts_at` datetime not null, `ends_at` datetime not null, `is_live` tinyint(1) not null default '0', `is_paid` tinyint(1) not null default '0', `price` numeric, `waiting_room_message` varchar(255), `stream_provider` varchar(255), `rtmp_endpoint` varchar(255), `recording_path` varchar(255), `status` varchar(255) not null default 'scheduled', `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`host_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO webinars VALUES(1,1,'Sample Webinar 1','Generated seed webinar for onboarding flows.','2025-12-04 05:54:50','2025-12-04 06:54:50',0,0,0,NULL,NULL,NULL,NULL,'scheduled',NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO webinars VALUES(2,1,'Sample Webinar 2','Generated seed webinar for onboarding flows.','2025-12-05 05:54:50','2025-12-05 06:54:50',0,0,0,NULL,NULL,NULL,NULL,'scheduled',NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO webinars VALUES(3,1,'Sample Webinar 3','Generated seed webinar for onboarding flows.','2025-12-06 05:54:50','2025-12-06 06:54:50',0,0,0,NULL,NULL,NULL,NULL,'scheduled',NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
CREATE TABLE IF NOT EXISTS `webinar_registrations` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `webinar_id` bigint unsigned not null, `user_id` bigint unsigned not null, `ticket_id` bigint unsigned, `status` varchar(255) not null default 'registered', `checked_in_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`webinar_id`) references `webinars`(`id`), foreign key(`user_id`) references `users`(`id`), foreign key(`ticket_id`) references `tickets`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `recordings` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `recordable_type` varchar(255) not null, `recordable_id` bigint unsigned not null, `user_id` bigint unsigned, `path` varchar(255) not null, `duration` bigint unsigned, `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `networking_sessions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `host_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `duration_seconds` bigint unsigned not null, `rotation_interval` bigint unsigned not null, `starts_at` datetime not null, `is_paid` tinyint(1) not null default '0', `price` numeric, `status` varchar(255) not null default 'scheduled', `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`host_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO networking_sessions VALUES(1,1,'Speed Networking 1','Autogenerated networking session.',1800,180,'2025-12-05 05:54:50',0,NULL,'scheduled',NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO networking_sessions VALUES(2,1,'Speed Networking 2','Autogenerated networking session.',1800,180,'2025-12-06 05:54:50',0,NULL,'scheduled',NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
CREATE TABLE IF NOT EXISTS `networking_participants` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `networking_session_id` bigint unsigned not null, `user_id` bigint unsigned not null, `current_partner_id` bigint unsigned, `rotation_position` bigint unsigned not null default '1', `joined_at` datetime, `status` varchar(255) not null default 'registered', `created_at` datetime, `updated_at` datetime, foreign key(`networking_session_id`) references `networking_sessions`(`id`), foreign key(`user_id`) references `users`(`id`), foreign key(`current_partner_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `podcast_series` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `host_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `cover_art_path` varchar(255), `is_public` tinyint(1) not null default '0', `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`host_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO podcast_series VALUES(1,1,'Podcast Series 1','Seeded professional insights series.',NULL,1,NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO podcast_series VALUES(2,1,'Podcast Series 2','Seeded professional insights series.',NULL,1,NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
CREATE TABLE IF NOT EXISTS `podcast_episodes` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `podcast_series_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `scheduled_for` datetime, `published_at` datetime, `audio_path` varchar(255), `duration` bigint unsigned, `is_public` tinyint(1) not null default '0', `metadata` text, `created_at` datetime, `updated_at` datetime, `is_paid` tinyint(1) not null default '0', `entitlement_type` varchar(255), `price_cents` bigint unsigned, `donation_suggested_cents` bigint unsigned, foreign key(`podcast_series_id`) references `podcast_series`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `interviews` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `host_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `scheduled_at` datetime not null, `duration_minutes` bigint unsigned not null, `is_panel` tinyint(1) not null default '0', `location` varchar(255), `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`host_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO interviews VALUES(1,1,'Interview Round 1','Seeded interview schedule.','2025-12-09 05:54:50',60,1,NULL,NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO interviews VALUES(2,1,'Interview Round 2','Seeded interview schedule.','2025-12-10 05:54:50',60,1,NULL,NULL,'2025-12-03 05:54:50','2025-12-03 05:54:50');
CREATE TABLE IF NOT EXISTS `interview_slots` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `interview_id` bigint unsigned not null, `interviewer_id` bigint unsigned not null, `interviewee_id` bigint unsigned not null, `starts_at` datetime not null, `ends_at` datetime not null, `status` varchar(255) not null default 'scheduled', `meeting_link` varchar(255), `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`interview_id`) references `interviews`(`id`), foreign key(`interviewer_id`) references `users`(`id`), foreign key(`interviewee_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `interview_scores` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `interview_id` bigint unsigned not null, `interview_slot_id` bigint unsigned not null, `interviewer_id` bigint unsigned not null, `criteria` text not null, `scores` text not null, `comments` text, `created_at` datetime, `updated_at` datetime, foreign key(`interview_id`) references `interviews`(`id`), foreign key(`interview_slot_id`) references `interview_slots`(`id`), foreign key(`interviewer_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `campaigns` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `advertiser_id` bigint unsigned not null, `title` varchar(255) not null, `start_date` datetime not null, `end_date` datetime not null, `budget` numeric not null, `bidding` varchar(255) check (`bidding` in ('click', 'view', 'conversion')) not null, `status` varchar(255) not null default 'draft', `spend` numeric not null default '0', `placement` varchar(255) not null, `objective` varchar(255), `targeting_reach` bigint unsigned not null default '0', `approval_state` varchar(255) not null default 'pending', `created_at` datetime, `updated_at` datetime, foreign key(`advertiser_id`) references `advertisers`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `launchpad_programmes` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `creator_id` bigint unsigned not null, `title` varchar(255) not null, `category` varchar(255) not null, `description` text, `estimated_hours` bigint unsigned not null default '0', `estimated_weeks` bigint unsigned not null default '0', `reference_offered` tinyint(1) not null default '0', `qualification_offered` tinyint(1) not null default '0', `pay_reduction_percentage` numeric, `status` varchar(255) not null default 'draft', `created_at` datetime, `updated_at` datetime, foreign key(`creator_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `launchpad_tasks` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `launchpad_programme_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `order` bigint unsigned not null default '0', `estimated_hours` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`launchpad_programme_id`) references `launchpad_programmes`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `launchpad_applications` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `launchpad_programme_id` bigint unsigned not null, `user_id` bigint unsigned not null, `status` varchar(255) not null default 'submitted', `motivation` text, `reference_issued` tinyint(1) not null default '0', `qualification_issued` tinyint(1) not null default '0', `hours_gained` bigint unsigned not null default '0', `weeks_gained` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`launchpad_programme_id`) references `launchpad_programmes`(`id`), foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `launchpad_interviews` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `launchpad_application_id` bigint unsigned not null, `scheduled_by` bigint unsigned not null, `scheduled_at` datetime not null, `status` varchar(255) not null default 'scheduled', `notes` text, `created_at` datetime, `updated_at` datetime, foreign key(`launchpad_application_id`) references `launchpad_applications`(`id`), foreign key(`scheduled_by`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `tickets` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `ticketable_type` varchar(255) not null, `ticketable_id` bigint unsigned not null, `user_id` bigint unsigned, `price` numeric not null default '0', `currency` varchar(255) not null default 'USD', `status` varchar(255) not null default 'available', `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `launchpad_application_task_progress` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `launchpad_application_id` bigint unsigned not null, `launchpad_task_id` bigint unsigned not null, `completed_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`launchpad_application_id`) references `launchpad_applications`(`id`), foreign key(`launchpad_task_id`) references `launchpad_tasks`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `networking_contact_exchanges` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `networking_session_id` bigint unsigned not null, `user_id` bigint unsigned not null, `partner_id` bigint unsigned not null, `starred` tinyint(1) not null default '0', `follow_up_at` datetime, `notes` text, `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`networking_session_id`) references `networking_sessions`(`id`), foreign key(`user_id`) references `users`(`id`), foreign key(`partner_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `podcast_series_followers` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `podcast_series_id` bigint unsigned not null, `user_id` bigint unsigned not null, `created_at` datetime, `updated_at` datetime, foreign key(`podcast_series_id`) references `podcast_series`(`id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `podcast_episode_transcripts` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `podcast_episode_id` bigint unsigned not null, `language` varchar(255) not null default 'en', `source` varchar(255), `content` text not null, `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`podcast_episode_id`) references `podcast_episodes`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `podcast_episode_highlights` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `podcast_episode_id` bigint unsigned not null, `title` varchar(255) not null, `description` text, `starts_at_seconds` bigint unsigned not null, `ends_at_seconds` bigint unsigned, `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`podcast_episode_id`) references `podcast_episodes`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `podcast_episode_entitlements` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `podcast_episode_id` bigint unsigned not null, `user_id` bigint unsigned not null, `entitlement_type` varchar(255) not null, `source` varchar(255), `granted_at` datetime default CURRENT_TIMESTAMP not null, `expires_at` datetime, `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`podcast_episode_id`) references `podcast_episodes`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `networking_pairings` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `networking_session_id` bigint unsigned not null, `participant_id` bigint unsigned not null, `partner_id` bigint unsigned, `round` bigint unsigned not null, `group_key` varchar(255), `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`networking_session_id`) references `networking_sessions`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ad_groups` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `name` varchar(255) not null, `daily_budget` numeric, `bid_amount` numeric, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ai_sessions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `tool` varchar(255) not null, `status` varchar(255) not null default 'pending', `prompt_tokens` bigint unsigned not null default '0', `completion_tokens` bigint unsigned not null default '0', `credit_cost` bigint unsigned not null default '0', `input` text, `output` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ai_byok_credentials` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `provider` varchar(255) not null, `api_key` varchar(255) not null, `meta` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ai_subscription_plans` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `slug` varchar(255) not null, `limits` text, `price` numeric not null default '0', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO ai_subscription_plans VALUES(1,'Basic','basic','{`daily`:20000,`monthly`:300000}',0,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO ai_subscription_plans VALUES(2,'Pro','pro','{`daily`:60000,`monthly`:900000}',0,'2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO ai_subscription_plans VALUES(3,'Enterprise','enterprise','{`daily`:120000,`monthly`:2000000}',0,'2025-12-03 05:54:50','2025-12-03 05:54:50');
CREATE TABLE IF NOT EXISTS `ai_user_subscriptions` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `ai_subscription_plan_id` bigint unsigned not null, `renews_at` date, `status` varchar(255) not null default 'active', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`), foreign key(`ai_subscription_plan_id`) references `ai_subscription_plans`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `ai_usage_aggregates` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `period_start` date not null, `period_end` date not null, `tokens_used` bigint unsigned not null default '0', `sessions_count` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `creatives` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `ad_group_id` bigint unsigned, `type` varchar(255) not null, `title` varchar(255) not null, `body` text, `destination_url` varchar(255), `media_path` varchar(255), `status` varchar(255) not null default 'draft', `cta` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade, foreign key(`ad_group_id`) references `ad_groups`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `volunteering_opportunities` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `organisation_id` bigint unsigned, `creator_id` bigint unsigned not null, `title` varchar(255) not null, `sector` varchar(255) not null, `location` varchar(255), `commitment` varchar(255), `expenses_covered` tinyint(1) not null default '0', `verified` tinyint(1) not null default '0', `status` varchar(255) not null default 'draft', `description` text, `created_at` datetime, `updated_at` datetime, foreign key(`organisation_id`) references `organizations`(`id`), foreign key(`creator_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `volunteering_applications` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `volunteering_opportunity_id` bigint unsigned not null, `user_id` bigint unsigned not null, `status` varchar(255) not null default 'submitted', `motivation` text, `hours_contributed` bigint unsigned not null default '0', `created_at` datetime, `updated_at` datetime, foreign key(`volunteering_opportunity_id`) references `volunteering_opportunities`(`id`), foreign key(`user_id`) references `users`(`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `targeting_rules` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `type` varchar(255) not null, `value` varchar(255) not null, `operator` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `metrics` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `impressions` bigint unsigned not null default '0', `clicks` bigint unsigned not null default '0', `conversions` bigint unsigned not null default '0', `spend` numeric not null default '0', `recorded_at` datetime not null, `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `forecasts` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `reach` bigint unsigned not null default '0', `clicks` bigint unsigned not null default '0', `conversions` bigint unsigned not null default '0', `estimated_spend` numeric not null default '0', `assumptions` text, `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `placements` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` varchar(255) not null, `channel` varchar(255) not null, `description` text, `is_active` tinyint(1) not null default '1', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
CREATE TABLE IF NOT EXISTS `targeting_rules` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `type` varchar(255) not null, `value` varchar(255) not null, `operator` varchar(255), `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `metrics` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `impressions` bigint unsigned not null default '0', `clicks` bigint unsigned not null default '0', `conversions` bigint unsigned not null default '0', `spend` numeric not null default '0', `recorded_at` datetime not null, `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `forecasts` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `campaign_id` bigint unsigned not null, `reach` bigint unsigned not null default '0', `clicks` bigint unsigned not null default '0', `conversions` bigint unsigned not null default '0', `estimated_spend` numeric not null default '0', `assumptions` text, `created_at` datetime, `updated_at` datetime, foreign key(`campaign_id`) references `campaigns`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `keyword_prices` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `keyword` varchar(255) not null, `cpc` numeric not null default '0', `cpa` numeric not null default '0', `cpm` numeric not null default '0', `created_at` datetime, `updated_at` datetime, `search_volume` bigint unsigned not null default '0', `competition_score` numeric not null default '0', `quality_score` numeric not null default '0.5', `ctr` numeric not null default '0', `conversion_rate` numeric not null default '0', `placement_multiplier` numeric not null default '1', `currency` varchar(255) not null default 'USD', `last_synced_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO keyword_prices VALUES(1,'networking',1.19999999999999995,6.5,3.29999999999999982,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(2,'jobs',1,7.5,3.89999999999999991,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(3,'freelance',0.900000000000000022,5.79999999999999982,3.10000000000000008,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(4,'podcast',0.800000000000000044,5.40000000000000035,2.89999999999999991,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
INSERT INTO keyword_prices VALUES(5,'webinar',1.10000000000000008,6.90000000000000035,3.60000000000000008,'2025-12-03 05:54:50','2025-12-03 05:54:50',0,0,0.5,0,0,1,'USD',NULL);
CREATE TABLE IF NOT EXISTS `affiliate_referrals` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `referrer_id` bigint unsigned not null, `referred_user_id` bigint unsigned not null, `campaign_id` bigint unsigned, `commission` numeric not null default '0', `status` varchar(255) not null default 'pending', `converted_at` datetime, `created_at` datetime, `updated_at` datetime, foreign key(`referrer_id`) references `users`(`id`) on delete cascade, foreign key(`referred_user_id`) references `users`(`id`) on delete cascade, foreign key(`campaign_id`) references `campaigns`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `affiliate_payouts` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `affiliate_id` bigint unsigned not null, `amount` numeric not null, `status` varchar(255) not null default 'requested', `requested_at` datetime, `processed_at` datetime, `notes` text, `created_at` datetime, `updated_at` datetime, foreign key(`affiliate_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `notifications` (
`id` varchar(255) not null,
`type` varchar(255) not null,
`notifiable_type` varchar(255) not null,
`notifiable_id` bigint unsigned not null,
`resource_type` varchar(50),
`resource_id` varchar(100),
`title` varchar(255),
`message` text,
`action_url` varchar(255),
`data` json,
`read_at` datetime,
`created_at` datetime,
`updated_at` datetime,
primary key (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `utilities_calendar_events` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `user_id` bigint unsigned not null, `source` varchar(255) not null, `source_id` varchar(255) not null, `title` varchar(255) not null, `subtitle` varchar(255), `description` text, `starts_at` datetime not null, `ends_at` datetime, `location` varchar(255), `status` varchar(255) not null default 'scheduled', `metadata` text, `created_at` datetime, `updated_at` datetime, foreign key(`user_id`) references `users`(`id`) on delete cascade) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `live_streaming_engagements` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `live_streaming_id` bigint unsigned not null, `user_id` bigint unsigned, `type` varchar(255) not null, `amount` numeric not null default '0', `payload` text, `created_at` datetime, `updated_at` datetime, foreign key(`live_streaming_id`) references `live_streamings`(`streaming_id`) on delete cascade, foreign key(`user_id`) references `users`(`id`) on delete set null) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `audit_logs` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `actor_id` bigint unsigned, `target_type` varchar(255), `target_id` bigint unsigned, `action` varchar(255) not null, `changes` text, `source` varchar(255) not null default 'web', `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO sqlite_sequence VALUES('migrations',67);
INSERT INTO sqlite_sequence VALUES('users',1);
CREATE TABLE IF NOT EXISTS `keyword_registry` (`id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, `keyword` varchar(255) not null, `normalized` varchar(255) not null, `source_type` varchar(255), `source_id` bigint unsigned, `country` varchar(255), `frequency` bigint unsigned not null default '1', `last_seen_at` datetime, `created_at` datetime, `updated_at` datetime) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO keyword_registry VALUES(1,'networking','networking','ads_keyword_price',1,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(2,'jobs','jobs','ads_keyword_price',2,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(3,'freelance','freelance','ads_keyword_price',3,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(4,'podcast','podcast','ads_keyword_price',4,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
INSERT INTO keyword_registry VALUES(5,'webinar','webinar','ads_keyword_price',5,NULL,1,'2025-12-03 05:54:50','2025-12-03 05:54:50','2025-12-03 05:54:50');
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('migrations',67);
INSERT INTO sqlite_sequence VALUES('users',1);
INSERT INTO sqlite_sequence VALUES('placements',24);
INSERT INTO sqlite_sequence VALUES('keyword_prices',5);
INSERT INTO sqlite_sequence VALUES('ats_pipelines',1);
INSERT INTO sqlite_sequence VALUES('ats_stages',5);
INSERT INTO sqlite_sequence VALUES('webinars',3);
INSERT INTO sqlite_sequence VALUES('networking_sessions',2);
INSERT INTO sqlite_sequence VALUES('podcast_series',2);
INSERT INTO sqlite_sequence VALUES('interviews',2);
INSERT INTO sqlite_sequence VALUES('ai_subscription_plans',3);
INSERT INTO sqlite_sequence VALUES('keyword_registry',5);
CREATE UNIQUE INDEX `users_email_unique` on `users` (`email`);
CREATE INDEX `personal_access_tokens_tokenable_type_tokenable_id_index` on `personal_access_tokens` (`tokenable_type`, `tokenable_id`);
CREATE UNIQUE INDEX `personal_access_tokens_token_unique` on `personal_access_tokens` (`token`);
CREATE INDEX `projects_author_id_index` on `projects` (`author_id`);
CREATE INDEX `projects_project_category_index` on `projects` (`project_category`);
CREATE INDEX `projects_slug_index` on `projects` (`slug`);
CREATE INDEX `projects_project_type_index` on `projects` (`project_type`);
CREATE INDEX `projects_project_payout_type_index` on `projects` (`project_payout_type`);
CREATE INDEX `projects_project_min_price_index` on `projects` (`project_min_price`);
CREATE INDEX `projects_project_max_price_index` on `projects` (`project_max_price`);
CREATE INDEX `projects_project_duration_index` on `projects` (`project_duration`);
CREATE INDEX `projects_project_expert_level_index` on `projects` (`project_expert_level`);
CREATE INDEX `projects_project_location_index` on `projects` (`project_location`);
CREATE INDEX `projects_is_featured_index` on `projects` (`is_featured`);
CREATE INDEX `projects_status_index` on `projects` (`status`);
CREATE INDEX `projects_project_title_index` on `projects` (`project_title`);
CREATE INDEX `gig_orders_author_id_index` on `gig_orders` (`author_id`);
CREATE INDEX `gig_orders_gig_id_index` on `gig_orders` (`gig_id`);
CREATE INDEX `gig_orders_status_index` on `gig_orders` (`status`);
CREATE INDEX `project_durations_status_index` on `project_durations` (`status`);
CREATE INDEX `project_durations_name_index` on `project_durations` (`name`);
CREATE INDEX `seller_project_invites_seller_id_index` on `seller_project_invites` (`seller_id`);
CREATE INDEX `seller_project_invites_project_id_index` on `seller_project_invites` (`project_id`);
CREATE INDEX `project_locations_status_index` on `project_locations` (`status`);
CREATE INDEX `project_locations_name_index` on `project_locations` (`name`);
CREATE INDEX `escrow_disburse_methods_seller_id_index` on `escrow_disburse_methods` (`seller_id`);
CREATE INDEX `escrow_disburse_methods_project_id_index` on `escrow_disburse_methods` (`project_id`);
CREATE INDEX `escrow_disburse_methods_disburse_methods_id_index` on `escrow_disburse_methods` (`disburse_methods_id`);
CREATE INDEX `gig_order_activities_sender_id_index` on `gig_order_activities` (`sender_id`);
CREATE INDEX `gig_order_activities_receiver_id_index` on `gig_order_activities` (`receiver_id`);
CREATE INDEX `gig_order_activities_gig_id_index` on `gig_order_activities` (`gig_id`);
CREATE INDEX `gig_order_activities_order_id_index` on `gig_order_activities` (`order_id`);
CREATE INDEX `gig_order_activities_type_index` on `gig_order_activities` (`type`);
CREATE INDEX `project_activities_sender_id_index` on `project_activities` (`sender_id`);
CREATE INDEX `project_activities_receiver_id_index` on `project_activities` (`receiver_id`);
CREATE INDEX `project_activities_project_id_index` on `project_activities` (`project_id`);
CREATE INDEX `disputes_created_by_index` on `disputes` (`created_by`);
CREATE INDEX `disputes_created_to_index` on `disputes` (`created_to`);
CREATE INDEX `disputes_proposal_id_index` on `disputes` (`proposal_id`);
CREATE INDEX `disputes_gig_order_id_index` on `disputes` (`gig_order_id`);
CREATE INDEX `disputes_status_index` on `disputes` (`status`);
CREATE INDEX `dispute_conversations_sender_id_index` on `dispute_conversations` (`sender_id`);
CREATE INDEX `dispute_conversations_dispute_id_index` on `dispute_conversations` (`dispute_id`);
CREATE INDEX `dispute_conversations_message_id_index` on `dispute_conversations` (`message_id`);
CREATE INDEX `project_categories_parent_id_index` on `project_categories` (`parent_id`);
CREATE INDEX `project_categories_slug_index` on `project_categories` (`slug`);
CREATE INDEX `project_categories_status_index` on `project_categories` (`status`);
CREATE INDEX `project_categories_name_index` on `project_categories` (`name`);
CREATE INDEX `gig_categories_parent_id_index` on `gig_categories` (`parent_id`);
CREATE INDEX `gig_categories_slug_index` on `gig_categories` (`slug`);
CREATE INDEX `gig_categories_status_index` on `gig_categories` (`status`);
CREATE INDEX `gig_categories_name_index` on `gig_categories` (`name`);
CREATE INDEX `gig_category_features_category_id_index` on `gig_category_features` (`category_id`);
CREATE INDEX `gig_category_features_label_index` on `gig_category_features` (`label`);
CREATE INDEX `gigs_author_id_index` on `gigs` (`author_id`);
CREATE INDEX `gigs_slug_index` on `gigs` (`slug`);
CREATE INDEX `gigs_is_featured_index` on `gigs` (`is_featured`);
CREATE INDEX `gigs_status_index` on `gigs` (`status`);
CREATE INDEX `gigs_title_index` on `gigs` (`title`);
CREATE INDEX `gigs_country_index` on `gigs` (`country`);
CREATE INDEX `gig_plans_gig_id_index` on `gig_plans` (`gig_id`);
CREATE INDEX `gig_plans_title_index` on `gig_plans` (`title`);
CREATE INDEX `gig_addons_gig_id_index` on `gig_addons` (`gig_id`);
CREATE INDEX `gig_addons_addon_id_index` on `gig_addons` (`addon_id`);
CREATE INDEX `gig_faqs_gig_id_index` on `gig_faqs` (`gig_id`);
CREATE INDEX `gig_tags_gig_id_index` on `gig_tags` (`gig_id`);
CREATE INDEX `gig_tags_tag_name_index` on `gig_tags` (`tag_name`);
CREATE INDEX `gig_category_link_gig_id_index` on `gig_category_link` (`gig_id`);
CREATE INDEX `gig_category_link_category_id_index` on `gig_category_link` (`category_id`);
CREATE INDEX `gig_category_link_category_level_index` on `gig_category_link` (`category_level`);
CREATE INDEX `advertisers_status_index` on `advertisers` (`status`);
CREATE UNIQUE INDEX `freelance_tags_slug_unique` on `freelance_tags` (`slug`);
CREATE INDEX `headhunter_profiles_user_id_status_index` on `headhunter_profiles` (`user_id`, `status`);
CREATE INDEX `headhunter_profiles_status_index` on `headhunter_profiles` (`status`);
CREATE INDEX `headhunter_mandates_organisation_id_status_index` on `headhunter_mandates` (`organisation_id`, `status`);
CREATE INDEX `headhunter_mandates_status_index` on `headhunter_mandates` (`status`);
CREATE INDEX `headhunter_candidates_user_id_index` on `headhunter_candidates` (`user_id`);
CREATE UNIQUE INDEX `headhunter_pipeline_items_headhunter_mandate_id_headhunter_candidate_id_unique` on `headhunter_pipeline_items` (`headhunter_mandate_id`, `headhunter_candidate_id`);
CREATE INDEX `headhunter_pipeline_items_stage_index` on `headhunter_pipeline_items` (`stage`);
CREATE INDEX `headhunter_interviews_scheduled_at_index` on `headhunter_interviews` (`scheduled_at`);
CREATE INDEX `headhunter_interviews_status_index` on `headhunter_interviews` (`status`);
CREATE UNIQUE INDEX `company_profiles_slug_unique` on `company_profiles` (`slug`);
CREATE UNIQUE INDEX `pro_network_connection_caches_user_id_connection_id_unique` on `pro_network_connection_caches` (`user_id`, `connection_id`);
CREATE UNIQUE INDEX `pro_network_mutual_connections_user_id_target_user_id_unique` on `pro_network_mutual_connections` (`user_id`, `target_user_id`);
CREATE UNIQUE INDEX `pro_network_professional_profiles_public_url_unique` on `pro_network_professional_profiles` (`public_url`);
CREATE UNIQUE INDEX `pro_network_profile_skills_user_id_name_unique` on `pro_network_profile_skills` (`user_id`, `name`);
CREATE UNIQUE INDEX `pro_network_profile_interests_user_id_interest_unique` on `pro_network_profile_interests` (`user_id`, `interest`);
CREATE UNIQUE INDEX `pro_network_company_employees_company_profile_id_user_id_unique` on `pro_network_company_employees` (`company_profile_id`, `user_id`);
CREATE UNIQUE INDEX `pro_network_live_session_participants_live_session_id_user_id_unique` on `pro_network_live_session_participants` (`live_session_id`, `user_id`);
CREATE INDEX `pro_network_reactions_reactable_type_reactable_id_index` on `pro_network_reactions` (`reactable_type`, `reactable_id`);
CREATE UNIQUE INDEX `pro_network_reactions_reactable_id_reactable_type_user_id_unique` on `pro_network_reactions` (`reactable_id`, `reactable_type`, `user_id`);
CREATE INDEX `pro_network_reaction_aggregates_reactable_type_reactable_id_index` on `pro_network_reaction_aggregates` (`reactable_type`, `reactable_id`);
CREATE UNIQUE INDEX `pro_network_hashtags_tag_unique` on `pro_network_hashtags` (`tag`);
CREATE UNIQUE INDEX `pro_network_hashtags_normalized_unique` on `pro_network_hashtags` (`normalized`);
CREATE INDEX `pro_network_hashtaggables_hashtaggable_type_hashtaggable_id_index` on `pro_network_hashtaggables` (`hashtaggable_type`, `hashtaggable_id`);
CREATE UNIQUE INDEX `hashtaggable_unique` on `pro_network_hashtaggables` (`hashtag_id`, `hashtaggable_id`, `hashtaggable_type`);
CREATE UNIQUE INDEX `pro_network_story_metadata_story_id_unique` on `pro_network_story_metadata` (`story_id`);
CREATE INDEX `pro_network_moderation_queue_moderatable_type_moderatable_id_index` on `pro_network_moderation_queue` (`moderatable_type`, `moderatable_id`);
CREATE UNIQUE INDEX `pro_network_bad_words_phrase_unique` on `pro_network_bad_words` (`phrase`);
CREATE UNIQUE INDEX `analytics_metric_unique` on `pro_network_analytics_metrics` (`entity_type`, `entity_id`, `metric`);
CREATE UNIQUE INDEX `pro_network_account_types_slug_unique` on `pro_network_account_types` (`slug`);
CREATE UNIQUE INDEX `pro_network_user_account_types_user_id_account_type_id_unique` on `pro_network_user_account_types` (`user_id`, `account_type_id`);
CREATE UNIQUE INDEX `pro_network_user_feature_flags_user_id_feature_unique` on `pro_network_user_feature_flags` (`user_id`, `feature`);
CREATE UNIQUE INDEX `pro_network_newsletter_subscriptions_email_unique` on `pro_network_newsletter_subscriptions` (`email`);
CREATE UNIQUE INDEX `pro_network_post_enhancements_post_id_unique` on `pro_network_post_enhancements` (`post_id`);
CREATE INDEX `recordings_recordable_type_recordable_id_index` on `recordings` (`recordable_type`, `recordable_id`);
CREATE INDEX `campaigns_advertiser_id_start_date_index` on `campaigns` (`advertiser_id`, `start_date`);
CREATE INDEX `campaigns_advertiser_id_status_index` on `campaigns` (`advertiser_id`, `status`);
CREATE INDEX `campaigns_status_index` on `campaigns` (`status`);
CREATE INDEX `campaigns_placement_index` on `campaigns` (`placement`);
CREATE INDEX `campaigns_approval_state_index` on `campaigns` (`approval_state`);
CREATE INDEX `launchpad_programmes_creator_id_status_index` on `launchpad_programmes` (`creator_id`, `status`);
CREATE INDEX `launchpad_programmes_status_index` on `launchpad_programmes` (`status`);
CREATE INDEX `launchpad_tasks_launchpad_programme_id_order_index` on `launchpad_tasks` (`launchpad_programme_id`, `order`);
CREATE INDEX `launchpad_applications_user_id_status_index` on `launchpad_applications` (`user_id`, `status`);
CREATE INDEX `launchpad_applications_status_index` on `launchpad_applications` (`status`);
CREATE INDEX `launchpad_interviews_status_index` on `launchpad_interviews` (`status`);
CREATE INDEX `tickets_ticketable_type_ticketable_id_index` on `tickets` (`ticketable_type`, `ticketable_id`);
CREATE UNIQUE INDEX `launchpad_application_task_progress_launchpad_application_id_launchpad_task_id_unique` on `launchpad_application_task_progress` (`launchpad_application_id`, `launchpad_task_id`);
CREATE UNIQUE INDEX `networking_contact_exchanges_networking_session_id_user_id_partner_id_unique` on `networking_contact_exchanges` (`networking_session_id`, `user_id`, `partner_id`);
CREATE INDEX `networking_contact_exchanges_networking_session_id_user_id_index` on `networking_contact_exchanges` (`networking_session_id`, `user_id`);
CREATE UNIQUE INDEX `podcast_series_followers_podcast_series_id_user_id_unique` on `podcast_series_followers` (`podcast_series_id`, `user_id`);
CREATE UNIQUE INDEX `podcast_episode_entitlements_podcast_episode_id_user_id_entitlement_type_unique` on `podcast_episode_entitlements` (`podcast_episode_id`, `user_id`, `entitlement_type`);
CREATE UNIQUE INDEX `networking_pairings_participant_round_unique` on `networking_pairings` (`networking_session_id`, `participant_id`, `round`);
CREATE INDEX `networking_pairings_networking_session_id_round_index` on `networking_pairings` (`networking_session_id`, `round`);
CREATE INDEX `networking_pairings_networking_session_id_partner_id_index` on `networking_pairings` (`networking_session_id`, `partner_id`);
CREATE INDEX `ad_groups_status_index` on `ad_groups` (`status`);
CREATE INDEX `ai_sessions_user_id_tool_index` on `ai_sessions` (`user_id`, `tool`);
CREATE INDEX `ai_sessions_status_index` on `ai_sessions` (`status`);
CREATE UNIQUE INDEX `ai_byok_credentials_user_id_provider_unique` on `ai_byok_credentials` (`user_id`, `provider`);
CREATE UNIQUE INDEX `ai_subscription_plans_slug_unique` on `ai_subscription_plans` (`slug`);
CREATE UNIQUE INDEX `ai_user_subscriptions_user_id_ai_subscription_plan_id_unique` on `ai_user_subscriptions` (`user_id`, `ai_subscription_plan_id`);
CREATE INDEX `ai_user_subscriptions_status_index` on `ai_user_subscriptions` (`status`);
CREATE INDEX `ai_usage_aggregates_user_id_period_start_period_end_index` on `ai_usage_aggregates` (`user_id`, `period_start`, `period_end`);
CREATE INDEX `creatives_type_index` on `creatives` (`type`);
CREATE INDEX `creatives_status_index` on `creatives` (`status`);
CREATE INDEX `volunteering_opportunities_creator_id_status_index` on `volunteering_opportunities` (`creator_id`, `status`);
CREATE INDEX `volunteering_opportunities_organisation_id_index` on `volunteering_opportunities` (`organisation_id`);
CREATE INDEX `volunteering_opportunities_status_index` on `volunteering_opportunities` (`status`);
CREATE INDEX `volunteering_applications_user_id_status_index` on `volunteering_applications` (`user_id`, `status`);
CREATE INDEX `volunteering_applications_status_index` on `volunteering_applications` (`status`);
CREATE UNIQUE INDEX `placements_name_unique` on `placements` (`name`);
CREATE INDEX `placements_channel_index` on `placements` (`channel`);
CREATE INDEX `placements_is_active_index` on `placements` (`is_active`);
CREATE INDEX `targeting_rules_campaign_id_type_index` on `targeting_rules` (`campaign_id`, `type`);
CREATE INDEX `targeting_rules_type_index` on `targeting_rules` (`type`);
CREATE INDEX `metrics_campaign_id_recorded_at_index` on `metrics` (`campaign_id`, `recorded_at`);
CREATE INDEX `forecasts_campaign_id_index` on `forecasts` (`campaign_id`);
CREATE UNIQUE INDEX `keyword_prices_keyword_unique` on `keyword_prices` (`keyword`);
CREATE UNIQUE INDEX `affiliate_referrals_referred_user_id_campaign_id_unique` on `affiliate_referrals` (`referred_user_id`, `campaign_id`);
CREATE INDEX `affiliate_referrals_status_index` on `affiliate_referrals` (`status`);
CREATE INDEX `affiliate_referrals_converted_at_index` on `affiliate_referrals` (`converted_at`);
CREATE INDEX `affiliate_payouts_status_index` on `affiliate_payouts` (`status`);
CREATE INDEX `affiliate_payouts_requested_at_index` on `affiliate_payouts` (`requested_at`);
CREATE INDEX `affiliate_payouts_processed_at_index` on `affiliate_payouts` (`processed_at`);
CREATE INDEX `notifications_notifiable_type_notifiable_id_index` on `notifications` (`notifiable_type`, `notifiable_id`);
CREATE UNIQUE INDEX `utilities_calendar_events_user_source_unique` on `utilities_calendar_events` (`user_id`, `source`, `source_id`);
CREATE INDEX `utilities_calendar_events_source_index` on `utilities_calendar_events` (`source`);
CREATE INDEX `utilities_calendar_events_starts_at_index` on `utilities_calendar_events` (`starts_at`);
CREATE INDEX `utilities_calendar_events_status_index` on `utilities_calendar_events` (`status`);
CREATE INDEX `notifications_resource_type_index` on `notifications` (`resource_type`);
CREATE INDEX `live_streaming_engagements_live_streaming_id_type_index` on `live_streaming_engagements` (`live_streaming_id`, `type`);
CREATE INDEX `audit_logs_actor_id_index` on `audit_logs` (`actor_id`);
CREATE INDEX `job_applications_job_id_index` on `job_applications` (`job_id`);
CREATE INDEX `job_applications_candidate_id_index` on `job_applications` (`candidate_id`);
CREATE INDEX `job_applications_status_index` on `job_applications` (`status`);
CREATE UNIQUE INDEX `job_bookmarks_job_user_unique` on `job_bookmarks` (`job_id`, `user_id`);
CREATE INDEX `webinars_host_id_index` on `webinars` (`host_id`);
CREATE INDEX `webinars_starts_at_index` on `webinars` (`starts_at`);
CREATE INDEX `webinars_status_index` on `webinars` (`status`);
CREATE INDEX `networking_sessions_host_id_index` on `networking_sessions` (`host_id`);
CREATE INDEX `networking_sessions_starts_at_index` on `networking_sessions` (`starts_at`);
CREATE INDEX `podcast_series_host_id_index` on `podcast_series` (`host_id`);
CREATE INDEX `podcast_series_is_public_index` on `podcast_series` (`is_public`);
CREATE INDEX `interviews_host_id_index` on `interviews` (`host_id`);
CREATE INDEX `interviews_scheduled_at_index` on `interviews` (`scheduled_at`);
CREATE INDEX `audit_logs_target_index` on `audit_logs` (`target_type`, `target_id`);
CREATE INDEX `advertisers_user_id_status_index` on `advertisers` (`user_id`, `status`);
CREATE INDEX `ad_groups_campaign_id_status_index` on `ad_groups` (`campaign_id`, `status`);
CREATE UNIQUE INDEX `keyword_registry_normalized_source_type_source_id_unique` on `keyword_registry` (`normalized`, `source_type`, `source_id`);
CREATE INDEX `keyword_registry_normalized_index` on `keyword_registry` (`normalized`);
CREATE INDEX `keyword_registry_source_type_index` on `keyword_registry` (`source_type`);
CREATE INDEX `keyword_registry_country_index` on `keyword_registry` (`country`);
SET FOREIGN_KEY_CHECKS=1;

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
-- Gigvora master install postamble
SET FOREIGN_KEY_CHECKS=1;
