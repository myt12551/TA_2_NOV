-- Database backup created on 2025-10-27 19:23:04



CREATE TABLE `absences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `login_at` time NOT NULL,
  `logout_at` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `absences_user_id_foreign` (`user_id`),
  CONSTRAINT `absences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `absences` VALUES ('81', '16', '14:25:00', '14:26:00', '2025-09-04 14:25:24', '2025-09-04 14:26:24');
INSERT INTO `absences` VALUES ('82', '12', '14:26:00', '14:27:00', '2025-09-04 14:26:33', '2025-09-04 14:27:27');
INSERT INTO `absences` VALUES ('83', '12', '04:09:00', '04:16:00', '2025-09-08 04:09:06', '2025-09-08 04:16:48');
INSERT INTO `absences` VALUES ('85', '12', '15:36:00', '15:39:00', '2025-09-10 15:36:54', '2025-09-10 15:39:23');
INSERT INTO `absences` VALUES ('86', '15', '15:39:00', '15:41:00', '2025-09-10 15:39:29', '2025-09-10 15:41:20');
INSERT INTO `absences` VALUES ('87', '16', '15:45:00', '15:45:00', '2025-09-10 15:45:14', '2025-09-10 15:45:25');
INSERT INTO `absences` VALUES ('88', '16', '16:22:00', '17:17:00', '2025-09-10 16:22:31', '2025-09-10 17:17:21');
INSERT INTO `absences` VALUES ('89', '16', '17:17:00', '17:21:00', '2025-09-10 17:17:39', '2025-09-10 17:21:51');
INSERT INTO `absences` VALUES ('90', '16', '17:22:00', '17:38:00', '2025-09-10 17:22:08', '2025-09-10 17:38:58');
INSERT INTO `absences` VALUES ('91', '16', '17:39:00', NULL, '2025-09-10 17:39:06', '2025-09-10 17:39:06');
INSERT INTO `absences` VALUES ('92', '14', '22:20:00', '22:20:00', '2025-09-10 22:20:29', '2025-09-10 22:20:48');
INSERT INTO `absences` VALUES ('93', '16', '22:20:00', '22:25:00', '2025-09-10 22:20:55', '2025-09-10 22:25:34');
INSERT INTO `absences` VALUES ('94', '14', '22:25:00', '22:31:00', '2025-09-10 22:25:49', '2025-09-10 22:31:26');
INSERT INTO `absences` VALUES ('95', '16', '22:31:00', NULL, '2025-09-10 22:31:34', '2025-09-10 22:31:34');
INSERT INTO `absences` VALUES ('96', '16', '13:00:00', '13:04:00', '2025-09-11 13:00:59', '2025-09-11 13:04:23');
INSERT INTO `absences` VALUES ('97', '14', '13:04:00', '13:12:00', '2025-09-11 13:04:48', '2025-09-11 13:12:03');
INSERT INTO `absences` VALUES ('98', '16', '13:12:00', '14:06:00', '2025-09-11 13:12:12', '2025-09-11 14:06:40');
INSERT INTO `absences` VALUES ('99', '16', '14:06:00', '14:22:00', '2025-09-11 14:06:53', '2025-09-11 14:22:51');
INSERT INTO `absences` VALUES ('100', '16', '14:26:00', '14:26:00', '2025-09-11 14:26:06', '2025-09-11 14:26:22');
INSERT INTO `absences` VALUES ('101', '15', '14:26:00', '14:28:00', '2025-09-11 14:26:46', '2025-09-11 14:28:57');
INSERT INTO `absences` VALUES ('102', '14', '14:29:00', NULL, '2025-09-11 14:29:04', '2025-09-11 14:29:04');
INSERT INTO `absences` VALUES ('103', '11', '22:09:00', '22:47:00', '2025-09-16 22:09:10', '2025-09-16 22:47:42');
INSERT INTO `absences` VALUES ('104', '15', '22:47:00', '23:10:00', '2025-09-16 22:47:49', '2025-09-16 23:10:10');
INSERT INTO `absences` VALUES ('105', '15', '23:10:00', '23:11:00', '2025-09-16 23:10:21', '2025-09-16 23:11:39');
INSERT INTO `absences` VALUES ('106', '16', '23:11:00', '23:12:00', '2025-09-16 23:11:45', '2025-09-16 23:12:44');
INSERT INTO `absences` VALUES ('107', '12', '23:12:00', '23:30:00', '2025-09-16 23:12:52', '2025-09-16 23:30:51');
INSERT INTO `absences` VALUES ('108', '13', '23:31:00', '23:31:00', '2025-09-16 23:31:07', '2025-09-16 23:31:17');
INSERT INTO `absences` VALUES ('109', '11', '23:31:00', NULL, '2025-09-16 23:31:29', '2025-09-16 23:31:29');
INSERT INTO `absences` VALUES ('110', '16', '18:33:00', '18:34:00', '2025-09-17 18:33:10', '2025-09-17 18:34:48');
INSERT INTO `absences` VALUES ('111', '11', '18:34:00', '18:35:00', '2025-09-17 18:34:57', '2025-09-17 18:35:27');
INSERT INTO `absences` VALUES ('112', '14', '18:35:00', '18:37:00', '2025-09-17 18:35:40', '2025-09-17 18:37:07');
INSERT INTO `absences` VALUES ('113', '16', '18:37:00', '18:49:00', '2025-09-17 18:37:13', '2025-09-17 18:49:07');
INSERT INTO `absences` VALUES ('114', '14', '18:49:00', '18:56:00', '2025-09-17 18:49:18', '2025-09-17 18:56:57');
INSERT INTO `absences` VALUES ('115', '12', '19:15:00', '19:22:00', '2025-09-17 19:15:04', '2025-09-17 19:22:53');
INSERT INTO `absences` VALUES ('116', '12', '19:23:00', '20:34:00', '2025-09-17 19:23:06', '2025-09-17 20:34:08');
INSERT INTO `absences` VALUES ('117', '15', '20:34:00', '20:34:00', '2025-09-17 20:34:15', '2025-09-17 20:34:42');
INSERT INTO `absences` VALUES ('118', '15', '20:34:00', '20:35:00', '2025-09-17 20:34:47', '2025-09-17 20:35:01');
INSERT INTO `absences` VALUES ('119', '12', '20:35:00', '20:36:00', '2025-09-17 20:35:14', '2025-09-17 20:36:42');
INSERT INTO `absences` VALUES ('120', '15', '20:36:00', '20:38:00', '2025-09-17 20:36:50', '2025-09-17 20:38:12');
INSERT INTO `absences` VALUES ('121', '12', '20:38:00', '20:39:00', '2025-09-17 20:38:22', '2025-09-17 20:39:45');
INSERT INTO `absences` VALUES ('122', '15', '20:39:00', '20:52:00', '2025-09-17 20:39:51', '2025-09-17 20:52:57');
INSERT INTO `absences` VALUES ('123', '12', '20:53:00', '23:02:00', '2025-09-17 20:53:28', '2025-09-17 23:02:34');
INSERT INTO `absences` VALUES ('124', '12', '23:02:00', NULL, '2025-09-17 23:02:44', '2025-09-17 23:02:44');
INSERT INTO `absences` VALUES ('125', '16', '12:09:00', '12:10:00', '2025-09-19 12:09:19', '2025-09-19 12:10:21');
INSERT INTO `absences` VALUES ('126', '12', '12:10:00', NULL, '2025-09-19 12:10:30', '2025-09-19 12:10:30');
INSERT INTO `absences` VALUES ('127', '12', '14:41:00', NULL, '2025-09-19 14:41:38', '2025-09-19 14:41:38');
INSERT INTO `absences` VALUES ('128', '15', '11:18:00', '11:20:00', '2025-10-07 11:18:46', '2025-10-07 11:20:01');
INSERT INTO `absences` VALUES ('129', '12', '11:20:00', NULL, '2025-10-07 11:20:10', '2025-10-07 11:20:10');
INSERT INTO `absences` VALUES ('130', '12', '20:34:00', NULL, '2025-10-07 20:34:32', '2025-10-07 20:34:32');
INSERT INTO `absences` VALUES ('131', '12', '17:40:00', '17:47:00', '2025-10-22 17:40:51', '2025-10-22 17:47:54');
INSERT INTO `absences` VALUES ('132', '18', '17:49:00', '17:50:00', '2025-10-22 17:49:05', '2025-10-22 17:50:00');
INSERT INTO `absences` VALUES ('133', '12', '17:50:00', NULL, '2025-10-22 17:50:07', '2025-10-22 17:50:07');
INSERT INTO `absences` VALUES ('134', '12', '18:21:00', NULL, '2025-10-27 18:21:52', '2025-10-27 18:21:52');


CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_item_id_foreign` (`item_id`),
  CONSTRAINT `carts_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES ('21', 'Makanan dan Minuman', NULL, '2025-07-22 14:13:43', '2025-07-22 14:13:43');
INSERT INTO `categories` VALUES ('22', 'produk rumah tangga', NULL, '2025-07-22 14:15:58', '2025-07-22 14:15:58');
INSERT INTO `categories` VALUES ('23', 'Kesehatan & Perawatan Diri', NULL, '2025-07-22 14:16:06', '2025-07-22 14:16:06');
INSERT INTO `categories` VALUES ('24', 'Kebutuhan Bayi & Anak', NULL, '2025-07-22 14:16:15', '2025-07-22 14:16:15');
INSERT INTO `categories` VALUES ('25', 'Alat Tulis & Perlengkapan Sekolah', NULL, '2025-07-22 14:16:24', '2025-07-22 14:16:24');
INSERT INTO `categories` VALUES ('26', 'Peralatan Rumah Tangga', NULL, '2025-07-22 14:16:33', '2025-07-22 14:16:33');


CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `customers` VALUES ('26', 'jihan kirana putri', '089522822332', 'jalan takat', '2025-08-01 09:12:38', '2025-08-01 09:12:38');


CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `goods_receipt_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `goods_receipt_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity_received` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `goods_receipt_items_goods_receipt_id_foreign` (`goods_receipt_id`),
  CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `goods_receipts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned NOT NULL,
  `gr_number` varchar(255) NOT NULL,
  `receipt_date` date NOT NULL,
  `received_by` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_receipts_gr_number_unique` (`gr_number`),
  KEY `goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `goods_receipts_received_by_foreign` (`received_by`),
  CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `invoice_file` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_purchase_order_id_foreign` (`purchase_order_id`),
  CONSTRAINT `invoices_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `item_supplier` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_supplier_item_id_foreign` (`item_id`),
  KEY `item_supplier_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `item_supplier_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_supplier_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `cost_price` int(11) NOT NULL,
  `selling_price` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `items_code_unique` (`code`),
  KEY `items_category_id_foreign` (`category_id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `items` VALUES ('307', 'Mie Goreng Aceh', 'HVDM6240', '21', '2500', '3500', '46', 'default.png', '2025-09-04 14:22:55', '2025-10-07 11:47:52');
