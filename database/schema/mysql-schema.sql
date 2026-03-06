/*M!999999\- enable the sandbox mode */ 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int DEFAULT NULL,
  `subject_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` int DEFAULT NULL,
  `causer_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `properties` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `browsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `browsers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `isread` int DEFAULT '0' COMMENT '0: unread, 1: read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `download_counts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `download_counts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `file_id` int unsigned NOT NULL COMMENT 'The id from the drupal file_managed table of the file downloaded.',
  `user_id` int unsigned DEFAULT '0' COMMENT 'The uid of the user that downloaded the file.',
  `ip_address` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'The IP address of the downloading user.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dc_fid_type_id` (`file_id`),
  KEY `download_counts_created_at_index` (`created_at`),
  KEY `download_counts_resource_id_index` (`resource_id`),
  KEY `download_counts_resource_id_file_id_index` (`resource_id`,`file_id`),
  KEY `download_counts_resource_id_file_id_created_at_index` (`resource_id`,`file_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `from` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cc` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bcc` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `featured_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_collections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name_tid` int unsigned DEFAULT NULL,
  `icon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `featured_resource_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_resource_levels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fcid` int unsigned DEFAULT NULL,
  `level_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `featured_resource_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_resource_subjects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fcid` int unsigned DEFAULT NULL,
  `subject_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `featured_resource_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_resource_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fcid` int unsigned DEFAULT NULL,
  `type_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `featured_urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `featured_urls` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `fcid` int unsigned DEFAULT NULL,
  `url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `files` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `glossary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `glossary` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `subject` int unsigned NOT NULL,
  `name_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `name_fa` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `name_ps` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `flagged_for_review` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `glossary_subject_foreign` (`subject`),
  CONSTRAINT `glossary_subject_foreign` FOREIGN KEY (`subject`) REFERENCES `glossary_subjects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `glossary_page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `glossary_page_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_bot` tinyint(1) NOT NULL DEFAULT '0',
  `language` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `browser_id` bigint unsigned NOT NULL,
  `status` tinyint NOT NULL COMMENT '1: view, 2: create',
  `user_id` int unsigned DEFAULT NULL,
  `glossary_subject_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `glossary_page_views_device_id_foreign` (`device_id`),
  KEY `glossary_page_views_platform_id_foreign` (`platform_id`),
  KEY `glossary_page_views_browser_id_foreign` (`browser_id`),
  KEY `glossary_page_views_user_id_foreign` (`user_id`),
  KEY `glossary_page_views_glossary_subject_id_foreign` (`glossary_subject_id`),
  KEY `glossary_page_views_gender_index` (`gender`),
  CONSTRAINT `glossary_page_views_browser_id_foreign` FOREIGN KEY (`browser_id`) REFERENCES `browsers` (`id`),
  CONSTRAINT `glossary_page_views_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`),
  CONSTRAINT `glossary_page_views_glossary_subject_id_foreign` FOREIGN KEY (`glossary_subject_id`) REFERENCES `glossary_subjects` (`id`),
  CONSTRAINT `glossary_page_views_platform_id_foreign` FOREIGN KEY (`platform_id`) REFERENCES `platforms` (`id`),
  CONSTRAINT `glossary_page_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `glossary_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `glossary_subjects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `fa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `pa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `mj` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `ps` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `sh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `sw` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `uz` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ltm_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ltm_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` int NOT NULL DEFAULT '0',
  `locale` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `group` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` int DEFAULT '0',
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `tnid` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `weight` (`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `tnid` int DEFAULT NULL COMMENT 'translation id',
  `status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `tnid` int DEFAULT NULL COMMENT 'translation id',
  `status` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `platforms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `platforms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `file_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_mime` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_watermarked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  CONSTRAINT `resource_attachments_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_authors` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_authors_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_authors_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_comments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int DEFAULT '0' COMMENT '0: not published, 1: published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `resource_comments_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_copyright_holders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_copyright_holders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `value` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  CONSTRAINT `resource_copyright_holders_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_creative_commons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_creative_commons` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_creative_commons_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_creative_commons_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_educational_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_educational_resources` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `value` int DEFAULT NULL COMMENT '0: not published, 1: published',
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `resource_educational_resources_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_educational_uses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_educational_uses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_educational_uses_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_educational_uses_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_favorites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `resource_favorites_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_favorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxonomy_term_data_id` int unsigned DEFAULT NULL,
  `resource_id` int unsigned DEFAULT NULL,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `height` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_files_taxonomy_term_data_id_foreign` (`taxonomy_term_data_id`),
  KEY `resource_files_resource_id_foreign` (`resource_id`),
  KEY `resource_files_label_index` (`label`),
  KEY `resource_files_language_index` (`language`),
  CONSTRAINT `resource_files_resource_id_foreign` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`),
  CONSTRAINT `resource_files_taxonomy_term_data_id_foreign` FOREIGN KEY (`taxonomy_term_data_id`) REFERENCES `taxonomy_term_data` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_flags` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `type` int DEFAULT NULL COMMENT '1: Graphic Violence, 2: Graphic Sexual Content, 3: Spam, Scam or Fraud, 4: Broken or Empty Data',
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `resource_flags_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_flags_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_iam_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_iam_author` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `value` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `resource_iam_author_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_keywords` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_keywords_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_keywords_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_learning_resource_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_learning_resource_types` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_learning_resource_types_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_learning_resource_types_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_levels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_levels_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_levels_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_publishers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_publishers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  CONSTRAINT `resource_publishers_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_share_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_share_permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_share_permissions_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_share_permissions_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_subject_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_subject_areas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  KEY `tid` (`tid`),
  CONSTRAINT `resource_subject_areas_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resource_subject_areas_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `taxonomy_term_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_translation_rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_translation_rights` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned DEFAULT NULL,
  `value` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `resource_translation_rights_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_translators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_translators` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL,
  `tid` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resourceid` (`resource_id`),
  CONSTRAINT `resource_translators_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resource_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resource_views` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_bot` tinyint(1) NOT NULL DEFAULT '0',
  `browser_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `browser_version` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `platform` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nid` (`resource_id`),
  KEY `ip` (`ip`),
  KEY `resource_views_resource_id_user_id_index` (`resource_id`,`user_id`),
  KEY `resource_views_resource_id_created_at_index` (`resource_id`,`created_at`),
  KEY `resource_views_resource_id_user_id_created_at_index` (`resource_id`,`user_id`,`created_at`),
  KEY `resource_views_is_bot_index` (`is_bot`),
  KEY `resource_views_is_bot_user_id_index` (`is_bot`,`user_id`),
  KEY `resource_views_is_bot_resource_id_index` (`is_bot`,`resource_id`),
  KEY `resource_views_is_bot_resource_id_created_at_index` (`is_bot`,`resource_id`,`created_at`),
  KEY `resource_views_created_at_is_bot_index` (`created_at`,`is_bot`),
  KEY `resource_views_created_at_is_bot_user_id_index` (`created_at`,`is_bot`,`user_id`),
  CONSTRAINT `resource_views_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `resources` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abstract` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `resource_file_id` bigint unsigned DEFAULT NULL,
  `status` int DEFAULT '0' COMMENT '0: not published, 1: published',
  `tnid` int DEFAULT NULL,
  `primary_tnid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`user_id`),
  KEY `status` (`status`),
  KEY `resources_title_index` (`title`),
  KEY `resources_language_index` (`language`),
  KEY `resources_created_at_index` (`created_at`),
  KEY `resources_id_language_index` (`id`,`language`),
  KEY `resources_created_at_language_index` (`created_at`,`language`),
  KEY `resources_resource_file_id_foreign` (`resource_file_id`),
  CONSTRAINT `resources_resource_file_id_foreign` FOREIGN KEY (`resource_file_id`) REFERENCES `resource_files` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `website_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_slogan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sitewide_page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sitewide_page_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `browser` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_bot` tinyint(1) NOT NULL DEFAULT '0',
  `language` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` bigint unsigned NOT NULL,
  `platform_id` bigint unsigned NOT NULL,
  `browser_id` bigint unsigned NOT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sitewide_page_views_device_id_foreign` (`device_id`),
  KEY `sitewide_page_views_platform_id_foreign` (`platform_id`),
  KEY `sitewide_page_views_browser_id_foreign` (`browser_id`),
  KEY `sitewide_page_views_user_id_foreign` (`user_id`),
  KEY `sitewide_page_views_gender_index` (`gender`),
  KEY `sitewide_page_views_is_bot_index` (`is_bot`),
  KEY `sitewide_page_views_created_at_index` (`created_at`),
  KEY `sitewide_page_views_is_bot_browser_id_created_at_index` (`is_bot`,`browser_id`,`created_at`),
  KEY `sitewide_page_views_is_bot_platform_id_created_at_index` (`is_bot`,`platform_id`,`created_at`),
  KEY `sitewide_page_views_is_bot_gender_created_at_index` (`is_bot`,`gender`,`created_at`),
  KEY `sitewide_page_views_is_bot_created_at_index` (`is_bot`,`created_at`),
  KEY `sitewide_page_views_is_bot_created_at_user_id_index` (`is_bot`,`created_at`,`user_id`),
  KEY `sitewide_page_views_is_bot_page_url_title_index` (`is_bot`,`page_url`,`title`),
  KEY `sitewide_page_views_is_bot_created_at_page_url_title_index` (`is_bot`,`created_at`,`page_url`,`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `static_subject_area_icons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `static_subject_area_icons` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aux_id` int unsigned NOT NULL AUTO_INCREMENT,
  `tid` int unsigned NOT NULL,
  `file_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_mime` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`tid`),
  KEY `static_subject_area_icons_aux_id_index` (`aux_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `survey_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `survey_answers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `question_id` int unsigned NOT NULL,
  `answer_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `survey_answers_question_id_foreign` (`question_id`),
  KEY `survey_answers_answer_id_foreign` (`answer_id`),
  CONSTRAINT `survey_answers_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `survey_question_options` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `survey_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `survey_question_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `survey_question_options` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tnid` int DEFAULT NULL COMMENT 'translation id',
  PRIMARY KEY (`id`),
  KEY `survey_question_options_question_id_foreign` (`question_id`),
  CONSTRAINT `survey_question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `survey_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `survey_questions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `survey_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tnid` int DEFAULT NULL COMMENT 'translation id',
  PRIMARY KEY (`id`),
  KEY `survey_questions_survey_id_foreign` (`survey_id`),
  CONSTRAINT `survey_questions_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `survey_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `survey_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `time` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `surveys` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tnid` int DEFAULT NULL COMMENT 'translation id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `surveys_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `taxonomy_term_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxonomy_term_data` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `vid` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `weight` int NOT NULL DEFAULT '0' COMMENT 'The weight of this term in relation to other terms.',
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'und',
  `tnid` int DEFAULT '0',
  `excluded` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `taxonomy_tree` (`vid`,`weight`,`name`),
  KEY `vid_name` (`vid`,`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `taxonomy_term_hierarchy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxonomy_term_hierarchy` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aux_id` int unsigned NOT NULL AUTO_INCREMENT,
  `tid` int unsigned NOT NULL DEFAULT '0',
  `parent` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`,`parent`),
  KEY `taxonomy_term_hierarchy_aux_id_index` (`aux_id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `taxonomy_vocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `taxonomy_vocabulary` (
  `vid` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `weight` int NOT NULL DEFAULT '0' COMMENT 'The weight of this vocabulary in relation to other vocabularies.',
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'und',
  PRIMARY KEY (`vid`),
  KEY `list` (`weight`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_profiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visited_storyweaver_disclaimer` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`user_id`),
  CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `role_id` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`user_id`),
  KEY `roleid` (`role_id`),
  CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `user_roles_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'User’s e-mail address.',
  `status` tinyint(1) DEFAULT '0',
  `language` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'en',
  `provider_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `access` (`accessed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

/*M!999999\- enable the sandbox mode */ 
set autocommit=0;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_04_02_193005_create_translations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2019_04_16_233711_create_contacts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_04_16_233711_create_download_counts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_04_16_233711_create_featured_collections_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2019_04_16_233711_create_featured_resource_levels_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2019_04_16_233711_create_featured_resource_subjects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2019_04_16_233711_create_featured_resource_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2019_04_16_233711_create_featured_urls_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2019_04_16_233711_create_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2019_04_16_233711_create_glossary_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2019_04_16_233711_create_menus_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2019_04_16_233711_create_news_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2019_04_16_233711_create_pages_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2019_04_16_233711_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2019_04_16_233711_create_resource_attachments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2019_04_16_233711_create_resource_authors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2019_04_16_233711_create_resource_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2019_04_16_233711_create_resource_copyright_holders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2019_04_16_233711_create_resource_creative_commons_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2019_04_16_233711_create_resource_educational_resources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2019_04_16_233711_create_resource_educational_uses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2019_04_16_233711_create_resource_favorites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2019_04_16_233711_create_resource_flags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2019_04_16_233711_create_resource_iam_author_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2019_04_16_233711_create_resource_keywords_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2019_04_16_233711_create_resource_learning_resource_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2019_04_16_233711_create_resource_levels_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2019_04_16_233711_create_resource_publishers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2019_04_16_233711_create_resource_share_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2019_04_16_233711_create_resource_subject_areas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2019_04_16_233711_create_resource_translation_rights_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2019_04_16_233711_create_resource_translators_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2019_04_16_233711_create_resource_views_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2019_04_16_233711_create_resources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2019_04_16_233711_create_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2019_04_16_233711_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2019_04_16_233711_create_static_subject_area_icons_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2019_04_16_233711_create_survey_answers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2019_04_16_233711_create_survey_question_options_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2019_04_16_233711_create_survey_questions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2019_04_16_233711_create_survey_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2019_04_16_233711_create_surveys_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2019_04_16_233711_create_taxonomy_term_data_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2019_04_16_233711_create_taxonomy_term_hierarchy_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2019_04_16_233711_create_taxonomy_vocabulary_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2019_04_16_233711_create_user_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2019_04_16_233711_create_user_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2019_04_16_233711_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2019_04_16_233712_add_foreign_keys_to_resource_attachments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2019_04_16_233712_add_foreign_keys_to_resource_authors_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2019_04_16_233712_add_foreign_keys_to_resource_comments_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2019_04_16_233712_add_foreign_keys_to_resource_copyright_holders_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2019_04_16_233712_add_foreign_keys_to_resource_creative_commons_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2019_04_16_233712_add_foreign_keys_to_resource_educational_resources_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2019_04_16_233712_add_foreign_keys_to_resource_educational_uses_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2019_04_16_233712_add_foreign_keys_to_resource_favorites_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2019_04_16_233712_add_foreign_keys_to_resource_flags_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2019_04_16_233712_add_foreign_keys_to_resource_iam_author_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2019_04_16_233712_add_foreign_keys_to_resource_keywords_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2019_04_16_233712_add_foreign_keys_to_resource_learning_resource_types_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2019_04_16_233712_add_foreign_keys_to_resource_levels_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2019_04_16_233712_add_foreign_keys_to_resource_publishers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2019_04_16_233712_add_foreign_keys_to_resource_share_permissions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2019_04_16_233712_add_foreign_keys_to_resource_subject_areas_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2019_04_16_233712_add_foreign_keys_to_resource_translation_rights_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2019_04_16_233712_add_foreign_keys_to_resource_translators_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2019_04_16_233712_add_foreign_keys_to_resource_views_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2019_04_16_233712_add_foreign_keys_to_survey_answers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2019_04_16_233712_add_foreign_keys_to_survey_question_options_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2019_04_16_233712_add_foreign_keys_to_survey_questions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2019_04_16_233712_add_foreign_keys_to_user_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2019_04_16_233712_add_foreign_keys_to_user_roles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2019_04_17_055642_create_activity_log_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2019_04_27_195052_add_language_to_survey_answers_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2020_07_18_140143_add_file_watermarked_to_resource_attachments',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2020_07_22_092404_users_email_nullable',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2020_08_13_073528_add_visited_storyweaver_disclaimer_to_user_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2020_09_30_055917_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2020_10_27_183558_add_flagged_for_review_to_glossary_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2021_01_17_045707_add_published_at_to_resources',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2021_01_28_142112_create_glossary_subjects',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2021_01_28_143800_change_glossary_subjects_type',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2021_05_05_051228_change_ip_size_in_resource_views_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2021_07_02_141111_add_email_verified_at_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2021_08_12_080544_add_event_column_to_activity_log_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2021_08_12_080545_add_batch_uuid_column_to_activity_log_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2021_08_13_110645_add_primary_key_to_password_resets_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2015_07_31_1_email_log',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2016_09_21_001638_add_bcc_column_email_log',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2017_11_10_001638_add_more_mail_columns_email_log',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2018_05_11_115355_use_longtext_for_attachments',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2019_12_14_000001_create_personal_access_tokens_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2022_05_24_040551_add_excluded_to_taxonomy_term_data',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2023_01_24_055958_add_status_to_menus_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2023_08_18_040241_add_avatar_to_users_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2023_11_29_000000_add_expires_at_to_personal_access_tokens_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2024_02_07_000000_rename_password_resets_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2023_12_07_125201_create_subscribers_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2024_04_09_045125_add_index_to_download_counts_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2024_04_30_142706_add_provider_to_users_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2024_05_01_124155_add_new_index_to_resource_views_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2024_05_02_181139_add_index_to_resources_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2024_07_01_060829_create_devices_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2024_07_01_062057_create_platforms_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2024_07_01_062632_create_browsers_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2024_07_01_065252_create_sitewide_page_views_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2024_07_03_154638_create_glossary_page_views_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2024_08_05_155841_change_sitewide_page_views_increase_user_agent_column_length',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2024_09_11_161248_add_is_bot_to_resource_views_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2024_09_11_165623_add_new_index_to_resource_views_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2024_09_11_172455_add_index_to_sitewide_page_views_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2025_05_14_055455_add_primary_tnid_to_resources_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2024_10_14_094521_create_resource_files_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2024_10_14_115038_add_resource_file_id_to_resources_table',26);
commit;
