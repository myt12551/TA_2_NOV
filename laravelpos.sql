-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 08:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravelpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

CREATE TABLE `absences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `login_at` time NOT NULL,
  `logout_at` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absences`
--

INSERT INTO `absences` (`id`, `user_id`, `login_at`, `logout_at`, `created_at`, `updated_at`) VALUES
(81, 16, '14:25:00', '14:26:00', '2025-09-04 07:25:24', '2025-09-04 07:26:24'),
(82, 12, '14:26:00', '14:27:00', '2025-09-04 07:26:33', '2025-09-04 07:27:27'),
(83, 12, '04:09:00', '04:16:00', '2025-09-07 21:09:06', '2025-09-07 21:16:48'),
(85, 12, '15:36:00', '15:39:00', '2025-09-10 08:36:54', '2025-09-10 08:39:23'),
(86, 15, '15:39:00', '15:41:00', '2025-09-10 08:39:29', '2025-09-10 08:41:20'),
(87, 16, '15:45:00', '15:45:00', '2025-09-10 08:45:14', '2025-09-10 08:45:25'),
(88, 16, '16:22:00', '17:17:00', '2025-09-10 09:22:31', '2025-09-10 10:17:21'),
(89, 16, '17:17:00', '17:21:00', '2025-09-10 10:17:39', '2025-09-10 10:21:51'),
(90, 16, '17:22:00', '17:38:00', '2025-09-10 10:22:08', '2025-09-10 10:38:58'),
(91, 16, '17:39:00', NULL, '2025-09-10 10:39:06', '2025-09-10 10:39:06'),
(92, 14, '22:20:00', '22:20:00', '2025-09-10 15:20:29', '2025-09-10 15:20:48'),
(93, 16, '22:20:00', '22:25:00', '2025-09-10 15:20:55', '2025-09-10 15:25:34'),
(94, 14, '22:25:00', '22:31:00', '2025-09-10 15:25:49', '2025-09-10 15:31:26'),
(95, 16, '22:31:00', NULL, '2025-09-10 15:31:34', '2025-09-10 15:31:34'),
(96, 16, '13:00:00', '13:04:00', '2025-09-11 06:00:59', '2025-09-11 06:04:23'),
(97, 14, '13:04:00', '13:12:00', '2025-09-11 06:04:48', '2025-09-11 06:12:03'),
(98, 16, '13:12:00', '14:06:00', '2025-09-11 06:12:12', '2025-09-11 07:06:40'),
(99, 16, '14:06:00', '14:22:00', '2025-09-11 07:06:53', '2025-09-11 07:22:51'),
(100, 16, '14:26:00', '14:26:00', '2025-09-11 07:26:06', '2025-09-11 07:26:22'),
(101, 15, '14:26:00', '14:28:00', '2025-09-11 07:26:46', '2025-09-11 07:28:57'),
(102, 14, '14:29:00', NULL, '2025-09-11 07:29:04', '2025-09-11 07:29:04'),
(103, 11, '22:09:00', '22:47:00', '2025-09-16 15:09:10', '2025-09-16 15:47:42'),
(104, 15, '22:47:00', '23:10:00', '2025-09-16 15:47:49', '2025-09-16 16:10:10'),
(105, 15, '23:10:00', '23:11:00', '2025-09-16 16:10:21', '2025-09-16 16:11:39'),
(106, 16, '23:11:00', '23:12:00', '2025-09-16 16:11:45', '2025-09-16 16:12:44'),
(107, 12, '23:12:00', '23:30:00', '2025-09-16 16:12:52', '2025-09-16 16:30:51'),
(108, 13, '23:31:00', '23:31:00', '2025-09-16 16:31:07', '2025-09-16 16:31:17'),
(109, 11, '23:31:00', NULL, '2025-09-16 16:31:29', '2025-09-16 16:31:29'),
(110, 16, '18:33:00', '18:34:00', '2025-09-17 11:33:10', '2025-09-17 11:34:48'),
(111, 11, '18:34:00', '18:35:00', '2025-09-17 11:34:57', '2025-09-17 11:35:27'),
(112, 14, '18:35:00', '18:37:00', '2025-09-17 11:35:40', '2025-09-17 11:37:07'),
(113, 16, '18:37:00', '18:49:00', '2025-09-17 11:37:13', '2025-09-17 11:49:07'),
(114, 14, '18:49:00', '18:56:00', '2025-09-17 11:49:18', '2025-09-17 11:56:57'),
(115, 12, '19:15:00', '19:22:00', '2025-09-17 12:15:04', '2025-09-17 12:22:53'),
(116, 12, '19:23:00', '20:34:00', '2025-09-17 12:23:06', '2025-09-17 13:34:08'),
(117, 15, '20:34:00', '20:34:00', '2025-09-17 13:34:15', '2025-09-17 13:34:42'),
(118, 15, '20:34:00', '20:35:00', '2025-09-17 13:34:47', '2025-09-17 13:35:01'),
(119, 12, '20:35:00', '20:36:00', '2025-09-17 13:35:14', '2025-09-17 13:36:42'),
(120, 15, '20:36:00', '20:38:00', '2025-09-17 13:36:50', '2025-09-17 13:38:12'),
(121, 12, '20:38:00', '20:39:00', '2025-09-17 13:38:22', '2025-09-17 13:39:45'),
(122, 15, '20:39:00', '20:52:00', '2025-09-17 13:39:51', '2025-09-17 13:52:57'),
(123, 12, '20:53:00', '23:02:00', '2025-09-17 13:53:28', '2025-09-17 16:02:34'),
(124, 12, '23:02:00', NULL, '2025-09-17 16:02:44', '2025-09-17 16:02:44'),
(125, 16, '12:09:00', '12:10:00', '2025-09-19 05:09:19', '2025-09-19 05:10:21'),
(126, 12, '12:10:00', NULL, '2025-09-19 05:10:30', '2025-09-19 05:10:30'),
(127, 12, '14:41:00', NULL, '2025-09-19 07:41:38', '2025-09-19 07:41:38'),
(128, 15, '11:18:00', '11:20:00', '2025-10-07 04:18:46', '2025-10-07 04:20:01'),
(129, 12, '11:20:00', NULL, '2025-10-07 04:20:10', '2025-10-07 04:20:10'),
(130, 12, '20:34:00', NULL, '2025-10-07 13:34:32', '2025-10-07 13:34:32'),
(131, 12, '17:40:00', '17:47:00', '2025-10-22 10:40:51', '2025-10-22 10:47:54'),
(132, 18, '17:49:00', '17:50:00', '2025-10-22 10:49:05', '2025-10-22 10:50:00'),
(133, 12, '17:50:00', NULL, '2025-10-22 10:50:07', '2025-10-22 10:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(21, 'Makanan dan Minuman', NULL, '2025-07-22 07:13:43', '2025-07-22 07:13:43'),
(22, 'produk rumah tangga', NULL, '2025-07-22 07:15:58', '2025-07-22 07:15:58'),
(23, 'Kesehatan & Perawatan Diri', NULL, '2025-07-22 07:16:06', '2025-07-22 07:16:06'),
(24, 'Kebutuhan Bayi & Anak', NULL, '2025-07-22 07:16:15', '2025-07-22 07:16:15'),
(25, 'Alat Tulis & Perlengkapan Sekolah', NULL, '2025-07-22 07:16:24', '2025-07-22 07:16:24'),
(26, 'Peralatan Rumah Tangga', NULL, '2025-07-22 07:16:33', '2025-07-22 07:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(26, 'jihan kirana putri', '089522822332', 'jalan takat', '2025-08-01 02:12:38', '2025-08-01 02:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `gr_number` varchar(255) NOT NULL,
  `receipt_date` date NOT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_items`
--

CREATE TABLE `goods_receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity_received` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `invoice_file` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `cost_price` int(11) NOT NULL,
  `selling_price` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `code`, `category_id`, `cost_price`, `selling_price`, `stock`, `picture`, `created_at`, `updated_at`) VALUES
(307, 'Mie Goreng Aceh', 'HVDM6240', 21, 2500, 3500, 46, 'default.png', '2025-09-04 07:22:55', '2025-10-07 04:47:52'),
(308, 'Mie Goreng Rendang', 'HXVW4621', 21, 2500, 3500, 62, 'default.png', '2025-09-04 07:23:40', '2025-10-07 04:47:52');

-- --------------------------------------------------------

--
-- Table structure for table `item_supplier`
--

CREATE TABLE `item_supplier` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_orders`
--

CREATE TABLE `marketplace_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `pickup_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `total_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_orders`
--

INSERT INTO `marketplace_orders` (`id`, `user_id`, `code`, `status`, `pickup_name`, `phone`, `notes`, `total_price`, `created_at`, `updated_at`) VALUES
(6, 15, 'PO-ZUMMUDYK', 'pending_pickup', 'berlian', '0895701033483', 'ambil jam 1 siang', 21000.00, '2025-09-17 13:37:36', '2025-09-17 13:37:36'),
(7, 16, 'PO-NCAXTXRA', 'pending_pickup', 'abuya', '08957121223', 'ambil jjam 3 sore', 17500.00, '2025-09-19 05:10:09', '2025-09-19 05:10:09'),
(8, 15, 'PO-ZO43KWRS', 'pending_pickup', 'berlian', '0895701033483', 'ambil jam 10', 38500.00, '2025-10-07 04:19:44', '2025-10-07 04:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_order_items`
--

CREATE TABLE `marketplace_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_order_items`
--

INSERT INTO `marketplace_order_items` (`id`, `order_id`, `item_id`, `qty`, `price`, `created_at`, `updated_at`) VALUES
(7, 6, 308, 2, 3500.00, '2025-09-17 13:37:36', '2025-09-17 13:37:36'),
(8, 6, 307, 4, 3500.00, '2025-09-17 13:37:36', '2025-09-17 13:37:36'),
(9, 7, 307, 4, 3500.00, '2025-09-19 05:10:09', '2025-09-19 05:10:09'),
(10, 7, 308, 1, 3500.00, '2025-09-19 05:10:09', '2025-09-19 05:10:09'),
(11, 8, 308, 7, 3500.00, '2025-10-07 04:19:44', '2025-10-07 04:19:44'),
(12, 8, 307, 4, 3500.00, '2025-10-07 04:19:44', '2025-10-07 04:19:44');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_05_21_174125_create_categories_table', 1),
(5, '2024_05_21_174227_create_customers_table', 1),
(6, '2024_05_21_174511_create_payment_methods_table', 1),
(7, '2024_05_21_175122_create_item_supplier_table', 1),
(8, '2024_05_21_175123_create_wholesale_prices_table', 1),
(9, '2024_05_21_182615_create_carts_table', 1),
(10, '2024_05_22_030109_create_transactions_table', 1),
(11, '2024_05_22_030902_create_transaction_details_table', 1),
(12, '2024_05_27_072011_create_absences_table', 1),
(13, '2025_07_22_152936_create_purchase_orders_table', 2),
(14, '2025_07_22_153641_create_purchase_order_items_table', 2),
(15, '2025_07_23_105030_create_supplier_products_table', 3),
(16, '2025_07_23_142750_add_item_name_to_purchase_order_items', 4),
(17, '2025_07_23_144324_create_purchase_orders_table', 5),
(18, '2025_07_23_144352_create_purchase_order_items_table', 5),
(19, '2025_07_23_145713_create_purchase_orders_table', 6),
(20, '2025_07_23_145728_create_purchase_order_items_table', 6),
(21, '2025_07_24_091800_add_status_and_invoice_to_purchase_orders', 7),
(22, '2025_07_24_100833_add_created_by_to_purchase_orders_table', 8),
(23, '2025_09_01_000001_add_online_fields_to_transactions_table', 9),
(24, '2025_09_03_000000_add_customer_role_and_contact_to_users_table', 10),
(25, '2025_09_04_000001_create_marketplace_orders_table', 11),
(26, '2025_09_04_000002_create_marketplace_order_items_table', 11),
(27, '2025_07_23_145729_add_item_name_to_purchase_order_items', 12),
(28, '2025_10_08_010229_add_online_fields_to_transactions_table', 12),
(29, '2025_10_13_000001_create_purchase_requests_table', 12),
(30, '2025_10_13_000002_create_purchase_request_items_table', 12),
(31, '2025_10_13_000003_add_purchase_request_id_and_fields_to_purchase_orders_table', 12),
(32, '2025_10_13_000004_create_goods_receipts_table', 12),
(33, '2025_10_13_000005_create_goods_receipt_items_table', 12),
(34, '2025_10_13_000006_create_invoices_table', 12),
(35, '2025_10_23_000001_add_unit_and_current_stock_to_purchase_request_items', 13),
(36, '2025_10_23_000005_add_fields_to_purchase_order_items', 14),
(37, '2025_10_23_000007_add_required_columns_to_purchase_orders_and_items', 15);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'Tunai',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Tunai', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(2, 'Debit', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(3, 'Kredit', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(4, 'Transfer', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(5, 'OVO', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(6, 'GoPay', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(7, 'Dana', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(8, 'QRIS', NULL, '2025-01-19 06:28:45', '2025-01-19 06:28:45');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `po_date` date NOT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `status` enum('draft','validated','received') NOT NULL DEFAULT 'draft',
  `supplier_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `supplier_notes` text DEFAULT NULL,
  `invoice_image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_request_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pr_number` varchar(255) NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `pr_number`, `requested_by`, `request_date`, `status`, `description`, `created_at`, `updated_at`) VALUES
(2, 'PR-0001/10/2025', 12, '2025-10-23', 'approved', NULL, '2025-10-22 10:50:30', '2025-10-22 10:50:56'),
(3, 'PR-0002/10/2025', 12, '2025-10-23', 'approved', NULL, '2025-10-22 11:00:02', '2025-10-22 11:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_items`
--

CREATE TABLE `purchase_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_request_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_request_items`
--

INSERT INTO `purchase_request_items` (`id`, `purchase_request_id`, `product_name`, `quantity`, `unit`, `current_stock`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'Mie Goreng Aceh', 50, 'pcs', 0, NULL, '2025-10-22 10:50:30', '2025-10-22 10:50:30'),
(2, 2, 'Mie Goreng Rendang', 50, 'pcs', 0, NULL, '2025-10-22 10:50:30', '2025-10-22 10:50:30'),
(3, 2, 'Kecap ABC manis', 10, 'pcs', 0, NULL, '2025-10-22 10:50:30', '2025-10-22 10:50:30'),
(4, 3, 'Saus ABC 500ML', 100, 'pcs', 0, NULL, '2025-10-22 11:00:02', '2025-10-22 11:00:02'),
(5, 3, 'Kecap ABC 750ML', 100, 'pcs', 0, NULL, '2025-10-22 11:00:02', '2025-10-22 11:00:02');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5VGIiGrL0koxd0SvpUtnIXtL8iWEQY1p1eQ0xX2R', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidWJWck5mdkhwTzV1bmhvSjlYeTdFeTR1SHVQWGVxam1QSm1pVkluUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdHJhbnNhY3Rpb24vb25saW5lLW9yZGVycyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO3M6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fX0=', 1759838983),
('EUUeIjCKDsuPHt03J7PosCfv0N8MXRikf2d2mJ08', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYXBvcmpNdFp1dHFiU2xNN2ExZWxjZzFaZzF4aUxSaW9aTlJwVEtnSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHVyY2hhc2UtcmVxdWVzdHMvY3JlYXRlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', 1761157825),
('NzYRLJdshczmmwu5kC4cdUz6V9GxTIGguif8khCU', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVTF1TE11enFBQWM4ekVzaWtqMHJyOENNeDNYUFdGanBNaXlOd0lISCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHVyY2hhc2Utb3JkZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', 1759870098),
('TM3rvtLh4qiR4gelZUenu09aTE1jma9yJ439OMWx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVR0RUVtZlhTbERDeDc2Y0VoS1VKRTZQR3JGVDdTYUEyZDh6YUNnRCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1759835913);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `phone`, `address`, `email`, `description`, `created_at`, `updated_at`) VALUES
(29, 'PT. INDOFOOD MACANEGARA', '082823232323', 'JAKABARING', 'INDOFOOD@GMAIL.COM', 'pesan akan di antar', '2025-09-04 07:20:09', '2025-09-04 07:20:09'),
(30, 'PT. LAMACAWW', '0123828833', 'JAKARTA', 'MACAWW@GMAIL.COM', 'pesanan akan di antar', '2025-09-04 07:21:35', '2025-09-04 07:21:35'),
(31, 'PT. INDOMARGA SEJAJHTERA', '123123123', 'jalan antasassri', 'maragafood@gmail.com', 'barang di pesan secara langsung dan diantar oleh pihak supplier', '2025-10-07 13:40:20', '2025-10-07 13:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_products`
--

CREATE TABLE `supplier_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_products`
--

INSERT INTO `supplier_products` (`id`, `supplier_id`, `product_name`, `created_at`, `updated_at`) VALUES
(12, 29, 'Mie Goreng Aceh', '2025-09-04 07:20:09', '2025-09-04 07:20:09'),
(13, 29, 'Mie Goreng Rendang', '2025-09-04 07:20:09', '2025-09-04 07:20:09'),
(14, 29, 'Kecap ABC manis', '2025-09-04 07:20:09', '2025-09-04 07:20:09'),
(15, 30, 'Mineral Akuzu', '2025-09-04 07:21:35', '2025-09-04 07:21:35'),
(16, 30, 'Tisu Medic', '2025-09-04 07:21:35', '2025-09-04 07:21:35'),
(17, 31, 'Saus ABC 500ML', '2025-10-07 13:40:20', '2025-10-07 13:40:20'),
(18, 31, 'Kecap ABC 750ML', '2025-10-07 13:40:20', '2025-10-07 13:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `channel` enum('pos','online') NOT NULL DEFAULT 'pos',
  `payment_status` enum('unpaid','paid','canceled') NOT NULL DEFAULT 'unpaid',
  `pickup_status` enum('waiting','ready','picked_up','canceled') NOT NULL DEFAULT 'waiting',
  `pickup_code` varchar(20) DEFAULT NULL,
  `pickup_deadline` datetime DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice` varchar(255) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `change` int(11) NOT NULL DEFAULT 0,
  `status` enum('paid','debt') NOT NULL DEFAULT 'paid',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `channel`, `payment_status`, `pickup_status`, `pickup_code`, `pickup_deadline`, `customer_id`, `invoice`, `invoice_no`, `total`, `discount`, `payment_method_id`, `amount`, `change`, `status`, `note`, `created_at`, `updated_at`) VALUES
(7, 12, 'pos', 'unpaid', 'waiting', NULL, NULL, NULL, '1709250001', '1', 31500, 0, 1, 31500, 0, 'paid', '(diproses: syafiq Muhammad Alif, 17/09/2025 21:07)', '2025-09-17 14:07:03', '2025-09-17 14:07:03'),
(8, 12, 'pos', 'unpaid', 'waiting', NULL, NULL, NULL, '1909250001', '1', 3500, 0, 1, 3500, 0, 'paid', '(diproses: syafiq Muhammad Alif, 19/09/2025 12:22)', '2025-09-19 05:22:55', '2025-09-19 05:22:55'),
(9, 12, 'pos', 'unpaid', 'waiting', NULL, NULL, NULL, '0710250001', '1', 24500, 0, 1, 24500, 0, 'paid', '(diproses: syafiq Muhammad Alif, 07/10/2025 11:47)', '2025-10-07 04:47:52', '2025-10-07 04:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `item_price` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `item_id`, `qty`, `item_price`, `total`, `created_at`, `updated_at`) VALUES
(12, 7, 307, 5, 3500, 17500, '2025-09-17 14:07:03', '2025-09-17 14:07:03'),
(13, 7, 308, 4, 3500, 14000, '2025-09-17 14:07:03', '2025-09-17 14:07:03'),
(14, 8, 308, 1, 3500, 3500, '2025-09-19 05:22:55', '2025-09-19 05:22:55'),
(15, 9, 307, 2, 3500, 7000, '2025-10-07 04:47:52', '2025-10-07 04:47:52'),
(16, 9, 308, 5, 3500, 17500, '2025-10-07 04:47:53', '2025-10-07 04:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'cashier',
  `position` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `picture` varchar(2048) NOT NULL DEFAULT 'profile.jpg',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `address`, `role`, `position`, `password`, `picture`, `remember_token`, `created_at`, `updated_at`) VALUES
(11, 'Admin', 'admin', 'dbauch@example.org', '(440) 392-0370', NULL, 'owner', 'Occupational Health Safety Specialist', '$2y$12$H3VttTnGR7c82snMTjSCbeQLVISM8H5TesiMJ8/MV9wtTiUqfpNL2', 'profile.jpg', 'on0ThdwJwc6zZyI3IqWOVjNWnG0PaAnMI4mWSHKTDCzcdjuQFjiZ7qGIbQFx', '2025-01-19 06:28:45', '2025-01-19 06:28:45'),
(12, 'syafiq Muhammad Alif', 'syafiq', 'syafiq12@gmail.com', '08924224242', NULL, 'supervisor', 'Manager Oprasional', '$2y$12$dpibl0hTUZTgaL6FUvPu/.mdD/xJCYOUuWrmEDOFjBhA8B1BYCUam', 'profile.jpg', NULL, '2025-07-22 07:24:38', '2025-07-22 07:24:38'),
(13, 'jihan Kirana', 'jihan', 'jihan12@gmail.com', '082442424444', NULL, 'admin', 'Admin Gudang', '$2y$12$xtA1TWfEeiI5iQapHproHe6gzCbx9kjlt8bZez.ePVf7Xza3dwIg2', 'profile.jpg', NULL, '2025-07-22 07:25:20', '2025-07-22 07:25:20'),
(14, 'leonando prastiko', 'leonando', 'leonando12@gmail.com', '089242121232', NULL, 'cashier', 'Kasir', '$2y$12$RhUlTXi2L0yN5xbPyUR7eOlUSxg83HVXKGc5r0M1Ooz.42JK4rkPO', 'profile.jpg', NULL, '2025-07-22 07:25:57', '2025-07-22 07:25:57'),
(15, 'berlian', 'berlian', 'berlian@gmail.com', '012372310972', 'berlianberlianberlian', 'customer', NULL, '$2y$12$Arirw85DqdAziXATFFFBTeNc8V6SfjsSMVtGul6s48J6uO1CTmnz6', 'profile.jpg', NULL, '2025-09-04 02:07:55', '2025-09-04 02:07:55'),
(16, 'abuya', 'abuya', 'abuya@gmail.com', '081729337222', 'abuyaabuyaabuya', 'customer', NULL, '$2y$12$At.MJVw/TtfNxrR6h8j/Sutw4D2Z1bJxKeRfuUrYubIizRV4Osk9G', 'profile.jpg', NULL, '2025-09-04 02:22:26', '2025-09-04 02:22:26'),
(18, 'farhan', 'farhan', 'farhan@gmail.com', '023149712397', 'pakjo', 'customer', NULL, '$2y$12$O6i5IkH2EO7D9Qz9ScF9YuT1pyNLkD7zBSGowjDqfLlmuZYFd463G', 'profile.jpg', NULL, '2025-10-22 10:48:37', '2025-10-22 10:48:37');

-- --------------------------------------------------------

--
-- Table structure for table `wholesale_prices`
--

CREATE TABLE `wholesale_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `min_qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_item_id_foreign` (`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_receipts_gr_number_unique` (`gr_number`),
  ADD KEY `goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_receipts_received_by_foreign` (`received_by`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_items_goods_receipt_id_foreign` (`goods_receipt_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD KEY `items_category_id_foreign` (`category_id`);

--
-- Indexes for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_supplier_item_id_foreign` (`item_id`),
  ADD KEY `item_supplier_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marketplace_orders_code_unique` (`code`),
  ADD KEY `marketplace_orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marketplace_order_items_order_id_foreign` (`order_id`),
  ADD KEY `marketplace_order_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_purchase_request_id_foreign` (`purchase_request_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_requests_pr_number_unique` (`pr_number`),
  ADD KEY `purchase_requests_requested_by_foreign` (`requested_by`);

--
-- Indexes for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_request_items_purchase_request_id_foreign` (`purchase_request_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_products_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_pickup_code_unique` (`pickup_code`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_customer_id_foreign` (`customer_id`),
  ADD KEY `transactions_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_details_item_id_foreign` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wholesale_prices_item_id_foreign` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `item_supplier`
--
ALTER TABLE `item_supplier`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `supplier_products`
--
ALTER TABLE `supplier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_supplier`
--
ALTER TABLE `item_supplier`
  ADD CONSTRAINT `item_supplier_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_supplier_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_orders`
--
ALTER TABLE `marketplace_orders`
  ADD CONSTRAINT `marketplace_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `marketplace_order_items`
--
ALTER TABLE `marketplace_order_items`
  ADD CONSTRAINT `marketplace_order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `marketplace_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `marketplace_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_products`
--
ALTER TABLE `supplier_products`
  ADD CONSTRAINT `supplier_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wholesale_prices`
--
ALTER TABLE `wholesale_prices`
  ADD CONSTRAINT `wholesale_prices_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