INSERT INTO `items` VALUES ('308', 'Mie Goreng Rendang', 'HXVW4621', '21', '2500', '3500', '62', 'default.png', '2025-09-04 14:23:40', '2025-10-07 11:47:52');


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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `marketplace_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `qty` int(10) unsigned NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketplace_order_items_order_id_foreign` (`order_id`),
  KEY `marketplace_order_items_item_id_foreign` (`item_id`),
  CONSTRAINT `marketplace_order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `marketplace_order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `marketplace_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `marketplace_order_items` VALUES ('7', '6', '308', '2', '3500.00', '2025-09-17 20:37:36', '2025-09-17 20:37:36');
INSERT INTO `marketplace_order_items` VALUES ('8', '6', '307', '4', '3500.00', '2025-09-17 20:37:36', '2025-09-17 20:37:36');
INSERT INTO `marketplace_order_items` VALUES ('9', '7', '307', '4', '3500.00', '2025-09-19 12:10:09', '2025-09-19 12:10:09');
INSERT INTO `marketplace_order_items` VALUES ('10', '7', '308', '1', '3500.00', '2025-09-19 12:10:09', '2025-09-19 12:10:09');
INSERT INTO `marketplace_order_items` VALUES ('11', '8', '308', '7', '3500.00', '2025-10-07 11:19:44', '2025-10-07 11:19:44');
INSERT INTO `marketplace_order_items` VALUES ('12', '8', '307', '4', '3500.00', '2025-10-07 11:19:44', '2025-10-07 11:19:44');


CREATE TABLE `marketplace_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `code` varchar(32) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `pickup_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `total_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marketplace_orders_code_unique` (`code`),
  KEY `marketplace_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `marketplace_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `marketplace_orders` VALUES ('6', '15', 'PO-ZUMMUDYK', 'pending_pickup', 'berlian', '0895701033483', 'ambil jam 1 siang', '21000.00', '2025-09-17 20:37:36', '2025-09-17 20:37:36');
INSERT INTO `marketplace_orders` VALUES ('7', '16', 'PO-NCAXTXRA', 'pending_pickup', 'abuya', '08957121223', 'ambil jjam 3 sore', '17500.00', '2025-09-19 12:10:09', '2025-09-19 12:10:09');
INSERT INTO `marketplace_orders` VALUES ('8', '15', 'PO-ZO43KWRS', 'pending_pickup', 'berlian', '0895701033483', 'ambil jam 10', '38500.00', '2025-10-07 11:19:44', '2025-10-07 11:19:44');


CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` VALUES ('4', '2024_05_21_174125_create_categories_table', '1');
INSERT INTO `migrations` VALUES ('5', '2024_05_21_174227_create_customers_table', '1');
INSERT INTO `migrations` VALUES ('6', '2024_05_21_174511_create_payment_methods_table', '1');
INSERT INTO `migrations` VALUES ('7', '2024_05_21_175122_create_item_supplier_table', '1');
INSERT INTO `migrations` VALUES ('8', '2024_05_21_175123_create_wholesale_prices_table', '1');
INSERT INTO `migrations` VALUES ('9', '2024_05_21_182615_create_carts_table', '1');
INSERT INTO `migrations` VALUES ('10', '2024_05_22_030109_create_transactions_table', '1');
INSERT INTO `migrations` VALUES ('11', '2024_05_22_030902_create_transaction_details_table', '1');
INSERT INTO `migrations` VALUES ('12', '2024_05_27_072011_create_absences_table', '1');
INSERT INTO `migrations` VALUES ('13', '2025_07_22_152936_create_purchase_orders_table', '2');
INSERT INTO `migrations` VALUES ('14', '2025_07_22_153641_create_purchase_order_items_table', '2');
INSERT INTO `migrations` VALUES ('15', '2025_07_23_105030_create_supplier_products_table', '3');
INSERT INTO `migrations` VALUES ('16', '2025_07_23_142750_add_item_name_to_purchase_order_items', '4');
INSERT INTO `migrations` VALUES ('17', '2025_07_23_144324_create_purchase_orders_table', '5');
INSERT INTO `migrations` VALUES ('18', '2025_07_23_144352_create_purchase_order_items_table', '5');
INSERT INTO `migrations` VALUES ('19', '2025_07_23_145713_create_purchase_orders_table', '6');
INSERT INTO `migrations` VALUES ('20', '2025_07_23_145728_create_purchase_order_items_table', '6');
INSERT INTO `migrations` VALUES ('21', '2025_07_24_091800_add_status_and_invoice_to_purchase_orders', '7');
INSERT INTO `migrations` VALUES ('22', '2025_07_24_100833_add_created_by_to_purchase_orders_table', '8');
INSERT INTO `migrations` VALUES ('23', '2025_09_01_000001_add_online_fields_to_transactions_table', '9');
INSERT INTO `migrations` VALUES ('24', '2025_09_03_000000_add_customer_role_and_contact_to_users_table', '10');
INSERT INTO `migrations` VALUES ('25', '2025_09_04_000001_create_marketplace_orders_table', '11');
INSERT INTO `migrations` VALUES ('26', '2025_09_04_000002_create_marketplace_order_items_table', '11');
INSERT INTO `migrations` VALUES ('27', '2025_07_23_145729_add_item_name_to_purchase_order_items', '12');
INSERT INTO `migrations` VALUES ('28', '2025_10_08_010229_add_online_fields_to_transactions_table', '12');
INSERT INTO `migrations` VALUES ('29', '2025_10_13_000001_create_purchase_requests_table', '12');
INSERT INTO `migrations` VALUES ('30', '2025_10_13_000002_create_purchase_request_items_table', '12');
INSERT INTO `migrations` VALUES ('31', '2025_10_13_000003_add_purchase_request_id_and_fields_to_purchase_orders_table', '12');
INSERT INTO `migrations` VALUES ('32', '2025_10_13_000004_create_goods_receipts_table', '12');
INSERT INTO `migrations` VALUES ('33', '2025_10_13_000005_create_goods_receipt_items_table', '12');
INSERT INTO `migrations` VALUES ('34', '2025_10_13_000006_create_invoices_table', '12');
INSERT INTO `migrations` VALUES ('35', '2025_10_23_000001_add_unit_and_current_stock_to_purchase_request_items', '13');
INSERT INTO `migrations` VALUES ('36', '2025_10_23_000005_add_fields_to_purchase_order_items', '14');
INSERT INTO `migrations` VALUES ('37', '2025_10_23_000007_add_required_columns_to_purchase_orders_and_items', '15');


CREATE TABLE `payment_methods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT 'Tunai',
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `payment_methods` VALUES ('1', 'Tunai', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('2', 'Debit', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('3', 'Kredit', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('4', 'Transfer', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('5', 'OVO', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('6', 'GoPay', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('7', 'Dana', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `payment_methods` VALUES ('8', 'QRIS', NULL, '2025-01-19 13:28:45', '2025-01-19 13:28:45');


CREATE TABLE `purchase_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `po_date` date NOT NULL,
  `total_amount` decimal(12,2) DEFAULT NULL,
  `status` enum('draft','validated','received') NOT NULL DEFAULT 'draft',
  `supplier_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `supplier_notes` text DEFAULT NULL,
  `invoice_image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_request_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_purchase_request_id_foreign` (`purchase_request_id`),
  CONSTRAINT `purchase_orders_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



CREATE TABLE `purchase_request_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_request_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_request_items_purchase_request_id_foreign` (`purchase_request_id`),
  CONSTRAINT `purchase_request_items_purchase_request_id_foreign` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_request_items` VALUES ('1', '2', 'Mie Goreng Aceh', '50', 'pcs', '0', NULL, '2025-10-22 17:50:30', '2025-10-22 17:50:30');
INSERT INTO `purchase_request_items` VALUES ('2', '2', 'Mie Goreng Rendang', '50', 'pcs', '0', NULL, '2025-10-22 17:50:30', '2025-10-22 17:50:30');
INSERT INTO `purchase_request_items` VALUES ('3', '2', 'Kecap ABC manis', '10', 'pcs', '0', NULL, '2025-10-22 17:50:30', '2025-10-22 17:50:30');
INSERT INTO `purchase_request_items` VALUES ('4', '3', 'Saus ABC 500ML', '100', 'pcs', '0', NULL, '2025-10-22 18:00:02', '2025-10-22 18:00:02');
INSERT INTO `purchase_request_items` VALUES ('5', '3', 'Kecap ABC 750ML', '100', 'pcs', '0', NULL, '2025-10-22 18:00:02', '2025-10-22 18:00:02');


CREATE TABLE `purchase_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pr_number` varchar(255) NOT NULL,
  `requested_by` bigint(20) unsigned NOT NULL,
  `request_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_requests_pr_number_unique` (`pr_number`),
  KEY `purchase_requests_requested_by_foreign` (`requested_by`),
  CONSTRAINT `purchase_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `purchase_requests` VALUES ('2', 'PR-0001/10/2025', '12', '2025-10-23', 'approved', NULL, '2025-10-22 17:50:30', '2025-10-22 17:50:56');
INSERT INTO `purchase_requests` VALUES ('3', 'PR-0002/10/2025', '12', '2025-10-23', 'approved', NULL, '2025-10-22 18:00:02', '2025-10-22 18:00:06');


CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` VALUES ('5VGIiGrL0koxd0SvpUtnIXtL8iWEQY1p1eQ0xX2R', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoidWJWck5mdkhwTzV1bmhvSjlYeTdFeTR1SHVQWGVxam1QSm1pVkluUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdHJhbnNhY3Rpb24vb25saW5lLW9yZGVycyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO3M6MjI6IlBIUERFQlVHQkFSX1NUQUNLX0RBVEEiO2E6MDp7fX0=', '1759838983');
INSERT INTO `sessions` VALUES ('EUUeIjCKDsuPHt03J7PosCfv0N8MXRikf2d2mJ08', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYXBvcmpNdFp1dHFiU2xNN2ExZWxjZzFaZzF4aUxSaW9aTlJwVEtnSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHVyY2hhc2UtcmVxdWVzdHMvY3JlYXRlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', '1761157825');
INSERT INTO `sessions` VALUES ('NzYRLJdshczmmwu5kC4cdUz6V9GxTIGguif8khCU', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVTF1TE11enFBQWM4ekVzaWtqMHJyOENNeDNYUFdGanBNaXlOd0lISCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvcHVyY2hhc2Utb3JkZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', '1759870098');
INSERT INTO `sessions` VALUES ('PRUuTe35G4UiF1KVfZls33cETuUbtxCPPSEuV4m1', '12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoia1dmOXFweHBLZVVFYXc0TDlLNjM5cHdRUUF1NjBLS0czYjlKaUl5SCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvaW52ZW50b3J5L2NhdGVnb3J5Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', '1761589334');
INSERT INTO `sessions` VALUES ('TM3rvtLh4qiR4gelZUenu09aTE1jma9yJ439OMWx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibVR0RUVtZlhTbERDeDc2Y0VoS1VKRTZQR3JGVDdTYUEyZDh6YUNnRCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', '1759835913');


CREATE TABLE `supplier_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_products_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `supplier_products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `supplier_products` VALUES ('12', '29', 'Mie Goreng Aceh', '2025-09-04 14:20:09', '2025-09-04 14:20:09');
INSERT INTO `supplier_products` VALUES ('13', '29', 'Mie Goreng Rendang', '2025-09-04 14:20:09', '2025-09-04 14:20:09');
INSERT INTO `supplier_products` VALUES ('14', '29', 'Kecap ABC manis', '2025-09-04 14:20:09', '2025-09-04 14:20:09');
INSERT INTO `supplier_products` VALUES ('15', '30', 'Mineral Akuzu', '2025-09-04 14:21:35', '2025-09-04 14:21:35');
INSERT INTO `supplier_products` VALUES ('16', '30', 'Tisu Medic', '2025-09-04 14:21:35', '2025-09-04 14:21:35');
INSERT INTO `supplier_products` VALUES ('17', '31', 'Saus ABC 500ML', '2025-10-07 20:40:20', '2025-10-07 20:40:20');
INSERT INTO `supplier_products` VALUES ('18', '31', 'Kecap ABC 750ML', '2025-10-07 20:40:20', '2025-10-07 20:40:20');


CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `suppliers` VALUES ('29', 'PT. INDOFOOD MACANEGARA', '082823232323', 'JAKABARING', 'INDOFOOD@GMAIL.COM', 'pesan akan di antar', '2025-09-04 14:20:09', '2025-09-04 14:20:09');
INSERT INTO `suppliers` VALUES ('30', 'PT. LAMACAWW', '0123828833', 'JAKARTA', 'MACAWW@GMAIL.COM', 'pesanan akan di antar', '2025-09-04 14:21:35', '2025-09-04 14:21:35');
INSERT INTO `suppliers` VALUES ('31', 'PT. INDOMARGA SEJAJHTERA', '123123123', 'jalan antasassri', 'maragafood@gmail.com', 'barang di pesan secara langsung dan diantar oleh pihak supplier', '2025-10-07 20:40:20', '2025-10-07 20:40:20');


CREATE TABLE `transaction_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `item_price` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_transaction_id_foreign` (`transaction_id`),
  KEY `transaction_details_item_id_foreign` (`item_id`),
  CONSTRAINT `transaction_details_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transaction_details` VALUES ('12', '7', '307', '5', '3500', '17500', '2025-09-17 21:07:03', '2025-09-17 21:07:03');
INSERT INTO `transaction_details` VALUES ('13', '7', '308', '4', '3500', '14000', '2025-09-17 21:07:03', '2025-09-17 21:07:03');
INSERT INTO `transaction_details` VALUES ('14', '8', '308', '1', '3500', '3500', '2025-09-19 12:22:55', '2025-09-19 12:22:55');
INSERT INTO `transaction_details` VALUES ('15', '9', '307', '2', '3500', '7000', '2025-10-07 11:47:52', '2025-10-07 11:47:52');
INSERT INTO `transaction_details` VALUES ('16', '9', '308', '5', '3500', '17500', '2025-10-07 11:47:53', '2025-10-07 11:47:53');


CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `channel` enum('pos','online') NOT NULL DEFAULT 'pos',
  `payment_status` enum('unpaid','paid','canceled') NOT NULL DEFAULT 'unpaid',
  `pickup_status` enum('waiting','ready','picked_up','canceled') NOT NULL DEFAULT 'waiting',
  `pickup_code` varchar(20) DEFAULT NULL,
  `pickup_deadline` datetime DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `invoice` varchar(255) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0,
  `payment_method_id` bigint(20) unsigned DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `change` int(11) NOT NULL DEFAULT 0,
  `status` enum('paid','debt') NOT NULL DEFAULT 'paid',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_pickup_code_unique` (`pickup_code`),
  KEY `transactions_user_id_foreign` (`user_id`),
  KEY `transactions_customer_id_foreign` (`customer_id`),
  KEY `transactions_payment_method_id_foreign` (`payment_method_id`),
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transactions` VALUES ('7', '12', 'pos', 'unpaid', 'waiting', NULL, NULL, NULL, '1709250001', '1', '31500', '0', '1', '31500', '0', 'paid', '(diproses: syafiq Muhammad Alif, 17/09/2025 21:07)', '2025-09-17 21:07:03', '2025-09-17 21:07:03');
INSERT INTO `transactions` VALUES ('8', '12', 'pos', 'unpaid', 'waiting', NULL, NULL, NULL, '1909250001', '1', '3500', '0', '1', '3500', '0', 'paid', '(diproses: syafiq Muhammad Alif, 19/09/2025 12:22)', '2025-09-19 12:22:55', '2025-09-19 12:22:55');
INSERT INTO `transactions` VALUES ('9', '12', 'pos', 'unpaid', 'waiting', NULL, NULL, NULL, '0710250001', '1', '24500', '0', '1', '24500', '0', 'paid', '(diproses: syafiq Muhammad Alif, 07/10/2025 11:47)', '2025-10-07 11:47:52', '2025-10-07 11:47:53');


CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES ('11', 'Admin', 'admin', 'dbauch@example.org', '(440) 392-0370', NULL, 'owner', 'Occupational Health Safety Specialist', '$2y$12$H3VttTnGR7c82snMTjSCbeQLVISM8H5TesiMJ8/MV9wtTiUqfpNL2', 'profile.jpg', 'on0ThdwJwc6zZyI3IqWOVjNWnG0PaAnMI4mWSHKTDCzcdjuQFjiZ7qGIbQFx', '2025-01-19 13:28:45', '2025-01-19 13:28:45');
INSERT INTO `users` VALUES ('12', 'syafiq Muhammad Alif', 'syafiq', 'syafiq12@gmail.com', '08924224242', NULL, 'supervisor', 'Manager Oprasional', '$2y$12$dpibl0hTUZTgaL6FUvPu/.mdD/xJCYOUuWrmEDOFjBhA8B1BYCUam', 'profile.jpg', NULL, '2025-07-22 14:24:38', '2025-07-22 14:24:38');
INSERT INTO `users` VALUES ('13', 'jihan Kirana', 'jihan', 'jihan12@gmail.com', '082442424444', NULL, 'admin', 'Admin Gudang', '$2y$12$xtA1TWfEeiI5iQapHproHe6gzCbx9kjlt8bZez.ePVf7Xza3dwIg2', 'profile.jpg', NULL, '2025-07-22 14:25:20', '2025-07-22 14:25:20');
INSERT INTO `users` VALUES ('14', 'leonando prastiko', 'leonando', 'leonando12@gmail.com', '089242121232', NULL, 'cashier', 'Kasir', '$2y$12$RhUlTXi2L0yN5xbPyUR7eOlUSxg83HVXKGc5r0M1Ooz.42JK4rkPO', 'profile.jpg', NULL, '2025-07-22 14:25:57', '2025-07-22 14:25:57');
INSERT INTO `users` VALUES ('15', 'berlian', 'berlian', 'berlian@gmail.com', '012372310972', 'berlianberlianberlian', 'customer', NULL, '$2y$12$Arirw85DqdAziXATFFFBTeNc8V6SfjsSMVtGul6s48J6uO1CTmnz6', 'profile.jpg', NULL, '2025-09-04 09:07:55', '2025-09-04 09:07:55');
INSERT INTO `users` VALUES ('16', 'abuya', 'abuya', 'abuya@gmail.com', '081729337222', 'abuyaabuyaabuya', 'customer', NULL, '$2y$12$At.MJVw/TtfNxrR6h8j/Sutw4D2Z1bJxKeRfuUrYubIizRV4Osk9G', 'profile.jpg', NULL, '2025-09-04 09:22:26', '2025-09-04 09:22:26');
INSERT INTO `users` VALUES ('18', 'farhan', 'farhan', 'farhan@gmail.com', '023149712397', 'pakjo', 'customer', NULL, '$2y$12$O6i5IkH2EO7D9Qz9ScF9YuT1pyNLkD7zBSGowjDqfLlmuZYFd463G', 'profile.jpg', NULL, '2025-10-22 17:48:37', '2025-10-22 17:48:37');


CREATE TABLE `wholesale_prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned NOT NULL,
  `min_qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wholesale_prices_item_id_foreign` (`item_id`),
  CONSTRAINT `wholesale_prices_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

