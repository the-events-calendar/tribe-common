/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.5.26-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: db    Database: test
-- ------------------------------------------------------
-- Server version	10.7.8-MariaDB-1:10.7.8+maria~ubu2004

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `wp_actionscheduler_actions`
--

DROP TABLE IF EXISTS `wp_actionscheduler_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_actions` (
  `action_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hook` varchar(191) NOT NULL,
  `status` varchar(20) NOT NULL,
  `scheduled_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `scheduled_date_local` datetime DEFAULT '0000-00-00 00:00:00',
  `priority` tinyint(3) unsigned NOT NULL DEFAULT 10,
  `args` varchar(191) DEFAULT NULL,
  `schedule` longtext DEFAULT NULL,
  `group_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `last_attempt_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `extended_args` varchar(8000) DEFAULT NULL,
  PRIMARY KEY (`action_id`),
  KEY `hook` (`hook`),
  KEY `status` (`status`),
  KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  KEY `args` (`args`),
  KEY `group_id` (`group_id`),
  KEY `last_attempt_gmt` (`last_attempt_gmt`),
  KEY `claim_id_status_scheduled_date_gmt` (`claim_id`,`status`,`scheduled_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_actions`
--

LOCK TABLES `wp_actionscheduler_actions` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_actions` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_actions` VALUES (7,'action_scheduler/migration_hook','failed','2023-11-28 12:15:16','2023-11-28 12:15:16',10,'[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1701173716;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1701173716;}',1,1,'2023-11-29 15:37:29','2023-11-29 15:37:29',0,NULL),(9,'action_scheduler/migration_hook','complete','2024-01-28 15:23:26','2024-01-28 15:23:26',10,'[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1706455406;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1706455406;}',1,1,'2024-09-24 14:20:05','2024-09-24 14:20:05',0,NULL),(10,'action_scheduler/migration_hook','complete','2024-09-24 14:28:07','2024-09-24 14:28:07',10,'[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1727188087;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1727188087;}',1,1,'2024-09-27 11:52:06','2024-09-27 11:52:06',0,NULL),(11,'action_scheduler/migration_hook','pending','2024-09-27 11:54:03','2024-09-27 11:54:03',10,'[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1727438043;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1727438043;}',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL);
/*!40000 ALTER TABLE `wp_actionscheduler_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_actionscheduler_claims`
--

DROP TABLE IF EXISTS `wp_actionscheduler_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_claims` (
  `claim_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_created_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`claim_id`),
  KEY `date_created_gmt` (`date_created_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_claims`
--

LOCK TABLES `wp_actionscheduler_claims` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_actionscheduler_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_actionscheduler_groups`
--

DROP TABLE IF EXISTS `wp_actionscheduler_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_groups` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_groups`
--

LOCK TABLES `wp_actionscheduler_groups` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_groups` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_groups` VALUES (1,'action-scheduler-migration');
/*!40000 ALTER TABLE `wp_actionscheduler_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_actionscheduler_logs`
--

DROP TABLE IF EXISTS `wp_actionscheduler_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_actionscheduler_logs` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `log_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `log_date_local` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`),
  KEY `action_id` (`action_id`),
  KEY `log_date_gmt` (`log_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_logs`
--

LOCK TABLES `wp_actionscheduler_logs` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_logs` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_logs` VALUES (7,7,'action created','2023-11-28 12:14:16','2023-11-28 12:14:16'),(8,7,'action started via Async Request','2023-11-29 15:37:29','2023-11-29 15:37:29'),(9,7,'action failed via Async Request: Scheduled action for action_scheduler/migration_hook will not be executed as no callbacks are registered.','2023-11-29 15:37:29','2023-11-29 15:37:29'),(13,9,'action created','2024-01-28 15:22:26','2024-01-28 15:22:26'),(14,9,'action started via Async Request','2024-09-24 14:20:05','2024-09-24 14:20:05'),(15,9,'action complete via Async Request','2024-09-24 14:20:05','2024-09-24 14:20:05'),(16,10,'action created','2024-09-24 14:27:07','2024-09-24 14:27:07'),(17,10,'action started via Async Request','2024-09-27 11:52:06','2024-09-27 11:52:06'),(18,10,'action complete via Async Request','2024-09-27 11:52:06','2024-09-27 11:52:06'),(19,11,'action created','2024-09-27 11:53:03','2024-09-27 11:53:03');
/*!40000 ALTER TABLE `wp_actionscheduler_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_commentmeta`
--

DROP TABLE IF EXISTS `wp_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_commentmeta`
--

LOCK TABLES `wp_commentmeta` WRITE;
/*!40000 ALTER TABLE `wp_commentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_commentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT 0,
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_comments`
--

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_adjustmentmeta`
--

DROP TABLE IF EXISTS `wp_edd_adjustmentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_adjustmentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_adjustment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_adjustment_id` (`edd_adjustment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_adjustmentmeta`
--

LOCK TABLES `wp_edd_adjustmentmeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_adjustmentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_adjustmentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_adjustments`
--

DROP TABLE IF EXISTS `wp_edd_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `scope` varchar(20) NOT NULL DEFAULT 'all',
  `amount_type` varchar(20) NOT NULL DEFAULT '',
  `amount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `description` longtext NOT NULL,
  `max_uses` bigint(20) unsigned NOT NULL DEFAULT 0,
  `use_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `once_per_customer` int(1) NOT NULL DEFAULT 0,
  `min_charge_amount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_status` (`type`,`status`),
  KEY `code` (`code`),
  KEY `date_created` (`date_created`),
  KEY `date_start_end` (`start_date`,`end_date`),
  KEY `type_status_dates` (`type`,`status`,`start_date`,`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_adjustments`
--

LOCK TABLES `wp_edd_adjustments` WRITE;
/*!40000 ALTER TABLE `wp_edd_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_customer_addresses`
--

DROP TABLE IF EXISTS `wp_edd_customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_customer_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'billing',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `name` mediumtext NOT NULL,
  `address` mediumtext NOT NULL,
  `address2` mediumtext NOT NULL,
  `city` mediumtext NOT NULL,
  `region` mediumtext NOT NULL,
  `postal_code` varchar(32) NOT NULL DEFAULT '',
  `country` mediumtext NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer_is_primary` (`customer_id`,`is_primary`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_customer_addresses`
--

LOCK TABLES `wp_edd_customer_addresses` WRITE;
/*!40000 ALTER TABLE `wp_edd_customer_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_customer_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_customer_email_addresses`
--

DROP TABLE IF EXISTS `wp_edd_customer_email_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_customer_email_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'secondary',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `email` varchar(100) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer` (`customer_id`),
  KEY `email` (`email`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_customer_email_addresses`
--

LOCK TABLES `wp_edd_customer_email_addresses` WRITE;
/*!40000 ALTER TABLE `wp_edd_customer_email_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_customer_email_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_customermeta`
--

DROP TABLE IF EXISTS `wp_edd_customermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_customermeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_customer_id` (`edd_customer_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_customermeta`
--

LOCK TABLES `wp_edd_customermeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_customermeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_customermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_customers`
--

DROP TABLE IF EXISTS `wp_edd_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `purchase_value` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `purchase_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user` (`user_id`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_customers`
--

LOCK TABLES `wp_edd_customers` WRITE;
/*!40000 ALTER TABLE `wp_edd_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_logmeta`
--

DROP TABLE IF EXISTS `wp_edd_logmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_logmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_log_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_log_id` (`edd_log_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_logmeta`
--

LOCK TABLES `wp_edd_logmeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_logmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_logmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_logs`
--

DROP TABLE IF EXISTS `wp_edd_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_logs`
--

LOCK TABLES `wp_edd_logs` WRITE;
/*!40000 ALTER TABLE `wp_edd_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_logs_api_requestmeta`
--

DROP TABLE IF EXISTS `wp_edd_logs_api_requestmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_logs_api_requestmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_logs_api_request_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_logs_api_request_id` (`edd_logs_api_request_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_logs_api_requestmeta`
--

LOCK TABLES `wp_edd_logs_api_requestmeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_logs_api_requestmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_logs_api_requestmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_logs_api_requests`
--

DROP TABLE IF EXISTS `wp_edd_logs_api_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_logs_api_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `api_key` varchar(32) NOT NULL DEFAULT 'public',
  `token` varchar(32) NOT NULL DEFAULT '',
  `version` varchar(32) NOT NULL DEFAULT '',
  `request` longtext NOT NULL,
  `error` longtext NOT NULL,
  `ip` varchar(60) NOT NULL DEFAULT '',
  `time` varchar(60) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_logs_api_requests`
--

LOCK TABLES `wp_edd_logs_api_requests` WRITE;
/*!40000 ALTER TABLE `wp_edd_logs_api_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_logs_api_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_logs_file_downloadmeta`
--

DROP TABLE IF EXISTS `wp_edd_logs_file_downloadmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_logs_file_downloadmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_logs_file_download_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_logs_file_download_id` (`edd_logs_file_download_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_logs_file_downloadmeta`
--

LOCK TABLES `wp_edd_logs_file_downloadmeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_logs_file_downloadmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_logs_file_downloadmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_logs_file_downloads`
--

DROP TABLE IF EXISTS `wp_edd_logs_file_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_logs_file_downloads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `file_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `price_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `ip` varchar(60) NOT NULL DEFAULT '',
  `user_agent` varchar(200) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_logs_file_downloads`
--

LOCK TABLES `wp_edd_logs_file_downloads` WRITE;
/*!40000 ALTER TABLE `wp_edd_logs_file_downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_logs_file_downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_notemeta`
--

DROP TABLE IF EXISTS `wp_edd_notemeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_notemeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_note_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_note_id` (`edd_note_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_notemeta`
--

LOCK TABLES `wp_edd_notemeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_notemeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_notemeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_notes`
--

DROP TABLE IF EXISTS `wp_edd_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) NOT NULL DEFAULT '',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `content` longtext NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `user_id` (`user_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_notes`
--

LOCK TABLES `wp_edd_notes` WRITE;
/*!40000 ALTER TABLE `wp_edd_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_notifications`
--

DROP TABLE IF EXISTS `wp_edd_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `remote_id` varchar(20) DEFAULT NULL,
  `source` varchar(20) NOT NULL DEFAULT 'api',
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `buttons` longtext DEFAULT NULL,
  `type` varchar(64) NOT NULL,
  `conditions` longtext DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `dismissed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `dismissed_start_end` (`dismissed`,`start`,`end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_notifications`
--

LOCK TABLES `wp_edd_notifications` WRITE;
/*!40000 ALTER TABLE `wp_edd_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_order_addresses`
--

DROP TABLE IF EXISTS `wp_edd_order_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_order_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'billing',
  `name` mediumtext NOT NULL,
  `address` mediumtext NOT NULL,
  `address2` mediumtext NOT NULL,
  `city` mediumtext NOT NULL,
  `region` mediumtext NOT NULL,
  `postal_code` varchar(32) NOT NULL DEFAULT '',
  `country` mediumtext NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `city` (`city`(191)),
  KEY `region` (`region`(191)),
  KEY `postal_code` (`postal_code`),
  KEY `country` (`country`(191)),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_order_addresses`
--

LOCK TABLES `wp_edd_order_addresses` WRITE;
/*!40000 ALTER TABLE `wp_edd_order_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_order_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_order_adjustmentmeta`
--

DROP TABLE IF EXISTS `wp_edd_order_adjustmentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_order_adjustmentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_order_adjustment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_order_adjustment_id` (`edd_order_adjustment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_order_adjustmentmeta`
--

LOCK TABLES `wp_edd_order_adjustmentmeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_order_adjustmentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_order_adjustmentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_order_adjustments`
--

DROP TABLE IF EXISTS `wp_edd_order_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_order_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) DEFAULT NULL,
  `type_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `type_key` varchar(255) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `subtotal` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `tax` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `date_created` (`date_created`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_order_adjustments`
--

LOCK TABLES `wp_edd_order_adjustments` WRITE;
/*!40000 ALTER TABLE `wp_edd_order_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_order_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_order_itemmeta`
--

DROP TABLE IF EXISTS `wp_edd_order_itemmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_order_itemmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_order_item_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_order_item_id` (`edd_order_item_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_order_itemmeta`
--

LOCK TABLES `wp_edd_order_itemmeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_order_itemmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_order_itemmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_order_items`
--

DROP TABLE IF EXISTS `wp_edd_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `product_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `product_name` text NOT NULL,
  `price_id` bigint(20) unsigned DEFAULT NULL,
  `cart_index` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'download',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `subtotal` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `discount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `tax` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_product_price_id` (`order_id`,`product_id`,`price_id`),
  KEY `type_status` (`type`,`status`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_order_items`
--

LOCK TABLES `wp_edd_order_items` WRITE;
/*!40000 ALTER TABLE `wp_edd_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_order_transactions`
--

DROP TABLE IF EXISTS `wp_edd_order_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_order_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) NOT NULL DEFAULT '',
  `transaction_id` varchar(256) NOT NULL DEFAULT '',
  `gateway` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`(64)),
  KEY `gateway` (`gateway`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`),
  KEY `object_type_object_id` (`object_type`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_order_transactions`
--

LOCK TABLES `wp_edd_order_transactions` WRITE;
/*!40000 ALTER TABLE `wp_edd_order_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_order_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_ordermeta`
--

DROP TABLE IF EXISTS `wp_edd_ordermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_ordermeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_order_id` (`edd_order_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_ordermeta`
--

LOCK TABLES `wp_edd_ordermeta` WRITE;
/*!40000 ALTER TABLE `wp_edd_ordermeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_ordermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_edd_orders`
--

DROP TABLE IF EXISTS `wp_edd_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_edd_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_number` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `type` varchar(20) NOT NULL DEFAULT 'sale',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(60) NOT NULL DEFAULT '',
  `gateway` varchar(100) NOT NULL DEFAULT 'manual',
  `mode` varchar(20) NOT NULL DEFAULT '',
  `currency` varchar(20) NOT NULL DEFAULT '',
  `payment_key` varchar(64) NOT NULL DEFAULT '',
  `tax_rate_id` bigint(20) DEFAULT NULL,
  `subtotal` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `discount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `tax` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `date_completed` datetime DEFAULT NULL,
  `date_refundable` datetime DEFAULT NULL,
  `date_actions_run` datetime DEFAULT NULL,
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_number` (`order_number`(191)),
  KEY `status_type` (`status`,`type`),
  KEY `user_id` (`user_id`),
  KEY `customer_id` (`customer_id`),
  KEY `email` (`email`),
  KEY `payment_key` (`payment_key`),
  KEY `date_created_completed` (`date_created`,`date_completed`),
  KEY `currency` (`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_edd_orders`
--

LOCK TABLES `wp_edd_orders` WRITE;
/*!40000 ALTER TABLE `wp_edd_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_edd_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_links`
--

LOCK TABLES `wp_links` WRITE;
/*!40000 ALTER TABLE `wp_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=522 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (2,'siteurl','http://wordpress.test','yes'),(3,'home','http://wordpress.test','yes'),(4,'blogname','Event Automator','yes'),(5,'blogdescription','','yes'),(6,'users_can_register','0','yes'),(7,'admin_email','you@example.com','yes'),(8,'start_of_week','1','yes'),(9,'use_balanceTags','0','yes'),(10,'use_smilies','1','yes'),(11,'require_name_email','1','yes'),(12,'comments_notify','1','yes'),(13,'posts_per_rss','10','yes'),(14,'rss_use_excerpt','0','yes'),(15,'mailserver_url','mail.example.com','yes'),(16,'mailserver_login','login@example.com','yes'),(17,'mailserver_pass','password','yes'),(18,'mailserver_port','110','yes'),(19,'default_category','1','yes'),(20,'default_comment_status','open','yes'),(21,'default_ping_status','open','yes'),(22,'default_pingback_flag','1','yes'),(23,'posts_per_page','10','yes'),(24,'date_format','F j, Y','yes'),(25,'time_format','g:i a','yes'),(26,'links_updated_date_format','F j, Y g:i a','yes'),(27,'comment_moderation','0','yes'),(28,'moderation_notify','1','yes'),(30,'hack_file','0','yes'),(31,'blog_charset','UTF-8','yes'),(32,'moderation_keys','','no'),(33,'active_plugins','a:0:{}','yes'),(34,'category_base','','yes'),(35,'ping_sites','http://rpc.pingomatic.com/','yes'),(36,'comment_max_links','2','yes'),(37,'gmt_offset','0','yes'),(38,'default_email_category','1','yes'),(39,'recently_edited','','no'),(40,'template','twentytwentythree','yes'),(41,'stylesheet','twentytwentythree','yes'),(42,'comment_registration','0','yes'),(43,'html_type','text/html','yes'),(44,'use_trackback','0','yes'),(45,'default_role','subscriber','yes'),(46,'db_version','57155','yes'),(47,'uploads_use_yearmonth_folders','1','yes'),(48,'upload_path','','yes'),(49,'blog_public','1','yes'),(50,'default_link_category','2','yes'),(51,'show_on_front','posts','yes'),(52,'tag_base','','yes'),(53,'show_avatars','1','yes'),(54,'avatar_rating','G','yes'),(55,'upload_url_path','','yes'),(56,'thumbnail_size_w','150','yes'),(57,'thumbnail_size_h','150','yes'),(58,'thumbnail_crop','1','yes'),(59,'medium_size_w','300','yes'),(60,'medium_size_h','300','yes'),(61,'avatar_default','mystery','yes'),(62,'large_size_w','1024','yes'),(63,'large_size_h','1024','yes'),(64,'image_default_link_type','none','yes'),(65,'image_default_size','','yes'),(66,'image_default_align','','yes'),(67,'close_comments_for_old_posts','0','yes'),(68,'close_comments_days_old','14','yes'),(69,'thread_comments','1','yes'),(70,'thread_comments_depth','5','yes'),(71,'page_comments','0','yes'),(72,'comments_per_page','50','yes'),(73,'default_comments_page','newest','yes'),(74,'comment_order','asc','yes'),(75,'sticky_posts','a:0:{}','yes'),(76,'widget_categories','a:0:{}','yes'),(77,'widget_text','a:0:{}','yes'),(78,'widget_rss','a:0:{}','yes'),(79,'uninstall_plugins','a:1:{s:35:\"event-automator/event-automator.php\";s:23:\"tec_automator_uninstall\";}','no'),(80,'timezone_string','','yes'),(81,'page_for_posts','0','yes'),(82,'page_on_front','0','yes'),(83,'default_post_format','0','yes'),(84,'link_manager_enabled','0','yes'),(85,'finished_splitting_shared_terms','1','yes'),(86,'site_icon','0','yes'),(87,'medium_large_size_w','768','yes'),(88,'medium_large_size_h','0','yes'),(89,'wp_page_for_privacy_policy','3','yes'),(90,'show_comments_cookies_opt_in','1','yes'),(92,'disallowed_keys','','no'),(93,'comment_previously_approved','1','yes'),(94,'auto_plugin_theme_update_emails','a:0:{}','no'),(95,'auto_update_core_dev','enabled','yes'),(96,'auto_update_core_minor','enabled','yes'),(97,'auto_update_core_major','enabled','yes'),(98,'wp_force_deactivated_plugins','a:0:{}','yes'),(99,'initial_db_version','53496','yes'),(100,'wp_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:71:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:31:\"read_private_aggregator-records\";b:1;s:23:\"edit_aggregator-records\";b:1;s:30:\"edit_others_aggregator-records\";b:1;s:31:\"edit_private_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:32:\"delete_others_aggregator-records\";b:1;s:33:\"delete_private_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:44:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:31:\"read_private_aggregator-records\";b:1;s:23:\"edit_aggregator-records\";b:1;s:30:\"edit_others_aggregator-records\";b:1;s:31:\"edit_private_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:32:\"delete_others_aggregator-records\";b:1;s:33:\"delete_private_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:15:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:23:\"edit_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:7:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:23:\"edit_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','yes'),(101,'fresh_site','1','yes'),(102,'user_count','1','no'),(103,'widget_block','a:6:{i:2;a:1:{s:7:\"content\";s:19:\"<!-- wp:search /-->\";}i:3;a:1:{s:7:\"content\";s:154:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->\";}i:4;a:1:{s:7:\"content\";s:227:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {\"displayAvatar\":false,\"displayDate\":false,\"displayExcerpt\":false} /--></div><!-- /wp:group -->\";}i:5;a:1:{s:7:\"content\";s:146:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->\";}i:6;a:1:{s:7:\"content\";s:150:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->\";}s:12:\"_multiwidget\";i:1;}','yes'),(104,'sidebars_widgets','a:4:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}s:13:\"array_version\";i:3;}','yes'),(105,'cron','a:11:{i:1736346692;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1736371892;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1736372425;a:1:{s:21:\"wp_update_user_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1736415086;a:2:{s:16:\"tribe_daily_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:24:\"tribe_common_log_cleanup\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736415087;a:1:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736415625;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736415627;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736423598;a:1:{s:21:\"tribe-recurrence-cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736857601;a:1:{s:30:\"wp_delete_temp_updater_backups\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1736933492;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}s:7:\"version\";i:2;}','yes'),(106,'tribe_last_updated_option','1727438041.2444','yes'),(107,'nonce_key','hMwU.4q>Tjyt##0W@,*#U0~RFLy`y)]akRY,a/iPhH.| mBStiq*5(*SH[IejO!L','no'),(108,'nonce_salt','ElWxp&9bPhFm;%kaVuDoxn:MYR)nC6O:e&~?9GAWPA>f,B t LXkV=NgKh-F`oM~','no'),(109,'tribe_events_calendar_options','a:23:{s:8:\"did_init\";b:1;s:19:\"tribeEventsTemplate\";s:0:\"\";s:16:\"tribeEnableViews\";a:3:{i:0;s:4:\"list\";i:1;s:5:\"month\";i:2;s:3:\"day\";}s:10:\"viewOption\";s:4:\"list\";s:25:\"ticket-enabled-post-types\";a:2:{i:0;s:12:\"tribe_events\";i:1;s:4:\"page\";}s:14:\"schema-version\";s:6:\"5.16.0\";s:28:\"event-tickets-schema-version\";s:6:\"5.13.4\";s:21:\"previous_ecp_versions\";a:6:{i:0;s:1:\"0\";i:1;s:5:\"6.2.2\";i:2;s:7:\"6.2.3.2\";i:3;s:7:\"6.2.8.1\";i:4;s:5:\"6.3.1\";i:5;s:7:\"6.6.4.2\";}s:18:\"latest_ecp_version\";s:5:\"6.7.0\";s:31:\"previous_event_tickets_versions\";a:6:{i:0;s:1:\"0\";i:1;s:5:\"5.6.5\";i:2;s:7:\"5.6.6.1\";i:3;s:5:\"5.7.0\";i:4;s:5:\"5.8.0\";i:5;s:8:\"5.13.3.1\";}s:28:\"latest_event_tickets_version\";s:6:\"5.13.4\";s:18:\"dateWithYearFormat\";s:6:\"F j, Y\";s:24:\"recurrenceMaxMonthsAfter\";i:60;s:22:\"google_maps_js_api_key\";s:39:\"AIzaSyDNsicAsP6-VuGtAb1O9riI3oc_NOb7IOU\";s:30:\"event-automator-schema-version\";s:5:\"1.7.0\";s:13:\"opt-in-status\";b:0;s:39:\"last-update-message-the-events-calendar\";s:5:\"6.7.0\";s:18:\"pro-schema-version\";s:5:\"7.1.0\";s:29:\"events-virtual-schema-version\";s:6:\"1.15.8\";s:26:\"flexible_tickets_activated\";b:1;s:36:\"previous_event_tickets_plus_versions\";a:1:{i:0;s:1:\"0\";}s:33:\"latest_event_tickets_plus_version\";s:5:\"6.0.4\";s:33:\"event-tickets-plus-schema-version\";s:5:\"6.0.4\";}','yes'),(110,'schema-ActionScheduler_StoreSchema','7.0.1697535092','yes'),(111,'schema-ActionScheduler_LoggerSchema','3.0.1697535092','yes'),(114,'tribe_last_save_post','1727438041.2446','yes'),(115,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(116,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(117,'widget_archives','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(118,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(119,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(120,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(121,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(122,'widget_meta','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(123,'widget_search','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(124,'widget_recent-posts','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(125,'widget_recent-comments','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(126,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(127,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(128,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(129,'widget_tribe-widget-events-list','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(130,'tec_timed_tec_custom_tables_v1_initialized','a:3:{s:3:\"key\";s:32:\"tec_custom_tables_v1_initialized\";s:5:\"value\";i:1;s:10:\"expiration\";i:1727524326;}','yes'),(131,'tec_ct1_migration_state','a:3:{s:18:\"complete_timestamp\";N;s:5:\"phase\";s:22:\"migration-not-required\";s:19:\"preview_unsupported\";b:0;}','yes'),(132,'tec_ct1_events_table_schema_version','1.0.1','yes'),(133,'tec_ct1_occurrences_table_schema_version','1.0.2','yes'),(134,'stellarwp_telemetry_last_send','','yes'),(135,'stellarwp_telemetry','a:1:{s:7:\"plugins\";a:3:{s:19:\"the-events-calendar\";a:2:{s:7:\"wp_slug\";s:43:\"the-events-calendar/the-events-calendar.php\";s:5:\"optin\";b:0;}s:13:\"event-tickets\";a:2:{s:7:\"wp_slug\";s:31:\"event-tickets/event-tickets.php\";s:5:\"optin\";b:0;}s:19:\"events-calendar-pro\";a:2:{s:7:\"wp_slug\";s:34:\"events-pro/events-calendar-pro.php\";s:5:\"optin\";b:0;}}}','yes'),(136,'stellarwp_telemetry_the-events-calendar_show_optin','0','yes'),(139,'tribe_last_generate_rewrite_rules','1697535093.6692','yes'),(190,'stellarwp_telemetry_event-tickets_show_optin','0','yes'),(192,'action_scheduler_lock_async-request-runner','66f69ca6a79ba9.98696118|1727438050','yes'),(193,'theme_mods_twentytwentythree','a:1:{s:18:\"custom_css_post_id\";i:-1;}','yes'),(211,'tec_timed_tribe_supports_async_process','','yes'),(225,'tec_timed_events_timezone_update_needed','a:3:{s:3:\"key\";s:29:\"events_timezone_update_needed\";s:5:\"value\";b:0;s:10:\"expiration\";i:1727524328;}','yes'),(229,'recently_activated','a:4:{s:34:\"events-pro/events-calendar-pro.php\";i:1727438043;s:43:\"the-events-calendar/the-events-calendar.php\";i:1727438041;s:31:\"event-tickets/event-tickets.php\";i:1727437980;s:41:\"event-tickets-plus/event-tickets-plus.php\";i:1727437980;}','yes'),(243,'tribe_feature_support_check_lock','1','yes'),(249,'pue_install_key_event_automator','5f53261eb16f7ddf4fe24b69918c8804c82ea817','yes'),(252,'tec_automator_zapier_secret_key','367c29c0d738e48eaf47a9d7095c4768194a4b69380f76d21a352f60523f58a9e28935457ad293a980aab00a09d4411ab749e8734b788b1c50ff99efa709e3cc7daff0ba3828e2777f1a2a2e4c56346ed9c5ab55b3e20f559a9b6d6b1a47565d75182c17ce62df7d6be8a6e3856d5edba8ab4d4354ce565bd8506afecafa9da4','yes'),(253,'tec_automator_power_automate_secret_key','35bfd05098069168616a1929f965a740cc86560622f12b2876bd277ab61047cabb42f84246ba5c852e6fce68783b2087739d0d894a8f67b089faf1cb12ff98dfc8f8e407bc041418481c9372b07b118bbd5faf59485f27a219d696d613782489376e4e27de90e0f9372f67b3b1a954044d5795756796c9353e322efe284f17b8','yes'),(265,'_transient_wp_core_block_css_files','a:2:{s:7:\"version\";s:3:\"6.5\";s:5:\"files\";a:500:{i:0;s:23:\"archives/editor-rtl.css\";i:1;s:27:\"archives/editor-rtl.min.css\";i:2;s:19:\"archives/editor.css\";i:3;s:23:\"archives/editor.min.css\";i:4;s:22:\"archives/style-rtl.css\";i:5;s:26:\"archives/style-rtl.min.css\";i:6;s:18:\"archives/style.css\";i:7;s:22:\"archives/style.min.css\";i:8;s:20:\"audio/editor-rtl.css\";i:9;s:24:\"audio/editor-rtl.min.css\";i:10;s:16:\"audio/editor.css\";i:11;s:20:\"audio/editor.min.css\";i:12;s:19:\"audio/style-rtl.css\";i:13;s:23:\"audio/style-rtl.min.css\";i:14;s:15:\"audio/style.css\";i:15;s:19:\"audio/style.min.css\";i:16;s:19:\"audio/theme-rtl.css\";i:17;s:23:\"audio/theme-rtl.min.css\";i:18;s:15:\"audio/theme.css\";i:19;s:19:\"audio/theme.min.css\";i:20;s:21:\"avatar/editor-rtl.css\";i:21;s:25:\"avatar/editor-rtl.min.css\";i:22;s:17:\"avatar/editor.css\";i:23;s:21:\"avatar/editor.min.css\";i:24;s:20:\"avatar/style-rtl.css\";i:25;s:24:\"avatar/style-rtl.min.css\";i:26;s:16:\"avatar/style.css\";i:27;s:20:\"avatar/style.min.css\";i:28;s:20:\"block/editor-rtl.css\";i:29;s:24:\"block/editor-rtl.min.css\";i:30;s:16:\"block/editor.css\";i:31;s:20:\"block/editor.min.css\";i:32;s:21:\"button/editor-rtl.css\";i:33;s:25:\"button/editor-rtl.min.css\";i:34;s:17:\"button/editor.css\";i:35;s:21:\"button/editor.min.css\";i:36;s:20:\"button/style-rtl.css\";i:37;s:24:\"button/style-rtl.min.css\";i:38;s:16:\"button/style.css\";i:39;s:20:\"button/style.min.css\";i:40;s:22:\"buttons/editor-rtl.css\";i:41;s:26:\"buttons/editor-rtl.min.css\";i:42;s:18:\"buttons/editor.css\";i:43;s:22:\"buttons/editor.min.css\";i:44;s:21:\"buttons/style-rtl.css\";i:45;s:25:\"buttons/style-rtl.min.css\";i:46;s:17:\"buttons/style.css\";i:47;s:21:\"buttons/style.min.css\";i:48;s:22:\"calendar/style-rtl.css\";i:49;s:26:\"calendar/style-rtl.min.css\";i:50;s:18:\"calendar/style.css\";i:51;s:22:\"calendar/style.min.css\";i:52;s:25:\"categories/editor-rtl.css\";i:53;s:29:\"categories/editor-rtl.min.css\";i:54;s:21:\"categories/editor.css\";i:55;s:25:\"categories/editor.min.css\";i:56;s:24:\"categories/style-rtl.css\";i:57;s:28:\"categories/style-rtl.min.css\";i:58;s:20:\"categories/style.css\";i:59;s:24:\"categories/style.min.css\";i:60;s:19:\"code/editor-rtl.css\";i:61;s:23:\"code/editor-rtl.min.css\";i:62;s:15:\"code/editor.css\";i:63;s:19:\"code/editor.min.css\";i:64;s:18:\"code/style-rtl.css\";i:65;s:22:\"code/style-rtl.min.css\";i:66;s:14:\"code/style.css\";i:67;s:18:\"code/style.min.css\";i:68;s:18:\"code/theme-rtl.css\";i:69;s:22:\"code/theme-rtl.min.css\";i:70;s:14:\"code/theme.css\";i:71;s:18:\"code/theme.min.css\";i:72;s:22:\"columns/editor-rtl.css\";i:73;s:26:\"columns/editor-rtl.min.css\";i:74;s:18:\"columns/editor.css\";i:75;s:22:\"columns/editor.min.css\";i:76;s:21:\"columns/style-rtl.css\";i:77;s:25:\"columns/style-rtl.min.css\";i:78;s:17:\"columns/style.css\";i:79;s:21:\"columns/style.min.css\";i:80;s:29:\"comment-content/style-rtl.css\";i:81;s:33:\"comment-content/style-rtl.min.css\";i:82;s:25:\"comment-content/style.css\";i:83;s:29:\"comment-content/style.min.css\";i:84;s:30:\"comment-template/style-rtl.css\";i:85;s:34:\"comment-template/style-rtl.min.css\";i:86;s:26:\"comment-template/style.css\";i:87;s:30:\"comment-template/style.min.css\";i:88;s:42:\"comments-pagination-numbers/editor-rtl.css\";i:89;s:46:\"comments-pagination-numbers/editor-rtl.min.css\";i:90;s:38:\"comments-pagination-numbers/editor.css\";i:91;s:42:\"comments-pagination-numbers/editor.min.css\";i:92;s:34:\"comments-pagination/editor-rtl.css\";i:93;s:38:\"comments-pagination/editor-rtl.min.css\";i:94;s:30:\"comments-pagination/editor.css\";i:95;s:34:\"comments-pagination/editor.min.css\";i:96;s:33:\"comments-pagination/style-rtl.css\";i:97;s:37:\"comments-pagination/style-rtl.min.css\";i:98;s:29:\"comments-pagination/style.css\";i:99;s:33:\"comments-pagination/style.min.css\";i:100;s:29:\"comments-title/editor-rtl.css\";i:101;s:33:\"comments-title/editor-rtl.min.css\";i:102;s:25:\"comments-title/editor.css\";i:103;s:29:\"comments-title/editor.min.css\";i:104;s:23:\"comments/editor-rtl.css\";i:105;s:27:\"comments/editor-rtl.min.css\";i:106;s:19:\"comments/editor.css\";i:107;s:23:\"comments/editor.min.css\";i:108;s:22:\"comments/style-rtl.css\";i:109;s:26:\"comments/style-rtl.min.css\";i:110;s:18:\"comments/style.css\";i:111;s:22:\"comments/style.min.css\";i:112;s:20:\"cover/editor-rtl.css\";i:113;s:24:\"cover/editor-rtl.min.css\";i:114;s:16:\"cover/editor.css\";i:115;s:20:\"cover/editor.min.css\";i:116;s:19:\"cover/style-rtl.css\";i:117;s:23:\"cover/style-rtl.min.css\";i:118;s:15:\"cover/style.css\";i:119;s:19:\"cover/style.min.css\";i:120;s:22:\"details/editor-rtl.css\";i:121;s:26:\"details/editor-rtl.min.css\";i:122;s:18:\"details/editor.css\";i:123;s:22:\"details/editor.min.css\";i:124;s:21:\"details/style-rtl.css\";i:125;s:25:\"details/style-rtl.min.css\";i:126;s:17:\"details/style.css\";i:127;s:21:\"details/style.min.css\";i:128;s:20:\"embed/editor-rtl.css\";i:129;s:24:\"embed/editor-rtl.min.css\";i:130;s:16:\"embed/editor.css\";i:131;s:20:\"embed/editor.min.css\";i:132;s:19:\"embed/style-rtl.css\";i:133;s:23:\"embed/style-rtl.min.css\";i:134;s:15:\"embed/style.css\";i:135;s:19:\"embed/style.min.css\";i:136;s:19:\"embed/theme-rtl.css\";i:137;s:23:\"embed/theme-rtl.min.css\";i:138;s:15:\"embed/theme.css\";i:139;s:19:\"embed/theme.min.css\";i:140;s:19:\"file/editor-rtl.css\";i:141;s:23:\"file/editor-rtl.min.css\";i:142;s:15:\"file/editor.css\";i:143;s:19:\"file/editor.min.css\";i:144;s:18:\"file/style-rtl.css\";i:145;s:22:\"file/style-rtl.min.css\";i:146;s:14:\"file/style.css\";i:147;s:18:\"file/style.min.css\";i:148;s:23:\"footnotes/style-rtl.css\";i:149;s:27:\"footnotes/style-rtl.min.css\";i:150;s:19:\"footnotes/style.css\";i:151;s:23:\"footnotes/style.min.css\";i:152;s:23:\"freeform/editor-rtl.css\";i:153;s:27:\"freeform/editor-rtl.min.css\";i:154;s:19:\"freeform/editor.css\";i:155;s:23:\"freeform/editor.min.css\";i:156;s:22:\"gallery/editor-rtl.css\";i:157;s:26:\"gallery/editor-rtl.min.css\";i:158;s:18:\"gallery/editor.css\";i:159;s:22:\"gallery/editor.min.css\";i:160;s:21:\"gallery/style-rtl.css\";i:161;s:25:\"gallery/style-rtl.min.css\";i:162;s:17:\"gallery/style.css\";i:163;s:21:\"gallery/style.min.css\";i:164;s:21:\"gallery/theme-rtl.css\";i:165;s:25:\"gallery/theme-rtl.min.css\";i:166;s:17:\"gallery/theme.css\";i:167;s:21:\"gallery/theme.min.css\";i:168;s:20:\"group/editor-rtl.css\";i:169;s:24:\"group/editor-rtl.min.css\";i:170;s:16:\"group/editor.css\";i:171;s:20:\"group/editor.min.css\";i:172;s:19:\"group/style-rtl.css\";i:173;s:23:\"group/style-rtl.min.css\";i:174;s:15:\"group/style.css\";i:175;s:19:\"group/style.min.css\";i:176;s:19:\"group/theme-rtl.css\";i:177;s:23:\"group/theme-rtl.min.css\";i:178;s:15:\"group/theme.css\";i:179;s:19:\"group/theme.min.css\";i:180;s:21:\"heading/style-rtl.css\";i:181;s:25:\"heading/style-rtl.min.css\";i:182;s:17:\"heading/style.css\";i:183;s:21:\"heading/style.min.css\";i:184;s:19:\"html/editor-rtl.css\";i:185;s:23:\"html/editor-rtl.min.css\";i:186;s:15:\"html/editor.css\";i:187;s:19:\"html/editor.min.css\";i:188;s:20:\"image/editor-rtl.css\";i:189;s:24:\"image/editor-rtl.min.css\";i:190;s:16:\"image/editor.css\";i:191;s:20:\"image/editor.min.css\";i:192;s:19:\"image/style-rtl.css\";i:193;s:23:\"image/style-rtl.min.css\";i:194;s:15:\"image/style.css\";i:195;s:19:\"image/style.min.css\";i:196;s:19:\"image/theme-rtl.css\";i:197;s:23:\"image/theme-rtl.min.css\";i:198;s:15:\"image/theme.css\";i:199;s:19:\"image/theme.min.css\";i:200;s:29:\"latest-comments/style-rtl.css\";i:201;s:33:\"latest-comments/style-rtl.min.css\";i:202;s:25:\"latest-comments/style.css\";i:203;s:29:\"latest-comments/style.min.css\";i:204;s:27:\"latest-posts/editor-rtl.css\";i:205;s:31:\"latest-posts/editor-rtl.min.css\";i:206;s:23:\"latest-posts/editor.css\";i:207;s:27:\"latest-posts/editor.min.css\";i:208;s:26:\"latest-posts/style-rtl.css\";i:209;s:30:\"latest-posts/style-rtl.min.css\";i:210;s:22:\"latest-posts/style.css\";i:211;s:26:\"latest-posts/style.min.css\";i:212;s:18:\"list/style-rtl.css\";i:213;s:22:\"list/style-rtl.min.css\";i:214;s:14:\"list/style.css\";i:215;s:18:\"list/style.min.css\";i:216;s:25:\"media-text/editor-rtl.css\";i:217;s:29:\"media-text/editor-rtl.min.css\";i:218;s:21:\"media-text/editor.css\";i:219;s:25:\"media-text/editor.min.css\";i:220;s:24:\"media-text/style-rtl.css\";i:221;s:28:\"media-text/style-rtl.min.css\";i:222;s:20:\"media-text/style.css\";i:223;s:24:\"media-text/style.min.css\";i:224;s:19:\"more/editor-rtl.css\";i:225;s:23:\"more/editor-rtl.min.css\";i:226;s:15:\"more/editor.css\";i:227;s:19:\"more/editor.min.css\";i:228;s:30:\"navigation-link/editor-rtl.css\";i:229;s:34:\"navigation-link/editor-rtl.min.css\";i:230;s:26:\"navigation-link/editor.css\";i:231;s:30:\"navigation-link/editor.min.css\";i:232;s:29:\"navigation-link/style-rtl.css\";i:233;s:33:\"navigation-link/style-rtl.min.css\";i:234;s:25:\"navigation-link/style.css\";i:235;s:29:\"navigation-link/style.min.css\";i:236;s:33:\"navigation-submenu/editor-rtl.css\";i:237;s:37:\"navigation-submenu/editor-rtl.min.css\";i:238;s:29:\"navigation-submenu/editor.css\";i:239;s:33:\"navigation-submenu/editor.min.css\";i:240;s:25:\"navigation/editor-rtl.css\";i:241;s:29:\"navigation/editor-rtl.min.css\";i:242;s:21:\"navigation/editor.css\";i:243;s:25:\"navigation/editor.min.css\";i:244;s:24:\"navigation/style-rtl.css\";i:245;s:28:\"navigation/style-rtl.min.css\";i:246;s:20:\"navigation/style.css\";i:247;s:24:\"navigation/style.min.css\";i:248;s:23:\"nextpage/editor-rtl.css\";i:249;s:27:\"nextpage/editor-rtl.min.css\";i:250;s:19:\"nextpage/editor.css\";i:251;s:23:\"nextpage/editor.min.css\";i:252;s:24:\"page-list/editor-rtl.css\";i:253;s:28:\"page-list/editor-rtl.min.css\";i:254;s:20:\"page-list/editor.css\";i:255;s:24:\"page-list/editor.min.css\";i:256;s:23:\"page-list/style-rtl.css\";i:257;s:27:\"page-list/style-rtl.min.css\";i:258;s:19:\"page-list/style.css\";i:259;s:23:\"page-list/style.min.css\";i:260;s:24:\"paragraph/editor-rtl.css\";i:261;s:28:\"paragraph/editor-rtl.min.css\";i:262;s:20:\"paragraph/editor.css\";i:263;s:24:\"paragraph/editor.min.css\";i:264;s:23:\"paragraph/style-rtl.css\";i:265;s:27:\"paragraph/style-rtl.min.css\";i:266;s:19:\"paragraph/style.css\";i:267;s:23:\"paragraph/style.min.css\";i:268;s:25:\"post-author/style-rtl.css\";i:269;s:29:\"post-author/style-rtl.min.css\";i:270;s:21:\"post-author/style.css\";i:271;s:25:\"post-author/style.min.css\";i:272;s:33:\"post-comments-form/editor-rtl.css\";i:273;s:37:\"post-comments-form/editor-rtl.min.css\";i:274;s:29:\"post-comments-form/editor.css\";i:275;s:33:\"post-comments-form/editor.min.css\";i:276;s:32:\"post-comments-form/style-rtl.css\";i:277;s:36:\"post-comments-form/style-rtl.min.css\";i:278;s:28:\"post-comments-form/style.css\";i:279;s:32:\"post-comments-form/style.min.css\";i:280;s:27:\"post-content/editor-rtl.css\";i:281;s:31:\"post-content/editor-rtl.min.css\";i:282;s:23:\"post-content/editor.css\";i:283;s:27:\"post-content/editor.min.css\";i:284;s:23:\"post-date/style-rtl.css\";i:285;s:27:\"post-date/style-rtl.min.css\";i:286;s:19:\"post-date/style.css\";i:287;s:23:\"post-date/style.min.css\";i:288;s:27:\"post-excerpt/editor-rtl.css\";i:289;s:31:\"post-excerpt/editor-rtl.min.css\";i:290;s:23:\"post-excerpt/editor.css\";i:291;s:27:\"post-excerpt/editor.min.css\";i:292;s:26:\"post-excerpt/style-rtl.css\";i:293;s:30:\"post-excerpt/style-rtl.min.css\";i:294;s:22:\"post-excerpt/style.css\";i:295;s:26:\"post-excerpt/style.min.css\";i:296;s:34:\"post-featured-image/editor-rtl.css\";i:297;s:38:\"post-featured-image/editor-rtl.min.css\";i:298;s:30:\"post-featured-image/editor.css\";i:299;s:34:\"post-featured-image/editor.min.css\";i:300;s:33:\"post-featured-image/style-rtl.css\";i:301;s:37:\"post-featured-image/style-rtl.min.css\";i:302;s:29:\"post-featured-image/style.css\";i:303;s:33:\"post-featured-image/style.min.css\";i:304;s:34:\"post-navigation-link/style-rtl.css\";i:305;s:38:\"post-navigation-link/style-rtl.min.css\";i:306;s:30:\"post-navigation-link/style.css\";i:307;s:34:\"post-navigation-link/style.min.css\";i:308;s:28:\"post-template/editor-rtl.css\";i:309;s:32:\"post-template/editor-rtl.min.css\";i:310;s:24:\"post-template/editor.css\";i:311;s:28:\"post-template/editor.min.css\";i:312;s:27:\"post-template/style-rtl.css\";i:313;s:31:\"post-template/style-rtl.min.css\";i:314;s:23:\"post-template/style.css\";i:315;s:27:\"post-template/style.min.css\";i:316;s:24:\"post-terms/style-rtl.css\";i:317;s:28:\"post-terms/style-rtl.min.css\";i:318;s:20:\"post-terms/style.css\";i:319;s:24:\"post-terms/style.min.css\";i:320;s:24:\"post-title/style-rtl.css\";i:321;s:28:\"post-title/style-rtl.min.css\";i:322;s:20:\"post-title/style.css\";i:323;s:24:\"post-title/style.min.css\";i:324;s:26:\"preformatted/style-rtl.css\";i:325;s:30:\"preformatted/style-rtl.min.css\";i:326;s:22:\"preformatted/style.css\";i:327;s:26:\"preformatted/style.min.css\";i:328;s:24:\"pullquote/editor-rtl.css\";i:329;s:28:\"pullquote/editor-rtl.min.css\";i:330;s:20:\"pullquote/editor.css\";i:331;s:24:\"pullquote/editor.min.css\";i:332;s:23:\"pullquote/style-rtl.css\";i:333;s:27:\"pullquote/style-rtl.min.css\";i:334;s:19:\"pullquote/style.css\";i:335;s:23:\"pullquote/style.min.css\";i:336;s:23:\"pullquote/theme-rtl.css\";i:337;s:27:\"pullquote/theme-rtl.min.css\";i:338;s:19:\"pullquote/theme.css\";i:339;s:23:\"pullquote/theme.min.css\";i:340;s:39:\"query-pagination-numbers/editor-rtl.css\";i:341;s:43:\"query-pagination-numbers/editor-rtl.min.css\";i:342;s:35:\"query-pagination-numbers/editor.css\";i:343;s:39:\"query-pagination-numbers/editor.min.css\";i:344;s:31:\"query-pagination/editor-rtl.css\";i:345;s:35:\"query-pagination/editor-rtl.min.css\";i:346;s:27:\"query-pagination/editor.css\";i:347;s:31:\"query-pagination/editor.min.css\";i:348;s:30:\"query-pagination/style-rtl.css\";i:349;s:34:\"query-pagination/style-rtl.min.css\";i:350;s:26:\"query-pagination/style.css\";i:351;s:30:\"query-pagination/style.min.css\";i:352;s:25:\"query-title/style-rtl.css\";i:353;s:29:\"query-title/style-rtl.min.css\";i:354;s:21:\"query-title/style.css\";i:355;s:25:\"query-title/style.min.css\";i:356;s:20:\"query/editor-rtl.css\";i:357;s:24:\"query/editor-rtl.min.css\";i:358;s:16:\"query/editor.css\";i:359;s:20:\"query/editor.min.css\";i:360;s:19:\"quote/style-rtl.css\";i:361;s:23:\"quote/style-rtl.min.css\";i:362;s:15:\"quote/style.css\";i:363;s:19:\"quote/style.min.css\";i:364;s:19:\"quote/theme-rtl.css\";i:365;s:23:\"quote/theme-rtl.min.css\";i:366;s:15:\"quote/theme.css\";i:367;s:19:\"quote/theme.min.css\";i:368;s:23:\"read-more/style-rtl.css\";i:369;s:27:\"read-more/style-rtl.min.css\";i:370;s:19:\"read-more/style.css\";i:371;s:23:\"read-more/style.min.css\";i:372;s:18:\"rss/editor-rtl.css\";i:373;s:22:\"rss/editor-rtl.min.css\";i:374;s:14:\"rss/editor.css\";i:375;s:18:\"rss/editor.min.css\";i:376;s:17:\"rss/style-rtl.css\";i:377;s:21:\"rss/style-rtl.min.css\";i:378;s:13:\"rss/style.css\";i:379;s:17:\"rss/style.min.css\";i:380;s:21:\"search/editor-rtl.css\";i:381;s:25:\"search/editor-rtl.min.css\";i:382;s:17:\"search/editor.css\";i:383;s:21:\"search/editor.min.css\";i:384;s:20:\"search/style-rtl.css\";i:385;s:24:\"search/style-rtl.min.css\";i:386;s:16:\"search/style.css\";i:387;s:20:\"search/style.min.css\";i:388;s:20:\"search/theme-rtl.css\";i:389;s:24:\"search/theme-rtl.min.css\";i:390;s:16:\"search/theme.css\";i:391;s:20:\"search/theme.min.css\";i:392;s:24:\"separator/editor-rtl.css\";i:393;s:28:\"separator/editor-rtl.min.css\";i:394;s:20:\"separator/editor.css\";i:395;s:24:\"separator/editor.min.css\";i:396;s:23:\"separator/style-rtl.css\";i:397;s:27:\"separator/style-rtl.min.css\";i:398;s:19:\"separator/style.css\";i:399;s:23:\"separator/style.min.css\";i:400;s:23:\"separator/theme-rtl.css\";i:401;s:27:\"separator/theme-rtl.min.css\";i:402;s:19:\"separator/theme.css\";i:403;s:23:\"separator/theme.min.css\";i:404;s:24:\"shortcode/editor-rtl.css\";i:405;s:28:\"shortcode/editor-rtl.min.css\";i:406;s:20:\"shortcode/editor.css\";i:407;s:24:\"shortcode/editor.min.css\";i:408;s:24:\"site-logo/editor-rtl.css\";i:409;s:28:\"site-logo/editor-rtl.min.css\";i:410;s:20:\"site-logo/editor.css\";i:411;s:24:\"site-logo/editor.min.css\";i:412;s:23:\"site-logo/style-rtl.css\";i:413;s:27:\"site-logo/style-rtl.min.css\";i:414;s:19:\"site-logo/style.css\";i:415;s:23:\"site-logo/style.min.css\";i:416;s:27:\"site-tagline/editor-rtl.css\";i:417;s:31:\"site-tagline/editor-rtl.min.css\";i:418;s:23:\"site-tagline/editor.css\";i:419;s:27:\"site-tagline/editor.min.css\";i:420;s:25:\"site-title/editor-rtl.css\";i:421;s:29:\"site-title/editor-rtl.min.css\";i:422;s:21:\"site-title/editor.css\";i:423;s:25:\"site-title/editor.min.css\";i:424;s:24:\"site-title/style-rtl.css\";i:425;s:28:\"site-title/style-rtl.min.css\";i:426;s:20:\"site-title/style.css\";i:427;s:24:\"site-title/style.min.css\";i:428;s:26:\"social-link/editor-rtl.css\";i:429;s:30:\"social-link/editor-rtl.min.css\";i:430;s:22:\"social-link/editor.css\";i:431;s:26:\"social-link/editor.min.css\";i:432;s:27:\"social-links/editor-rtl.css\";i:433;s:31:\"social-links/editor-rtl.min.css\";i:434;s:23:\"social-links/editor.css\";i:435;s:27:\"social-links/editor.min.css\";i:436;s:26:\"social-links/style-rtl.css\";i:437;s:30:\"social-links/style-rtl.min.css\";i:438;s:22:\"social-links/style.css\";i:439;s:26:\"social-links/style.min.css\";i:440;s:21:\"spacer/editor-rtl.css\";i:441;s:25:\"spacer/editor-rtl.min.css\";i:442;s:17:\"spacer/editor.css\";i:443;s:21:\"spacer/editor.min.css\";i:444;s:20:\"spacer/style-rtl.css\";i:445;s:24:\"spacer/style-rtl.min.css\";i:446;s:16:\"spacer/style.css\";i:447;s:20:\"spacer/style.min.css\";i:448;s:20:\"table/editor-rtl.css\";i:449;s:24:\"table/editor-rtl.min.css\";i:450;s:16:\"table/editor.css\";i:451;s:20:\"table/editor.min.css\";i:452;s:19:\"table/style-rtl.css\";i:453;s:23:\"table/style-rtl.min.css\";i:454;s:15:\"table/style.css\";i:455;s:19:\"table/style.min.css\";i:456;s:19:\"table/theme-rtl.css\";i:457;s:23:\"table/theme-rtl.min.css\";i:458;s:15:\"table/theme.css\";i:459;s:19:\"table/theme.min.css\";i:460;s:23:\"tag-cloud/style-rtl.css\";i:461;s:27:\"tag-cloud/style-rtl.min.css\";i:462;s:19:\"tag-cloud/style.css\";i:463;s:23:\"tag-cloud/style.min.css\";i:464;s:28:\"template-part/editor-rtl.css\";i:465;s:32:\"template-part/editor-rtl.min.css\";i:466;s:24:\"template-part/editor.css\";i:467;s:28:\"template-part/editor.min.css\";i:468;s:27:\"template-part/theme-rtl.css\";i:469;s:31:\"template-part/theme-rtl.min.css\";i:470;s:23:\"template-part/theme.css\";i:471;s:27:\"template-part/theme.min.css\";i:472;s:30:\"term-description/style-rtl.css\";i:473;s:34:\"term-description/style-rtl.min.css\";i:474;s:26:\"term-description/style.css\";i:475;s:30:\"term-description/style.min.css\";i:476;s:27:\"text-columns/editor-rtl.css\";i:477;s:31:\"text-columns/editor-rtl.min.css\";i:478;s:23:\"text-columns/editor.css\";i:479;s:27:\"text-columns/editor.min.css\";i:480;s:26:\"text-columns/style-rtl.css\";i:481;s:30:\"text-columns/style-rtl.min.css\";i:482;s:22:\"text-columns/style.css\";i:483;s:26:\"text-columns/style.min.css\";i:484;s:19:\"verse/style-rtl.css\";i:485;s:23:\"verse/style-rtl.min.css\";i:486;s:15:\"verse/style.css\";i:487;s:19:\"verse/style.min.css\";i:488;s:20:\"video/editor-rtl.css\";i:489;s:24:\"video/editor-rtl.min.css\";i:490;s:16:\"video/editor.css\";i:491;s:20:\"video/editor.min.css\";i:492;s:19:\"video/style-rtl.css\";i:493;s:23:\"video/style-rtl.min.css\";i:494;s:15:\"video/style.css\";i:495;s:19:\"video/style.min.css\";i:496;s:19:\"video/theme-rtl.css\";i:497;s:23:\"video/theme-rtl.min.css\";i:498;s:15:\"video/theme.css\";i:499;s:19:\"video/theme.min.css\";}}','yes'),(270,'permalink_structure','','yes'),(272,'wp_attachment_pages_enabled','1','yes'),(273,'db_upgraded','1','yes'),(337,'wpdg_specific_version_name','6.2.3','yes'),(338,'wpdg_download_url','','yes'),(339,'wpdg_edit_download_url','','yes'),(401,'_site_transient_update_plugins','O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1736344977;s:8:\"response\";a:1:{s:31:\"event-tickets/event-tickets.php\";O:8:\"stdClass\":13:{s:2:\"id\";s:27:\"w.org/plugins/event-tickets\";s:4:\"slug\";s:13:\"event-tickets\";s:6:\"plugin\";s:31:\"event-tickets/event-tickets.php\";s:11:\"new_version\";s:8:\"5.18.0.1\";s:3:\"url\";s:44:\"https://wordpress.org/plugins/event-tickets/\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/plugin/event-tickets.5.18.0.1.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:58:\"https://ps.w.org/event-tickets/assets/icon.svg?rev=2259340\";s:3:\"svg\";s:58:\"https://ps.w.org/event-tickets/assets/icon.svg?rev=2259340\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:69:\"https://ps.w.org/event-tickets/assets/banner-1544x500.png?rev=2257626\";s:2:\"1x\";s:68:\"https://ps.w.org/event-tickets/assets/banner-772x250.png?rev=2257626\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.5\";s:6:\"tested\";s:5:\"6.7.1\";s:12:\"requires_php\";s:3:\"7.4\";s:16:\"requires_plugins\";a:0:{}}}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:7:{s:49:\"easy-digital-downloads/easy-digital-downloads.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:36:\"w.org/plugins/easy-digital-downloads\";s:4:\"slug\";s:22:\"easy-digital-downloads\";s:6:\"plugin\";s:49:\"easy-digital-downloads/easy-digital-downloads.php\";s:11:\"new_version\";s:7:\"3.3.5.2\";s:3:\"url\";s:53:\"https://wordpress.org/plugins/easy-digital-downloads/\";s:7:\"package\";s:73:\"https://downloads.wordpress.org/plugin/easy-digital-downloads.3.3.5.2.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:66:\"https://ps.w.org/easy-digital-downloads/assets/icon.svg?rev=971968\";s:3:\"svg\";s:66:\"https://ps.w.org/easy-digital-downloads/assets/icon.svg?rev=971968\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:78:\"https://ps.w.org/easy-digital-downloads/assets/banner-1544x500.png?rev=2636140\";s:2:\"1x\";s:77:\"https://ps.w.org/easy-digital-downloads/assets/banner-772x250.png?rev=2636140\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.0\";}s:31:\"query-monitor/query-monitor.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:27:\"w.org/plugins/query-monitor\";s:4:\"slug\";s:13:\"query-monitor\";s:6:\"plugin\";s:31:\"query-monitor/query-monitor.php\";s:11:\"new_version\";s:6:\"3.17.0\";s:3:\"url\";s:44:\"https://wordpress.org/plugins/query-monitor/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/query-monitor.3.17.0.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:58:\"https://ps.w.org/query-monitor/assets/icon.svg?rev=2994095\";s:3:\"svg\";s:58:\"https://ps.w.org/query-monitor/assets/icon.svg?rev=2994095\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:69:\"https://ps.w.org/query-monitor/assets/banner-1544x500.png?rev=2870124\";s:2:\"1x\";s:68:\"https://ps.w.org/query-monitor/assets/banner-772x250.png?rev=2457098\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.9\";}s:43:\"the-events-calendar/the-events-calendar.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:33:\"w.org/plugins/the-events-calendar\";s:4:\"slug\";s:19:\"the-events-calendar\";s:6:\"plugin\";s:43:\"the-events-calendar/the-events-calendar.php\";s:11:\"new_version\";s:5:\"6.9.0\";s:3:\"url\";s:50:\"https://wordpress.org/plugins/the-events-calendar/\";s:7:\"package\";s:68:\"https://downloads.wordpress.org/plugin/the-events-calendar.6.9.0.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:72:\"https://ps.w.org/the-events-calendar/assets/icon-256x256.gif?rev=2516440\";s:2:\"1x\";s:72:\"https://ps.w.org/the-events-calendar/assets/icon-128x128.gif?rev=2516440\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:75:\"https://ps.w.org/the-events-calendar/assets/banner-1544x500.png?rev=2257622\";s:2:\"1x\";s:74:\"https://ps.w.org/the-events-calendar/assets/banner-772x250.png?rev=2257622\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.5\";}s:33:\"user-switching/user-switching.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:28:\"w.org/plugins/user-switching\";s:4:\"slug\";s:14:\"user-switching\";s:6:\"plugin\";s:33:\"user-switching/user-switching.php\";s:11:\"new_version\";s:5:\"1.9.1\";s:3:\"url\";s:45:\"https://wordpress.org/plugins/user-switching/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/user-switching.1.9.1.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:59:\"https://ps.w.org/user-switching/assets/icon.svg?rev=3193956\";s:3:\"svg\";s:59:\"https://ps.w.org/user-switching/assets/icon.svg?rev=3193956\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:70:\"https://ps.w.org/user-switching/assets/banner-1544x500.png?rev=2204929\";s:2:\"1x\";s:69:\"https://ps.w.org/user-switching/assets/banner-772x250.png?rev=2204929\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.9\";}s:27:\"woocommerce/woocommerce.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:25:\"w.org/plugins/woocommerce\";s:4:\"slug\";s:11:\"woocommerce\";s:6:\"plugin\";s:27:\"woocommerce/woocommerce.php\";s:11:\"new_version\";s:5:\"9.5.1\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/woocommerce/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/woocommerce.9.5.1.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/woocommerce/assets/icon-256x256.gif?rev=2869506\";s:2:\"1x\";s:64:\"https://ps.w.org/woocommerce/assets/icon-128x128.gif?rev=2869506\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/woocommerce/assets/banner-1544x500.png?rev=3000842\";s:2:\"1x\";s:66:\"https://ps.w.org/woocommerce/assets/banner-772x250.png?rev=3000842\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.6\";}s:35:\"wp-mail-logging/wp-mail-logging.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:29:\"w.org/plugins/wp-mail-logging\";s:4:\"slug\";s:15:\"wp-mail-logging\";s:6:\"plugin\";s:35:\"wp-mail-logging/wp-mail-logging.php\";s:11:\"new_version\";s:6:\"1.14.0\";s:3:\"url\";s:46:\"https://wordpress.org/plugins/wp-mail-logging/\";s:7:\"package\";s:65:\"https://downloads.wordpress.org/plugin/wp-mail-logging.1.14.0.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:68:\"https://ps.w.org/wp-mail-logging/assets/icon-256x256.jpg?rev=2562296\";s:2:\"1x\";s:68:\"https://ps.w.org/wp-mail-logging/assets/icon-128x128.jpg?rev=2562296\";}s:7:\"banners\";a:1:{s:2:\"1x\";s:70:\"https://ps.w.org/wp-mail-logging/assets/banner-772x250.jpg?rev=2562296\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.0\";}s:27:\"wp-rollback/wp-rollback.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:25:\"w.org/plugins/wp-rollback\";s:4:\"slug\";s:11:\"wp-rollback\";s:6:\"plugin\";s:27:\"wp-rollback/wp-rollback.php\";s:11:\"new_version\";s:5:\"2.0.7\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/wp-rollback/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/wp-rollback.2.0.7.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/wp-rollback/assets/icon-256x256.jpg?rev=3014868\";s:2:\"1x\";s:64:\"https://ps.w.org/wp-rollback/assets/icon-128x128.jpg?rev=3014868\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/wp-rollback/assets/banner-1544x500.jpg?rev=3014868\";s:2:\"1x\";s:66:\"https://ps.w.org/wp-rollback/assets/banner-772x250.jpg?rev=3014872\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.0\";}}s:7:\"checked\";a:11:{s:49:\"easy-digital-downloads/easy-digital-downloads.php\";s:7:\"3.3.5.2\";s:31:\"event-tickets/event-tickets.php\";s:6:\"5.18.0\";s:41:\"event-tickets-plus/event-tickets-plus.php\";s:5:\"6.1.2\";s:31:\"query-monitor/query-monitor.php\";s:6:\"3.17.0\";s:43:\"the-events-calendar/the-events-calendar.php\";s:5:\"6.9.0\";s:38:\"tec-labs-remove-past-events/plugin.php\";s:5:\"1.2.2\";s:34:\"events-pro/events-calendar-pro.php\";s:5:\"7.3.1\";s:33:\"user-switching/user-switching.php\";s:5:\"1.9.1\";s:27:\"woocommerce/woocommerce.php\";s:5:\"9.5.1\";s:35:\"wp-mail-logging/wp-mail-logging.php\";s:6:\"1.14.0\";s:27:\"wp-rollback/wp-rollback.php\";s:5:\"2.0.7\";}}','no'),(419,'widget_tribe-widget-event-countdown','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(420,'widget_tribe-widget-featured-venue','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(421,'widget_tribe-widget-events-month','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(422,'widget_tribe-widget-events-week','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(423,'tec_ct1_series_relationship_table_schema_version','1.0.0','yes'),(424,'tec_ct1_events_field_schema_version','1.0.1','yes'),(425,'tec_ct1_occurrences_field_schema_version','1.0.1','yes'),(428,'tec_timed_tec_custom_tables_v1_ecp_initialized','a:3:{s:3:\"key\";s:36:\"tec_custom_tables_v1_ecp_initialized\";s:5:\"value\";i:1;s:10:\"expiration\";i:1727524326;}','yes'),(431,'stellarwp_telemetry_events-calendar-pro_show_optin','0','yes'),(436,'external_updates-events-calendar-pro','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1727438041;s:14:\"checkedVersion\";s:5:\"7.1.0\";s:6:\"update\";N;}','no'),(437,'tribe_pue_key_notices','a:0:{}','yes'),(445,'_transient_tec_tickets_commerce_setup_stripe_webhook','1','yes'),(456,'tec_timed_events_is_rest_api_blocked','a:3:{s:3:\"key\";s:26:\"events_is_rest_api_blocked\";s:5:\"value\";s:59:\"http://localhost:8888/index.php?rest_route=/tribe/events/v1\";s:10:\"expiration\";i:1727610770;}','yes'),(476,'stellar_schema_version_tec-ft-ticket-groups','1.0.0','yes'),(477,'stellar_schema_version_tec-ft-posts-and-ticket-groups','1.0.0','yes'),(480,'external_updates-event-tickets-plus','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1727437980;s:14:\"checkedVersion\";s:5:\"6.0.4\";s:6:\"update\";N;}','no'),(481,'admin_email_lifespan','1742989968','yes'),(492,'recovery_keys','a:0:{}','yes'),(496,'finished_updating_comment_type','1','yes'),(497,'https_detection_errors','a:1:{s:20:\"https_request_failed\";a:1:{i:0;s:21:\"HTTPS request failed.\";}}','yes'),(498,'rewrite_rules','','yes'),(499,'_transient_health-check-site-status-result','{\"good\":13,\"recommended\":6,\"critical\":4}','yes'),(513,'_site_transient_update_themes','O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1736344939;s:7:\"checked\";a:6:{s:12:\"twentytwenty\";s:3:\"2.8\";s:16:\"twentytwentyfive\";s:3:\"1.0\";s:16:\"twentytwentyfour\";s:3:\"1.3\";s:15:\"twentytwentyone\";s:3:\"2.4\";s:17:\"twentytwentythree\";s:3:\"1.6\";s:15:\"twentytwentytwo\";s:3:\"1.9\";}s:8:\"response\";a:0:{}s:9:\"no_update\";a:6:{s:12:\"twentytwenty\";a:6:{s:5:\"theme\";s:12:\"twentytwenty\";s:11:\"new_version\";s:3:\"2.8\";s:3:\"url\";s:42:\"https://wordpress.org/themes/twentytwenty/\";s:7:\"package\";s:58:\"https://downloads.wordpress.org/theme/twentytwenty.2.8.zip\";s:8:\"requires\";s:3:\"4.7\";s:12:\"requires_php\";s:5:\"5.2.4\";}s:16:\"twentytwentyfive\";a:6:{s:5:\"theme\";s:16:\"twentytwentyfive\";s:11:\"new_version\";s:3:\"1.0\";s:3:\"url\";s:46:\"https://wordpress.org/themes/twentytwentyfive/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/theme/twentytwentyfive.1.0.zip\";s:8:\"requires\";s:3:\"6.7\";s:12:\"requires_php\";s:3:\"7.2\";}s:16:\"twentytwentyfour\";a:6:{s:5:\"theme\";s:16:\"twentytwentyfour\";s:11:\"new_version\";s:3:\"1.3\";s:3:\"url\";s:46:\"https://wordpress.org/themes/twentytwentyfour/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/theme/twentytwentyfour.1.3.zip\";s:8:\"requires\";s:3:\"6.4\";s:12:\"requires_php\";s:3:\"7.0\";}s:15:\"twentytwentyone\";a:6:{s:5:\"theme\";s:15:\"twentytwentyone\";s:11:\"new_version\";s:3:\"2.4\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentyone/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentyone.2.4.zip\";s:8:\"requires\";s:3:\"5.3\";s:12:\"requires_php\";s:3:\"5.6\";}s:17:\"twentytwentythree\";a:6:{s:5:\"theme\";s:17:\"twentytwentythree\";s:11:\"new_version\";s:3:\"1.6\";s:3:\"url\";s:47:\"https://wordpress.org/themes/twentytwentythree/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/theme/twentytwentythree.1.6.zip\";s:8:\"requires\";s:3:\"6.1\";s:12:\"requires_php\";s:3:\"5.6\";}s:15:\"twentytwentytwo\";a:6:{s:5:\"theme\";s:15:\"twentytwentytwo\";s:11:\"new_version\";s:3:\"1.9\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentytwo/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentytwo.1.9.zip\";s:8:\"requires\";s:3:\"5.9\";s:12:\"requires_php\";s:3:\"5.6\";}}s:12:\"translations\";a:0:{}}','off'),(514,'_site_transient_timeout_wp_theme_files_patterns-df909b1065e8ede02011fb6511f00e22','1736346726','off'),(515,'_site_transient_wp_theme_files_patterns-df909b1065e8ede02011fb6511f00e22','a:2:{s:7:\"version\";s:3:\"1.6\";s:8:\"patterns\";a:7:{s:18:\"call-to-action.php\";a:6:{s:5:\"title\";s:14:\"Call to action\";s:4:\"slug\";s:21:\"twentytwentythree/cta\";s:11:\"description\";s:52:\"Left-aligned text with a CTA button and a separator.\";s:10:\"categories\";a:1:{i:0;s:8:\"featured\";}s:8:\"keywords\";a:3:{i:0;s:4:\"Call\";i:1;s:2:\"to\";i:2;s:6:\"action\";}s:10:\"blockTypes\";a:1:{i:0;s:12:\"core/buttons\";}}s:18:\"footer-default.php\";a:5:{s:5:\"title\";s:14:\"Default Footer\";s:4:\"slug\";s:32:\"twentytwentythree/footer-default\";s:11:\"description\";s:48:\"Footer with site title and powered by WordPress.\";s:10:\"categories\";a:1:{i:0;s:6:\"footer\";}s:10:\"blockTypes\";a:1:{i:0;s:25:\"core/template-part/footer\";}}s:14:\"hidden-404.php\";a:4:{s:5:\"title\";s:10:\"Hidden 404\";s:4:\"slug\";s:28:\"twentytwentythree/hidden-404\";s:11:\"description\";s:0:\"\";s:8:\"inserter\";b:0;}s:19:\"hidden-comments.php\";a:4:{s:5:\"title\";s:15:\"Hidden Comments\";s:4:\"slug\";s:33:\"twentytwentythree/hidden-comments\";s:11:\"description\";s:0:\"\";s:8:\"inserter\";b:0;}s:18:\"hidden-heading.php\";a:4:{s:5:\"title\";s:27:\"Hidden Heading for Homepage\";s:4:\"slug\";s:32:\"twentytwentythree/hidden-heading\";s:11:\"description\";s:0:\"\";s:8:\"inserter\";b:0;}s:21:\"hidden-no-results.php\";a:4:{s:5:\"title\";s:25:\"Hidden No Results Content\";s:4:\"slug\";s:43:\"twentytwentythree/hidden-no-results-content\";s:11:\"description\";s:0:\"\";s:8:\"inserter\";b:0;}s:13:\"post-meta.php\";a:6:{s:5:\"title\";s:9:\"Post Meta\";s:4:\"slug\";s:27:\"twentytwentythree/post-meta\";s:11:\"description\";s:48:\"Post meta information with separator on the top.\";s:10:\"categories\";a:1:{i:0;s:5:\"query\";}s:8:\"keywords\";a:2:{i:0;s:4:\"post\";i:1;s:4:\"meta\";}s:10:\"blockTypes\";a:1:{i:0;s:28:\"core/template-part/post-meta\";}}}}','off'),(519,'_site_transient_timeout_theme_roots','1736346738','off'),(520,'_site_transient_theme_roots','a:6:{s:12:\"twentytwenty\";s:7:\"/themes\";s:16:\"twentytwentyfive\";s:7:\"/themes\";s:16:\"twentytwentyfour\";s:7:\"/themes\";s:15:\"twentytwentyone\";s:7:\"/themes\";s:17:\"twentytwentythree\";s:7:\"/themes\";s:15:\"twentytwentytwo\";s:7:\"/themes\";}','off'),(521,'_site_transient_update_core','O:8:\"stdClass\":4:{s:7:\"updates\";a:4:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:7:\"upgrade\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.7.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.7.1.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-6.7.1-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-6.7.1-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.7.1\";s:7:\"version\";s:5:\"6.7.1\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";}i:1;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.7.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.7.1.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.7.1-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.7.1-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.7.1\";s:7:\"version\";s:5:\"6.7.1\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:2;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.6.2.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.6.2.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.6.2-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.6.2-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.6.2\";s:7:\"version\";s:5:\"6.6.2\";s:11:\"php_version\";s:6:\"7.2.24\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:0:\"\";s:9:\"new_files\";s:1:\"1\";}i:3;O:8:\"stdClass\":11:{s:8:\"response\";s:10:\"autoupdate\";s:8:\"download\";s:51:\"https://downloads.w.org/release/wordpress-6.5.5.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:51:\"https://downloads.w.org/release/wordpress-6.5.5.zip\";s:10:\"no_content\";s:62:\"https://downloads.w.org/release/wordpress-6.5.5-no-content.zip\";s:11:\"new_bundled\";s:63:\"https://downloads.w.org/release/wordpress-6.5.5-new-bundled.zip\";s:7:\"partial\";s:61:\"https://downloads.w.org/release/wordpress-6.5.5-partial-0.zip\";s:8:\"rollback\";s:62:\"https://downloads.w.org/release/wordpress-6.5.5-rollback-0.zip\";}s:7:\"current\";s:5:\"6.5.5\";s:7:\"version\";s:5:\"6.5.5\";s:11:\"php_version\";s:5:\"7.0.0\";s:13:\"mysql_version\";s:5:\"5.5.5\";s:11:\"new_bundled\";s:3:\"6.7\";s:15:\"partial_version\";s:3:\"6.5\";s:9:\"new_files\";s:0:\"\";}}s:12:\"last_checked\";i:1736344939;s:15:\"version_checked\";s:3:\"6.5\";s:12:\"translations\";a:0:{}}','off');
/*!40000 ALTER TABLE `wp_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(255) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (5,0,'2023-11-28 12:24:59','2023-11-28 12:24:59','<!-- wp:page-list /-->','Navigation','','publish','closed','closed','','navigation','','','2023-11-28 12:24:59','2023-11-28 12:24:59','',0,'http://localhost:8888/?p=5',0,'wp_navigation','',0),(6,1,'2024-01-28 15:23:50','2024-01-28 15:23:50','<!-- wp:template-part {\"slug\":\"header\",\"tagName\":\"header\",\"theme\":\"twentytwentythree\"} /-->\n<!-- wp:tec/archive-events /-->\n<!-- wp:template-part {\"slug\":\"footer\",\"tagName\":\"footer\",\"theme\":\"twentytwentythree\"} /-->\n','Calendar Views (Event Archive)','Displays the calendar views.','publish','closed','closed','','archive-events','','','2024-01-28 15:23:50','2024-01-28 15:23:50','',0,'http://localhost:8888/?p=6',0,'wp_template','',0),(7,1,'2024-01-28 15:23:50','2024-01-28 15:23:50','<!-- wp:template-part {\"slug\":\"header\",\"tagName\":\"header\",\"theme\":\"twentytwentythree\"} /-->\n<!-- wp:tec/single-event /-->\n<!-- wp:template-part {\"slug\":\"footer\",\"tagName\":\"footer\",\"theme\":\"twentytwentythree\"} /-->\n','Event Single','Displays a single event.','publish','closed','closed','','single-event','','','2024-01-28 15:23:50','2024-01-28 15:23:50','',0,'http://localhost:8888/?p=7',0,'wp_template','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_events`
--

DROP TABLE IF EXISTS `wp_tec_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_events` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `start_date` varchar(19) NOT NULL,
  `end_date` varchar(19) DEFAULT NULL,
  `timezone` varchar(30) NOT NULL DEFAULT 'UTC',
  `start_date_utc` varchar(19) NOT NULL,
  `end_date_utc` varchar(19) DEFAULT NULL,
  `duration` mediumint(30) DEFAULT 7200,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hash` varchar(40) NOT NULL,
  `rset` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_events`
--

LOCK TABLES `wp_tec_events` WRITE;
/*!40000 ALTER TABLE `wp_tec_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_occurrences`
--

DROP TABLE IF EXISTS `wp_tec_occurrences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_occurrences` (
  `occurrence_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `start_date` varchar(19) NOT NULL,
  `start_date_utc` varchar(19) NOT NULL,
  `end_date` varchar(19) NOT NULL,
  `end_date_utc` varchar(19) NOT NULL,
  `duration` mediumint(30) DEFAULT 7200,
  `hash` varchar(40) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `has_recurrence` tinyint(1) DEFAULT 0,
  `sequence` bigint(20) unsigned DEFAULT 0,
  `is_rdate` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`occurrence_id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_occurrences`
--

LOCK TABLES `wp_tec_occurrences` WRITE;
/*!40000 ALTER TABLE `wp_tec_occurrences` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_occurrences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_posts_and_ticket_groups`
--

DROP TABLE IF EXISTS `wp_tec_posts_and_ticket_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_posts_and_ticket_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_posts_and_ticket_groups`
--

LOCK TABLES `wp_tec_posts_and_ticket_groups` WRITE;
/*!40000 ALTER TABLE `wp_tec_posts_and_ticket_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_posts_and_ticket_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_series_relationships`
--

DROP TABLE IF EXISTS `wp_tec_series_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_series_relationships` (
  `relationship_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `series_post_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `event_post_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `series_post_id` (`series_post_id`),
  KEY `event_post_id` (`event_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_series_relationships`
--

LOCK TABLES `wp_tec_series_relationships` WRITE;
/*!40000 ALTER TABLE `wp_tec_series_relationships` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_series_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_slr_layouts`
--

DROP TABLE IF EXISTS `wp_tec_slr_layouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_slr_layouts` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `map` varchar(36) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  `screenshot_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_slr_layouts`
--

LOCK TABLES `wp_tec_slr_layouts` WRITE;
/*!40000 ALTER TABLE `wp_tec_slr_layouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_slr_layouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_slr_maps`
--

DROP TABLE IF EXISTS `wp_tec_slr_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_slr_maps` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  `screenshot_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_slr_maps`
--

LOCK TABLES `wp_tec_slr_maps` WRITE;
/*!40000 ALTER TABLE `wp_tec_slr_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_slr_maps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_slr_seat_types`
--

DROP TABLE IF EXISTS `wp_tec_slr_seat_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_slr_seat_types` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `map` varchar(36) NOT NULL,
  `layout` varchar(36) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_slr_seat_types`
--

LOCK TABLES `wp_tec_slr_seat_types` WRITE;
/*!40000 ALTER TABLE `wp_tec_slr_seat_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_slr_seat_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_slr_sessions`
--

DROP TABLE IF EXISTS `wp_tec_slr_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_slr_sessions` (
  `token` varchar(150) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `expiration` int(11) NOT NULL,
  `reservations` longblob DEFAULT NULL,
  `expiration_lock` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_slr_sessions`
--

LOCK TABLES `wp_tec_slr_sessions` WRITE;
/*!40000 ALTER TABLE `wp_tec_slr_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_slr_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_tec_ticket_groups`
--

DROP TABLE IF EXISTS `wp_tec_ticket_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_tec_ticket_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_ticket_groups`
--

LOCK TABLES `wp_tec_ticket_groups` WRITE;
/*!40000 ALTER TABLE `wp_tec_ticket_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_tec_ticket_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_relationships`
--

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (6,2,0),(7,2,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_taxonomy`
--

LOCK TABLES `wp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES (1,1,'category','',0,0),(2,2,'wp_theme','',0,2);
/*!40000 ALTER TABLE `wp_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_termmeta`
--

DROP TABLE IF EXISTS `wp_termmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_termmeta`
--

LOCK TABLES `wp_termmeta` WRITE;
/*!40000 ALTER TABLE `wp_termmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_termmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_terms`
--

LOCK TABLES `wp_terms` WRITE;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
INSERT INTO `wp_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'tec','tec',0);
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'syntax_highlighting','true'),(7,1,'comment_shortcuts','false'),(8,1,'admin_color','fresh'),(9,1,'use_ssl','0'),(10,1,'show_admin_bar_front','true'),(11,1,'locale',''),(12,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(13,1,'wp_user_level','10'),(14,1,'dismissed_wp_pointers',''),(15,1,'show_welcome_panel','0'),(16,1,'session_tokens','a:2:{s:64:\"a6233aa92784ccf34133b422a043a132d7deb9a06c0cf7d0c0b087decd6b9b9b\";a:4:{s:10:\"expiration\";i:1727610725;s:2:\"ip\";s:10:\"172.18.0.5\";s:2:\"ua\";s:114:\"Mozilla/5.0 (X11; Linux aarch64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/98.0.4758.102 Safari/537.36\";s:5:\"login\";i:1727437925;}s:64:\"84ed88639ca7ed3c090225ca086d83454072e04e3caddbfb1a81a303887effec\";a:4:{s:10:\"expiration\";i:1727610766;s:2:\"ip\";s:12:\"192.168.65.1\";s:2:\"ua\";s:84:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:130.0) Gecko/20100101 Firefox/130.0\";s:5:\"login\";i:1727437966;}}'),(17,1,'wp_user-settings','mfold=o'),(18,1,'wp_user-settings-time','1697535621'),(19,1,'wp_dashboard_quick_press_last_post_id','4'),(20,1,'community-events-location','a:1:{s:2:\"ip\";s:10:\"172.18.0.0\";}'),(21,1,'tribe-dismiss-notice-time-events-rest-api-notice','1727438010'),(22,1,'tribe-dismiss-notice','events-rest-api-notice'),(23,1,'tribe-dismiss-notice-time-events-utc-timezone-2024-02-25','1727438012'),(24,1,'tribe-dismiss-notice','events-utc-timezone-2024-02-25'),(25,1,'tribe-dismiss-notice-time-event-tickets-activate','1727438012'),(26,1,'tribe-dismiss-notice','event-tickets-activate'),(27,1,'tribe-dismiss-notice-time-updated-to-merge-version-consolidated-notice','1727438014'),(28,1,'tribe-dismiss-notice','updated-to-merge-version-consolidated-notice');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'admin','$P$BcXEl2qeHY.vvWz0MdOCCjwWXopOrO.','admin','','http://wordpress.test','2023-10-17 09:31:26','',0,'admin');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_admin_note_actions`
--

DROP TABLE IF EXISTS `wp_wc_admin_note_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_admin_note_actions` (
  `action_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `note_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `query` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `actioned_text` varchar(255) NOT NULL,
  `nonce_action` varchar(255) DEFAULT NULL,
  `nonce_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`action_id`),
  KEY `note_id` (`note_id`)
) ENGINE=InnoDB AUTO_INCREMENT=695 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_admin_note_actions`
--

LOCK TABLES `wp_wc_admin_note_actions` WRITE;
/*!40000 ALTER TABLE `wp_wc_admin_note_actions` DISABLE KEYS */;
INSERT INTO `wp_wc_admin_note_actions` VALUES (1,1,'notify-refund-returns-page','Edit page','http://wordpress.test/wp-admin/post.php?post=14&action=edit','actioned','',NULL,NULL),(2,2,'connect','Connect','?page=wc-addons&section=helper','unactioned','',NULL,NULL),(25,23,'share-navigation-survey-feedback','Share feedback','https://automattic.survey.fm/feedback-on-woocommerce-navigation','actioned','',NULL,NULL),(45,37,'add-first-product','Add a product','http://wordpress.test/wp-admin/admin.php?page=wc-admin&task=products','actioned','',NULL,NULL),(46,38,'visit-the-theme-marketplace','Visit the theme marketplace','https://woocommerce.com/product-category/themes/?utm_source=inbox&utm_medium=product','actioned','',NULL,NULL),(47,39,'learn-more','Learn more','https://woocommerce.com/mobile/?utm_medium=product','actioned','',NULL,NULL),(48,3,'browse_extensions','Browse extensions','http://wordpress.test/wp-admin/admin.php?page=wc-addons','unactioned','',NULL,NULL),(76,28,'wc-admin-wisepad3','Grow my business offline','https://woocommerce.com/products/wisepad3-card-reader/?utm_source=inbox_note&utm_medium=product&utm_campaign=wc-admin-wisepad3','actioned','',NULL,NULL),(81,31,'woocommerce_admin_deprecation_q4_2022','Deactivate WooCommerce Admin','http://wordpress.test/wp-admin/plugins.php','actioned','',NULL,NULL),(82,32,'paypal_paylater_g3_q4_22','Install PayPal Payments','https://woocommerce.com/products/woocommerce-paypal-payments/?utm_source=inbox_note&utm_medium=product&utm_campaign=paypal_paylater_g3_q4_22','unactioned','',NULL,NULL),(83,33,'paypal_paylater_g2_q4_22','Install PayPal Payments','https://woocommerce.com/products/woocommerce-paypal-payments/?utm_source=inbox_note&utm_medium=product&utm_campaign=paypal_paylater_g2_q4_22','unactioned','',NULL,NULL),(84,34,'google_listings_ads_custom_attribute_mapping_q4_2022','Learn more','https://woocommerce.com/document/google-listings-and-ads/?utm_source=inbox_note&utm_medium=product&utm_campaign=google_listings_ads_custom_attribute_mapping_q4_2022#attribute-mapping','actioned','',NULL,NULL),(424,40,'update-db_done','Thanks!','http://localhost:8888/wp-admin/plugins.php?activate=true&plugin_status=all&paged=1&s&wc-hide-notice=update','actioned','woocommerce_hide_notices_nonce','woocommerce_hide_notices_nonce','_wc_notice_nonce'),(605,4,'wayflyer_bnpl_q4_2021','Level up with funding','https://woo.com/products/wayflyer/?utm_source=inbox_note&utm_medium=product&utm_campaign=wayflyer_bnpl_q4_2021','actioned','',NULL,NULL),(606,5,'wc_shipping_mobile_app_usps_q4_2021','Get WooCommerce Shipping','https://woo.com/woocommerce-shipping/?utm_source=inbox_note&utm_medium=product&utm_campaign=wc_shipping_mobile_app_usps_q4_2021','actioned','',NULL,NULL),(607,6,'learn-more','Learn more','https://docs.woocommerce.com/document/woocommerce-shipping-and-tax/?utm_source=inbox','unactioned','',NULL,NULL),(608,7,'learn-more','Learn more','https://woo.com/posts/ecommerce-shipping-solutions-guide/?utm_source=inbox_note&utm_medium=product&utm_campaign=learn-more','actioned','',NULL,NULL),(609,8,'optimizing-the-checkout-flow','Learn more','https://woo.com/posts/optimizing-woocommerce-checkout?utm_source=inbox_note&utm_medium=product&utm_campaign=optimizing-the-checkout-flow','actioned','',NULL,NULL),(610,9,'qualitative-feedback-from-new-users','Share feedback','https://automattic.survey.fm/wc-pay-new','actioned','',NULL,NULL),(611,10,'share-feedback','Share feedback','http://automattic.survey.fm/paypal-feedback','unactioned','',NULL,NULL),(612,11,'get-started','Get started','https://woo.com/products/google-listings-and-ads?utm_source=inbox_note&utm_medium=product&utm_campaign=get-started','actioned','',NULL,NULL),(613,12,'update-wc-subscriptions-3-0-15','View latest version','http://localhost:8888/wp-admin/&page=wc-addons&section=helper','actioned','',NULL,NULL),(614,13,'update-wc-core-5-4-0','How to update WooCommerce','https://docs.woocommerce.com/document/how-to-update-woocommerce/','actioned','',NULL,NULL),(615,16,'ppxo-pps-install-paypal-payments-1','View upgrade guide','https://docs.woocommerce.com/document/woocommerce-paypal-payments/paypal-payments-upgrade-guide/','actioned','',NULL,NULL),(616,17,'ppxo-pps-install-paypal-payments-2','View upgrade guide','https://docs.woocommerce.com/document/woocommerce-paypal-payments/paypal-payments-upgrade-guide/','actioned','',NULL,NULL),(617,18,'learn-more','Learn more','https://woo.com/posts/critical-vulnerability-detected-july-2021/?utm_source=inbox_note&utm_medium=product&utm_campaign=learn-more','unactioned','',NULL,NULL),(618,18,'dismiss','Dismiss','','actioned','',NULL,NULL),(619,19,'learn-more','Learn more','https://woo.com/posts/critical-vulnerability-detected-july-2021/?utm_source=inbox_note&utm_medium=product&utm_campaign=learn-more','unactioned','',NULL,NULL),(620,19,'dismiss','Dismiss','','actioned','',NULL,NULL),(621,20,'learn-more','Learn more','https://woo.com/posts/critical-vulnerability-detected-july-2021/?utm_source=inbox_note&utm_medium=product&utm_campaign=learn-more','unactioned','',NULL,NULL),(622,20,'dismiss','Dismiss','','actioned','',NULL,NULL),(623,21,'learn-more','Learn more','https://woo.com/posts/critical-vulnerability-detected-july-2021/?utm_source=inbox_note&utm_medium=product&utm_campaign=learn-more','unactioned','',NULL,NULL),(624,21,'dismiss','Dismiss','','actioned','',NULL,NULL),(625,22,'share-feedback','Share feedback','https://automattic.survey.fm/store-management','unactioned','',NULL,NULL),(626,24,'learn-more','Learn more','https://developer.woocommerce.com/2022/03/10/woocommerce-3-5-10-6-3-1-security-releases/','unactioned','',NULL,NULL),(627,24,'woocommerce-core-paypal-march-2022-dismiss','Dismiss','','actioned','',NULL,NULL),(628,25,'learn-more','Learn more','https://developer.woocommerce.com/2022/03/10/woocommerce-3-5-10-6-3-1-security-releases/','unactioned','',NULL,NULL),(629,25,'dismiss','Dismiss','','actioned','',NULL,NULL),(630,26,'pinterest_03_2022_update','Update Instructions','https://woo.com/document/pinterest-for-woocommerce/?utm_source=inbox_note&utm_medium=product&utm_campaign=pinterest_03_2022_update#section-3','actioned','',NULL,NULL),(631,27,'store_setup_survey_survey_q2_2022_share_your_thoughts','Tell us how it’s going','https://automattic.survey.fm/store-setup-survey-2022','actioned','',NULL,NULL),(632,29,'learn-more','Find out more','https://developer.woocommerce.com/2022/08/09/woocommerce-payments-3-9-4-4-5-1-security-releases/','unactioned','',NULL,NULL),(633,29,'dismiss','Dismiss','','actioned','',NULL,NULL),(634,30,'learn-more','Find out more','https://developer.woocommerce.com/2022/08/09/woocommerce-payments-3-9-4-4-5-1-security-releases/','unactioned','',NULL,NULL),(635,30,'dismiss','Dismiss','','actioned','',NULL,NULL),(636,35,'needs-update-eway-payment-gateway-rin-action-button-2022-12-20','See available updates','http://localhost:8888/wp-admin/update-core.php','unactioned','',NULL,NULL),(637,35,'needs-update-eway-payment-gateway-rin-dismiss-button-2022-12-20','Dismiss','#','actioned','',NULL,NULL),(638,36,'updated-eway-payment-gateway-rin-action-button-2022-12-20','See all updates','http://localhost:8888/wp-admin/update-core.php','unactioned','',NULL,NULL),(639,36,'updated-eway-payment-gateway-rin-dismiss-button-2022-12-20','Dismiss','#','actioned','',NULL,NULL),(640,41,'share-navigation-survey-feedback','Share feedback','https://automattic.survey.fm/new-ecommerce-plan-navigation','actioned','',NULL,NULL),(641,42,'woopay-beta-merchantrecruitment-activate-04MAY23','Activate WooPay','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','actioned','',NULL,NULL),(642,42,'woopay-beta-merchantrecruitment-activate-learnmore-04MAY23','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-activate-learnmore-04MAY23','unactioned','',NULL,NULL),(643,43,'woocommerce-wcpay-march-2023-update-needed-button','See Blog Post','https://developer.woocommerce.com/2023/03/23/critical-vulnerability-detected-in-woocommerce-payments-what-you-need-to-know','unactioned','',NULL,NULL),(644,43,'woocommerce-wcpay-march-2023-update-needed-dismiss-button','Dismiss','#','actioned','',NULL,NULL),(645,44,'tap_to_pay_iphone_q2_2023_no_wcpay','Simplify my payments','https://woo.com/products/woocommerce-payments/?utm_source=inbox_note&utm_medium=product&utm_campaign=tap_to_pay_iphone_q2_2023_no_wcpay','actioned','',NULL,NULL),(646,45,'extension-settings','See available updates','http://localhost:8888/wp-admin/update-core.php','unactioned','',NULL,NULL),(647,45,'dismiss','Dismiss','#','actioned','',NULL,NULL),(648,46,'woopay-beta-merchantrecruitment-update-WCPay-04MAY23','Update WooCommerce Payments','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(649,46,'woopay-beta-merchantrecruitment-update-activate-04MAY23','Activate WooPay','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','actioned','',NULL,NULL),(650,47,'woopay-beta-existingmerchants-noaction-documentation-27APR23','Documentation','https://woo.com/document/woopay-merchant-documentation/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-existingmerchants-noaction-documentation-27APR23','actioned','',NULL,NULL),(651,48,'woopay-beta-existingmerchants-update-WCPay-27APR23','Update WooCommerce Payments','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','actioned','',NULL,NULL),(652,49,'woopay-beta-merchantrecruitment-short-activate-04MAY23','Activate WooPay','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','actioned','',NULL,NULL),(653,49,'woopay-beta-merchantrecruitment-short-activate-learnmore-04MAY23','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-short-activate-learnmore-04MAY23','actioned','',NULL,NULL),(654,50,'woopay-beta-merchantrecruitment-short-update-WCPay-04MAY23','Update WooCommerce Payments','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(655,50,'woopay-beta-merchantrecruitment-short-update-activate-04MAY23','Activate WooPay','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','actioned','',NULL,NULL),(656,51,'woopay-beta-merchantrecruitment-short-activate-06MAY23-TESTA','Activate WooPay Test A','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','unactioned','',NULL,NULL),(657,51,'woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTA','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTA','unactioned','',NULL,NULL),(658,52,'woopay-beta-merchantrecruitment-short-activate-06MAY23-TESTB','Activate WooPay Test B','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','unactioned','',NULL,NULL),(659,52,'woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTA','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTA','unactioned','',NULL,NULL),(660,53,'woopay-beta-merchantrecruitment-short-activate-06MAY23-TESTC','Activate WooPay Test C','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','unactioned','',NULL,NULL),(661,53,'woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTC','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTC','unactioned','',NULL,NULL),(662,54,'woopay-beta-merchantrecruitment-short-activate-06MAY23-TESTD','Activate WooPay Test D','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','unactioned','',NULL,NULL),(663,54,'woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTD','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-short-activate-learnmore-06MAY23-TESTD','unactioned','',NULL,NULL),(664,55,'woopay-beta-merchantrecruitment-short-activate-button-09MAY23','Activate WooPay','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','unactioned','',NULL,NULL),(665,55,'woopay-beta-merchantrecruitment-short-activate-learnmore-button2-09MAY23','Learn More','https://woo.com/woopay-businesses/?utm_source=inbox_note&utm_medium=product&utm_campaign=woopay-beta-merchantrecruitment-short-activate-learnmore-button2-09MAY23','unactioned','',NULL,NULL),(666,56,'woopay-beta-merchantrecruitment-short-update-WCPay-09MAY23','Update WooCommerce Payments','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(667,56,'woopay-beta-merchantrecruitment-short-update-activate-09MAY23','Activate WooPay','http://localhost:8888/wp-admin/admin.php?page=wc-settings&tab=checkout&section=woocommerce_payments&method=platform_checkout','unactioned','',NULL,NULL),(668,57,'woocommerce-WCStripe-May-2023-updated-needed-Plugin-Settings','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(669,57,'woocommerce-WCStripe-May-2023-updated-needed-Plugin-Settings-dismiss','Dismiss','#','actioned','',NULL,NULL),(670,58,'woocommerce-WCPayments-June-2023-updated-needed-Plugin-Settings','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(671,58,'woocommerce-WCPayments-June-2023-updated-needed-Dismiss','Dismiss','#','actioned','',NULL,NULL),(672,59,'woocommerce-WCSubscriptions-June-2023-updated-needed-Plugin-Settings','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(673,59,'woocommerce-WCSubscriptions-June-2023-updated-needed-dismiss','Dismiss','#','actioned','',NULL,NULL),(674,60,'woocommerce-WCReturnsWarranty-June-2023-updated-needed','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(675,60,'woocommerce-WCReturnsWarranty-June-2023-updated-needed','Dismiss','#','actioned','',NULL,NULL),(676,61,'woocommerce-WCOPC-June-2023-updated-needed','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','actioned','',NULL,NULL),(677,61,'woocommerce-WCOPC-June-2023-updated-needed','Dismiss','http://localhost:8888/wp-admin/#','actioned','',NULL,NULL),(678,62,'woocommerce-WCGC-July-2023-update-needed','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(679,62,'woocommerce-WCGC-July-2023-update-needed','Dismiss','#','actioned','',NULL,NULL),(680,63,'learn-more','Learn more','https://woo.com/document/fedex/?utm_medium=product&utm_source=inbox_note&utm_campaign=learn-more#july-2023-api-outage','unactioned','',NULL,NULL),(681,64,'plugin-list','See available updates','http://localhost:8888/wp-admin/plugins.php?plugin_status=all','unactioned','',NULL,NULL),(682,64,'dismiss','Dismiss','http://localhost:8888/wp-admin/admin.php?page=wc-admin','actioned','',NULL,NULL),(683,65,'woocommerce-WCStripe-Aug-2023-update-needed','See available updates','http://localhost:8888/wp-admin/update-core.php?','unactioned','',NULL,NULL),(684,65,'dismiss','Dismiss','#','actioned','',NULL,NULL),(685,66,'dismiss','Dismiss','#','actioned','',NULL,NULL),(686,67,'woocommerce-WooPayments-Aug-2023-update-needed','See available updates','http://localhost:8888/wp-admin/update-core.php?','unactioned','',NULL,NULL),(687,67,'dismiss','Dismiss','#','actioned','',NULL,NULL),(688,68,'dismiss','Dismiss','#','actioned','',NULL,NULL),(689,69,'avalara_q3-2023_noAvaTax','Automate my sales tax','https://woo.com/products/woocommerce-avatax/?utm_source=inbox_note&utm_medium=product&utm_campaign=avalara_q3-2023_noAvaTax','unactioned','',NULL,NULL),(690,70,'woo-activation-survey-blockers-survey-button-22AUG23','Take our short survey','https://woocommerce.survey.fm/getting-started-with-woo','unactioned','',NULL,NULL),(691,71,'woocommerce-usermeta-Sept2023-productvendors','See available updates','http://localhost:8888/wp-admin/plugins.php','unactioned','',NULL,NULL),(692,71,'dismiss','Dismiss','http://localhost:8888/wp-admin/#','actioned','',NULL,NULL),(693,72,'woocommerce-STRIPE-Oct-2023-update-needed','See available updates','http://localhost:8888/wp-admin/update-core.php','unactioned','',NULL,NULL),(694,72,'dismiss','Dismiss','#','actioned','',NULL,NULL);
/*!40000 ALTER TABLE `wp_wc_admin_note_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_admin_notes`
--

DROP TABLE IF EXISTS `wp_wc_admin_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_admin_notes` (
  `note_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `locale` varchar(20) NOT NULL,
  `title` longtext NOT NULL,
  `content` longtext NOT NULL,
  `content_data` longtext DEFAULT NULL,
  `status` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_reminder` datetime DEFAULT NULL,
  `is_snoozable` tinyint(1) NOT NULL DEFAULT 0,
  `layout` varchar(20) NOT NULL DEFAULT '',
  `image` varchar(200) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(200) NOT NULL DEFAULT 'info',
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_admin_notes`
--

LOCK TABLES `wp_wc_admin_notes` WRITE;
/*!40000 ALTER TABLE `wp_wc_admin_notes` DISABLE KEYS */;
INSERT INTO `wp_wc_admin_notes` VALUES (1,'wc-refund-returns-page','info','en_US','Setup a Refund and Returns Policy page to boost your store\'s credibility.','We have created a sample draft Refund and Returns Policy page for you. Please have a look and update it to fit your store.','{}','unactioned','woocommerce-core','2023-01-05 13:11:55',NULL,0,'plain','',1,0,'info'),(2,'wc-admin-wc-helper-connection','info','en_US','Connect to WooCommerce.com','Connect to get important product notifications and updates.','{}','unactioned','woocommerce-admin','2023-01-05 13:11:56',NULL,0,'plain','',1,0,'info'),(3,'new_in_app_marketplace_2021','info','en_US','Customize your store with extensions','Check out our NEW Extensions tab to see our favorite extensions for customizing your store, and discover the most popular extensions in the WooCommerce Marketplace.','{}','unactioned','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',1,0,'info'),(4,'wayflyer_bnpl_q4_2021','marketing','en_US','Grow your business with funding through Wayflyer','Fast, flexible financing to boost cash flow and help your business grow – one fee, no interest rates, penalties, equity, or personal guarantees. Based on your store’s performance, Wayflyer provides funding and analytical insights to invest in your business.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(5,'wc_shipping_mobile_app_usps_q4_2021','marketing','en_US','Print and manage your shipping labels with WooCommerce Shipping and the WooCommerce Mobile App','Save time by printing, purchasing, refunding, and tracking shipping labels generated by <a href=\"https://woo.com/woocommerce-shipping/\">WooCommerce Shipping</a> – all directly from your mobile device!','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(6,'woocommerce-services','info','en_US','WooCommerce Shipping & Tax','WooCommerce Shipping &amp; Tax helps get your store \"ready to sell\" as quickly as possible. You create your products. We take care of tax calculation, payment processing, and shipping label printing! Learn more about the extension that you just installed.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(7,'your-first-product','info','en_US','Your first product','That’s huge! You’re well on your way to building a successful online store — now it’s time to think about how you’ll fulfill your orders.<br /><br />Read our shipping guide to learn best practices and options for putting together your shipping strategy. And for WooCommerce stores in the United States, you can print discounted shipping labels via USPS with <a href=\"https://href.li/?https://woo.com/shipping\" target=\"_blank\">WooCommerce Shipping</a>.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(8,'wc-admin-optimizing-the-checkout-flow','info','en_US','Optimizing the checkout flow','It’s crucial to get your store’s checkout as smooth as possible to avoid losing sales. Let’s take a look at how you can optimize the checkout experience for your shoppers.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(9,'wc-payments-qualitative-feedback','info','en_US','WooCommerce Payments setup - let us know what you think','Congrats on enabling WooCommerce Payments for your store. Please share your feedback in this 2 minute survey to help us improve the setup process.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(10,'share-your-feedback-on-paypal','info','en_US','Share your feedback on PayPal','Share your feedback in this 2 minute survey about how we can make the process of accepting payments more useful for your store.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(11,'google_listings_and_ads_install','marketing','en_US','Drive traffic and sales with Google','Reach online shoppers to drive traffic and sales for your store by showcasing products across Google, for free or with ads.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(12,'wc-subscriptions-security-update-3-0-15','info','en_US','WooCommerce Subscriptions security update!','We recently released an important security update to WooCommerce Subscriptions. To ensure your site’s data is protected, please upgrade <strong>WooCommerce Subscriptions to version 3.0.15</strong> or later.<br /><br />Click the button below to view and update to the latest Subscriptions version, or log in to <a href=\"https://woo.com/my-dashboard\">WooCommerce.com Dashboard</a> and navigate to your <strong>Downloads</strong> page.<br /><br />We recommend always using the latest version of WooCommerce Subscriptions, and other software running on your site, to ensure maximum security.<br /><br />If you have any questions we are here to help — just <a href=\"https://woo.com/my-account/create-a-ticket/\">open a ticket</a>.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(13,'woocommerce-core-update-5-4-0','info','en_US','Update to WooCommerce 5.4.1 now','WooCommerce 5.4.1 addresses a checkout issue discovered in WooCommerce 5.4. We recommend upgrading to WooCommerce 5.4.1 as soon as possible.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(14,'wcpay-promo-2020-11','marketing','en_US','wcpay-promo-2020-11','wcpay-promo-2020-11','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(15,'wcpay-promo-2020-12','marketing','en_US','wcpay-promo-2020-12','wcpay-promo-2020-12','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(16,'ppxo-pps-upgrade-paypal-payments-1','info','en_US','Get the latest PayPal extension for WooCommerce','Heads up! There’s a new PayPal on the block!<br /><br />Now is a great time to upgrade to our latest <a href=\"https://woo.com/products/woocommerce-paypal-payments/\" target=\"_blank\">PayPal extension</a> to continue to receive support and updates with PayPal.<br /><br />Get access to a full suite of PayPal payment methods, extensive currency and country coverage, and pay later options with the all-new PayPal extension for WooCommerce.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(17,'ppxo-pps-upgrade-paypal-payments-2','info','en_US','Upgrade your PayPal experience!','Get access to a full suite of PayPal payment methods, extensive currency and country coverage, offer subscription and recurring payments, and the new PayPal pay later options.<br /><br />Start using our <a href=\"https://woo.com/products/woocommerce-paypal-payments/\" target=\"_blank\">latest PayPal today</a> to continue to receive support and updates.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(18,'woocommerce-core-sqli-july-2021-need-to-update','update','en_US','Action required: Critical vulnerabilities in WooCommerce','In response to a critical vulnerability identified on July 13, 2021, we are working with the WordPress Plugins Team to deploy software updates to stores running WooCommerce (versions 3.3 to 5.5) and the WooCommerce Blocks feature plugin (versions 2.5 to 5.5).<br /><br />Our investigation into this vulnerability is ongoing, but <strong>we wanted to let you know now about the importance of updating immediately</strong>.<br /><br />For more information on which actions you should take, as well as answers to FAQs, please urgently review our blog post detailing this issue.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(19,'woocommerce-blocks-sqli-july-2021-need-to-update','update','en_US','Action required: Critical vulnerabilities in WooCommerce Blocks','In response to a critical vulnerability identified on July 13, 2021, we are working with the WordPress Plugins Team to deploy software updates to stores running WooCommerce (versions 3.3 to 5.5) and the WooCommerce Blocks feature plugin (versions 2.5 to 5.5).<br /><br />Our investigation into this vulnerability is ongoing, but <strong>we wanted to let you know now about the importance of updating immediately</strong>.<br /><br />For more information on which actions you should take, as well as answers to FAQs, please urgently review our blog post detailing this issue.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(20,'woocommerce-core-sqli-july-2021-store-patched','update','en_US','Solved: Critical vulnerabilities patched in WooCommerce','In response to a critical vulnerability identified on July 13, 2021, we worked with the WordPress Plugins Team to deploy software updates to stores running WooCommerce (versions 3.3 to 5.5) and the WooCommerce Blocks feature plugin (versions 2.5 to 5.5).<br /><br /><strong>Your store has been updated to the latest secure version(s)</strong>. For more information and answers to FAQs, please review our blog post detailing this issue.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(21,'woocommerce-blocks-sqli-july-2021-store-patched','update','en_US','Solved: Critical vulnerabilities patched in WooCommerce Blocks','In response to a critical vulnerability identified on July 13, 2021, we worked with the WordPress Plugins Team to deploy software updates to stores running WooCommerce (versions 3.3 to 5.5) and the WooCommerce Blocks feature plugin (versions 2.5 to 5.5).<br /><br /><strong>Your store has been updated to the latest secure version(s)</strong>. For more information and answers to FAQs, please review our blog post detailing this issue.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(22,'habit-moment-survey','marketing','en_US','We’re all ears! Share your experience so far with WooCommerce','We’d love your input to shape the future of WooCommerce together. Feel free to share any feedback, ideas or suggestions that you have.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(23,'ecomm-wc-navigation-survey','info','en_US','We’d like your feedback on the WooCommerce navigation','We’re making improvements to the WooCommerce navigation and would love your feedback. Share your experience in this 2 minute survey.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(24,'woocommerce-core-paypal-march-2022-updated','update','en_US','Security auto-update of WooCommerce','<strong>Your store has been updated to the latest secure version of WooCommerce</strong>. We worked with WordPress to deploy PayPal Standard security updates for stores running WooCommerce (version 3.5 to 6.3). It’s recommended to disable PayPal Standard, and use <a href=\"https://woo.com/products/woocommerce-paypal-payments/\" target=\"_blank\">PayPal Payments</a> to accept PayPal.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(25,'woocommerce-core-paypal-march-2022-updated-nopp','update','en_US','Security auto-update of WooCommerce','<strong>Your store has been updated to the latest secure version of WooCommerce</strong>. We worked with WordPress to deploy security updates related to PayPal Standard payment gateway for stores running WooCommerce (version 3.5 to 6.3).','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(26,'pinterest_03_2022_update','marketing','en_US','Your Pinterest for WooCommerce plugin is out of date!','Update to the latest version of Pinterest for WooCommerce to continue using this plugin and keep your store connected with Pinterest. To update, visit <strong>Plugins &gt; Installed Plugins</strong>, and click on “update now” under Pinterest for WooCommerce.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(27,'store_setup_survey_survey_q2_2022','survey','en_US','How is your store setup going?','Our goal is to make sure you have all the right tools to start setting up your store in the smoothest way possible.\r\nWe’d love to know if we hit our mark and how we can improve. To collect your thoughts, we made a 2-minute survey.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(28,'wc-admin-wisepad3','marketing','en_US','Take your business on the go in Canada with WooCommerce In-Person Payments','Quickly create new orders, accept payment in person for orders placed online, and automatically sync your inventory – no matter where your business takes you. With WooCommerce In-Person Payments and the WisePad 3 card reader, you can bring the power of your store anywhere.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(29,'woocommerce-payments-august-2022-need-to-update','update','en_US','Action required: Please update WooCommerce Payments','An updated secure version of WooCommerce Payments is available – please ensure that you’re using the latest patch version. For more information on what action you need to take, please review the article below.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(30,'woocommerce-payments-august-2022-store-patched','update','en_US','WooCommerce Payments has been automatically updated','You’re now running the latest secure version of WooCommerce Payments. We’ve worked with the WordPress Plugins team to deploy a security update to stores running WooCommerce Payments (version 3.9 to 4.5). For further information, please review the article below.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(31,'woocommerce_admin_deprecation_q4_2022','info','en_US','WooCommerce Admin is part of WooCommerce!','To make sure your store continues to run smoothly, check that WooCommerce is up-to-date – at least version 6.5 – and then disable the WooCommerce Admin plugin.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(32,'paypal_paylater_g3_q4_22','marketing','en_US','Turn browsers into buyers with Pay Later','Add PayPal at checkout, plus give customers a buy now, pay later option from the name they trust. With Pay in 4 &amp; Pay Monthly, available in PayPal Payments, you get paid up front while letting customers spread their payments over time. Boost your average order value and convert more sales – at no extra cost to you.','{}','unactioned','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',1,0,'info'),(33,'paypal_paylater_g2_q4_22','marketing','en_US','Upgrade to PayPal Payments to offer Pay Later at checkout','PayPal Pay Later is included in PayPal Payments at no additional cost to you. Customers can spread their payments over time while you get paid up front. \r\nThere’s never been a better time to upgrade your PayPal plugin. Simply download it and connect with a PayPal Business account.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(34,'google_listings_ads_custom_attribute_mapping_q4_2022','marketing','en_US','Our latest improvement to the Google Listings & Ads extension: Attribute Mapping','You spoke, we listened. This new feature enables you to easily upload your products, customize your product attributes in one place, and target shoppers with more relevant ads. Extend how far your ad dollars go with each campaign.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(35,'needs-update-eway-payment-gateway-rin-2022-12-20','update','en_US','Security vulnerability patched in WooCommerce Eway Gateway','In response to a potential vulnerability identified in WooCommerce Eway Gateway versions 3.1.0 to 3.5.0, we’ve worked to deploy security fixes and have released an updated version.\r\nNo external exploits have been detected, but we recommend you update to your latest supported version 3.1.26, 3.2.3, 3.3.1, 3.4.6, or 3.5.1','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(36,'updated-eway-payment-gateway-rin-2022-12-20','update','en_US','WooCommerce Eway Gateway has been automatically updated','Your store is now running the latest secure version of WooCommerce Eway Gateway. We worked with the WordPress Plugins team to deploy a software update to stores running WooCommerce Eway Gateway (versions 3.1.0 to 3.5.0) in response to a security vulnerability that was discovered.','{}','pending','woocommerce.com','2023-01-05 13:13:22',NULL,0,'plain','',0,0,'info'),(37,'wc-admin-add-first-product-note','email','en_US','Add your first product','{greetings}<br /><br />Nice one; you\'ve created a WooCommerce store! Now it\'s time to add your first product and get ready to start selling.<br /><br />There are three ways to add your products: you can <strong>create products manually, import them at once via CSV file</strong>, or <strong>migrate them from another service</strong>.<br /><br /><a href=\"https://woocommerce.com/document/managing-products/?utm_source=help_panel&amp;utm_medium=product\">Explore our docs</a> for more information, or just get started!','{\"role\":\"administrator\"}','unactioned','woocommerce-admin','2023-01-09 14:29:21',NULL,0,'plain','http://wordpress.test/wp-content/plugins/woocommerce/images/admin_notes/dashboard-widget-setup.png',0,0,'info'),(38,'wc-admin-choosing-a-theme','marketing','en_US','Choosing a theme?','Check out the themes that are compatible with WooCommerce and choose one aligned with your brand and business needs.','{}','unactioned','woocommerce-admin','2023-01-09 14:29:21',NULL,0,'plain','',0,0,'info'),(39,'wc-admin-mobile-app','info','en_US','Install Woo mobile app','Install the WooCommerce mobile app to manage orders, receive sales notifications, and view key metrics — wherever you are.','{}','unactioned','woocommerce-admin','2023-01-09 14:29:21',NULL,0,'plain','',0,0,'info'),(40,'wc-update-db-reminder','update','en_US','WooCommerce database update done','WooCommerce database update complete. Thank you for updating to the latest version!','{}','actioned','woocommerce-core','2023-11-17 12:52:13',NULL,0,'plain','',0,0,'info'),(41,'ecomm-wc-navigation-survey-2023','info','en_US','Navigating WooCommerce on WordPress.com','We are improving the WooCommerce navigation on WordPress.com and would love your help to make it better! Please share your experience with us in this 2-minute survey.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(42,'woopay-beta-merchantrecruitment-04MAY23','info','en_US','Increase conversions with WooPay — our fastest checkout yet','WooPay, a new express checkout feature built into WooCommerce Payments, is now available —and we’re inviting you to be one of the first to try it. \r\n<br><br>\r\nBoost conversions by offering your customers a simple, secure way to pay with a single click.\r\n<br><br>\r\nGet started in seconds.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(43,'woocommerce-wcpay-march-2023-update-needed','update','en_US','Action required: Security update for WooCommerce Payments','<strong>Your store requires a security update for WooCommerce Payments</strong>. Please update to the latest version of WooCommerce Payments immediately to address a potential vulnerability discovered on March 22. For more information on how to update, visit this WooCommerce Developer Blog Post.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(44,'tap_to_pay_iphone_q2_2023_no_wcpay','marketing','en_US','Accept in-person contactless payments on your iPhone','Tap to Pay on iPhone and WooCommerce Payments is quick, secure, and simple to set up — no extra terminals or card readers are needed. Accept contactless debit and credit cards, Apple Pay, and other NFC digital wallets in person.','{}','unactioned','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(45,'woocommerce-WCPreOrders-april-2023-update-needed','update','en_US','Action required: Security update of WooCommerce Pre-Orders extension','<strong>Your store requires a security update for the WooCommerce Pre-Orders extension</strong>. Please update the WooCommerce Pre-Orders extension immediately to address a potential vulnerability discovered on April 11.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(46,'woopay-beta-merchantrecruitment-update-04MAY23','info','en_US','Increase conversions with WooPay — our fastest checkout yet','WooPay, a new express checkout feature built into WooCommerce Payments, is now available — and you’re invited to try it. \r\n<br /><br />\r\nBoost conversions by offering your customers a simple, secure way to pay with a single click.\r\n<br /><br />\r\nUpdate WooCommerce Payments to get started.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(47,'woopay-beta-existingmerchants-noaction-27APR23','info','en_US','WooPay is back!','Thanks for previously trying WooPay, the express checkout feature built into WooCommerce Payments. We’re excited to announce that WooPay availability has resumed. No action is required on your part.\r\n<br /><br />\r\nYou can now continue boosting conversions by offering your customers a simple, secure way to pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(48,'woopay-beta-existingmerchants-update-27APR23','info','en_US','WooPay is back!','Thanks for previously trying WooPay, the express checkout feature built into WooCommerce Payments. We’re excited to announce that WooPay availability has resumed.\r\n<br /><br />\r\n\r\nUpdate to the latest WooCommerce Payments version to continue boosting conversions by offering your customers a simple, secure way to pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(49,'woopay-beta-merchantrecruitment-short-04MAY23','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, a new express checkout feature for WooCommerce Payments. \r\n<br><br>\r\nBoost conversions by letting customers pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(50,'woopay-beta-merchantrecruitment-short-update-04MAY23','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, our new express checkout feature. <br>Boost conversions by letting customers pay with a single click. <br><br>Update to the latest version of WooCommerce Payments to get started.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(51,'woopay-beta-merchantrecruitment-short-06MAY23-TESTA','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, a new express checkout feature for WooCommerce Payments. \r\n<br><br>\r\nBoost conversions by letting customers pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(52,'woopay-beta-merchantrecruitment-short-06MAY23-TESTB','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, a new express checkout feature for WooCommerce Payments. \r\n<br><br>\r\nBoost conversions by letting customers pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(53,'woopay-beta-merchantrecruitment-short-06MAY23-TESTC','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, a new express checkout feature for WooCommerce Payments. \r\n<br><br>\r\nBoost conversions by letting customers pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(54,'woopay-beta-merchantrecruitment-short-06MAY23-TESTD','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, a new express checkout feature for WooCommerce Payments. \r\n<br><br>\r\nBoost conversions by letting customers pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(55,'woopay-beta-merchantrecruitment-short-09MAY23','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, a new express checkout feature for WooCommerce Payments. \r\n<br><br>\r\nBoost conversions by letting customers pay with a single click.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(56,'woopay-beta-merchantrecruitment-short-update-09MAY23','info','en_US','Increase conversions with WooPay — our fastest checkout yet','Be one of the first to try WooPay, our new express checkout feature. <br>Boost conversions by letting customers pay with a single click. <br><br>Update to the latest version of WooCommerce Payments to get started.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(57,'woocommerce-WCstripe-May-2023-updated-needed','update','en_US','Action required: Security update of WooCommerce Stripe plugin','<strong>Your store requires a security update for the WooCommerce Stripe plugin</strong>. Please update the WooCommerce Stripe plugin immediately to address a potential vulnerability.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(58,'woocommerce-WCPayments-June-2023-updated-needed','update','en_US','Action required: Security update of WooCommerce Payments','<strong>Your store requires a security update for the WooCommerce Payments plugin</strong>. Please update the WooCommerce Payments plugin immediately to address a potential vulnerability.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(59,'woocommerce-WCSubscriptions-June-2023-updated-needed','marketing','en_US','Action required: Security update of WooCommerce Subscriptions','<strong>Your store requires a security update for the WooCommerce Subscriptions plugin</strong>. Please update the WooCommerce Subscriptions plugin immediately to address a potential vulnerability.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(60,'woocommerce-WCReturnsWarranty-June-2023-updated-needed','update','en_US','Action required: Security update of WooCommerce Returns and Warranty Requests extension','<strong>Your store requires a security update for the Returns and Warranty Requests extension</strong>.  Please update to the latest version of the WooCommerce Returns and Warranty Requests extension immediately to address a potential vulnerability discovered on May 31.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(61,'woocommerce-WCOPC-June-2023-updated-needed','update','en_US','Action required: Security update of WooCommerce One Page Checkout','<strong>Your shop requires a security update to address a vulnerability in the WooCommerce One Page Checkout extension</strong>. The fix for this vulnerability was released for this extension on June 13th. Please update immediately.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(62,'woocommerce-WCGC-July-2023-update-needed','update','en_US','Action required: Security update of WooCommerce GoCardless Extension','<strong>Your shop requires a security update to address a vulnerability in the WooCommerce GoCardless extension</strong>. The fix for this vulnerability was released on July 4th. Please update immediately.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(63,'woocommerce-shipping-fedex-api-outage-2023-07-16','warning','en_US','Scheduled FedEx API outage — July 2023','On July 16 there will be a full outage of the FedEx API from 04:00 to 08:00 AM UTC. Due to planned maintenance by FedEx, you\'ll be unable to provide FedEx shipping rates during this time. Follow the link below for more information and recommendations on how to minimize impact.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(64,'wcship-2023-07-hazmat-update-needed','update','en_US','Action required: USPS HAZMAT compliance update for WooCommerce Shipping & Tax extension','<strong>Your store requires an update for the WooCommerce Shipping extension</strong>. Please update to the latest version of the WooCommerce Shipping &amp; Tax extension immediately to ensure compliance with new USPS HAZMAT rules currently in effect.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(65,'woocommerce-WCStripe-Aug-2023-update-needed','update','en_US','Action required: Security update for WooCommerce Stripe plugin','<strong>Your shop requires an important security update for the  WooCommerce Stripe plugin</strong>. The fix for this vulnerability was released on July 31. Please update immediately.','{}','pending','woocommerce.com','2023-11-17 13:57:14',NULL,0,'plain','',0,0,'info'),(66,'woocommerce-WCStripe-Aug-2023-security-updated','update','en_US','Security update of WooCommerce Stripe plugin','<strong>Your store has been updated to the latest secure version of the WooCommerce Stripe plugin</strong>. This update was released on July 31.','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info'),(67,'woocommerce-WooPayments-Aug-2023-update-needed','update','en_US','Action required: Security update for WooPayments (WooCommerce Payments) plugin','<strong>Your shop requires an important security update for the WooPayments (WooCommerce Payments) extension</strong>. The fix for this vulnerability was released on July 31. Please update immediately.','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info'),(68,'woocommerce-WooPayments-Aug-2023-security-updated','update','en_US','Security update of WooPayments (WooCommerce Payments) plugin','<strong>Your store has been updated to the more secure version of WooPayments (WooCommerce Payments)</strong>. This update was released on July 31.','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info'),(69,'avalara_q3-2023_noAvaTax','marketing','en_US','Automatically calculate VAT in real time','Take the effort out of determining tax rates and sell confidently across borders with automated tax management from Avalara AvaTax— including built-in VAT calculation when you sell into or across the EU and UK. Save time and stay compliant when you let Avalara do the heavy lifting.','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info'),(70,'woo-activation-survey-blockers-22AUG23','info','en_US','How can we help you get that first sale?','Your feedback is vital. Please take a minute to share your experience of setting up your new store and whether anything is preventing you from making those first few sales. Together, we can make Woo even better!','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info'),(71,'woocommerce-usermeta-Sept2023-productvendors','update','en_US','Your store requires a security update','<strong>Your shop needs an update to address a vulnerability in WooCommerce.</strong> The fix was released on Sept 15. Please update WooCommerce to the latest version immediately. <a href=\"https://developer.woocommerce.com/2023/09/16/woocommerce-vulnerability-reintroduced-from-7-0-1/\" />Read our developer update</a> for more information.','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info'),(72,'woocommerce-STRIPE-Oct-2023-update-needed','update','en_US','Action required: Security update for WooCommerce Stripe Gateway','<strong>Your shop requires a security update to address a vulnerability in the WooCommerce Stripe Gateway</strong>. The fix for this vulnerability was released on October 17. Please update immediately.','{}','pending','woocommerce.com','2023-11-17 13:57:15',NULL,0,'plain','',0,0,'info');
/*!40000 ALTER TABLE `wp_wc_admin_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_category_lookup`
--

DROP TABLE IF EXISTS `wp_wc_category_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_category_lookup` (
  `category_tree_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`category_tree_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_category_lookup`
--

LOCK TABLES `wp_wc_category_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_category_lookup` DISABLE KEYS */;
INSERT INTO `wp_wc_category_lookup` VALUES (15,15);
/*!40000 ALTER TABLE `wp_wc_category_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_customer_lookup`
--

DROP TABLE IF EXISTS `wp_wc_customer_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_customer_lookup` (
  `customer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(60) NOT NULL DEFAULT '',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_last_active` timestamp NULL DEFAULT NULL,
  `date_registered` timestamp NULL DEFAULT NULL,
  `country` char(2) NOT NULL DEFAULT '',
  `postcode` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_customer_lookup`
--

LOCK TABLES `wp_wc_customer_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_customer_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_customer_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_download_log`
--

DROP TABLE IF EXISTS `wp_wc_download_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_download_log` (
  `download_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_ip_address` varchar(100) DEFAULT '',
  PRIMARY KEY (`download_log_id`),
  KEY `permission_id` (`permission_id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_download_log`
--

LOCK TABLES `wp_wc_download_log` WRITE;
/*!40000 ALTER TABLE `wp_wc_download_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_download_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_addresses`
--

DROP TABLE IF EXISTS `wp_wc_order_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `address_type` varchar(20) DEFAULT NULL,
  `first_name` text DEFAULT NULL,
  `last_name` text DEFAULT NULL,
  `company` text DEFAULT NULL,
  `address_1` text DEFAULT NULL,
  `address_2` text DEFAULT NULL,
  `city` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `postcode` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `email` varchar(320) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `address_type_order_id` (`address_type`,`order_id`),
  KEY `order_id` (`order_id`),
  KEY `email` (`email`(191)),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_addresses`
--

LOCK TABLES `wp_wc_order_addresses` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_coupon_lookup`
--

DROP TABLE IF EXISTS `wp_wc_order_coupon_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_coupon_lookup` (
  `order_id` bigint(20) unsigned NOT NULL,
  `coupon_id` bigint(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_amount` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_id`,`coupon_id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_coupon_lookup`
--

LOCK TABLES `wp_wc_order_coupon_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_coupon_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_coupon_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_operational_data`
--

DROP TABLE IF EXISTS `wp_wc_order_operational_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_operational_data` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `created_via` varchar(100) DEFAULT NULL,
  `woocommerce_version` varchar(20) DEFAULT NULL,
  `prices_include_tax` tinyint(1) DEFAULT NULL,
  `coupon_usages_are_counted` tinyint(1) DEFAULT NULL,
  `download_permission_granted` tinyint(1) DEFAULT NULL,
  `cart_hash` varchar(100) DEFAULT NULL,
  `new_order_email_sent` tinyint(1) DEFAULT NULL,
  `order_key` varchar(100) DEFAULT NULL,
  `order_stock_reduced` tinyint(1) DEFAULT NULL,
  `date_paid_gmt` datetime DEFAULT NULL,
  `date_completed_gmt` datetime DEFAULT NULL,
  `shipping_tax_amount` decimal(26,8) DEFAULT NULL,
  `shipping_total_amount` decimal(26,8) DEFAULT NULL,
  `discount_tax_amount` decimal(26,8) DEFAULT NULL,
  `discount_total_amount` decimal(26,8) DEFAULT NULL,
  `recorded_sales` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `order_key` (`order_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_operational_data`
--

LOCK TABLES `wp_wc_order_operational_data` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_operational_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_operational_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_product_lookup`
--

DROP TABLE IF EXISTS `wp_wc_order_product_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_product_lookup` (
  `order_item_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variation_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `product_qty` int(11) NOT NULL,
  `product_net_revenue` double NOT NULL DEFAULT 0,
  `product_gross_revenue` double NOT NULL DEFAULT 0,
  `coupon_amount` double NOT NULL DEFAULT 0,
  `tax_amount` double NOT NULL DEFAULT 0,
  `shipping_amount` double NOT NULL DEFAULT 0,
  `shipping_tax_amount` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_product_lookup`
--

LOCK TABLES `wp_wc_order_product_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_product_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_product_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_stats`
--

DROP TABLE IF EXISTS `wp_wc_order_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_stats` (
  `order_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `num_items_sold` int(11) NOT NULL DEFAULT 0,
  `total_sales` double NOT NULL DEFAULT 0,
  `tax_total` double NOT NULL DEFAULT 0,
  `shipping_total` double NOT NULL DEFAULT 0,
  `net_total` double NOT NULL DEFAULT 0,
  `returning_customer` tinyint(1) DEFAULT NULL,
  `status` varchar(200) NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `date_paid` datetime DEFAULT '0000-00-00 00:00:00',
  `date_completed` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_id`),
  KEY `date_created` (`date_created`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_stats`
--

LOCK TABLES `wp_wc_order_stats` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_order_tax_lookup`
--

DROP TABLE IF EXISTS `wp_wc_order_tax_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_order_tax_lookup` (
  `order_id` bigint(20) unsigned NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shipping_tax` double NOT NULL DEFAULT 0,
  `order_tax` double NOT NULL DEFAULT 0,
  `total_tax` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_id`,`tax_rate_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_order_tax_lookup`
--

LOCK TABLES `wp_wc_order_tax_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_order_tax_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_order_tax_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_orders`
--

DROP TABLE IF EXISTS `wp_wc_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_orders` (
  `id` bigint(20) unsigned NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `tax_amount` decimal(26,8) DEFAULT NULL,
  `total_amount` decimal(26,8) DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `billing_email` varchar(320) DEFAULT NULL,
  `date_created_gmt` datetime DEFAULT NULL,
  `date_updated_gmt` datetime DEFAULT NULL,
  `parent_order_id` bigint(20) unsigned DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_method_title` text DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `customer_note` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created_gmt`),
  KEY `customer_id_billing_email` (`customer_id`,`billing_email`(171)),
  KEY `billing_email` (`billing_email`(191)),
  KEY `type_status_date` (`type`,`status`,`date_created_gmt`),
  KEY `parent_order_id` (`parent_order_id`),
  KEY `date_updated` (`date_updated_gmt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_orders`
--

LOCK TABLES `wp_wc_orders` WRITE;
/*!40000 ALTER TABLE `wp_wc_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_orders_meta`
--

DROP TABLE IF EXISTS `wp_wc_orders_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_orders_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_key_value` (`meta_key`(100),`meta_value`(82)),
  KEY `order_id_meta_key_meta_value` (`order_id`,`meta_key`(100),`meta_value`(82))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_orders_meta`
--

LOCK TABLES `wp_wc_orders_meta` WRITE;
/*!40000 ALTER TABLE `wp_wc_orders_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_orders_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_product_attributes_lookup`
--

DROP TABLE IF EXISTS `wp_wc_product_attributes_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_product_attributes_lookup` (
  `product_id` bigint(20) NOT NULL,
  `product_or_parent_id` bigint(20) NOT NULL,
  `taxonomy` varchar(32) NOT NULL,
  `term_id` bigint(20) NOT NULL,
  `is_variation_attribute` tinyint(1) NOT NULL,
  `in_stock` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_or_parent_id`,`term_id`,`product_id`,`taxonomy`),
  KEY `is_variation_attribute_term_id` (`is_variation_attribute`,`term_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_product_attributes_lookup`
--

LOCK TABLES `wp_wc_product_attributes_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_product_attributes_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_product_attributes_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_product_download_directories`
--

DROP TABLE IF EXISTS `wp_wc_product_download_directories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_product_download_directories` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`url_id`),
  KEY `url` (`url`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_product_download_directories`
--

LOCK TABLES `wp_wc_product_download_directories` WRITE;
/*!40000 ALTER TABLE `wp_wc_product_download_directories` DISABLE KEYS */;
INSERT INTO `wp_wc_product_download_directories` VALUES (1,'file:///Users/brianjessee/Local Sites/tribe/tests/app/public/wp-content/uploads/woocommerce_uploads/',1),(2,'http://wordpress.test/wp-content/uploads/woocommerce_uploads/',1);
/*!40000 ALTER TABLE `wp_wc_product_download_directories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_product_meta_lookup`
--

DROP TABLE IF EXISTS `wp_wc_product_meta_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_product_meta_lookup` (
  `product_id` bigint(20) NOT NULL,
  `sku` varchar(100) DEFAULT '',
  `virtual` tinyint(1) DEFAULT 0,
  `downloadable` tinyint(1) DEFAULT 0,
  `min_price` decimal(19,4) DEFAULT NULL,
  `max_price` decimal(19,4) DEFAULT NULL,
  `onsale` tinyint(1) DEFAULT 0,
  `stock_quantity` double DEFAULT NULL,
  `stock_status` varchar(100) DEFAULT 'instock',
  `rating_count` bigint(20) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `total_sales` bigint(20) DEFAULT 0,
  `tax_status` varchar(100) DEFAULT 'taxable',
  `tax_class` varchar(100) DEFAULT '',
  PRIMARY KEY (`product_id`),
  KEY `virtual` (`virtual`),
  KEY `downloadable` (`downloadable`),
  KEY `stock_status` (`stock_status`),
  KEY `stock_quantity` (`stock_quantity`),
  KEY `onsale` (`onsale`),
  KEY `min_max_price` (`min_price`,`max_price`),
  KEY `sku` (`sku`(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_product_meta_lookup`
--

LOCK TABLES `wp_wc_product_meta_lookup` WRITE;
/*!40000 ALTER TABLE `wp_wc_product_meta_lookup` DISABLE KEYS */;
INSERT INTO `wp_wc_product_meta_lookup` VALUES (19,'',1,0,0.0000,0.0000,0,NULL,'instock',0,0.00,0,'taxable','');
/*!40000 ALTER TABLE `wp_wc_product_meta_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_rate_limits`
--

DROP TABLE IF EXISTS `wp_wc_rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_rate_limits` (
  `rate_limit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rate_limit_key` varchar(200) NOT NULL,
  `rate_limit_expiry` bigint(20) unsigned NOT NULL,
  `rate_limit_remaining` smallint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rate_limit_id`),
  UNIQUE KEY `rate_limit_key` (`rate_limit_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_rate_limits`
--

LOCK TABLES `wp_wc_rate_limits` WRITE;
/*!40000 ALTER TABLE `wp_wc_rate_limits` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_reserved_stock`
--

DROP TABLE IF EXISTS `wp_wc_reserved_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_reserved_stock` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `stock_quantity` double NOT NULL DEFAULT 0,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_reserved_stock`
--

LOCK TABLES `wp_wc_reserved_stock` WRITE;
/*!40000 ALTER TABLE `wp_wc_reserved_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_reserved_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_tax_rate_classes`
--

DROP TABLE IF EXISTS `wp_wc_tax_rate_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_tax_rate_classes` (
  `tax_rate_class_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_class_id`),
  UNIQUE KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_tax_rate_classes`
--

LOCK TABLES `wp_wc_tax_rate_classes` WRITE;
/*!40000 ALTER TABLE `wp_wc_tax_rate_classes` DISABLE KEYS */;
INSERT INTO `wp_wc_tax_rate_classes` VALUES (1,'Reduced rate','reduced-rate'),(2,'Zero rate','zero-rate');
/*!40000 ALTER TABLE `wp_wc_tax_rate_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_wc_webhooks`
--

DROP TABLE IF EXISTS `wp_wc_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_wc_webhooks` (
  `webhook_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(200) NOT NULL,
  `name` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `delivery_url` text NOT NULL,
  `secret` text NOT NULL,
  `topic` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `api_version` smallint(4) NOT NULL,
  `failure_count` smallint(10) NOT NULL DEFAULT 0,
  `pending_delivery` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`webhook_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_wc_webhooks`
--

LOCK TABLES `wp_wc_webhooks` WRITE;
/*!40000 ALTER TABLE `wp_wc_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_wc_webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_api_keys`
--

DROP TABLE IF EXISTS `wp_woocommerce_api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_api_keys` (
  `key_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `permissions` varchar(10) NOT NULL,
  `consumer_key` char(64) NOT NULL,
  `consumer_secret` char(43) NOT NULL,
  `nonces` longtext DEFAULT NULL,
  `truncated_key` char(7) NOT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  KEY `consumer_key` (`consumer_key`),
  KEY `consumer_secret` (`consumer_secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_api_keys`
--

LOCK TABLES `wp_woocommerce_api_keys` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_attribute_taxonomies`
--

DROP TABLE IF EXISTS `wp_woocommerce_attribute_taxonomies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(200) NOT NULL,
  `attribute_label` varchar(200) DEFAULT NULL,
  `attribute_type` varchar(20) NOT NULL,
  `attribute_orderby` varchar(20) NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`attribute_id`),
  KEY `attribute_name` (`attribute_name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_attribute_taxonomies`
--

LOCK TABLES `wp_woocommerce_attribute_taxonomies` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_attribute_taxonomies` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_attribute_taxonomies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_downloadable_product_permissions`
--

DROP TABLE IF EXISTS `wp_woocommerce_downloadable_product_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_downloadable_product_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `download_id` varchar(36) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_key` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `downloads_remaining` varchar(9) DEFAULT NULL,
  `access_granted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access_expires` datetime DEFAULT NULL,
  `download_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`permission_id`),
  KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(16),`download_id`),
  KEY `download_order_product` (`download_id`,`order_id`,`product_id`),
  KEY `order_id` (`order_id`),
  KEY `user_order_remaining_expires` (`user_id`,`order_id`,`downloads_remaining`,`access_expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_downloadable_product_permissions`
--

LOCK TABLES `wp_woocommerce_downloadable_product_permissions` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_downloadable_product_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_downloadable_product_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_log`
--

DROP TABLE IF EXISTS `wp_woocommerce_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `level` smallint(4) NOT NULL,
  `source` varchar(200) NOT NULL,
  `message` longtext NOT NULL,
  `context` longtext DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_log`
--

LOCK TABLES `wp_woocommerce_log` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_order_itemmeta`
--

DROP TABLE IF EXISTS `wp_woocommerce_order_itemmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_order_itemmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_order_itemmeta`
--

LOCK TABLES `wp_woocommerce_order_itemmeta` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_order_itemmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_order_itemmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_order_items`
--

DROP TABLE IF EXISTS `wp_woocommerce_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_order_items` (
  `order_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_name` text NOT NULL,
  `order_item_type` varchar(200) NOT NULL DEFAULT '',
  `order_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_order_items`
--

LOCK TABLES `wp_woocommerce_order_items` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_payment_tokenmeta`
--

DROP TABLE IF EXISTS `wp_woocommerce_payment_tokenmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_payment_tokenmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_token_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `payment_token_id` (`payment_token_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_payment_tokenmeta`
--

LOCK TABLES `wp_woocommerce_payment_tokenmeta` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokenmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokenmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_payment_tokens`
--

DROP TABLE IF EXISTS `wp_woocommerce_payment_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_payment_tokens` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_id` varchar(200) NOT NULL,
  `token` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(200) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`token_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_payment_tokens`
--

LOCK TABLES `wp_woocommerce_payment_tokens` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_payment_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_sessions`
--

DROP TABLE IF EXISTS `wp_woocommerce_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_sessions` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` char(32) NOT NULL,
  `session_value` longtext NOT NULL,
  `session_expiry` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_key` (`session_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_sessions`
--

LOCK TABLES `wp_woocommerce_sessions` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_sessions` DISABLE KEYS */;
INSERT INTO `wp_woocommerce_sessions` VALUES (1,'1','a:7:{s:4:\"cart\";s:6:\"a:0:{}\";s:11:\"cart_totals\";s:367:\"a:15:{s:8:\"subtotal\";i:0;s:12:\"subtotal_tax\";i:0;s:14:\"shipping_total\";i:0;s:12:\"shipping_tax\";i:0;s:14:\"shipping_taxes\";a:0:{}s:14:\"discount_total\";i:0;s:12:\"discount_tax\";i:0;s:19:\"cart_contents_total\";i:0;s:17:\"cart_contents_tax\";i:0;s:19:\"cart_contents_taxes\";a:0:{}s:9:\"fee_total\";i:0;s:7:\"fee_tax\";i:0;s:9:\"fee_taxes\";a:0:{}s:5:\"total\";i:0;s:9:\"total_tax\";i:0;}\";s:15:\"applied_coupons\";s:6:\"a:0:{}\";s:22:\"coupon_discount_totals\";s:6:\"a:0:{}\";s:26:\"coupon_discount_tax_totals\";s:6:\"a:0:{}\";s:21:\"removed_cart_contents\";s:6:\"a:0:{}\";s:8:\"customer\";s:741:\"a:27:{s:2:\"id\";s:1:\"1\";s:13:\"date_modified\";s:0:\"\";s:8:\"postcode\";s:0:\"\";s:4:\"city\";s:0:\"\";s:9:\"address_1\";s:0:\"\";s:7:\"address\";s:0:\"\";s:9:\"address_2\";s:0:\"\";s:5:\"state\";s:2:\"NY\";s:7:\"country\";s:2:\"US\";s:17:\"shipping_postcode\";s:0:\"\";s:13:\"shipping_city\";s:0:\"\";s:18:\"shipping_address_1\";s:0:\"\";s:16:\"shipping_address\";s:0:\"\";s:18:\"shipping_address_2\";s:0:\"\";s:14:\"shipping_state\";s:2:\"NY\";s:16:\"shipping_country\";s:2:\"US\";s:13:\"is_vat_exempt\";s:0:\"\";s:19:\"calculated_shipping\";s:0:\"\";s:10:\"first_name\";s:0:\"\";s:9:\"last_name\";s:0:\"\";s:7:\"company\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:5:\"email\";s:20:\"admin@wordpress.test\";s:19:\"shipping_first_name\";s:0:\"\";s:18:\"shipping_last_name\";s:0:\"\";s:16:\"shipping_company\";s:0:\"\";s:14:\"shipping_phone\";s:0:\"\";}\";}',1673097178);
/*!40000 ALTER TABLE `wp_woocommerce_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_shipping_zone_locations`
--

DROP TABLE IF EXISTS `wp_woocommerce_shipping_zone_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_shipping_zone_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` bigint(20) unsigned NOT NULL,
  `location_code` varchar(200) NOT NULL,
  `location_type` varchar(40) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `location_id` (`location_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20)),
  KEY `zone_id` (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_shipping_zone_locations`
--

LOCK TABLES `wp_woocommerce_shipping_zone_locations` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_shipping_zone_methods`
--

DROP TABLE IF EXISTS `wp_woocommerce_shipping_zone_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_shipping_zone_methods` (
  `zone_id` bigint(20) unsigned NOT NULL,
  `instance_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `method_id` varchar(200) NOT NULL,
  `method_order` bigint(20) unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_shipping_zone_methods`
--

LOCK TABLES `wp_woocommerce_shipping_zone_methods` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zone_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_shipping_zones`
--

DROP TABLE IF EXISTS `wp_woocommerce_shipping_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_shipping_zones` (
  `zone_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(200) NOT NULL,
  `zone_order` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_shipping_zones`
--

LOCK TABLES `wp_woocommerce_shipping_zones` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_shipping_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_tax_rate_locations`
--

DROP TABLE IF EXISTS `wp_woocommerce_tax_rate_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_tax_rate_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_code` varchar(200) NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `location_type` varchar(40) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_tax_rate_locations`
--

LOCK TABLES `wp_woocommerce_tax_rate_locations` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rate_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rate_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_woocommerce_tax_rates`
--

DROP TABLE IF EXISTS `wp_woocommerce_tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wp_woocommerce_tax_rates` (
  `tax_rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tax_rate_country` varchar(2) NOT NULL DEFAULT '',
  `tax_rate_state` varchar(200) NOT NULL DEFAULT '',
  `tax_rate` varchar(8) NOT NULL DEFAULT '',
  `tax_rate_name` varchar(200) NOT NULL DEFAULT '',
  `tax_rate_priority` bigint(20) unsigned NOT NULL,
  `tax_rate_compound` int(1) NOT NULL DEFAULT 0,
  `tax_rate_shipping` int(1) NOT NULL DEFAULT 1,
  `tax_rate_order` bigint(20) unsigned NOT NULL,
  `tax_rate_class` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_id`),
  KEY `tax_rate_country` (`tax_rate_country`),
  KEY `tax_rate_state` (`tax_rate_state`(2)),
  KEY `tax_rate_class` (`tax_rate_class`(10)),
  KEY `tax_rate_priority` (`tax_rate_priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_woocommerce_tax_rates`
--

LOCK TABLES `wp_woocommerce_tax_rates` WRITE;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_woocommerce_tax_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_actionscheduler_actions`
--

DROP TABLE IF EXISTS `wptests_actionscheduler_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_actionscheduler_actions` (
  `action_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hook` varchar(191) NOT NULL,
  `status` varchar(20) NOT NULL,
  `scheduled_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `scheduled_date_local` datetime DEFAULT '0000-00-00 00:00:00',
  `priority` tinyint(3) unsigned NOT NULL DEFAULT 10,
  `args` varchar(191) DEFAULT NULL,
  `schedule` longtext DEFAULT NULL,
  `group_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `last_attempt_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `extended_args` varchar(8000) DEFAULT NULL,
  PRIMARY KEY (`action_id`),
  KEY `hook_status_scheduled_date_gmt` (`hook`(163),`status`,`scheduled_date_gmt`),
  KEY `status_scheduled_date_gmt` (`status`,`scheduled_date_gmt`),
  KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  KEY `args` (`args`),
  KEY `group_id` (`group_id`),
  KEY `last_attempt_gmt` (`last_attempt_gmt`),
  KEY `claim_id_status_scheduled_date_gmt` (`claim_id`,`status`,`scheduled_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_actionscheduler_actions`
--

LOCK TABLES `wptests_actionscheduler_actions` WRITE;
/*!40000 ALTER TABLE `wptests_actionscheduler_actions` DISABLE KEYS */;
INSERT INTO `wptests_actionscheduler_actions` VALUES (5,'action_scheduler/migration_hook','pending','2025-01-08 13:21:20','2025-01-08 13:21:20',10,'[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342480;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342480;}',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(6,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:28','2025-01-08 08:20:28',10,'[5127,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342428;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342428;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(7,'wc-admin_import_orders','pending','2025-01-08 13:20:32','2025-01-08 08:20:32',10,'[5128]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342432;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342432;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(8,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:28','2025-01-08 08:20:28',10,'[5131,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342428;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342428;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(9,'wc-admin_import_orders','pending','2025-01-08 13:20:32','2025-01-08 08:20:32',10,'[5132]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342432;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342432;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(10,'wc-admin_import_orders','pending','2025-01-08 13:20:32','2025-01-08 08:20:32',10,'[5134]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342432;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342432;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(11,'wc-admin_import_orders','pending','2025-01-08 13:20:32','2025-01-08 08:20:32',10,'[5136]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342432;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342432;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(12,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:28','2025-01-08 08:20:28',10,'[5139,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342428;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342428;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(13,'wc-admin_import_orders','pending','2025-01-08 13:20:32','2025-01-08 08:20:32',10,'[5140]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342432;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342432;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(14,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:29','2025-01-08 08:20:29',10,'[5178,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342429;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342429;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(15,'wc-admin_import_orders','pending','2025-01-08 13:20:33','2025-01-08 08:20:33',10,'[5179]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342433;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342433;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(16,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:29','2025-01-08 08:20:29',10,'[5182,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342429;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342429;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(17,'wc-admin_import_orders','pending','2025-01-08 13:20:33','2025-01-08 08:20:33',10,'[5183]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342433;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342433;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(18,'wc-admin_import_orders','pending','2025-01-08 13:20:33','2025-01-08 08:20:33',10,'[5185]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342433;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342433;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(19,'wc-admin_import_orders','pending','2025-01-08 13:20:33','2025-01-08 08:20:33',10,'[5187]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342433;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342433;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(20,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:29','2025-01-08 08:20:29',10,'[5190,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342429;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342429;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(21,'wc-admin_import_orders','pending','2025-01-08 13:20:33','2025-01-08 08:20:33',10,'[5191]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342433;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342433;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(24,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:31','2025-01-08 08:20:31',10,'[5220,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342431;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342431;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(26,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:31','2025-01-08 08:20:31',10,'[5225,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342431;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342431;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(27,'wc-admin_import_orders','pending','2025-01-08 13:20:35','2025-01-08 08:20:35',10,'[5226]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342435;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342435;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(28,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:31','2025-01-08 08:20:31',10,'[5229,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342431;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342431;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(33,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:33','2025-01-08 08:20:33',10,'[5251,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342433;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342433;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(36,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:34','2025-01-08 08:20:34',10,'[5257,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342434;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342434;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(39,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:34','2025-01-08 08:20:34',10,'[5263,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342434;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342434;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(42,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:35','2025-01-08 08:20:35',10,'[5290,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342435;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342435;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(43,'wc-admin_import_orders','pending','2025-01-08 13:20:39','2025-01-08 08:20:39',10,'[5291]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342439;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342439;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(44,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:35','2025-01-08 08:20:35',10,'[5294,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342435;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342435;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(45,'wc-admin_import_orders','pending','2025-01-08 13:20:39','2025-01-08 08:20:39',10,'[5295]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342439;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342439;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(46,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:35','2025-01-08 08:20:35',10,'[5298,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342435;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342435;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(47,'wc-admin_import_orders','pending','2025-01-08 13:20:39','2025-01-08 08:20:39',10,'[5299]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342439;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342439;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(48,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:36','2025-01-08 08:20:36',10,'[5324,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342436;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342436;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(49,'wc-admin_import_orders','pending','2025-01-08 13:20:40','2025-01-08 08:20:40',10,'[5325]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342440;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342440;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(50,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:39','2025-01-08 08:20:39',10,'[5368,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342439;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342439;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(52,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:39','2025-01-08 08:20:39',10,'[5373,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342439;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342439;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(54,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:39','2025-01-08 08:20:39',10,'[5378,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342439;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342439;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(56,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:40','2025-01-08 08:20:40',10,'[5383,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342440;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342440;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(59,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:42','2025-01-08 08:20:42',10,'[5445,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342442;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342442;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(60,'wc-admin_import_orders','pending','2025-01-08 13:20:46','2025-01-08 08:20:46',10,'[5446]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342446;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342446;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(61,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:43','2025-01-08 08:20:43',10,'[5449,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342443;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342443;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(62,'wc-admin_import_orders','pending','2025-01-08 13:20:47','2025-01-08 08:20:47',10,'[5450]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342447;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342447;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(63,'wc-admin_import_orders','pending','2025-01-08 13:20:47','2025-01-08 08:20:47',10,'[5452]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342447;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342447;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(64,'wc-admin_import_orders','pending','2025-01-08 13:20:47','2025-01-08 08:20:47',10,'[5454]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342447;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342447;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(65,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:43','2025-01-08 08:20:43',10,'[5457,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342443;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342443;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(66,'wc-admin_import_orders','pending','2025-01-08 13:20:47','2025-01-08 08:20:47',10,'[5458]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342447;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342447;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(67,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:44','2025-01-08 08:20:44',10,'[5496,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342444;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342444;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(68,'wc-admin_import_orders','pending','2025-01-08 13:20:48','2025-01-08 08:20:48',10,'[5497]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342448;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342448;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(69,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:44','2025-01-08 08:20:44',10,'[5500,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342444;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342444;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(70,'wc-admin_import_orders','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5501]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(71,'wc-admin_import_orders','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5503]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(72,'wc-admin_import_orders','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5505]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(73,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:45','2025-01-08 08:20:45',10,'[5508,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342445;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342445;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(74,'wc-admin_import_orders','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5509]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(75,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:45','2025-01-08 08:20:45',10,'[5512,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342445;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342445;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(76,'wc-admin_import_orders','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5513]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(79,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:48','2025-01-08 08:20:48',10,'[5547,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342448;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342448;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(81,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:48','2025-01-08 08:20:48',10,'[5552,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342448;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342448;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(82,'wc-admin_import_orders','pending','2025-01-08 13:20:52','2025-01-08 08:20:52',10,'[5553]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342452;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342452;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(83,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5556,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(85,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:49','2025-01-08 08:20:49',10,'[5561,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342449;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342449;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(90,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:52','2025-01-08 08:20:52',10,'[5579,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342452;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342452;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(93,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:52','2025-01-08 08:20:52',10,'[5585,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342452;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342452;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(96,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:52','2025-01-08 08:20:52',10,'[5591,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342452;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342452;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(99,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:53','2025-01-08 08:20:53',10,'[5618,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342453;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342453;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(100,'wc-admin_import_orders','pending','2025-01-08 13:20:57','2025-01-08 08:20:57',10,'[5619]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342457;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342457;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(101,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:20:53','2025-01-08 08:20:53',10,'[5622,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342453;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342453;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(102,'wc-admin_import_orders','pending','2025-01-08 13:20:58','2025-01-08 08:20:58',10,'[5623]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736342458;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736342458;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(103,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:33:29','2025-01-08 08:33:29',10,'[5097,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343209;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343209;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(105,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:33:29','2025-01-08 08:33:29',10,'[5102,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343209;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343209;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(107,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:33:29','2025-01-08 08:33:29',10,'[5107,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343209;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343209;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(109,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:33:30','2025-01-08 08:33:30',10,'[5112,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343210;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343210;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(115,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:37:42','2025-01-08 08:37:42',10,'[5227,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343462;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343462;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(116,'wc-admin_import_orders','pending','2025-01-08 13:37:46','2025-01-08 08:37:46',10,'[5228]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343466;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343466;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(117,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:37:42','2025-01-08 08:37:42',10,'[5231,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343462;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343462;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(122,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:37:57','2025-01-08 08:37:57',10,'[5253,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343477;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343477;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(125,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:37:58','2025-01-08 08:37:58',10,'[5259,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343478;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343478;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(128,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:37:59','2025-01-08 08:37:59',10,'[5265,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343479;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343479;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(131,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:00','2025-01-08 08:38:00',10,'[5292,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343480;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343480;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(132,'wc-admin_import_orders','pending','2025-01-08 13:38:05','2025-01-08 08:38:05',10,'[5293]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343485;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343485;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(133,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:01','2025-01-08 08:38:01',10,'[5296,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343481;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343481;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(134,'wc-admin_import_orders','pending','2025-01-08 13:38:05','2025-01-08 08:38:05',10,'[5297]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343485;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343485;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(135,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:01','2025-01-08 08:38:01',10,'[5300,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343481;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343481;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(136,'wc-admin_import_orders','pending','2025-01-08 13:38:05','2025-01-08 08:38:05',10,'[5301]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343485;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343485;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(137,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:03','2025-01-08 08:38:03',10,'[5326,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343483;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343483;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(138,'wc-admin_import_orders','pending','2025-01-08 13:38:07','2025-01-08 08:38:07',10,'[5327]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343487;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343487;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(139,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:14','2025-01-08 08:38:14',10,'[5370,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343494;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343494;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(141,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:15','2025-01-08 08:38:15',10,'[5375,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343495;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343495;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(143,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:15','2025-01-08 08:38:15',10,'[5380,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343495;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343495;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(145,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:16','2025-01-08 08:38:16',10,'[5385,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343496;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343496;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(148,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:20','2025-01-08 08:38:20',10,'[5447,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343500;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343500;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(149,'wc-admin_import_orders','pending','2025-01-08 13:38:24','2025-01-08 08:38:24',10,'[5448]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343504;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343504;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(150,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:20','2025-01-08 08:38:20',10,'[5451,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343500;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343500;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(151,'wc-admin_import_orders','pending','2025-01-08 13:38:25','2025-01-08 08:38:25',10,'[5456]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343505;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343505;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(152,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:21','2025-01-08 08:38:21',10,'[5459,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343501;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343501;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(153,'wc-admin_import_orders','pending','2025-01-08 13:38:25','2025-01-08 08:38:25',10,'[5460]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343505;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343505;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(154,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:23','2025-01-08 08:38:23',10,'[5498,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343503;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343503;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(155,'wc-admin_import_orders','pending','2025-01-08 13:38:27','2025-01-08 08:38:27',10,'[5499]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343507;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343507;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(156,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:23','2025-01-08 08:38:23',10,'[5502,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343503;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343503;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(157,'wc-admin_import_orders','pending','2025-01-08 13:38:28','2025-01-08 08:38:28',10,'[5507]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343508;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343508;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(158,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:24','2025-01-08 08:38:24',10,'[5510,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343504;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343504;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(159,'wc-admin_import_orders','pending','2025-01-08 13:38:28','2025-01-08 08:38:28',10,'[5511]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343508;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343508;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(160,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:24','2025-01-08 08:38:24',10,'[5514,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343504;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343504;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(161,'wc-admin_import_orders','pending','2025-01-08 13:38:28','2025-01-08 08:38:28',10,'[5515]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343508;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343508;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(164,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:36','2025-01-08 08:38:36',10,'[5549,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343516;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343516;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(166,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:36','2025-01-08 08:38:36',10,'[5554,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343516;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343516;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(167,'wc-admin_import_orders','pending','2025-01-08 13:38:40','2025-01-08 08:38:40',10,'[5555]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343520;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343520;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(168,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:37','2025-01-08 08:38:37',10,'[5558,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343517;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343517;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(170,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:37','2025-01-08 08:38:37',10,'[5563,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343517;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343517;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(175,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:54','2025-01-08 08:38:54',10,'[5581,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343534;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343534;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(178,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:55','2025-01-08 08:38:55',10,'[5587,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343535;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343535;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(181,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:55','2025-01-08 08:38:55',10,'[5593,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343535;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343535;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(184,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:57','2025-01-08 08:38:57',10,'[5620,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343537;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343537;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(185,'wc-admin_import_orders','pending','2025-01-08 13:39:01','2025-01-08 08:39:01',10,'[5621]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343541;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343541;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(186,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:38:57','2025-01-08 08:38:57',10,'[5624,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343537;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343537;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(187,'wc-admin_import_orders','pending','2025-01-08 13:39:02','2025-01-08 08:39:02',10,'[5625]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736343542;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736343542;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(191,'wc-admin_import_orders','pending','2025-01-08 13:47:05','2025-01-08 08:47:05',10,'[5230]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344025;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344025;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(192,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:02','2025-01-08 08:47:02',10,'[5235,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344022;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344022;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(201,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:24','2025-01-08 08:47:24',10,'[5273,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344044;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344044;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(204,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:26','2025-01-08 08:47:26',10,'[5304,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344046;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344046;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(205,'wc-admin_import_orders','pending','2025-01-08 13:47:30','2025-01-08 08:47:30',10,'[5305]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344050;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344050;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(206,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:26','2025-01-08 08:47:26',10,'[5308,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344046;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344046;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(207,'wc-admin_import_orders','pending','2025-01-08 13:47:30','2025-01-08 08:47:30',10,'[5309]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344050;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344050;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(208,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:27','2025-01-08 08:47:27',10,'[5334,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344047;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344047;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(209,'wc-admin_import_orders','pending','2025-01-08 13:47:32','2025-01-08 08:47:32',10,'[5335]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344052;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344052;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(212,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:44','2025-01-08 08:47:44',10,'[5392,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344064;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344064;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(214,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:45','2025-01-08 08:47:45',10,'[5397,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344065;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344065;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(217,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:49','2025-01-08 08:47:49',10,'[5463,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344069;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344069;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(218,'wc-admin_import_orders','pending','2025-01-08 13:47:53','2025-01-08 08:47:53',10,'[5464]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344073;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344073;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(219,'wc-admin_import_orders','pending','2025-01-08 13:47:53','2025-01-08 08:47:53',10,'[5466]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344073;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344073;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(220,'wc-admin_import_orders','pending','2025-01-08 13:47:53','2025-01-08 08:47:53',10,'[5468]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344073;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344073;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(221,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:50','2025-01-08 08:47:50',10,'[5471,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344070;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344070;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(222,'wc-admin_import_orders','pending','2025-01-08 13:47:54','2025-01-08 08:47:54',10,'[5472]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344074;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344074;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(223,'wc-admin_import_orders','pending','2025-01-08 13:47:56','2025-01-08 08:47:56',10,'[5517]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344076;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344076;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(224,'wc-admin_import_orders','pending','2025-01-08 13:47:56','2025-01-08 08:47:56',10,'[5519]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344076;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344076;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(225,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:52','2025-01-08 08:47:52',10,'[5522,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344072;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344072;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(226,'wc-admin_import_orders','pending','2025-01-08 13:47:57','2025-01-08 08:47:57',10,'[5523]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344077;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344077;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(227,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:47:53','2025-01-08 08:47:53',10,'[5526,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344073;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344073;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(228,'wc-admin_import_orders','pending','2025-01-08 13:47:57','2025-01-08 08:47:57',10,'[5527]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344077;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344077;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(232,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:09','2025-01-08 08:48:09',10,'[5568,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344089;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344089;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(233,'wc-admin_import_orders','pending','2025-01-08 13:48:13','2025-01-08 08:48:13',10,'[5569]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344093;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344093;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(234,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:10','2025-01-08 08:48:10',10,'[5572,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344090;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344090;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(236,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:10','2025-01-08 08:48:10',10,'[5577,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344090;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344090;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(241,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:32','2025-01-08 08:48:32',10,'[5595,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344112;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344112;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(244,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:33','2025-01-08 08:48:33',10,'[5601,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344113;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344113;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(247,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:33','2025-01-08 08:48:33',10,'[5607,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344113;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344113;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(250,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:35','2025-01-08 08:48:35',10,'[5634,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344115;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344115;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(251,'wc-admin_import_orders','pending','2025-01-08 13:48:39','2025-01-08 08:48:39',10,'[5635]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344119;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344119;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(252,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:48:35','2025-01-08 08:48:35',10,'[5638,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344115;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344115;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(253,'wc-admin_import_orders','pending','2025-01-08 13:48:39','2025-01-08 08:48:39',10,'[5639]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344119;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344119;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(257,'wc-admin_import_orders','pending','2025-01-08 13:49:50','2025-01-08 08:49:50',10,'[5232]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344190;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344190;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(258,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:49:46','2025-01-08 08:49:46',10,'[5237,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344186;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344186;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(265,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:14','2025-01-08 08:50:14',10,'[5267,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344214;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344214;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(270,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:16','2025-01-08 08:50:16',10,'[5302,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344216;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344216;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(271,'wc-admin_import_orders','pending','2025-01-08 13:50:20','2025-01-08 08:50:20',10,'[5303]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344220;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344220;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(272,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:16','2025-01-08 08:50:16',10,'[5306,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344216;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344216;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(273,'wc-admin_import_orders','pending','2025-01-08 13:50:20','2025-01-08 08:50:20',10,'[5307]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344220;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344220;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(274,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:16','2025-01-08 08:50:16',10,'[5310,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344216;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344216;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(275,'wc-admin_import_orders','pending','2025-01-08 13:50:21','2025-01-08 08:50:21',10,'[5311]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344221;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344221;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(276,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:18','2025-01-08 08:50:18',10,'[5336,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344218;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344218;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(277,'wc-admin_import_orders','pending','2025-01-08 13:50:22','2025-01-08 08:50:22',10,'[5337]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344222;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344222;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(279,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:37','2025-01-08 08:50:37',10,'[5387,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344237;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344237;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(282,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:38','2025-01-08 08:50:38',10,'[5399,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344238;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344238;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(285,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:41','2025-01-08 08:50:41',10,'[5461,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344241;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344241;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(286,'wc-admin_import_orders','pending','2025-01-08 13:50:45','2025-01-08 08:50:45',10,'[5462]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344245;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344245;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(287,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:42','2025-01-08 08:50:42',10,'[5465,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344242;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344242;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(288,'wc-admin_import_orders','pending','2025-01-08 13:50:46','2025-01-08 08:50:46',10,'[5470]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344246;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344246;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(289,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:42','2025-01-08 08:50:42',10,'[5473,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344242;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344242;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(290,'wc-admin_import_orders','pending','2025-01-08 13:50:46','2025-01-08 08:50:46',10,'[5474]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344246;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344246;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(291,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:44','2025-01-08 08:50:44',10,'[5516,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344244;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344244;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(292,'wc-admin_import_orders','pending','2025-01-08 13:50:49','2025-01-08 08:50:49',10,'[5521]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344249;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344249;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(293,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:45','2025-01-08 08:50:45',10,'[5524,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344245;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344245;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(294,'wc-admin_import_orders','pending','2025-01-08 13:50:49','2025-01-08 08:50:49',10,'[5525]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344249;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344249;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(295,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:50:45','2025-01-08 08:50:45',10,'[5528,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344245;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344245;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(296,'wc-admin_import_orders','pending','2025-01-08 13:50:49','2025-01-08 08:50:49',10,'[5529]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344249;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344249;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(300,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:51:05','2025-01-08 08:51:05',10,'[5570,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344265;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344265;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(301,'wc-admin_import_orders','pending','2025-01-08 13:51:09','2025-01-08 08:51:09',10,'[5571]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344269;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344269;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(302,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:51:05','2025-01-08 08:51:05',10,'[5574,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344265;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344265;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(308,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:51:32','2025-01-08 08:51:32',10,'[5599,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344292;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344292;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(311,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:51:33','2025-01-08 08:51:33',10,'[5605,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344293;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344293;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(314,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:51:34','2025-01-08 08:51:34',10,'[5611,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344294;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344294;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(317,'woocommerce_run_product_attribute_lookup_update_callback','pending','2025-01-08 13:51:35','2025-01-08 08:51:35',10,'[5642,3]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344295;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344295;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL),(318,'wc-admin_import_orders','pending','2025-01-08 13:51:40','2025-01-08 08:51:40',10,'[5643]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1736344300;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1736344300;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL);
/*!40000 ALTER TABLE `wptests_actionscheduler_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_actionscheduler_claims`
--

DROP TABLE IF EXISTS `wptests_actionscheduler_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_actionscheduler_claims` (
  `claim_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date_created_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`claim_id`),
  KEY `date_created_gmt` (`date_created_gmt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_actionscheduler_claims`
--

LOCK TABLES `wptests_actionscheduler_claims` WRITE;
/*!40000 ALTER TABLE `wptests_actionscheduler_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_actionscheduler_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_actionscheduler_groups`
--

DROP TABLE IF EXISTS `wptests_actionscheduler_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_actionscheduler_groups` (
  `group_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`group_id`),
  KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_actionscheduler_groups`
--

LOCK TABLES `wptests_actionscheduler_groups` WRITE;
/*!40000 ALTER TABLE `wptests_actionscheduler_groups` DISABLE KEYS */;
INSERT INTO `wptests_actionscheduler_groups` VALUES (1,'action-scheduler-migration'),(2,'woocommerce-db-updates'),(3,'wc-admin-data');
/*!40000 ALTER TABLE `wptests_actionscheduler_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_actionscheduler_logs`
--

DROP TABLE IF EXISTS `wptests_actionscheduler_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_actionscheduler_logs` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `log_date_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `log_date_local` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`log_id`),
  KEY `action_id` (`action_id`),
  KEY `log_date_gmt` (`log_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_actionscheduler_logs`
--

LOCK TABLES `wptests_actionscheduler_logs` WRITE;
/*!40000 ALTER TABLE `wptests_actionscheduler_logs` DISABLE KEYS */;
INSERT INTO `wptests_actionscheduler_logs` VALUES (1,5,'action created','2025-01-08 13:20:20','2025-01-08 13:20:20'),(2,6,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(3,7,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(4,8,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(5,9,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(6,10,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(7,11,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(8,12,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(9,13,'action created','2025-01-08 13:20:27','2025-01-08 08:20:27'),(10,14,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(11,15,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(12,16,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(13,17,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(14,18,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(15,19,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(16,20,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(17,21,'action created','2025-01-08 13:20:28','2025-01-08 08:20:28'),(20,24,'action created','2025-01-08 13:20:30','2025-01-08 08:20:30'),(22,26,'action created','2025-01-08 13:20:30','2025-01-08 08:20:30'),(23,27,'action created','2025-01-08 13:20:30','2025-01-08 08:20:30'),(24,28,'action created','2025-01-08 13:20:30','2025-01-08 08:20:30'),(29,33,'action created','2025-01-08 13:20:32','2025-01-08 08:20:32'),(32,36,'action created','2025-01-08 13:20:33','2025-01-08 08:20:33'),(35,39,'action created','2025-01-08 13:20:33','2025-01-08 08:20:33'),(38,42,'action created','2025-01-08 13:20:34','2025-01-08 08:20:34'),(39,43,'action created','2025-01-08 13:20:34','2025-01-08 08:20:34'),(40,44,'action created','2025-01-08 13:20:34','2025-01-08 08:20:34'),(41,45,'action created','2025-01-08 13:20:34','2025-01-08 08:20:34'),(42,46,'action created','2025-01-08 13:20:34','2025-01-08 08:20:34'),(43,47,'action created','2025-01-08 13:20:34','2025-01-08 08:20:34'),(44,48,'action created','2025-01-08 13:20:35','2025-01-08 08:20:35'),(45,49,'action created','2025-01-08 13:20:35','2025-01-08 08:20:35'),(46,50,'action created','2025-01-08 13:20:38','2025-01-08 08:20:38'),(48,52,'action created','2025-01-08 13:20:38','2025-01-08 08:20:38'),(50,54,'action created','2025-01-08 13:20:38','2025-01-08 08:20:38'),(52,56,'action created','2025-01-08 13:20:39','2025-01-08 08:20:39'),(55,59,'action created','2025-01-08 13:20:41','2025-01-08 08:20:41'),(56,60,'action created','2025-01-08 13:20:41','2025-01-08 08:20:41'),(57,61,'action created','2025-01-08 13:20:42','2025-01-08 08:20:42'),(58,62,'action created','2025-01-08 13:20:42','2025-01-08 08:20:42'),(59,63,'action created','2025-01-08 13:20:42','2025-01-08 08:20:42'),(60,64,'action created','2025-01-08 13:20:42','2025-01-08 08:20:42'),(61,65,'action created','2025-01-08 13:20:42','2025-01-08 08:20:42'),(62,66,'action created','2025-01-08 13:20:42','2025-01-08 08:20:42'),(63,67,'action created','2025-01-08 13:20:43','2025-01-08 08:20:43'),(64,68,'action created','2025-01-08 13:20:43','2025-01-08 08:20:43'),(65,69,'action created','2025-01-08 13:20:43','2025-01-08 08:20:43'),(66,70,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(67,71,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(68,72,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(69,73,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(70,74,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(71,75,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(72,76,'action created','2025-01-08 13:20:44','2025-01-08 08:20:44'),(75,79,'action created','2025-01-08 13:20:47','2025-01-08 08:20:47'),(77,81,'action created','2025-01-08 13:20:47','2025-01-08 08:20:47'),(78,82,'action created','2025-01-08 13:20:47','2025-01-08 08:20:47'),(79,83,'action created','2025-01-08 13:20:48','2025-01-08 08:20:48'),(81,85,'action created','2025-01-08 13:20:48','2025-01-08 08:20:48'),(86,90,'action created','2025-01-08 13:20:51','2025-01-08 08:20:51'),(89,93,'action created','2025-01-08 13:20:51','2025-01-08 08:20:51'),(92,96,'action created','2025-01-08 13:20:51','2025-01-08 08:20:51'),(95,99,'action created','2025-01-08 13:20:52','2025-01-08 08:20:52'),(96,100,'action created','2025-01-08 13:20:52','2025-01-08 08:20:52'),(97,101,'action created','2025-01-08 13:20:52','2025-01-08 08:20:52'),(98,102,'action created','2025-01-08 13:20:53','2025-01-08 08:20:53'),(99,103,'action created','2025-01-08 13:33:28','2025-01-08 08:33:28'),(101,105,'action created','2025-01-08 13:33:28','2025-01-08 08:33:28'),(103,107,'action created','2025-01-08 13:33:28','2025-01-08 08:33:28'),(105,109,'action created','2025-01-08 13:33:29','2025-01-08 08:33:29'),(111,115,'action created','2025-01-08 13:37:41','2025-01-08 08:37:41'),(112,116,'action created','2025-01-08 13:37:41','2025-01-08 08:37:41'),(113,117,'action created','2025-01-08 13:37:41','2025-01-08 08:37:41'),(118,122,'action created','2025-01-08 13:37:56','2025-01-08 08:37:56'),(121,125,'action created','2025-01-08 13:37:57','2025-01-08 08:37:57'),(124,128,'action created','2025-01-08 13:37:58','2025-01-08 08:37:58'),(127,131,'action created','2025-01-08 13:37:59','2025-01-08 08:37:59'),(128,132,'action created','2025-01-08 13:38:00','2025-01-08 08:38:00'),(129,133,'action created','2025-01-08 13:38:00','2025-01-08 08:38:00'),(130,134,'action created','2025-01-08 13:38:00','2025-01-08 08:38:00'),(131,135,'action created','2025-01-08 13:38:00','2025-01-08 08:38:00'),(132,136,'action created','2025-01-08 13:38:00','2025-01-08 08:38:00'),(133,137,'action created','2025-01-08 13:38:02','2025-01-08 08:38:02'),(134,138,'action created','2025-01-08 13:38:02','2025-01-08 08:38:02'),(135,139,'action created','2025-01-08 13:38:13','2025-01-08 08:38:13'),(137,141,'action created','2025-01-08 13:38:14','2025-01-08 08:38:14'),(139,143,'action created','2025-01-08 13:38:14','2025-01-08 08:38:14'),(141,145,'action created','2025-01-08 13:38:15','2025-01-08 08:38:15'),(144,148,'action created','2025-01-08 13:38:19','2025-01-08 08:38:19'),(145,149,'action created','2025-01-08 13:38:19','2025-01-08 08:38:19'),(146,150,'action created','2025-01-08 13:38:19','2025-01-08 08:38:19'),(147,151,'action created','2025-01-08 13:38:20','2025-01-08 08:38:20'),(148,152,'action created','2025-01-08 13:38:20','2025-01-08 08:38:20'),(149,153,'action created','2025-01-08 13:38:20','2025-01-08 08:38:20'),(150,154,'action created','2025-01-08 13:38:22','2025-01-08 08:38:22'),(151,155,'action created','2025-01-08 13:38:22','2025-01-08 08:38:22'),(152,156,'action created','2025-01-08 13:38:22','2025-01-08 08:38:22'),(153,157,'action created','2025-01-08 13:38:23','2025-01-08 08:38:23'),(154,158,'action created','2025-01-08 13:38:23','2025-01-08 08:38:23'),(155,159,'action created','2025-01-08 13:38:23','2025-01-08 08:38:23'),(156,160,'action created','2025-01-08 13:38:23','2025-01-08 08:38:23'),(157,161,'action created','2025-01-08 13:38:23','2025-01-08 08:38:23'),(160,164,'action created','2025-01-08 13:38:35','2025-01-08 08:38:35'),(162,166,'action created','2025-01-08 13:38:35','2025-01-08 08:38:35'),(163,167,'action created','2025-01-08 13:38:35','2025-01-08 08:38:35'),(164,168,'action created','2025-01-08 13:38:36','2025-01-08 08:38:36'),(166,170,'action created','2025-01-08 13:38:36','2025-01-08 08:38:36'),(171,175,'action created','2025-01-08 13:38:53','2025-01-08 08:38:53'),(174,178,'action created','2025-01-08 13:38:54','2025-01-08 08:38:54'),(177,181,'action created','2025-01-08 13:38:54','2025-01-08 08:38:54'),(180,184,'action created','2025-01-08 13:38:56','2025-01-08 08:38:56'),(181,185,'action created','2025-01-08 13:38:56','2025-01-08 08:38:56'),(182,186,'action created','2025-01-08 13:38:56','2025-01-08 08:38:56'),(183,187,'action created','2025-01-08 13:38:57','2025-01-08 08:38:57'),(187,191,'action created','2025-01-08 13:47:00','2025-01-08 08:47:00'),(188,192,'action created','2025-01-08 13:47:01','2025-01-08 08:47:01'),(197,201,'action created','2025-01-08 13:47:23','2025-01-08 08:47:23'),(200,204,'action created','2025-01-08 13:47:25','2025-01-08 08:47:25'),(201,205,'action created','2025-01-08 13:47:25','2025-01-08 08:47:25'),(202,206,'action created','2025-01-08 13:47:25','2025-01-08 08:47:25'),(203,207,'action created','2025-01-08 13:47:25','2025-01-08 08:47:25'),(204,208,'action created','2025-01-08 13:47:26','2025-01-08 08:47:26'),(205,209,'action created','2025-01-08 13:47:27','2025-01-08 08:47:27'),(208,212,'action created','2025-01-08 13:47:43','2025-01-08 08:47:43'),(210,214,'action created','2025-01-08 13:47:44','2025-01-08 08:47:44'),(213,217,'action created','2025-01-08 13:47:48','2025-01-08 08:47:48'),(214,218,'action created','2025-01-08 13:47:48','2025-01-08 08:47:48'),(215,219,'action created','2025-01-08 13:47:48','2025-01-08 08:47:48'),(216,220,'action created','2025-01-08 13:47:48','2025-01-08 08:47:48'),(217,221,'action created','2025-01-08 13:47:49','2025-01-08 08:47:49'),(218,222,'action created','2025-01-08 13:47:49','2025-01-08 08:47:49'),(219,223,'action created','2025-01-08 13:47:51','2025-01-08 08:47:51'),(220,224,'action created','2025-01-08 13:47:51','2025-01-08 08:47:51'),(221,225,'action created','2025-01-08 13:47:51','2025-01-08 08:47:51'),(222,226,'action created','2025-01-08 13:47:52','2025-01-08 08:47:52'),(223,227,'action created','2025-01-08 13:47:52','2025-01-08 08:47:52'),(224,228,'action created','2025-01-08 13:47:52','2025-01-08 08:47:52'),(228,232,'action created','2025-01-08 13:48:08','2025-01-08 08:48:08'),(229,233,'action created','2025-01-08 13:48:08','2025-01-08 08:48:08'),(230,234,'action created','2025-01-08 13:48:09','2025-01-08 08:48:09'),(232,236,'action created','2025-01-08 13:48:09','2025-01-08 08:48:09'),(237,241,'action created','2025-01-08 13:48:31','2025-01-08 08:48:31'),(240,244,'action created','2025-01-08 13:48:32','2025-01-08 08:48:32'),(243,247,'action created','2025-01-08 13:48:32','2025-01-08 08:48:32'),(246,250,'action created','2025-01-08 13:48:34','2025-01-08 08:48:34'),(247,251,'action created','2025-01-08 13:48:34','2025-01-08 08:48:34'),(248,252,'action created','2025-01-08 13:48:34','2025-01-08 08:48:34'),(249,253,'action created','2025-01-08 13:48:34','2025-01-08 08:48:34'),(253,257,'action created','2025-01-08 13:49:45','2025-01-08 08:49:45'),(254,258,'action created','2025-01-08 13:49:45','2025-01-08 08:49:45'),(261,265,'action created','2025-01-08 13:50:13','2025-01-08 08:50:13'),(266,270,'action created','2025-01-08 13:50:15','2025-01-08 08:50:15'),(267,271,'action created','2025-01-08 13:50:15','2025-01-08 08:50:15'),(268,272,'action created','2025-01-08 13:50:15','2025-01-08 08:50:15'),(269,273,'action created','2025-01-08 13:50:15','2025-01-08 08:50:15'),(270,274,'action created','2025-01-08 13:50:15','2025-01-08 08:50:15'),(271,275,'action created','2025-01-08 13:50:16','2025-01-08 08:50:16'),(272,276,'action created','2025-01-08 13:50:17','2025-01-08 08:50:17'),(273,277,'action created','2025-01-08 13:50:17','2025-01-08 08:50:17'),(275,279,'action created','2025-01-08 13:50:36','2025-01-08 08:50:36'),(278,282,'action created','2025-01-08 13:50:37','2025-01-08 08:50:37'),(281,285,'action created','2025-01-08 13:50:40','2025-01-08 08:50:40'),(282,286,'action created','2025-01-08 13:50:40','2025-01-08 08:50:40'),(283,287,'action created','2025-01-08 13:50:41','2025-01-08 08:50:41'),(284,288,'action created','2025-01-08 13:50:41','2025-01-08 08:50:41'),(285,289,'action created','2025-01-08 13:50:41','2025-01-08 08:50:41'),(286,290,'action created','2025-01-08 13:50:41','2025-01-08 08:50:41'),(287,291,'action created','2025-01-08 13:50:43','2025-01-08 08:50:43'),(288,292,'action created','2025-01-08 13:50:44','2025-01-08 08:50:44'),(289,293,'action created','2025-01-08 13:50:44','2025-01-08 08:50:44'),(290,294,'action created','2025-01-08 13:50:44','2025-01-08 08:50:44'),(291,295,'action created','2025-01-08 13:50:44','2025-01-08 08:50:44'),(292,296,'action created','2025-01-08 13:50:44','2025-01-08 08:50:44'),(296,300,'action created','2025-01-08 13:51:04','2025-01-08 08:51:04'),(297,301,'action created','2025-01-08 13:51:04','2025-01-08 08:51:04'),(298,302,'action created','2025-01-08 13:51:04','2025-01-08 08:51:04'),(304,308,'action created','2025-01-08 13:51:31','2025-01-08 08:51:31'),(307,311,'action created','2025-01-08 13:51:32','2025-01-08 08:51:32'),(310,314,'action created','2025-01-08 13:51:33','2025-01-08 08:51:33'),(313,317,'action created','2025-01-08 13:51:34','2025-01-08 08:51:34'),(314,318,'action created','2025-01-08 13:51:35','2025-01-08 08:51:35');
/*!40000 ALTER TABLE `wptests_actionscheduler_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_commentmeta`
--

DROP TABLE IF EXISTS `wptests_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_commentmeta`
--

LOCK TABLES `wptests_commentmeta` WRITE;
/*!40000 ALTER TABLE `wptests_commentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_commentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_comments`
--

DROP TABLE IF EXISTS `wptests_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT 0,
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT 0,
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT 'comment',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10)),
  KEY `woo_idx_comment_type` (`comment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_comments`
--

LOCK TABLES `wptests_comments` WRITE;
/*!40000 ALTER TABLE `wptests_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_adjustmentmeta`
--

DROP TABLE IF EXISTS `wptests_edd_adjustmentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_adjustmentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_adjustment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_adjustment_id` (`edd_adjustment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_adjustmentmeta`
--

LOCK TABLES `wptests_edd_adjustmentmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_adjustmentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_adjustmentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_adjustments`
--

DROP TABLE IF EXISTS `wptests_edd_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `scope` varchar(20) NOT NULL DEFAULT 'all',
  `amount_type` varchar(20) NOT NULL DEFAULT '',
  `amount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `description` longtext NOT NULL DEFAULT '',
  `max_uses` bigint(20) unsigned NOT NULL DEFAULT 0,
  `use_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `once_per_customer` int(1) NOT NULL DEFAULT 0,
  `min_charge_amount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_status` (`type`,`status`),
  KEY `type_status_dates` (`type`,`status`,`start_date`,`end_date`),
  KEY `code` (`code`),
  KEY `date_created` (`date_created`),
  KEY `date_start_end` (`start_date`,`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_adjustments`
--

LOCK TABLES `wptests_edd_adjustments` WRITE;
/*!40000 ALTER TABLE `wptests_edd_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_customer_addresses`
--

DROP TABLE IF EXISTS `wptests_edd_customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_customer_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'billing',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `name` mediumtext NOT NULL,
  `address` mediumtext NOT NULL,
  `address2` mediumtext NOT NULL,
  `city` mediumtext NOT NULL,
  `region` mediumtext NOT NULL,
  `postal_code` varchar(32) NOT NULL DEFAULT '',
  `country` mediumtext NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer_is_primary` (`customer_id`,`is_primary`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_customer_addresses`
--

LOCK TABLES `wptests_edd_customer_addresses` WRITE;
/*!40000 ALTER TABLE `wptests_edd_customer_addresses` DISABLE KEYS */;
INSERT INTO `wptests_edd_customer_addresses` VALUES (2,2,1,'billing','active','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:29','2025-01-08 13:51:24','urn:uuid:ce807483-967e-4996-b506-0bf6cfef7855');
/*!40000 ALTER TABLE `wptests_edd_customer_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_customer_email_addresses`
--

DROP TABLE IF EXISTS `wptests_edd_customer_email_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_customer_email_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'secondary',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `email` varchar(100) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer` (`customer_id`),
  KEY `email` (`email`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_customer_email_addresses`
--

LOCK TABLES `wptests_edd_customer_email_addresses` WRITE;
/*!40000 ALTER TABLE `wptests_edd_customer_email_addresses` DISABLE KEYS */;
INSERT INTO `wptests_edd_customer_email_addresses` VALUES (2,2,'primary','active','carlotta30@kemmer.org','2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a');
/*!40000 ALTER TABLE `wptests_edd_customer_email_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_customermeta`
--

DROP TABLE IF EXISTS `wptests_edd_customermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_customermeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_customer_id` (`edd_customer_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_customermeta`
--

LOCK TABLES `wptests_edd_customermeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_customermeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_customermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_customers`
--

DROP TABLE IF EXISTS `wptests_edd_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `purchase_value` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `purchase_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user` (`user_id`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_customers`
--

LOCK TABLES `wptests_edd_customers` WRITE;
/*!40000 ALTER TABLE `wptests_edd_customers` DISABLE KEYS */;
INSERT INTO `wptests_edd_customers` VALUES (2,0,'carlotta30@kemmer.org','Melyssa Towne','active',960.000000000,60,'2025-01-08 13:20:29','2025-01-08 13:51:24','urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c');
/*!40000 ALTER TABLE `wptests_edd_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_emailmeta`
--

DROP TABLE IF EXISTS `wptests_edd_emailmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_emailmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_email_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `email_id` (`edd_email_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_emailmeta`
--

LOCK TABLES `wptests_edd_emailmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_emailmeta` DISABLE KEYS */;
INSERT INTO `wptests_edd_emailmeta` VALUES (1,1,'legacy','purchase_receipt'),(2,1,'legacy','purchase_subject'),(3,1,'legacy','purchase_heading'),(4,2,'legacy','sale_notification'),(5,2,'legacy','sale_notification_subject'),(6,2,'legacy','sale_notification_heading'),(7,2,'legacy','disable_admin_notices'),(8,2,'recipients','admin');
/*!40000 ALTER TABLE `wptests_edd_emailmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_emails`
--

DROP TABLE IF EXISTS `wptests_edd_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_emails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email_id` varchar(32) NOT NULL,
  `context` varchar(32) NOT NULL DEFAULT 'order',
  `sender` varchar(32) NOT NULL DEFAULT 'edd',
  `recipient` varchar(32) NOT NULL DEFAULT 'customer',
  `subject` text NOT NULL,
  `heading` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_id` (`email_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_emails`
--

LOCK TABLES `wptests_edd_emails` WRITE;
/*!40000 ALTER TABLE `wptests_edd_emails` DISABLE KEYS */;
INSERT INTO `wptests_edd_emails` VALUES (1,'order_receipt','order','edd','customer','Purchase Receipt','Purchase Receipt','Dear {name},\n\nThank you for your purchase. Please click on the link(s) below to download your files.\n\n{download_list}\n\n{sitename}',1,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(2,'admin_order_notice','order','edd','admin','New download purchase - Order #{payment_id}','New Sale!','Hello\n\nA Downloads purchase has been made.\n\nDownloads sold:\n\n{download_list}\n\nPurchased by: {fullname}\nAmount: {price}\nPayment Method: {payment_method}\n\nThank you',1,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(3,'order_refund','refund','edd','customer','Your order has been refunded','','Dear {name},\n\nYour order has been refunded.',0,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(4,'admin_order_refund','refund','edd','admin','An order has been refunded','','Order {payment_id} has been refunded.',0,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(5,'new_user','user','edd','customer','[{sitename}] Your username and password','Your account info','Username: {username}\r\nPassword: [entered on site]\r\n<a href=\"http://wordpress.test/wp-login.php\"> Click here to log in &rarr;</a>\r\n',1,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(6,'new_user_admin','user','edd','admin','[{sitename}] New User Registration','New user registration','Username: {username}\r\n\r\nE-mail: {user_email}\r\n',1,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(7,'user_verification','user','edd','user','Verify your account','Verify your account','Hello {fullname},\n\nYour account with {sitename} needs to be verified before you can access your order history.\n\nVisit this link to verify your account: {verification_url}\n\n',1,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(8,'password_reset','user','wp','user','[EVA Integration Tests] Password Reset','','Someone has requested a password reset for the following account:\r\n\r\nSite Name: {sitename}\r\n\r\nUsername: {username}\r\n\r\nIf this was a mistake, ignore this email and nothing will happen.\r\n\r\nTo reset your password, visit the following address:\r\n\r\n{password_reset_link}\r\n\r\nThis password reset request originated from the IP address {ip_address}.\r\n',0,'2025-01-08 13:20:23','2025-01-08 13:20:23'),(9,'stripe_early_fraud_warning','order','edd','admin','Stripe Early Fraud Warning - Order #{payment_id}','Possible Fraudulent Order','Hello\n\nStripe has detected a potential fraudulent order.\n\nDownloads sold:\n\n{download_list}\n\nPurchased by: {fullname}\nAmount: {price}\n<a href=\"{order_details_link}\">Order Details</a>\n\nNote: Once you have reviewed the order, ensure you take the appropriate action within your Stripe dashboard to help improve future fraud detection.',0,'2025-01-08 13:20:23','2025-01-08 13:20:23');
/*!40000 ALTER TABLE `wptests_edd_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logmeta`
--

DROP TABLE IF EXISTS `wptests_edd_logmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_log_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_log_id` (`edd_log_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logmeta`
--

LOCK TABLES `wptests_edd_logmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs`
--

DROP TABLE IF EXISTS `wptests_edd_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs`
--

LOCK TABLES `wptests_edd_logs` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs_api_requestmeta`
--

DROP TABLE IF EXISTS `wptests_edd_logs_api_requestmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs_api_requestmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_logs_api_request_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_logs_api_request_id` (`edd_logs_api_request_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs_api_requestmeta`
--

LOCK TABLES `wptests_edd_logs_api_requestmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs_api_requestmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs_api_requestmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs_api_requests`
--

DROP TABLE IF EXISTS `wptests_edd_logs_api_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs_api_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `api_key` varchar(32) NOT NULL DEFAULT 'public',
  `token` varchar(32) NOT NULL DEFAULT '',
  `version` varchar(32) NOT NULL DEFAULT '',
  `request` longtext NOT NULL DEFAULT '',
  `error` longtext NOT NULL DEFAULT '',
  `ip` varchar(60) NOT NULL DEFAULT '',
  `time` varchar(60) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs_api_requests`
--

LOCK TABLES `wptests_edd_logs_api_requests` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs_api_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs_api_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs_emailmeta`
--

DROP TABLE IF EXISTS `wptests_edd_logs_emailmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs_emailmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_logs_email_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_logs_email_id` (`edd_logs_email_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs_emailmeta`
--

LOCK TABLES `wptests_edd_logs_emailmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs_emailmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs_emailmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs_emails`
--

DROP TABLE IF EXISTS `wptests_edd_logs_emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs_emails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) NOT NULL DEFAULT 'customer',
  `email` varchar(100) NOT NULL DEFAULT '',
  `email_id` varchar(32) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `email_id` (`email_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs_emails`
--

LOCK TABLES `wptests_edd_logs_emails` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs_emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs_file_downloadmeta`
--

DROP TABLE IF EXISTS `wptests_edd_logs_file_downloadmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs_file_downloadmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_logs_file_download_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_logs_file_download_id` (`edd_logs_file_download_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs_file_downloadmeta`
--

LOCK TABLES `wptests_edd_logs_file_downloadmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs_file_downloadmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs_file_downloadmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_logs_file_downloads`
--

DROP TABLE IF EXISTS `wptests_edd_logs_file_downloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_logs_file_downloads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `file_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `price_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `ip` varchar(60) NOT NULL DEFAULT '',
  `user_agent` varchar(200) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_logs_file_downloads`
--

LOCK TABLES `wptests_edd_logs_file_downloads` WRITE;
/*!40000 ALTER TABLE `wptests_edd_logs_file_downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_logs_file_downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_notemeta`
--

DROP TABLE IF EXISTS `wptests_edd_notemeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_notemeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_note_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_note_id` (`edd_note_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_notemeta`
--

LOCK TABLES `wptests_edd_notemeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_notemeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_notemeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_notes`
--

DROP TABLE IF EXISTS `wptests_edd_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) NOT NULL DEFAULT '',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `content` longtext NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `user_id` (`user_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_notes`
--

LOCK TABLES `wptests_edd_notes` WRITE;
/*!40000 ALTER TABLE `wptests_edd_notes` DISABLE KEYS */;
INSERT INTO `wptests_edd_notes` VALUES (2,2,'order',0,'Status changed from new to Completed','2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(3,3,'order',0,'Status changed from new to Completed','2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(4,4,'order',0,'Status changed from new to Completed','2025-01-08 13:20:30','2025-01-08 13:20:30','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(5,5,'order',0,'Status changed from new to Completed','2025-01-08 13:20:31','2025-01-08 13:20:31','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(9,8,'order',0,'Status changed from new to Completed','2025-01-08 13:20:31','2025-01-08 13:20:31','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(12,10,'order',0,'Status changed from new to Completed','2025-01-08 13:20:32','2025-01-08 13:20:32','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(15,12,'order',0,'Status changed from new to Completed','2025-01-08 13:20:35','2025-01-08 13:20:35','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(16,13,'order',0,'Status changed from new to Completed','2025-01-08 13:20:36','2025-01-08 13:20:36','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(17,14,'order',0,'Status changed from new to Completed','2025-01-08 13:20:36','2025-01-08 13:20:36','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(18,15,'order',0,'Status changed from new to Completed','2025-01-08 13:20:37','2025-01-08 13:20:37','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(20,17,'order',0,'Status changed from new to Completed','2025-01-08 13:20:45','2025-01-08 13:20:45','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(21,18,'order',0,'Status changed from new to Completed','2025-01-08 13:20:46','2025-01-08 13:20:46','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(22,19,'order',0,'Status changed from new to Completed','2025-01-08 13:20:46','2025-01-08 13:20:46','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(26,22,'order',0,'Status changed from new to Completed','2025-01-08 13:20:49','2025-01-08 13:20:49','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(29,24,'order',0,'Status changed from new to Completed','2025-01-08 13:20:50','2025-01-08 13:20:50','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(33,27,'order',0,'Status changed from new to Completed','2025-01-08 13:37:33','2025-01-08 13:37:33','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(34,28,'order',0,'Status changed from new to Completed','2025-01-08 13:37:35','2025-01-08 13:37:35','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(35,29,'order',0,'Status changed from new to Completed','2025-01-08 13:37:38','2025-01-08 13:37:38','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(36,30,'order',0,'Status changed from new to Completed','2025-01-08 13:37:42','2025-01-08 13:37:42','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(40,33,'order',0,'Status changed from new to Completed','2025-01-08 13:37:47','2025-01-08 13:37:47','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(43,35,'order',0,'Status changed from new to Completed','2025-01-08 13:37:52','2025-01-08 13:37:52','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(46,37,'order',0,'Status changed from new to Completed','2025-01-08 13:38:02','2025-01-08 13:38:02','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(47,38,'order',0,'Status changed from new to Completed','2025-01-08 13:38:05','2025-01-08 13:38:05','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(48,39,'order',0,'Status changed from new to Completed','2025-01-08 13:38:07','2025-01-08 13:38:07','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(49,40,'order',0,'Status changed from new to Completed','2025-01-08 13:38:10','2025-01-08 13:38:10','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(51,42,'order',0,'Status changed from new to Completed','2025-01-08 13:38:27','2025-01-08 13:38:27','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(52,43,'order',0,'Status changed from new to Completed','2025-01-08 13:38:29','2025-01-08 13:38:29','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(53,44,'order',0,'Status changed from new to Completed','2025-01-08 13:38:32','2025-01-08 13:38:32','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(57,47,'order',0,'Status changed from new to Completed','2025-01-08 13:38:42','2025-01-08 13:38:42','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(60,49,'order',0,'Status changed from new to Completed','2025-01-08 13:38:47','2025-01-08 13:38:47','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(64,52,'order',0,'Status changed from new to Completed','2025-01-08 13:46:50','2025-01-08 13:46:50','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(65,53,'order',0,'Status changed from new to Completed','2025-01-08 13:46:53','2025-01-08 13:46:53','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(66,54,'order',0,'Status changed from new to Completed','2025-01-08 13:46:56','2025-01-08 13:46:56','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(67,55,'order',0,'Status changed from new to Completed','2025-01-08 13:47:01','2025-01-08 13:47:01','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(71,58,'order',0,'Status changed from new to Completed','2025-01-08 13:47:10','2025-01-08 13:47:10','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(74,60,'order',0,'Status changed from new to Completed','2025-01-08 13:47:16','2025-01-08 13:47:16','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(77,62,'order',0,'Status changed from new to Completed','2025-01-08 13:47:27','2025-01-08 13:47:27','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(78,63,'order',0,'Status changed from new to Completed','2025-01-08 13:47:30','2025-01-08 13:47:30','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(79,64,'order',0,'Status changed from new to Completed','2025-01-08 13:47:34','2025-01-08 13:47:34','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(80,65,'order',0,'Status changed from new to Completed','2025-01-08 13:47:38','2025-01-08 13:47:38','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(82,67,'order',0,'Status changed from new to Completed','2025-01-08 13:47:56','2025-01-08 13:47:56','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(83,68,'order',0,'Status changed from new to Completed','2025-01-08 13:48:00','2025-01-08 13:48:00','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(84,69,'order',0,'Status changed from new to Completed','2025-01-08 13:48:04','2025-01-08 13:48:04','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(88,72,'order',0,'Status changed from new to Completed','2025-01-08 13:48:16','2025-01-08 13:48:16','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(91,74,'order',0,'Status changed from new to Completed','2025-01-08 13:48:24','2025-01-08 13:48:24','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(95,77,'order',0,'Status changed from new to Completed','2025-01-08 13:49:32','2025-01-08 13:49:32','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(96,78,'order',0,'Status changed from new to Completed','2025-01-08 13:49:36','2025-01-08 13:49:36','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(97,79,'order',0,'Status changed from new to Completed','2025-01-08 13:49:40','2025-01-08 13:49:40','urn:uuid:fddefa27-76d0-40ba-943f-0968e7aa1540'),(98,80,'order',0,'Status changed from new to Completed','2025-01-08 13:49:45','2025-01-08 13:49:45','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(102,83,'order',0,'Status changed from new to Completed','2025-01-08 13:49:57','2025-01-08 13:49:57','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(105,85,'order',0,'Status changed from new to Completed','2025-01-08 13:50:04','2025-01-08 13:50:04','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(108,87,'order',0,'Status changed from new to Completed','2025-01-08 13:50:17','2025-01-08 13:50:17','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(109,88,'order',0,'Status changed from new to Completed','2025-01-08 13:50:21','2025-01-08 13:50:21','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(110,89,'order',0,'Status changed from new to Completed','2025-01-08 13:50:26','2025-01-08 13:50:26','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(111,90,'order',0,'Status changed from new to Completed','2025-01-08 13:50:30','2025-01-08 13:50:30','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(113,92,'order',0,'Status changed from new to Completed','2025-01-08 13:50:50','2025-01-08 13:50:50','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(114,93,'order',0,'Status changed from new to Completed','2025-01-08 13:50:54','2025-01-08 13:50:54','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(115,94,'order',0,'Status changed from new to Completed','2025-01-08 13:50:58','2025-01-08 13:50:58','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(119,97,'order',0,'Status changed from new to Completed','2025-01-08 13:51:13','2025-01-08 13:51:13','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a'),(122,99,'order',0,'Status changed from new to Completed','2025-01-08 13:51:22','2025-01-08 13:51:22','urn:uuid:398ec1a6-e1f6-4aa7-92b5-81e1df00c65a');
/*!40000 ALTER TABLE `wptests_edd_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_notifications`
--

DROP TABLE IF EXISTS `wptests_edd_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `remote_id` varchar(20) DEFAULT NULL,
  `source` varchar(20) NOT NULL DEFAULT 'api',
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `buttons` longtext DEFAULT NULL,
  `type` varchar(64) NOT NULL DEFAULT 'success',
  `conditions` longtext DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `dismissed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `dismissed_start_end` (`dismissed`,`start`,`end`),
  KEY `remote_id` (`remote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_notifications`
--

LOCK TABLES `wptests_edd_notifications` WRITE;
/*!40000 ALTER TABLE `wptests_edd_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_order_addresses`
--

DROP TABLE IF EXISTS `wptests_edd_order_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_order_addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'billing',
  `name` mediumtext NOT NULL,
  `address` mediumtext NOT NULL,
  `address2` mediumtext NOT NULL,
  `city` mediumtext NOT NULL,
  `region` mediumtext NOT NULL,
  `postal_code` varchar(32) NOT NULL DEFAULT '',
  `country` mediumtext NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `city` (`city`(191)),
  KEY `region` (`region`(191)),
  KEY `postal_code` (`postal_code`),
  KEY `country` (`country`(191)),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_order_addresses`
--

LOCK TABLES `wptests_edd_order_addresses` WRITE;
/*!40000 ALTER TABLE `wptests_edd_order_addresses` DISABLE KEYS */;
INSERT INTO `wptests_edd_order_addresses` VALUES (2,2,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:0e72aaa1-b0f1-4a93-8c60-ed859dd83f16'),(3,3,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(4,4,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:30','2025-01-08 13:20:30','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(5,5,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:31','2025-01-08 13:20:31','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(7,8,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:31','2025-01-08 13:20:31','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(8,10,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:32','2025-01-08 13:20:32','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(9,12,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:35','2025-01-08 13:20:35','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(10,13,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:36','2025-01-08 13:20:36','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(11,14,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:36','2025-01-08 13:20:36','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(12,15,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:37','2025-01-08 13:20:37','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(14,17,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:45','2025-01-08 13:20:45','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(15,18,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:46','2025-01-08 13:20:46','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(16,19,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:46','2025-01-08 13:20:46','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(18,22,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:49','2025-01-08 13:20:49','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(19,24,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:20:50','2025-01-08 13:20:50','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(21,27,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:37:34','2025-01-08 13:37:34','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(22,28,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:37:36','2025-01-08 13:37:36','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(23,29,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:37:38','2025-01-08 13:37:38','urn:uuid:df4c0f82-9e67-4960-aa69-3b8f5486b2b3'),(24,30,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:37:42','2025-01-08 13:37:42','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(26,33,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:37:48','2025-01-08 13:37:48','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(27,35,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:37:52','2025-01-08 13:37:52','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(28,37,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:03','2025-01-08 13:38:03','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(29,38,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:05','2025-01-08 13:38:05','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(30,39,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:08','2025-01-08 13:38:08','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(31,40,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:11','2025-01-08 13:38:11','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(33,42,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:28','2025-01-08 13:38:28','urn:uuid:aeec6504-3613-460c-8b73-a03862a51afe'),(34,43,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:30','2025-01-08 13:38:30','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(35,44,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:33','2025-01-08 13:38:33','urn:uuid:424d1794-4bc3-4a37-bd83-670b8797a318'),(37,47,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:43','2025-01-08 13:38:43','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(38,49,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:38:48','2025-01-08 13:38:48','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(40,52,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:46:51','2025-01-08 13:46:51','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(41,53,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:46:54','2025-01-08 13:46:54','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(42,54,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:46:57','2025-01-08 13:46:57','urn:uuid:1719458f-d8b7-4c30-b6c3-7bdca68d81bd'),(43,55,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:02','2025-01-08 13:47:02','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(45,58,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:11','2025-01-08 13:47:11','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(46,60,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:17','2025-01-08 13:47:17','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(47,62,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:28','2025-01-08 13:47:28','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(48,63,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:32','2025-01-08 13:47:32','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(49,64,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:35','2025-01-08 13:47:35','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(50,65,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:39','2025-01-08 13:47:39','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(52,67,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:47:58','2025-01-08 13:47:58','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(53,68,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:48:02','2025-01-08 13:48:02','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(54,69,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:48:05','2025-01-08 13:48:05','urn:uuid:a4bb9f20-8a56-4c1a-bb9a-754120373f32'),(56,72,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:48:18','2025-01-08 13:48:18','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(57,74,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:48:25','2025-01-08 13:48:25','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(59,77,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:49:34','2025-01-08 13:49:34','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(60,78,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:49:37','2025-01-08 13:49:37','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(61,79,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:49:41','2025-01-08 13:49:41','urn:uuid:cf8a3e1b-8631-469d-b1ba-ddeee16edbab'),(62,80,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:49:47','2025-01-08 13:49:47','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(64,83,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:49:58','2025-01-08 13:49:58','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(65,85,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:06','2025-01-08 13:50:06','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(66,87,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:19','2025-01-08 13:50:19','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(67,88,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:23','2025-01-08 13:50:23','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(68,89,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:27','2025-01-08 13:50:27','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(69,90,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:32','2025-01-08 13:50:32','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(71,92,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:51','2025-01-08 13:50:51','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(72,93,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:50:56','2025-01-08 13:50:56','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(73,94,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:51:00','2025-01-08 13:51:00','urn:uuid:58b3f6eb-8219-42f7-92f0-e1685f7e82bf'),(75,97,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:51:15','2025-01-08 13:51:15','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(76,99,'billing','Melyssa Towne','','','','Michigan','48126','Poland','2025-01-08 13:51:24','2025-01-08 13:51:24','urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b');
/*!40000 ALTER TABLE `wptests_edd_order_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_order_adjustmentmeta`
--

DROP TABLE IF EXISTS `wptests_edd_order_adjustmentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_order_adjustmentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_order_adjustment_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_order_adjustment_id` (`edd_order_adjustment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_order_adjustmentmeta`
--

LOCK TABLES `wptests_edd_order_adjustmentmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_order_adjustmentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_order_adjustmentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_order_adjustments`
--

DROP TABLE IF EXISTS `wptests_edd_order_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_order_adjustments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) DEFAULT NULL,
  `type_id` bigint(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `type_key` varchar(255) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `subtotal` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `tax` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object_id_type` (`object_id`,`object_type`),
  KEY `date_created` (`date_created`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_order_adjustments`
--

LOCK TABLES `wptests_edd_order_adjustments` WRITE;
/*!40000 ALTER TABLE `wptests_edd_order_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_order_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_order_itemmeta`
--

DROP TABLE IF EXISTS `wptests_edd_order_itemmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_order_itemmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_order_item_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_order_item_id` (`edd_order_item_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_order_itemmeta`
--

LOCK TABLES `wptests_edd_order_itemmeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_order_itemmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_order_itemmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_order_items`
--

DROP TABLE IF EXISTS `wptests_edd_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `product_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `product_name` text NOT NULL DEFAULT '',
  `price_id` bigint(20) unsigned DEFAULT NULL,
  `cart_index` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'download',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `quantity` int(11) NOT NULL DEFAULT 0,
  `amount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `subtotal` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `discount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `tax` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_product_price_id` (`order_id`,`product_id`,`price_id`),
  KEY `type_status` (`type`,`status`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_order_items`
--

LOCK TABLES `wptests_edd_order_items` WRITE;
/*!40000 ALTER TABLE `wptests_edd_order_items` DISABLE KEYS */;
INSERT INTO `wptests_edd_order_items` VALUES (2,0,2,5207,'Test EDD ticket for 5206',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:26f2530d-c0ae-4b7a-b4e7-bd5fa97f1d42'),(3,0,3,5211,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:29','2025-01-08 13:20:29','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(4,0,4,5214,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:30','2025-01-08 13:20:30','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(5,0,5,5234,'Test EDD ticket for 5233',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:31','2025-01-08 13:20:31','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(8,0,8,5240,'Test EDD ticket for 5239',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:31','2025-01-08 13:20:31','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(10,0,10,5244,'Test EDD ticket for 5243',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:32','2025-01-08 13:20:32','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(12,0,12,5328,'Test EDD ticket for 5327',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:35','2025-01-08 13:20:35','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(13,0,13,5332,'Test EDD ticket for 5331',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:36','2025-01-08 13:20:36','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(14,0,14,5336,'Test EDD ticket for 5335',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:36','2025-01-08 13:20:36','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(15,0,15,5340,'Test EDD ticket for 5339',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:37','2025-01-08 13:20:37','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(17,0,17,5534,'Test EDD ticket for 5533',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:45','2025-01-08 13:20:45','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(18,0,18,5538,'Test EDD ticket for 5537',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:46','2025-01-08 13:20:46','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(19,0,19,5541,'Test EDD ticket for 5537',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:46','2025-01-08 13:20:46','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(22,0,22,5568,'Test EDD ticket for 5567',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:49','2025-01-08 13:20:49','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(24,0,24,5572,'Test EDD ticket for 5571',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:50','2025-01-08 13:20:50','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(27,0,27,5207,'Test EDD ticket for 5206',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:34','2025-01-08 13:37:34','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(28,0,28,5211,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:36','2025-01-08 13:37:36','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(29,0,29,5214,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:38','2025-01-08 13:37:38','urn:uuid:17d67646-eabf-492b-b7f5-74473c5cc97b'),(30,0,30,5236,'Test EDD ticket for 5235',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:42','2025-01-08 13:37:42','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(33,0,33,5242,'Test EDD ticket for 5241',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:48','2025-01-08 13:37:48','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(35,0,35,5246,'Test EDD ticket for 5245',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:52','2025-01-08 13:37:52','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(37,0,37,5330,'Test EDD ticket for 5329',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:03','2025-01-08 13:38:03','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(38,0,38,5334,'Test EDD ticket for 5333',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:05','2025-01-08 13:38:05','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(39,0,39,5338,'Test EDD ticket for 5337',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:08','2025-01-08 13:38:08','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(40,0,40,5342,'Test EDD ticket for 5341',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:11','2025-01-08 13:38:11','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(42,0,42,5536,'Test EDD ticket for 5535',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:28','2025-01-08 13:38:28','urn:uuid:4d2eff16-3895-4911-b9ab-ec65ff72afcf'),(43,0,43,5540,'Test EDD ticket for 5539',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:30','2025-01-08 13:38:30','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(44,0,44,5543,'Test EDD ticket for 5539',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:33','2025-01-08 13:38:33','urn:uuid:7d013840-56d3-4c9a-9304-014653548d76'),(47,0,47,5570,'Test EDD ticket for 5569',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:43','2025-01-08 13:38:43','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(49,0,49,5574,'Test EDD ticket for 5573',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:48','2025-01-08 13:38:48','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(52,0,52,5207,'Test EDD ticket for 5206',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:46:51','2025-01-08 13:46:51','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(53,0,53,5211,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:46:54','2025-01-08 13:46:54','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(54,0,54,5214,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:46:57','2025-01-08 13:46:57','urn:uuid:adbf477f-3ebf-4544-879b-42cd89953652'),(55,0,55,5240,'Test EDD ticket for 5239',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:02','2025-01-08 13:47:02','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(58,0,58,5246,'Test EDD ticket for 5245',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:11','2025-01-08 13:47:11','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(60,0,60,5250,'Test EDD ticket for 5249',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:17','2025-01-08 13:47:17','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(62,0,62,5338,'Test EDD ticket for 5337',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:28','2025-01-08 13:47:28','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(63,0,63,5342,'Test EDD ticket for 5341',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:32','2025-01-08 13:47:32','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(64,0,64,5346,'Test EDD ticket for 5345',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:35','2025-01-08 13:47:35','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(65,0,65,5350,'Test EDD ticket for 5349',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:39','2025-01-08 13:47:39','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(67,0,67,5548,'Test EDD ticket for 5547',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:58','2025-01-08 13:47:58','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(68,0,68,5552,'Test EDD ticket for 5551',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:02','2025-01-08 13:48:02','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(69,0,69,5555,'Test EDD ticket for 5551',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:05','2025-01-08 13:48:05','urn:uuid:372f6cd0-ff87-411d-9410-e1c8251a6e90'),(72,0,72,5584,'Test EDD ticket for 5583',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:18','2025-01-08 13:48:18','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(74,0,74,5588,'Test EDD ticket for 5587',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:25','2025-01-08 13:48:25','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(77,0,77,5207,'Test EDD ticket for 5206',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:34','2025-01-08 13:49:34','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(78,0,78,5211,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:38','2025-01-08 13:49:38','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(79,0,79,5214,'Test EDD ticket for 5210',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:41','2025-01-08 13:49:41','urn:uuid:5721625b-7d0c-45b7-bcc8-f4a7e7d9957e'),(80,0,80,5242,'Test EDD ticket for 5241',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:47','2025-01-08 13:49:47','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(83,0,83,5248,'Test EDD ticket for 5247',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:58','2025-01-08 13:49:58','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(85,0,85,5252,'Test EDD ticket for 5251',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:06','2025-01-08 13:50:06','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(87,0,87,5340,'Test EDD ticket for 5339',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:19','2025-01-08 13:50:19','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(88,0,88,5344,'Test EDD ticket for 5343',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:23','2025-01-08 13:50:23','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(89,0,89,5348,'Test EDD ticket for 5347',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:27','2025-01-08 13:50:27','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(90,0,90,5352,'Test EDD ticket for 5351',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:32','2025-01-08 13:50:32','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(92,0,92,5550,'Test EDD ticket for 5549',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:51','2025-01-08 13:50:51','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(93,0,93,5554,'Test EDD ticket for 5553',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:56','2025-01-08 13:50:56','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(94,0,94,5557,'Test EDD ticket for 5553',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:51:00','2025-01-08 13:51:00','urn:uuid:89d485f7-3368-488b-af88-8e2695e1c6ff'),(97,0,97,5588,'Test EDD ticket for 5587',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:51:15','2025-01-08 13:51:15','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d'),(99,0,99,5592,'Test EDD ticket for 5591',NULL,0,'download','complete',2,8.000000000,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:51:24','2025-01-08 13:51:24','urn:uuid:9d2e5143-4647-4ca3-a7bb-ef31469e2e8d');
/*!40000 ALTER TABLE `wptests_edd_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_order_transactions`
--

DROP TABLE IF EXISTS `wptests_edd_order_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_order_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `object_type` varchar(20) NOT NULL DEFAULT '',
  `transaction_id` varchar(256) NOT NULL DEFAULT '',
  `gateway` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`(64)),
  KEY `gateway` (`gateway`),
  KEY `status` (`status`),
  KEY `date_created` (`date_created`),
  KEY `object_type_object_id` (`object_type`,`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_order_transactions`
--

LOCK TABLES `wptests_edd_order_transactions` WRITE;
/*!40000 ALTER TABLE `wptests_edd_order_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_order_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_ordermeta`
--

DROP TABLE IF EXISTS `wptests_edd_ordermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_ordermeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `edd_order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `edd_order_id` (`edd_order_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_ordermeta`
--

LOCK TABLES `wptests_edd_ordermeta` WRITE;
/*!40000 ALTER TABLE `wptests_edd_ordermeta` DISABLE KEYS */;
INSERT INTO `wptests_edd_ordermeta` VALUES (3,3535,'_tec_zapier_queue_zapier_order_run_once','1'),(4,3535,'_tec_power_automate_queue_power_automate_order_run_once','1'),(5,2,'_tec_zapier_queue_zapier_order_run_once','1'),(6,2,'_tec_power_automate_queue_power_automate_order_run_once','1'),(8,3,'_tec_zapier_queue_zapier_order_run_once','1'),(9,3,'_tec_power_automate_queue_power_automate_order_run_once','1'),(10,3,'_tribe_has_tickets','1'),(11,4,'_tec_zapier_queue_zapier_order_run_once','1'),(12,4,'_tec_power_automate_queue_power_automate_order_run_once','1'),(14,5,'_tec_zapier_queue_zapier_order_run_once','1'),(16,8,'_tec_zapier_queue_zapier_order_run_once','1'),(17,8,'_tec_power_automate_queue_power_automate_order_run_once','1'),(19,10,'_tec_zapier_queue_zapier_order_run_once','1'),(20,10,'_tec_power_automate_queue_power_automate_order_run_once','1'),(22,12,'_tec_zapier_queue_zapier_order_run_once','1'),(23,12,'_tec_power_automate_queue_power_automate_order_run_once','1'),(25,13,'_tec_zapier_queue_zapier_order_run_once','1'),(26,13,'_tec_power_automate_queue_power_automate_order_run_once','1'),(28,14,'_tec_zapier_queue_zapier_order_run_once','1'),(29,14,'_tec_power_automate_queue_power_automate_order_run_once','1'),(31,15,'_tec_zapier_queue_zapier_order_run_once','1'),(32,15,'_tec_power_automate_queue_power_automate_order_run_once','1'),(34,17,'_tec_zapier_queue_zapier_order_run_once','1'),(35,17,'_tec_power_automate_queue_power_automate_order_run_once','1'),(37,18,'_tec_zapier_queue_zapier_order_run_once','1'),(38,18,'_tec_power_automate_queue_power_automate_order_run_once','1'),(39,18,'_tribe_has_tickets','1'),(40,19,'_tec_zapier_queue_zapier_order_run_once','1'),(41,19,'_tec_power_automate_queue_power_automate_order_run_once','1'),(43,22,'_tec_zapier_queue_zapier_order_run_once','1'),(44,22,'_tec_power_automate_queue_power_automate_order_run_once','1'),(46,24,'_tec_zapier_queue_zapier_order_run_once','1'),(47,24,'_tec_power_automate_queue_power_automate_order_run_once','1'),(49,27,'_tec_zapier_queue_zapier_order_run_once','1'),(50,27,'_tec_power_automate_queue_power_automate_order_run_once','1'),(52,28,'_tec_zapier_queue_zapier_order_run_once','1'),(53,28,'_tec_power_automate_queue_power_automate_order_run_once','1'),(54,28,'_tribe_has_tickets','1'),(55,29,'_tec_zapier_queue_zapier_order_run_once','1'),(56,29,'_tec_power_automate_queue_power_automate_order_run_once','1'),(58,30,'_tec_zapier_queue_zapier_order_run_once','1'),(60,33,'_tec_zapier_queue_zapier_order_run_once','1'),(61,33,'_tec_power_automate_queue_power_automate_order_run_once','1'),(63,35,'_tec_zapier_queue_zapier_order_run_once','1'),(64,35,'_tec_power_automate_queue_power_automate_order_run_once','1'),(66,37,'_tec_zapier_queue_zapier_order_run_once','1'),(67,37,'_tec_power_automate_queue_power_automate_order_run_once','1'),(69,38,'_tec_zapier_queue_zapier_order_run_once','1'),(70,38,'_tec_power_automate_queue_power_automate_order_run_once','1'),(72,39,'_tec_zapier_queue_zapier_order_run_once','1'),(73,39,'_tec_power_automate_queue_power_automate_order_run_once','1'),(75,40,'_tec_zapier_queue_zapier_order_run_once','1'),(76,40,'_tec_power_automate_queue_power_automate_order_run_once','1'),(78,42,'_tec_zapier_queue_zapier_order_run_once','1'),(79,42,'_tec_power_automate_queue_power_automate_order_run_once','1'),(81,43,'_tec_zapier_queue_zapier_order_run_once','1'),(82,43,'_tec_power_automate_queue_power_automate_order_run_once','1'),(83,43,'_tribe_has_tickets','1'),(84,44,'_tec_zapier_queue_zapier_order_run_once','1'),(85,44,'_tec_power_automate_queue_power_automate_order_run_once','1'),(87,47,'_tec_zapier_queue_zapier_order_run_once','1'),(88,47,'_tec_power_automate_queue_power_automate_order_run_once','1'),(90,49,'_tec_zapier_queue_zapier_order_run_once','1'),(91,49,'_tec_power_automate_queue_power_automate_order_run_once','1'),(93,52,'_tec_zapier_queue_zapier_order_run_once','1'),(94,52,'_tec_power_automate_queue_power_automate_order_run_once','1'),(96,53,'_tec_zapier_queue_zapier_order_run_once','1'),(97,53,'_tec_power_automate_queue_power_automate_order_run_once','1'),(98,53,'_tribe_has_tickets','1'),(99,54,'_tec_zapier_queue_zapier_order_run_once','1'),(100,54,'_tec_power_automate_queue_power_automate_order_run_once','1'),(102,55,'_tec_zapier_queue_zapier_order_run_once','1'),(104,58,'_tec_zapier_queue_zapier_order_run_once','1'),(105,58,'_tec_power_automate_queue_power_automate_order_run_once','1'),(107,60,'_tec_zapier_queue_zapier_order_run_once','1'),(108,60,'_tec_power_automate_queue_power_automate_order_run_once','1'),(110,62,'_tec_zapier_queue_zapier_order_run_once','1'),(111,62,'_tec_power_automate_queue_power_automate_order_run_once','1'),(113,63,'_tec_zapier_queue_zapier_order_run_once','1'),(114,63,'_tec_power_automate_queue_power_automate_order_run_once','1'),(116,64,'_tec_zapier_queue_zapier_order_run_once','1'),(117,64,'_tec_power_automate_queue_power_automate_order_run_once','1'),(119,65,'_tec_zapier_queue_zapier_order_run_once','1'),(120,65,'_tec_power_automate_queue_power_automate_order_run_once','1'),(122,67,'_tec_zapier_queue_zapier_order_run_once','1'),(123,67,'_tec_power_automate_queue_power_automate_order_run_once','1'),(125,68,'_tec_zapier_queue_zapier_order_run_once','1'),(126,68,'_tec_power_automate_queue_power_automate_order_run_once','1'),(127,68,'_tribe_has_tickets','1'),(128,69,'_tec_zapier_queue_zapier_order_run_once','1'),(129,69,'_tec_power_automate_queue_power_automate_order_run_once','1'),(131,72,'_tec_zapier_queue_zapier_order_run_once','1'),(132,72,'_tec_power_automate_queue_power_automate_order_run_once','1'),(134,74,'_tec_zapier_queue_zapier_order_run_once','1'),(135,74,'_tec_power_automate_queue_power_automate_order_run_once','1'),(137,77,'_tec_zapier_queue_zapier_order_run_once','1'),(138,77,'_tec_power_automate_queue_power_automate_order_run_once','1'),(140,78,'_tec_zapier_queue_zapier_order_run_once','1'),(141,78,'_tec_power_automate_queue_power_automate_order_run_once','1'),(142,78,'_tribe_has_tickets','1'),(143,79,'_tec_zapier_queue_zapier_order_run_once','1'),(144,79,'_tec_power_automate_queue_power_automate_order_run_once','1'),(146,80,'_tec_zapier_queue_zapier_order_run_once','1'),(148,83,'_tec_zapier_queue_zapier_order_run_once','1'),(149,83,'_tec_power_automate_queue_power_automate_order_run_once','1'),(151,85,'_tec_zapier_queue_zapier_order_run_once','1'),(152,85,'_tec_power_automate_queue_power_automate_order_run_once','1'),(154,87,'_tec_zapier_queue_zapier_order_run_once','1'),(155,87,'_tec_power_automate_queue_power_automate_order_run_once','1'),(157,88,'_tec_zapier_queue_zapier_order_run_once','1'),(158,88,'_tec_power_automate_queue_power_automate_order_run_once','1'),(160,89,'_tec_zapier_queue_zapier_order_run_once','1'),(161,89,'_tec_power_automate_queue_power_automate_order_run_once','1'),(163,90,'_tec_zapier_queue_zapier_order_run_once','1'),(164,90,'_tec_power_automate_queue_power_automate_order_run_once','1'),(166,92,'_tec_zapier_queue_zapier_order_run_once','1'),(167,92,'_tec_power_automate_queue_power_automate_order_run_once','1'),(169,93,'_tec_zapier_queue_zapier_order_run_once','1'),(170,93,'_tec_power_automate_queue_power_automate_order_run_once','1'),(171,93,'_tribe_has_tickets','1'),(172,94,'_tec_zapier_queue_zapier_order_run_once','1'),(173,94,'_tec_power_automate_queue_power_automate_order_run_once','1'),(175,97,'_tec_zapier_queue_zapier_order_run_once','1'),(176,97,'_tec_power_automate_queue_power_automate_order_run_once','1'),(178,99,'_tec_zapier_queue_zapier_order_run_once','1'),(179,99,'_tec_power_automate_queue_power_automate_order_run_once','1');
/*!40000 ALTER TABLE `wptests_edd_ordermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_orders`
--

DROP TABLE IF EXISTS `wptests_edd_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_number` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `type` varchar(20) NOT NULL DEFAULT 'sale',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(60) NOT NULL DEFAULT '',
  `gateway` varchar(100) NOT NULL DEFAULT 'manual',
  `mode` varchar(20) NOT NULL DEFAULT '',
  `currency` varchar(20) NOT NULL DEFAULT '',
  `payment_key` varchar(64) NOT NULL DEFAULT '',
  `tax_rate_id` bigint(20) DEFAULT NULL,
  `subtotal` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `discount` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `tax` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `total` decimal(18,9) NOT NULL DEFAULT 0.000000000,
  `rate` decimal(10,5) NOT NULL DEFAULT 1.00000,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_modified` datetime NOT NULL DEFAULT current_timestamp(),
  `date_completed` datetime DEFAULT NULL,
  `date_refundable` datetime DEFAULT NULL,
  `date_actions_run` datetime DEFAULT NULL,
  `uuid` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `order_number` (`order_number`(191)),
  KEY `status_type` (`status`,`type`),
  KEY `user_id` (`user_id`),
  KEY `customer_id` (`customer_id`),
  KEY `email` (`email`),
  KEY `payment_key` (`payment_key`),
  KEY `date_created_completed` (`date_created`,`date_completed`),
  KEY `currency` (`currency`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_orders`
--

LOCK TABLES `wptests_edd_orders` WRITE;
/*!40000 ALTER TABLE `wptests_edd_orders` DISABLE KEYS */;
INSERT INTO `wptests_edd_orders` VALUES (2,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','accb9852412ade29ddc57f04cb90e1e9',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:29','2025-01-08 13:20:29','2025-01-08 13:20:29','2025-02-07 13:20:29',NULL,'urn:uuid:7749b9e5-6ace-4af3-bd7c-55d541c1258b'),(3,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','d972e586a0d195f7141ad9c2046d278c',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:29','2025-01-08 13:20:29','2025-01-08 13:20:29','2025-02-07 13:20:29',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(4,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','49ba514fd7e399d20481287d9ad4c025',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:30','2025-01-08 13:20:30','2025-01-08 13:20:30','2025-02-07 13:20:30',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(5,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','9bbd0415ab74f2fdf6a21e679d167e26',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:31','2025-01-08 13:20:31','2025-01-08 13:20:31','2025-02-07 13:20:31',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(8,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','28702d8d31f8d75624545c2ce6e80a4d',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:31','2025-01-08 13:20:31','2025-01-08 13:20:31','2025-02-07 13:20:31',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(10,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','5ab08193fe2430be8aa578a482af4ded',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:32','2025-01-08 13:20:32','2025-01-08 13:20:32','2025-02-07 13:20:32',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(12,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','1ad61205033f8983df1e463112f91d35',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:35','2025-01-08 13:20:35','2025-01-08 13:20:35','2025-02-07 13:20:35',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(13,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','c6ecff0111f8e22ef9348b802f273c85',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:36','2025-01-08 13:20:36','2025-01-08 13:20:36','2025-02-07 13:20:36',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(14,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','144205cb7a9deeb38a39214d5b81aeb1',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:36','2025-01-08 13:20:36','2025-01-08 13:20:36','2025-02-07 13:20:36',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(15,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','249da32dbc93fe9f911af2a192166dd2',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:37','2025-01-08 13:20:37','2025-01-08 13:20:37','2025-02-07 13:20:37',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(17,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','8c3ba29febf344cbc0aeaceed422ccc5',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:45','2025-01-08 13:20:45','2025-01-08 13:20:45','2025-02-07 13:20:45',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(18,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','24d1423ed951e142648f54a4e5a6c43e',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:46','2025-01-08 13:20:46','2025-01-08 13:20:46','2025-02-07 13:20:46',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(19,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','f661947b5ddc690029efd3b1490f8002',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:46','2025-01-08 13:20:46','2025-01-08 13:20:46','2025-02-07 13:20:46',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(22,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','ba135372a80a3524e8051ec188a9ce1d',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:49','2025-01-08 13:20:49','2025-01-08 13:20:49','2025-02-07 13:20:49',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(24,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','6a7db0bc24b7bf5b43abe2b9d7a264d8',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:20:50','2025-01-08 13:20:50','2025-01-08 13:20:50','2025-02-07 13:20:50',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(27,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','28ffe0d41989046df85e83c1c5ec677f',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:33','2025-01-08 13:37:34','2025-01-08 13:37:33','2025-02-07 13:37:33',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(28,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','11339c0514b8e2b2b0d83d0935e47ec4',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:35','2025-01-08 13:37:36','2025-01-08 13:37:35','2025-02-07 13:37:35',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(29,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','0e27845ab8f95e75abe6df3e15734bc1',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:37','2025-01-08 13:37:38','2025-01-08 13:37:37','2025-02-07 13:37:37',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(30,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','2d125cacf1750ac226a0668f67f3c8b7',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:42','2025-01-08 13:37:42','2025-01-08 13:37:42','2025-02-07 13:37:42',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(33,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','b33f47cbae0131a6e2b287a0c6cf0c73',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:47','2025-01-08 13:37:48','2025-01-08 13:37:47','2025-02-07 13:37:47',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(35,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','74d4520ffb1d77a1df2f3c791836b8db',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:37:52','2025-01-08 13:37:52','2025-01-08 13:37:52','2025-02-07 13:37:52',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(37,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','ac4ee35da50afa381f2b9456598d6e00',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:02','2025-01-08 13:38:03','2025-01-08 13:38:02','2025-02-07 13:38:02',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(38,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','06468687c2fda88edf6272388d84645a',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:04','2025-01-08 13:38:05','2025-01-08 13:38:04','2025-02-07 13:38:04',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(39,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','1be8dabccd88645348e9718299312e5e',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:07','2025-01-08 13:38:08','2025-01-08 13:38:07','2025-02-07 13:38:07',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(40,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','8c41e4dd91bf756c3250bf93d81e9e4d',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:10','2025-01-08 13:38:11','2025-01-08 13:38:10','2025-02-07 13:38:10',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(42,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','a6cf913c1a429a51bd335f7b6455084c',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:27','2025-01-08 13:38:28','2025-01-08 13:38:27','2025-02-07 13:38:27',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(43,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','a07a08037db1bc8c095d15759cfe2b0e',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:29','2025-01-08 13:38:30','2025-01-08 13:38:29','2025-02-07 13:38:29',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(44,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','27103a8fdc88081e6b34ef7c95e5bb42',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:32','2025-01-08 13:38:33','2025-01-08 13:38:32','2025-02-07 13:38:32',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(47,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','304166f795cd5dd325f87b7048ae88e2',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:42','2025-01-08 13:38:43','2025-01-08 13:38:42','2025-02-07 13:38:42',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(49,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','d8ccdad360862b121f1671276d65fa51',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:38:47','2025-01-08 13:38:48','2025-01-08 13:38:47','2025-02-07 13:38:47',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(52,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','78fc85c4e6264c5b259c2f99f9998575',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:46:50','2025-01-08 13:46:52','2025-01-08 13:46:50','2025-02-07 13:46:50',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(53,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','2a80c8eeb88e9f163efbcdf7756aed96',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:46:53','2025-01-08 13:46:54','2025-01-08 13:46:53','2025-02-07 13:46:53',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(54,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','db0a24e64e90135b092901f6c792648d',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:46:56','2025-01-08 13:46:57','2025-01-08 13:46:56','2025-02-07 13:46:56',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(55,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','a9c27348152373a0c261070aea36dc78',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:01','2025-01-08 13:47:02','2025-01-08 13:47:01','2025-02-07 13:47:01',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(58,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','9eb52698e6d11bc28af07896554f196b',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:10','2025-01-08 13:47:11','2025-01-08 13:47:10','2025-02-07 13:47:10',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(60,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','dd9b6a4b8d1b972f6b98d8d4c2be1c77',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:16','2025-01-08 13:47:17','2025-01-08 13:47:16','2025-02-07 13:47:16',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(62,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','ffde5ee82c6c2b2507ca0c1903396ca3',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:27','2025-01-08 13:47:28','2025-01-08 13:47:27','2025-02-07 13:47:27',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(63,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','5b915e0c2b8e893e32c88b57bfea2f1c',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:30','2025-01-08 13:47:32','2025-01-08 13:47:30','2025-02-07 13:47:30',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(64,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','1216c37232cb3c068459355fe9a67750',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:34','2025-01-08 13:47:35','2025-01-08 13:47:34','2025-02-07 13:47:34',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(65,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','a6965d2b35bcba401ec0ea36990cfc81',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:38','2025-01-08 13:47:39','2025-01-08 13:47:38','2025-02-07 13:47:38',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(67,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','9876c62dcf922ccd8e75faee25720335',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:47:56','2025-01-08 13:47:58','2025-01-08 13:47:56','2025-02-07 13:47:56',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(68,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','7a8f17d16ba271d6d80d9e3215e3a0c0',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:00','2025-01-08 13:48:02','2025-01-08 13:48:00','2025-02-07 13:48:00',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(69,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','020cf9c583d0eea8ba84203628282b61',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:04','2025-01-08 13:48:05','2025-01-08 13:48:04','2025-02-07 13:48:04',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(72,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','822913b97e13701799d5e0a005aac156',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:16','2025-01-08 13:48:18','2025-01-08 13:48:16','2025-02-07 13:48:16',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(74,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','manual','live','USD','5b2d14da3c46a26498de0867acf53de8',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:48:24','2025-01-08 13:48:25','2025-01-08 13:48:24','2025-02-07 13:48:24',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(77,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','ced71a1072ff4dc411132663ce0924ae',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:32','2025-01-08 13:49:34','2025-01-08 13:49:32','2025-02-07 13:49:32',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(78,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','c481c0d21d08b089ecf868283d0c28f9',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:36','2025-01-08 13:49:38','2025-01-08 13:49:36','2025-02-07 13:49:36',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(79,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','91ac71bb438f3f30b8c2390c63e6a62c',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:40','2025-01-08 13:49:41','2025-01-08 13:49:40','2025-02-07 13:49:40',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(80,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','d8b3b04ce3690299b82e3e8788bc1e50',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:45','2025-01-08 13:49:47','2025-01-08 13:49:45','2025-02-07 13:49:45',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(83,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','7fd14d29e6e9c4518941dfc1e09902b4',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:49:57','2025-01-08 13:49:58','2025-01-08 13:49:57','2025-02-07 13:49:57',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(85,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','be72370b65277af57819e226a4e308c3',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:04','2025-01-08 13:50:06','2025-01-08 13:50:04','2025-02-07 13:50:04',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(87,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','2b578c4d6c66d2da49922f1a33501636',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:17','2025-01-08 13:50:19','2025-01-08 13:50:17','2025-02-07 13:50:17',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(88,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','cc4ea3f61bc023e2f1a6c41836547dd1',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:21','2025-01-08 13:50:23','2025-01-08 13:50:21','2025-02-07 13:50:21',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(89,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','0dea28736ee59aae6c2e0becadd264fc',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:25','2025-01-08 13:50:27','2025-01-08 13:50:25','2025-02-07 13:50:25',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(90,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','a2bdeb301c25b61e8e4c9fb3cc4f9b26',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:30','2025-01-08 13:50:32','2025-01-08 13:50:30','2025-02-07 13:50:30',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(92,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','383b5d849622a8605020839451778560',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:49','2025-01-08 13:50:51','2025-01-08 13:50:49','2025-02-07 13:50:49',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(93,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','4fe4607b408842b6b30f47928b87a558',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:54','2025-01-08 13:50:56','2025-01-08 13:50:54','2025-02-07 13:50:54',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(94,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','d70a511b1e9fb7c2be83526397a152e2',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:50:58','2025-01-08 13:51:00','2025-01-08 13:50:58','2025-02-07 13:50:58',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(97,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','061a064737207defab33ba54e42260e0',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:51:13','2025-01-08 13:51:15','2025-01-08 13:51:13','2025-02-07 13:51:13',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c'),(99,0,'','complete','sale',0,2,'carlotta30@kemmer.org','127.0.0.1','','live','USD','cce62b312a35667114a7e0c6ab2e9d70',NULL,16.000000000,0.000000000,0.000000000,16.000000000,1.00000,'2025-01-08 13:51:22','2025-01-08 13:51:24','2025-01-08 13:51:22','2025-02-07 13:51:22',NULL,'urn:uuid:f239842b-f8d3-41a7-ba07-cf736433c89c');
/*!40000 ALTER TABLE `wptests_edd_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_edd_sessions`
--

DROP TABLE IF EXISTS `wptests_edd_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_edd_sessions` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` varchar(64) NOT NULL,
  `session_value` longtext NOT NULL,
  `session_expiry` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `session_key` (`session_key`),
  KEY `session_expiry` (`session_expiry`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_edd_sessions`
--

LOCK TABLES `wptests_edd_sessions` WRITE;
/*!40000 ALTER TABLE `wptests_edd_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_edd_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_links`
--

DROP TABLE IF EXISTS `wptests_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT 1,
  `link_rating` int(11) NOT NULL DEFAULT 0,
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_links`
--

LOCK TABLES `wptests_links` WRITE;
/*!40000 ALTER TABLE `wptests_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_options`
--

DROP TABLE IF EXISTS `wptests_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=780 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_options`
--

LOCK TABLES `wptests_options` WRITE;
/*!40000 ALTER TABLE `wptests_options` DISABLE KEYS */;
INSERT INTO `wptests_options` VALUES (1,'cron','a:34:{i:1736344155;a:4:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:30:\"tribe_schedule_transient_purge\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"tribe_daily_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736344156;a:2:{s:26:\"action_scheduler_run_queue\";a:1:{s:32:\"0d04ed39571b55704c122d726248bbac\";a:3:{s:8:\"schedule\";s:12:\"every_minute\";s:4:\"args\";a:1:{i:0;s:7:\"WP Cron\";}s:8:\"interval\";i:60;}}s:21:\"tribe-recurrence-cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736344157;a:2:{s:31:\"tec_tickets_seating_tables_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:26:\"tribe_tickets_migrate_4_12\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1736344159;a:4:{s:26:\"edd_daily_scheduled_events\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:27:\"edd_weekly_scheduled_events\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}s:30:\"edds_cleanup_rate_limiting_log\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:20:\"edd_cleanup_sessions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:20:\"edd_cleanup_sessions\";s:4:\"args\";a:0:{}s:8:\"interval\";i:21600;}}}i:1736344160;a:1:{s:14:\"wc_admin_daily\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736344161;a:3:{s:20:\"jetpack_clean_nonces\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:20:\"jetpack_v2_heartbeat\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:33:\"wc_admin_process_orders_milestone\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1736344163;a:1:{s:30:\"wp_1_wc_regenerate_images_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:39:\"wp_1_wc_regenerate_images_cron_interval\";s:4:\"args\";a:0:{}s:8:\"interval\";i:300;}}}i:1736344170;a:3:{s:33:\"woocommerce_cleanup_personal_data\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:30:\"woocommerce_tracker_send_event\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:30:\"generate_category_lookup_table\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1736344202;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"b6ee8e012fb6baaac6290d34ec53e998\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:77;i:1;b:0;}}}}i:1736344206;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"bb19f41442b5bf9890323a83dcb8896f\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:78;i:1;b:0;}}}}i:1736344210;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"025215d1c3ac255e5e51124a96ea8d16\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:79;i:1;b:0;}}}}i:1736344215;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"4753b89ea7d07b6525fbf99d2c2b800c\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:80;i:1;b:0;}}}}i:1736344220;a:1:{s:25:\"woocommerce_geoip_updater\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:11:\"fifteendays\";s:4:\"args\";a:0:{}s:8:\"interval\";i:1296000;}}}i:1736344227;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"94675654eb4534835dd9559163a7e338\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:83;i:1;b:0;}}}}i:1736344234;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"0f8fa3a74548320dbc0e3e8ed3d13cbb\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:85;i:1;b:0;}}}}i:1736344247;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"11988441d5eab4e3525e57dcc826face\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:87;i:1;b:0;}}}}i:1736344251;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"88e795d5ded70148833c5cc87b8358b5\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:88;i:1;b:0;}}}}i:1736344256;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"66e26c46444ebaed798eb34574290c70\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:89;i:1;b:0;}}}}i:1736344260;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"503f760f228f200ef8460e4c0cf52b54\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:90;i:1;b:0;}}}}i:1736344279;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"592615ce934df4f56a48ad5b3e4de98e\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:92;i:1;b:0;}}}}i:1736344284;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"c97f04cab484afd021db27e4d4a32442\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:93;i:1;b:0;}}}}i:1736344288;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"62444b415477ececaa6804f10b74a039\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:94;i:1;b:0;}}}}i:1736344303;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"f010f5973aa9672856b62cbbfdc81b75\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:97;i:1;b:0;}}}}i:1736344312;a:1:{s:35:\"edd_after_payment_scheduled_actions\";a:1:{s:32:\"a7c06079104342af83151d384347865c\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:2:{i:0;i:99;i:1;b:0;}}}}i:1736347754;a:1:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1736347760;a:1:{s:32:\"woocommerce_cancel_unpaid_orders\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1736349554;a:1:{s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1736351354;a:1:{s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1736354960;a:2:{s:24:\"woocommerce_cleanup_logs\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:31:\"woocommerce_cleanup_rate_limits\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736365760;a:1:{s:28:\"woocommerce_cleanup_sessions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1736380800;a:1:{s:27:\"woocommerce_scheduled_sales\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736430555;a:2:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}s:24:\"tribe_common_log_cleanup\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1736773200;a:1:{s:22:\"edd_email_summary_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}s:7:\"version\";i:2;}','auto'),(3,'siteurl','http://wordpress.test','on'),(4,'home','http://wordpress.test','on'),(5,'blogname','EVA Integration Tests','on'),(6,'blogdescription','','on'),(7,'users_can_register','0','on'),(8,'admin_email','admin@wordpress.test','on'),(9,'start_of_week','1','on'),(10,'use_balanceTags','0','on'),(11,'use_smilies','1','on'),(12,'require_name_email','1','on'),(13,'comments_notify','1','on'),(14,'posts_per_rss','10','on'),(15,'rss_use_excerpt','0','on'),(16,'mailserver_url','mail.example.com','on'),(17,'mailserver_login','login@example.com','on'),(18,'mailserver_pass','password','on'),(19,'mailserver_port','110','on'),(20,'default_category','1','on'),(21,'default_comment_status','open','on'),(22,'default_ping_status','open','on'),(23,'default_pingback_flag','1','on'),(24,'posts_per_page','10','on'),(25,'date_format','F j, Y','on'),(26,'time_format','g:i a','on'),(27,'links_updated_date_format','F j, Y g:i a','on'),(28,'comment_moderation','0','on'),(29,'moderation_notify','1','on'),(30,'rewrite_rules','','on'),(31,'hack_file','0','on'),(32,'blog_charset','UTF-8','on'),(33,'moderation_keys','','off'),(34,'active_plugins','a:6:{i:0;s:43:\"the-events-calendar/the-events-calendar.php\";i:1;s:34:\"events-pro/events-calendar-pro.php\";i:2;s:31:\"event-tickets/event-tickets.php\";i:3;s:41:\"event-tickets-plus/event-tickets-plus.php\";i:4;s:49:\"easy-digital-downloads/easy-digital-downloads.php\";i:5;s:27:\"woocommerce/woocommerce.php\";}','on'),(35,'category_base','','on'),(36,'ping_sites','http://rpc.pingomatic.com/','on'),(37,'comment_max_links','2','on'),(38,'gmt_offset','0','on'),(39,'default_email_category','1','on'),(40,'recently_edited','','off'),(41,'template','twentytwentyfour','on'),(42,'stylesheet','twentytwenty','on'),(43,'comment_registration','0','on'),(44,'html_type','text/html','on'),(45,'use_trackback','0','on'),(46,'default_role','subscriber','on'),(47,'db_version','57155','on'),(48,'uploads_use_yearmonth_folders','1','on'),(49,'upload_path','','on'),(50,'blog_public','1','on'),(51,'default_link_category','2','on'),(52,'show_on_front','posts','on'),(53,'tag_base','','on'),(54,'show_avatars','1','on'),(55,'avatar_rating','G','on'),(56,'upload_url_path','','on'),(57,'thumbnail_size_w','150','on'),(58,'thumbnail_size_h','150','on'),(59,'thumbnail_crop','1','on'),(60,'medium_size_w','300','on'),(61,'medium_size_h','300','on'),(62,'avatar_default','mystery','on'),(63,'large_size_w','1024','on'),(64,'large_size_h','1024','on'),(65,'image_default_link_type','none','on'),(66,'image_default_size','','on'),(67,'image_default_align','','on'),(68,'close_comments_for_old_posts','0','on'),(69,'close_comments_days_old','14','on'),(70,'thread_comments','1','on'),(71,'thread_comments_depth','5','on'),(72,'page_comments','0','on'),(73,'comments_per_page','50','on'),(74,'default_comments_page','newest','on'),(75,'comment_order','asc','on'),(76,'sticky_posts','a:0:{}','on'),(77,'widget_categories','a:0:{}','on'),(78,'widget_text','a:0:{}','on'),(79,'widget_rss','a:0:{}','on'),(80,'uninstall_plugins','a:0:{}','off'),(81,'timezone_string','America/New_York','on'),(82,'page_for_posts','0','on'),(83,'page_on_front','0','on'),(84,'default_post_format','0','on'),(85,'link_manager_enabled','0','on'),(86,'finished_splitting_shared_terms','1','on'),(87,'site_icon','0','on'),(88,'medium_large_size_w','768','on'),(89,'medium_large_size_h','0','on'),(90,'wp_page_for_privacy_policy','3','on'),(91,'show_comments_cookies_opt_in','1','on'),(92,'admin_email_lifespan','1751896154','on'),(93,'disallowed_keys','','off'),(94,'comment_previously_approved','1','on'),(95,'auto_plugin_theme_update_emails','a:0:{}','off'),(96,'auto_update_core_dev','enabled','on'),(97,'auto_update_core_minor','enabled','on'),(98,'auto_update_core_major','enabled','on'),(99,'wp_force_deactivated_plugins','a:0:{}','on'),(100,'wp_attachment_pages_enabled','0','on'),(101,'initial_db_version','57155','on'),(102,'wptests_user_roles','a:10:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:199:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:25:\"read_private_tribe_events\";b:1;s:17:\"edit_tribe_events\";b:1;s:24:\"edit_others_tribe_events\";b:1;s:25:\"edit_private_tribe_events\";b:1;s:27:\"edit_published_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:26:\"delete_others_tribe_events\";b:1;s:27:\"delete_private_tribe_events\";b:1;s:29:\"delete_published_tribe_events\";b:1;s:20:\"publish_tribe_events\";b:1;s:25:\"read_private_tribe_venues\";b:1;s:17:\"edit_tribe_venues\";b:1;s:24:\"edit_others_tribe_venues\";b:1;s:25:\"edit_private_tribe_venues\";b:1;s:27:\"edit_published_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:26:\"delete_others_tribe_venues\";b:1;s:27:\"delete_private_tribe_venues\";b:1;s:29:\"delete_published_tribe_venues\";b:1;s:20:\"publish_tribe_venues\";b:1;s:29:\"read_private_tribe_organizers\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:28:\"edit_others_tribe_organizers\";b:1;s:29:\"edit_private_tribe_organizers\";b:1;s:31:\"edit_published_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:30:\"delete_others_tribe_organizers\";b:1;s:31:\"delete_private_tribe_organizers\";b:1;s:33:\"delete_published_tribe_organizers\";b:1;s:24:\"publish_tribe_organizers\";b:1;s:31:\"read_private_aggregator-records\";b:1;s:23:\"edit_aggregator-records\";b:1;s:30:\"edit_others_aggregator-records\";b:1;s:31:\"edit_private_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:32:\"delete_others_aggregator-records\";b:1;s:33:\"delete_private_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;s:17:\"view_shop_reports\";b:1;s:24:\"view_shop_sensitive_data\";b:1;s:19:\"export_shop_reports\";b:1;s:21:\"manage_shop_discounts\";b:1;s:20:\"manage_shop_settings\";b:1;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:18:\"view_product_stats\";b:1;s:15:\"import_products\";b:1;s:17:\"edit_shop_payment\";b:1;s:17:\"read_shop_payment\";b:1;s:19:\"delete_shop_payment\";b:1;s:18:\"edit_shop_payments\";b:1;s:25:\"edit_others_shop_payments\";b:1;s:21:\"publish_shop_payments\";b:1;s:26:\"read_private_shop_payments\";b:1;s:20:\"delete_shop_payments\";b:1;s:28:\"delete_private_shop_payments\";b:1;s:30:\"delete_published_shop_payments\";b:1;s:27:\"delete_others_shop_payments\";b:1;s:26:\"edit_private_shop_payments\";b:1;s:28:\"edit_published_shop_payments\";b:1;s:25:\"manage_shop_payment_terms\";b:1;s:23:\"edit_shop_payment_terms\";b:1;s:25:\"delete_shop_payment_terms\";b:1;s:25:\"assign_shop_payment_terms\";b:1;s:23:\"view_shop_payment_stats\";b:1;s:20:\"import_shop_payments\";b:1;s:18:\"edit_shop_discount\";b:1;s:18:\"read_shop_discount\";b:1;s:20:\"delete_shop_discount\";b:1;s:19:\"edit_shop_discounts\";b:1;s:26:\"edit_others_shop_discounts\";b:1;s:22:\"publish_shop_discounts\";b:1;s:27:\"read_private_shop_discounts\";b:1;s:21:\"delete_shop_discounts\";b:1;s:29:\"delete_private_shop_discounts\";b:1;s:31:\"delete_published_shop_discounts\";b:1;s:28:\"delete_others_shop_discounts\";b:1;s:27:\"edit_private_shop_discounts\";b:1;s:29:\"edit_published_shop_discounts\";b:1;s:26:\"manage_shop_discount_terms\";b:1;s:24:\"edit_shop_discount_terms\";b:1;s:26:\"delete_shop_discount_terms\";b:1;s:26:\"assign_shop_discount_terms\";b:1;s:24:\"view_shop_discount_stats\";b:1;s:21:\"import_shop_discounts\";b:1;s:18:\"manage_woocommerce\";b:1;s:24:\"view_woocommerce_reports\";b:1;s:15:\"edit_shop_order\";b:1;s:15:\"read_shop_order\";b:1;s:17:\"delete_shop_order\";b:1;s:16:\"edit_shop_orders\";b:1;s:23:\"edit_others_shop_orders\";b:1;s:19:\"publish_shop_orders\";b:1;s:24:\"read_private_shop_orders\";b:1;s:18:\"delete_shop_orders\";b:1;s:26:\"delete_private_shop_orders\";b:1;s:28:\"delete_published_shop_orders\";b:1;s:25:\"delete_others_shop_orders\";b:1;s:24:\"edit_private_shop_orders\";b:1;s:26:\"edit_published_shop_orders\";b:1;s:23:\"manage_shop_order_terms\";b:1;s:21:\"edit_shop_order_terms\";b:1;s:23:\"delete_shop_order_terms\";b:1;s:23:\"assign_shop_order_terms\";b:1;s:16:\"edit_shop_coupon\";b:1;s:16:\"read_shop_coupon\";b:1;s:18:\"delete_shop_coupon\";b:1;s:17:\"edit_shop_coupons\";b:1;s:24:\"edit_others_shop_coupons\";b:1;s:20:\"publish_shop_coupons\";b:1;s:25:\"read_private_shop_coupons\";b:1;s:19:\"delete_shop_coupons\";b:1;s:27:\"delete_private_shop_coupons\";b:1;s:29:\"delete_published_shop_coupons\";b:1;s:26:\"delete_others_shop_coupons\";b:1;s:25:\"edit_private_shop_coupons\";b:1;s:27:\"edit_published_shop_coupons\";b:1;s:24:\"manage_shop_coupon_terms\";b:1;s:22:\"edit_shop_coupon_terms\";b:1;s:24:\"delete_shop_coupon_terms\";b:1;s:24:\"assign_shop_coupon_terms\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:74:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:25:\"read_private_tribe_events\";b:1;s:17:\"edit_tribe_events\";b:1;s:24:\"edit_others_tribe_events\";b:1;s:25:\"edit_private_tribe_events\";b:1;s:27:\"edit_published_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:26:\"delete_others_tribe_events\";b:1;s:27:\"delete_private_tribe_events\";b:1;s:29:\"delete_published_tribe_events\";b:1;s:20:\"publish_tribe_events\";b:1;s:25:\"read_private_tribe_venues\";b:1;s:17:\"edit_tribe_venues\";b:1;s:24:\"edit_others_tribe_venues\";b:1;s:25:\"edit_private_tribe_venues\";b:1;s:27:\"edit_published_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:26:\"delete_others_tribe_venues\";b:1;s:27:\"delete_private_tribe_venues\";b:1;s:29:\"delete_published_tribe_venues\";b:1;s:20:\"publish_tribe_venues\";b:1;s:29:\"read_private_tribe_organizers\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:28:\"edit_others_tribe_organizers\";b:1;s:29:\"edit_private_tribe_organizers\";b:1;s:31:\"edit_published_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:30:\"delete_others_tribe_organizers\";b:1;s:31:\"delete_private_tribe_organizers\";b:1;s:33:\"delete_published_tribe_organizers\";b:1;s:24:\"publish_tribe_organizers\";b:1;s:31:\"read_private_aggregator-records\";b:1;s:23:\"edit_aggregator-records\";b:1;s:30:\"edit_others_aggregator-records\";b:1;s:31:\"edit_private_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:32:\"delete_others_aggregator-records\";b:1;s:33:\"delete_private_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:30:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:17:\"edit_tribe_events\";b:1;s:27:\"edit_published_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:29:\"delete_published_tribe_events\";b:1;s:20:\"publish_tribe_events\";b:1;s:17:\"edit_tribe_venues\";b:1;s:27:\"edit_published_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:29:\"delete_published_tribe_venues\";b:1;s:20:\"publish_tribe_venues\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:31:\"edit_published_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:33:\"delete_published_tribe_organizers\";b:1;s:24:\"publish_tribe_organizers\";b:1;s:23:\"edit_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:13:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:17:\"edit_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:17:\"edit_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:23:\"edit_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}s:12:\"shop_manager\";a:2:{s:4:\"name\";s:12:\"Shop Manager\";s:12:\"capabilities\";a:126:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:1;s:12:\"delete_posts\";b:1;s:15:\"unfiltered_html\";b:1;s:12:\"upload_files\";b:1;s:6:\"export\";b:1;s:6:\"import\";b:1;s:19:\"delete_others_pages\";b:1;s:19:\"delete_others_posts\";b:1;s:12:\"delete_pages\";b:1;s:20:\"delete_private_pages\";b:1;s:20:\"delete_private_posts\";b:1;s:22:\"delete_published_pages\";b:1;s:22:\"delete_published_posts\";b:1;s:17:\"edit_others_pages\";b:1;s:17:\"edit_others_posts\";b:1;s:10:\"edit_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"edit_private_posts\";b:1;s:20:\"edit_published_pages\";b:1;s:20:\"edit_published_posts\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:17:\"moderate_comments\";b:1;s:13:\"publish_pages\";b:1;s:13:\"publish_posts\";b:1;s:18:\"read_private_pages\";b:1;s:18:\"read_private_posts\";b:1;s:17:\"view_shop_reports\";b:1;s:24:\"view_shop_sensitive_data\";b:1;s:19:\"export_shop_reports\";b:1;s:20:\"manage_shop_settings\";b:1;s:21:\"manage_shop_discounts\";b:1;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:18:\"view_product_stats\";b:1;s:15:\"import_products\";b:1;s:17:\"edit_shop_payment\";b:1;s:17:\"read_shop_payment\";b:1;s:19:\"delete_shop_payment\";b:1;s:18:\"edit_shop_payments\";b:1;s:25:\"edit_others_shop_payments\";b:1;s:21:\"publish_shop_payments\";b:1;s:26:\"read_private_shop_payments\";b:1;s:20:\"delete_shop_payments\";b:1;s:28:\"delete_private_shop_payments\";b:1;s:30:\"delete_published_shop_payments\";b:1;s:27:\"delete_others_shop_payments\";b:1;s:26:\"edit_private_shop_payments\";b:1;s:28:\"edit_published_shop_payments\";b:1;s:25:\"manage_shop_payment_terms\";b:1;s:23:\"edit_shop_payment_terms\";b:1;s:25:\"delete_shop_payment_terms\";b:1;s:25:\"assign_shop_payment_terms\";b:1;s:23:\"view_shop_payment_stats\";b:1;s:20:\"import_shop_payments\";b:1;s:18:\"edit_shop_discount\";b:1;s:18:\"read_shop_discount\";b:1;s:20:\"delete_shop_discount\";b:1;s:19:\"edit_shop_discounts\";b:1;s:26:\"edit_others_shop_discounts\";b:1;s:22:\"publish_shop_discounts\";b:1;s:27:\"read_private_shop_discounts\";b:1;s:21:\"delete_shop_discounts\";b:1;s:29:\"delete_private_shop_discounts\";b:1;s:31:\"delete_published_shop_discounts\";b:1;s:28:\"delete_others_shop_discounts\";b:1;s:27:\"edit_private_shop_discounts\";b:1;s:29:\"edit_published_shop_discounts\";b:1;s:26:\"manage_shop_discount_terms\";b:1;s:24:\"edit_shop_discount_terms\";b:1;s:26:\"delete_shop_discount_terms\";b:1;s:26:\"assign_shop_discount_terms\";b:1;s:24:\"view_shop_discount_stats\";b:1;s:21:\"import_shop_discounts\";b:1;s:18:\"manage_woocommerce\";b:1;s:24:\"view_woocommerce_reports\";b:1;s:15:\"edit_shop_order\";b:1;s:15:\"read_shop_order\";b:1;s:17:\"delete_shop_order\";b:1;s:16:\"edit_shop_orders\";b:1;s:23:\"edit_others_shop_orders\";b:1;s:19:\"publish_shop_orders\";b:1;s:24:\"read_private_shop_orders\";b:1;s:18:\"delete_shop_orders\";b:1;s:26:\"delete_private_shop_orders\";b:1;s:28:\"delete_published_shop_orders\";b:1;s:25:\"delete_others_shop_orders\";b:1;s:24:\"edit_private_shop_orders\";b:1;s:26:\"edit_published_shop_orders\";b:1;s:23:\"manage_shop_order_terms\";b:1;s:21:\"edit_shop_order_terms\";b:1;s:23:\"delete_shop_order_terms\";b:1;s:23:\"assign_shop_order_terms\";b:1;s:16:\"edit_shop_coupon\";b:1;s:16:\"read_shop_coupon\";b:1;s:18:\"delete_shop_coupon\";b:1;s:17:\"edit_shop_coupons\";b:1;s:24:\"edit_others_shop_coupons\";b:1;s:20:\"publish_shop_coupons\";b:1;s:25:\"read_private_shop_coupons\";b:1;s:19:\"delete_shop_coupons\";b:1;s:27:\"delete_private_shop_coupons\";b:1;s:29:\"delete_published_shop_coupons\";b:1;s:26:\"delete_others_shop_coupons\";b:1;s:25:\"edit_private_shop_coupons\";b:1;s:27:\"edit_published_shop_coupons\";b:1;s:24:\"manage_shop_coupon_terms\";b:1;s:22:\"edit_shop_coupon_terms\";b:1;s:24:\"delete_shop_coupon_terms\";b:1;s:24:\"assign_shop_coupon_terms\";b:1;}}s:15:\"shop_accountant\";a:2:{s:4:\"name\";s:15:\"Shop Accountant\";s:12:\"capabilities\";a:8:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"delete_posts\";b:0;s:13:\"edit_products\";b:1;s:21:\"read_private_products\";b:1;s:17:\"view_shop_reports\";b:1;s:19:\"export_shop_reports\";b:1;s:18:\"edit_shop_payments\";b:1;}}s:11:\"shop_worker\";a:2:{s:4:\"name\";s:11:\"Shop Worker\";s:12:\"capabilities\";a:61:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"upload_files\";b:1;s:12:\"delete_posts\";b:0;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:18:\"view_product_stats\";b:1;s:15:\"import_products\";b:1;s:17:\"edit_shop_payment\";b:1;s:17:\"read_shop_payment\";b:1;s:19:\"delete_shop_payment\";b:1;s:18:\"edit_shop_payments\";b:1;s:25:\"edit_others_shop_payments\";b:1;s:21:\"publish_shop_payments\";b:1;s:26:\"read_private_shop_payments\";b:1;s:20:\"delete_shop_payments\";b:1;s:28:\"delete_private_shop_payments\";b:1;s:30:\"delete_published_shop_payments\";b:1;s:27:\"delete_others_shop_payments\";b:1;s:26:\"edit_private_shop_payments\";b:1;s:28:\"edit_published_shop_payments\";b:1;s:25:\"manage_shop_payment_terms\";b:1;s:23:\"edit_shop_payment_terms\";b:1;s:25:\"delete_shop_payment_terms\";b:1;s:25:\"assign_shop_payment_terms\";b:1;s:23:\"view_shop_payment_stats\";b:1;s:20:\"import_shop_payments\";b:1;s:18:\"edit_shop_discount\";b:1;s:18:\"read_shop_discount\";b:1;s:20:\"delete_shop_discount\";b:1;s:19:\"edit_shop_discounts\";b:1;s:26:\"edit_others_shop_discounts\";b:1;s:22:\"publish_shop_discounts\";b:1;s:27:\"read_private_shop_discounts\";b:1;s:21:\"delete_shop_discounts\";b:1;s:29:\"delete_private_shop_discounts\";b:1;s:31:\"delete_published_shop_discounts\";b:1;s:28:\"delete_others_shop_discounts\";b:1;s:27:\"edit_private_shop_discounts\";b:1;s:29:\"edit_published_shop_discounts\";b:1;s:26:\"manage_shop_discount_terms\";b:1;s:24:\"edit_shop_discount_terms\";b:1;s:26:\"delete_shop_discount_terms\";b:1;s:26:\"assign_shop_discount_terms\";b:1;s:24:\"view_shop_discount_stats\";b:1;s:21:\"import_shop_discounts\";b:1;}}s:11:\"shop_vendor\";a:2:{s:4:\"name\";s:11:\"Shop Vendor\";s:12:\"capabilities\";a:11:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"upload_files\";b:1;s:12:\"delete_posts\";b:0;s:12:\"edit_product\";b:1;s:13:\"edit_products\";b:1;s:14:\"delete_product\";b:1;s:15:\"delete_products\";b:1;s:16:\"publish_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"assign_product_terms\";b:1;}}s:8:\"customer\";a:2:{s:4:\"name\";s:8:\"Customer\";s:12:\"capabilities\";a:1:{s:4:\"read\";b:1;}}}','auto'),(103,'fresh_site','0','auto'),(104,'user_count','1','off'),(105,'widget_block','a:6:{i:2;a:1:{s:7:\"content\";s:19:\"<!-- wp:search /-->\";}i:3;a:1:{s:7:\"content\";s:154:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->\";}i:4;a:1:{s:7:\"content\";s:227:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {\"displayAvatar\":false,\"displayDate\":false,\"displayExcerpt\":false} /--></div><!-- /wp:group -->\";}i:5;a:1:{s:7:\"content\";s:146:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->\";}i:6;a:1:{s:7:\"content\";s:150:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->\";}s:12:\"_multiwidget\";i:1;}','auto'),(106,'sidebars_widgets','a:2:{s:19:\"wp_inactive_widgets\";a:5:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";i:3;s:7:\"block-5\";i:4;s:7:\"block-6\";}s:13:\"array_version\";i:3;}','auto'),(107,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(108,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(109,'widget_archives','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(110,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(111,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(112,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(113,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(114,'widget_meta','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(115,'widget_search','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(116,'widget_recent-posts','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(117,'widget_recent-comments','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(118,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(119,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(120,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(121,'_transient_wp_core_block_css_files','a:2:{s:7:\"version\";s:3:\"6.6\";s:5:\"files\";a:496:{i:0;s:23:\"archives/editor-rtl.css\";i:1;s:27:\"archives/editor-rtl.min.css\";i:2;s:19:\"archives/editor.css\";i:3;s:23:\"archives/editor.min.css\";i:4;s:22:\"archives/style-rtl.css\";i:5;s:26:\"archives/style-rtl.min.css\";i:6;s:18:\"archives/style.css\";i:7;s:22:\"archives/style.min.css\";i:8;s:20:\"audio/editor-rtl.css\";i:9;s:24:\"audio/editor-rtl.min.css\";i:10;s:16:\"audio/editor.css\";i:11;s:20:\"audio/editor.min.css\";i:12;s:19:\"audio/style-rtl.css\";i:13;s:23:\"audio/style-rtl.min.css\";i:14;s:15:\"audio/style.css\";i:15;s:19:\"audio/style.min.css\";i:16;s:19:\"audio/theme-rtl.css\";i:17;s:23:\"audio/theme-rtl.min.css\";i:18;s:15:\"audio/theme.css\";i:19;s:19:\"audio/theme.min.css\";i:20;s:21:\"avatar/editor-rtl.css\";i:21;s:25:\"avatar/editor-rtl.min.css\";i:22;s:17:\"avatar/editor.css\";i:23;s:21:\"avatar/editor.min.css\";i:24;s:20:\"avatar/style-rtl.css\";i:25;s:24:\"avatar/style-rtl.min.css\";i:26;s:16:\"avatar/style.css\";i:27;s:20:\"avatar/style.min.css\";i:28;s:21:\"button/editor-rtl.css\";i:29;s:25:\"button/editor-rtl.min.css\";i:30;s:17:\"button/editor.css\";i:31;s:21:\"button/editor.min.css\";i:32;s:20:\"button/style-rtl.css\";i:33;s:24:\"button/style-rtl.min.css\";i:34;s:16:\"button/style.css\";i:35;s:20:\"button/style.min.css\";i:36;s:22:\"buttons/editor-rtl.css\";i:37;s:26:\"buttons/editor-rtl.min.css\";i:38;s:18:\"buttons/editor.css\";i:39;s:22:\"buttons/editor.min.css\";i:40;s:21:\"buttons/style-rtl.css\";i:41;s:25:\"buttons/style-rtl.min.css\";i:42;s:17:\"buttons/style.css\";i:43;s:21:\"buttons/style.min.css\";i:44;s:22:\"calendar/style-rtl.css\";i:45;s:26:\"calendar/style-rtl.min.css\";i:46;s:18:\"calendar/style.css\";i:47;s:22:\"calendar/style.min.css\";i:48;s:25:\"categories/editor-rtl.css\";i:49;s:29:\"categories/editor-rtl.min.css\";i:50;s:21:\"categories/editor.css\";i:51;s:25:\"categories/editor.min.css\";i:52;s:24:\"categories/style-rtl.css\";i:53;s:28:\"categories/style-rtl.min.css\";i:54;s:20:\"categories/style.css\";i:55;s:24:\"categories/style.min.css\";i:56;s:19:\"code/editor-rtl.css\";i:57;s:23:\"code/editor-rtl.min.css\";i:58;s:15:\"code/editor.css\";i:59;s:19:\"code/editor.min.css\";i:60;s:18:\"code/style-rtl.css\";i:61;s:22:\"code/style-rtl.min.css\";i:62;s:14:\"code/style.css\";i:63;s:18:\"code/style.min.css\";i:64;s:18:\"code/theme-rtl.css\";i:65;s:22:\"code/theme-rtl.min.css\";i:66;s:14:\"code/theme.css\";i:67;s:18:\"code/theme.min.css\";i:68;s:22:\"columns/editor-rtl.css\";i:69;s:26:\"columns/editor-rtl.min.css\";i:70;s:18:\"columns/editor.css\";i:71;s:22:\"columns/editor.min.css\";i:72;s:21:\"columns/style-rtl.css\";i:73;s:25:\"columns/style-rtl.min.css\";i:74;s:17:\"columns/style.css\";i:75;s:21:\"columns/style.min.css\";i:76;s:29:\"comment-content/style-rtl.css\";i:77;s:33:\"comment-content/style-rtl.min.css\";i:78;s:25:\"comment-content/style.css\";i:79;s:29:\"comment-content/style.min.css\";i:80;s:30:\"comment-template/style-rtl.css\";i:81;s:34:\"comment-template/style-rtl.min.css\";i:82;s:26:\"comment-template/style.css\";i:83;s:30:\"comment-template/style.min.css\";i:84;s:42:\"comments-pagination-numbers/editor-rtl.css\";i:85;s:46:\"comments-pagination-numbers/editor-rtl.min.css\";i:86;s:38:\"comments-pagination-numbers/editor.css\";i:87;s:42:\"comments-pagination-numbers/editor.min.css\";i:88;s:34:\"comments-pagination/editor-rtl.css\";i:89;s:38:\"comments-pagination/editor-rtl.min.css\";i:90;s:30:\"comments-pagination/editor.css\";i:91;s:34:\"comments-pagination/editor.min.css\";i:92;s:33:\"comments-pagination/style-rtl.css\";i:93;s:37:\"comments-pagination/style-rtl.min.css\";i:94;s:29:\"comments-pagination/style.css\";i:95;s:33:\"comments-pagination/style.min.css\";i:96;s:29:\"comments-title/editor-rtl.css\";i:97;s:33:\"comments-title/editor-rtl.min.css\";i:98;s:25:\"comments-title/editor.css\";i:99;s:29:\"comments-title/editor.min.css\";i:100;s:23:\"comments/editor-rtl.css\";i:101;s:27:\"comments/editor-rtl.min.css\";i:102;s:19:\"comments/editor.css\";i:103;s:23:\"comments/editor.min.css\";i:104;s:22:\"comments/style-rtl.css\";i:105;s:26:\"comments/style-rtl.min.css\";i:106;s:18:\"comments/style.css\";i:107;s:22:\"comments/style.min.css\";i:108;s:20:\"cover/editor-rtl.css\";i:109;s:24:\"cover/editor-rtl.min.css\";i:110;s:16:\"cover/editor.css\";i:111;s:20:\"cover/editor.min.css\";i:112;s:19:\"cover/style-rtl.css\";i:113;s:23:\"cover/style-rtl.min.css\";i:114;s:15:\"cover/style.css\";i:115;s:19:\"cover/style.min.css\";i:116;s:22:\"details/editor-rtl.css\";i:117;s:26:\"details/editor-rtl.min.css\";i:118;s:18:\"details/editor.css\";i:119;s:22:\"details/editor.min.css\";i:120;s:21:\"details/style-rtl.css\";i:121;s:25:\"details/style-rtl.min.css\";i:122;s:17:\"details/style.css\";i:123;s:21:\"details/style.min.css\";i:124;s:20:\"embed/editor-rtl.css\";i:125;s:24:\"embed/editor-rtl.min.css\";i:126;s:16:\"embed/editor.css\";i:127;s:20:\"embed/editor.min.css\";i:128;s:19:\"embed/style-rtl.css\";i:129;s:23:\"embed/style-rtl.min.css\";i:130;s:15:\"embed/style.css\";i:131;s:19:\"embed/style.min.css\";i:132;s:19:\"embed/theme-rtl.css\";i:133;s:23:\"embed/theme-rtl.min.css\";i:134;s:15:\"embed/theme.css\";i:135;s:19:\"embed/theme.min.css\";i:136;s:19:\"file/editor-rtl.css\";i:137;s:23:\"file/editor-rtl.min.css\";i:138;s:15:\"file/editor.css\";i:139;s:19:\"file/editor.min.css\";i:140;s:18:\"file/style-rtl.css\";i:141;s:22:\"file/style-rtl.min.css\";i:142;s:14:\"file/style.css\";i:143;s:18:\"file/style.min.css\";i:144;s:23:\"footnotes/style-rtl.css\";i:145;s:27:\"footnotes/style-rtl.min.css\";i:146;s:19:\"footnotes/style.css\";i:147;s:23:\"footnotes/style.min.css\";i:148;s:23:\"freeform/editor-rtl.css\";i:149;s:27:\"freeform/editor-rtl.min.css\";i:150;s:19:\"freeform/editor.css\";i:151;s:23:\"freeform/editor.min.css\";i:152;s:22:\"gallery/editor-rtl.css\";i:153;s:26:\"gallery/editor-rtl.min.css\";i:154;s:18:\"gallery/editor.css\";i:155;s:22:\"gallery/editor.min.css\";i:156;s:21:\"gallery/style-rtl.css\";i:157;s:25:\"gallery/style-rtl.min.css\";i:158;s:17:\"gallery/style.css\";i:159;s:21:\"gallery/style.min.css\";i:160;s:21:\"gallery/theme-rtl.css\";i:161;s:25:\"gallery/theme-rtl.min.css\";i:162;s:17:\"gallery/theme.css\";i:163;s:21:\"gallery/theme.min.css\";i:164;s:20:\"group/editor-rtl.css\";i:165;s:24:\"group/editor-rtl.min.css\";i:166;s:16:\"group/editor.css\";i:167;s:20:\"group/editor.min.css\";i:168;s:19:\"group/style-rtl.css\";i:169;s:23:\"group/style-rtl.min.css\";i:170;s:15:\"group/style.css\";i:171;s:19:\"group/style.min.css\";i:172;s:19:\"group/theme-rtl.css\";i:173;s:23:\"group/theme-rtl.min.css\";i:174;s:15:\"group/theme.css\";i:175;s:19:\"group/theme.min.css\";i:176;s:21:\"heading/style-rtl.css\";i:177;s:25:\"heading/style-rtl.min.css\";i:178;s:17:\"heading/style.css\";i:179;s:21:\"heading/style.min.css\";i:180;s:19:\"html/editor-rtl.css\";i:181;s:23:\"html/editor-rtl.min.css\";i:182;s:15:\"html/editor.css\";i:183;s:19:\"html/editor.min.css\";i:184;s:20:\"image/editor-rtl.css\";i:185;s:24:\"image/editor-rtl.min.css\";i:186;s:16:\"image/editor.css\";i:187;s:20:\"image/editor.min.css\";i:188;s:19:\"image/style-rtl.css\";i:189;s:23:\"image/style-rtl.min.css\";i:190;s:15:\"image/style.css\";i:191;s:19:\"image/style.min.css\";i:192;s:19:\"image/theme-rtl.css\";i:193;s:23:\"image/theme-rtl.min.css\";i:194;s:15:\"image/theme.css\";i:195;s:19:\"image/theme.min.css\";i:196;s:29:\"latest-comments/style-rtl.css\";i:197;s:33:\"latest-comments/style-rtl.min.css\";i:198;s:25:\"latest-comments/style.css\";i:199;s:29:\"latest-comments/style.min.css\";i:200;s:27:\"latest-posts/editor-rtl.css\";i:201;s:31:\"latest-posts/editor-rtl.min.css\";i:202;s:23:\"latest-posts/editor.css\";i:203;s:27:\"latest-posts/editor.min.css\";i:204;s:26:\"latest-posts/style-rtl.css\";i:205;s:30:\"latest-posts/style-rtl.min.css\";i:206;s:22:\"latest-posts/style.css\";i:207;s:26:\"latest-posts/style.min.css\";i:208;s:18:\"list/style-rtl.css\";i:209;s:22:\"list/style-rtl.min.css\";i:210;s:14:\"list/style.css\";i:211;s:18:\"list/style.min.css\";i:212;s:25:\"media-text/editor-rtl.css\";i:213;s:29:\"media-text/editor-rtl.min.css\";i:214;s:21:\"media-text/editor.css\";i:215;s:25:\"media-text/editor.min.css\";i:216;s:24:\"media-text/style-rtl.css\";i:217;s:28:\"media-text/style-rtl.min.css\";i:218;s:20:\"media-text/style.css\";i:219;s:24:\"media-text/style.min.css\";i:220;s:19:\"more/editor-rtl.css\";i:221;s:23:\"more/editor-rtl.min.css\";i:222;s:15:\"more/editor.css\";i:223;s:19:\"more/editor.min.css\";i:224;s:30:\"navigation-link/editor-rtl.css\";i:225;s:34:\"navigation-link/editor-rtl.min.css\";i:226;s:26:\"navigation-link/editor.css\";i:227;s:30:\"navigation-link/editor.min.css\";i:228;s:29:\"navigation-link/style-rtl.css\";i:229;s:33:\"navigation-link/style-rtl.min.css\";i:230;s:25:\"navigation-link/style.css\";i:231;s:29:\"navigation-link/style.min.css\";i:232;s:33:\"navigation-submenu/editor-rtl.css\";i:233;s:37:\"navigation-submenu/editor-rtl.min.css\";i:234;s:29:\"navigation-submenu/editor.css\";i:235;s:33:\"navigation-submenu/editor.min.css\";i:236;s:25:\"navigation/editor-rtl.css\";i:237;s:29:\"navigation/editor-rtl.min.css\";i:238;s:21:\"navigation/editor.css\";i:239;s:25:\"navigation/editor.min.css\";i:240;s:24:\"navigation/style-rtl.css\";i:241;s:28:\"navigation/style-rtl.min.css\";i:242;s:20:\"navigation/style.css\";i:243;s:24:\"navigation/style.min.css\";i:244;s:23:\"nextpage/editor-rtl.css\";i:245;s:27:\"nextpage/editor-rtl.min.css\";i:246;s:19:\"nextpage/editor.css\";i:247;s:23:\"nextpage/editor.min.css\";i:248;s:24:\"page-list/editor-rtl.css\";i:249;s:28:\"page-list/editor-rtl.min.css\";i:250;s:20:\"page-list/editor.css\";i:251;s:24:\"page-list/editor.min.css\";i:252;s:23:\"page-list/style-rtl.css\";i:253;s:27:\"page-list/style-rtl.min.css\";i:254;s:19:\"page-list/style.css\";i:255;s:23:\"page-list/style.min.css\";i:256;s:24:\"paragraph/editor-rtl.css\";i:257;s:28:\"paragraph/editor-rtl.min.css\";i:258;s:20:\"paragraph/editor.css\";i:259;s:24:\"paragraph/editor.min.css\";i:260;s:23:\"paragraph/style-rtl.css\";i:261;s:27:\"paragraph/style-rtl.min.css\";i:262;s:19:\"paragraph/style.css\";i:263;s:23:\"paragraph/style.min.css\";i:264;s:25:\"post-author/style-rtl.css\";i:265;s:29:\"post-author/style-rtl.min.css\";i:266;s:21:\"post-author/style.css\";i:267;s:25:\"post-author/style.min.css\";i:268;s:33:\"post-comments-form/editor-rtl.css\";i:269;s:37:\"post-comments-form/editor-rtl.min.css\";i:270;s:29:\"post-comments-form/editor.css\";i:271;s:33:\"post-comments-form/editor.min.css\";i:272;s:32:\"post-comments-form/style-rtl.css\";i:273;s:36:\"post-comments-form/style-rtl.min.css\";i:274;s:28:\"post-comments-form/style.css\";i:275;s:32:\"post-comments-form/style.min.css\";i:276;s:27:\"post-content/editor-rtl.css\";i:277;s:31:\"post-content/editor-rtl.min.css\";i:278;s:23:\"post-content/editor.css\";i:279;s:27:\"post-content/editor.min.css\";i:280;s:23:\"post-date/style-rtl.css\";i:281;s:27:\"post-date/style-rtl.min.css\";i:282;s:19:\"post-date/style.css\";i:283;s:23:\"post-date/style.min.css\";i:284;s:27:\"post-excerpt/editor-rtl.css\";i:285;s:31:\"post-excerpt/editor-rtl.min.css\";i:286;s:23:\"post-excerpt/editor.css\";i:287;s:27:\"post-excerpt/editor.min.css\";i:288;s:26:\"post-excerpt/style-rtl.css\";i:289;s:30:\"post-excerpt/style-rtl.min.css\";i:290;s:22:\"post-excerpt/style.css\";i:291;s:26:\"post-excerpt/style.min.css\";i:292;s:34:\"post-featured-image/editor-rtl.css\";i:293;s:38:\"post-featured-image/editor-rtl.min.css\";i:294;s:30:\"post-featured-image/editor.css\";i:295;s:34:\"post-featured-image/editor.min.css\";i:296;s:33:\"post-featured-image/style-rtl.css\";i:297;s:37:\"post-featured-image/style-rtl.min.css\";i:298;s:29:\"post-featured-image/style.css\";i:299;s:33:\"post-featured-image/style.min.css\";i:300;s:34:\"post-navigation-link/style-rtl.css\";i:301;s:38:\"post-navigation-link/style-rtl.min.css\";i:302;s:30:\"post-navigation-link/style.css\";i:303;s:34:\"post-navigation-link/style.min.css\";i:304;s:28:\"post-template/editor-rtl.css\";i:305;s:32:\"post-template/editor-rtl.min.css\";i:306;s:24:\"post-template/editor.css\";i:307;s:28:\"post-template/editor.min.css\";i:308;s:27:\"post-template/style-rtl.css\";i:309;s:31:\"post-template/style-rtl.min.css\";i:310;s:23:\"post-template/style.css\";i:311;s:27:\"post-template/style.min.css\";i:312;s:24:\"post-terms/style-rtl.css\";i:313;s:28:\"post-terms/style-rtl.min.css\";i:314;s:20:\"post-terms/style.css\";i:315;s:24:\"post-terms/style.min.css\";i:316;s:24:\"post-title/style-rtl.css\";i:317;s:28:\"post-title/style-rtl.min.css\";i:318;s:20:\"post-title/style.css\";i:319;s:24:\"post-title/style.min.css\";i:320;s:26:\"preformatted/style-rtl.css\";i:321;s:30:\"preformatted/style-rtl.min.css\";i:322;s:22:\"preformatted/style.css\";i:323;s:26:\"preformatted/style.min.css\";i:324;s:24:\"pullquote/editor-rtl.css\";i:325;s:28:\"pullquote/editor-rtl.min.css\";i:326;s:20:\"pullquote/editor.css\";i:327;s:24:\"pullquote/editor.min.css\";i:328;s:23:\"pullquote/style-rtl.css\";i:329;s:27:\"pullquote/style-rtl.min.css\";i:330;s:19:\"pullquote/style.css\";i:331;s:23:\"pullquote/style.min.css\";i:332;s:23:\"pullquote/theme-rtl.css\";i:333;s:27:\"pullquote/theme-rtl.min.css\";i:334;s:19:\"pullquote/theme.css\";i:335;s:23:\"pullquote/theme.min.css\";i:336;s:39:\"query-pagination-numbers/editor-rtl.css\";i:337;s:43:\"query-pagination-numbers/editor-rtl.min.css\";i:338;s:35:\"query-pagination-numbers/editor.css\";i:339;s:39:\"query-pagination-numbers/editor.min.css\";i:340;s:31:\"query-pagination/editor-rtl.css\";i:341;s:35:\"query-pagination/editor-rtl.min.css\";i:342;s:27:\"query-pagination/editor.css\";i:343;s:31:\"query-pagination/editor.min.css\";i:344;s:30:\"query-pagination/style-rtl.css\";i:345;s:34:\"query-pagination/style-rtl.min.css\";i:346;s:26:\"query-pagination/style.css\";i:347;s:30:\"query-pagination/style.min.css\";i:348;s:25:\"query-title/style-rtl.css\";i:349;s:29:\"query-title/style-rtl.min.css\";i:350;s:21:\"query-title/style.css\";i:351;s:25:\"query-title/style.min.css\";i:352;s:20:\"query/editor-rtl.css\";i:353;s:24:\"query/editor-rtl.min.css\";i:354;s:16:\"query/editor.css\";i:355;s:20:\"query/editor.min.css\";i:356;s:19:\"quote/style-rtl.css\";i:357;s:23:\"quote/style-rtl.min.css\";i:358;s:15:\"quote/style.css\";i:359;s:19:\"quote/style.min.css\";i:360;s:19:\"quote/theme-rtl.css\";i:361;s:23:\"quote/theme-rtl.min.css\";i:362;s:15:\"quote/theme.css\";i:363;s:19:\"quote/theme.min.css\";i:364;s:23:\"read-more/style-rtl.css\";i:365;s:27:\"read-more/style-rtl.min.css\";i:366;s:19:\"read-more/style.css\";i:367;s:23:\"read-more/style.min.css\";i:368;s:18:\"rss/editor-rtl.css\";i:369;s:22:\"rss/editor-rtl.min.css\";i:370;s:14:\"rss/editor.css\";i:371;s:18:\"rss/editor.min.css\";i:372;s:17:\"rss/style-rtl.css\";i:373;s:21:\"rss/style-rtl.min.css\";i:374;s:13:\"rss/style.css\";i:375;s:17:\"rss/style.min.css\";i:376;s:21:\"search/editor-rtl.css\";i:377;s:25:\"search/editor-rtl.min.css\";i:378;s:17:\"search/editor.css\";i:379;s:21:\"search/editor.min.css\";i:380;s:20:\"search/style-rtl.css\";i:381;s:24:\"search/style-rtl.min.css\";i:382;s:16:\"search/style.css\";i:383;s:20:\"search/style.min.css\";i:384;s:20:\"search/theme-rtl.css\";i:385;s:24:\"search/theme-rtl.min.css\";i:386;s:16:\"search/theme.css\";i:387;s:20:\"search/theme.min.css\";i:388;s:24:\"separator/editor-rtl.css\";i:389;s:28:\"separator/editor-rtl.min.css\";i:390;s:20:\"separator/editor.css\";i:391;s:24:\"separator/editor.min.css\";i:392;s:23:\"separator/style-rtl.css\";i:393;s:27:\"separator/style-rtl.min.css\";i:394;s:19:\"separator/style.css\";i:395;s:23:\"separator/style.min.css\";i:396;s:23:\"separator/theme-rtl.css\";i:397;s:27:\"separator/theme-rtl.min.css\";i:398;s:19:\"separator/theme.css\";i:399;s:23:\"separator/theme.min.css\";i:400;s:24:\"shortcode/editor-rtl.css\";i:401;s:28:\"shortcode/editor-rtl.min.css\";i:402;s:20:\"shortcode/editor.css\";i:403;s:24:\"shortcode/editor.min.css\";i:404;s:24:\"site-logo/editor-rtl.css\";i:405;s:28:\"site-logo/editor-rtl.min.css\";i:406;s:20:\"site-logo/editor.css\";i:407;s:24:\"site-logo/editor.min.css\";i:408;s:23:\"site-logo/style-rtl.css\";i:409;s:27:\"site-logo/style-rtl.min.css\";i:410;s:19:\"site-logo/style.css\";i:411;s:23:\"site-logo/style.min.css\";i:412;s:27:\"site-tagline/editor-rtl.css\";i:413;s:31:\"site-tagline/editor-rtl.min.css\";i:414;s:23:\"site-tagline/editor.css\";i:415;s:27:\"site-tagline/editor.min.css\";i:416;s:25:\"site-title/editor-rtl.css\";i:417;s:29:\"site-title/editor-rtl.min.css\";i:418;s:21:\"site-title/editor.css\";i:419;s:25:\"site-title/editor.min.css\";i:420;s:24:\"site-title/style-rtl.css\";i:421;s:28:\"site-title/style-rtl.min.css\";i:422;s:20:\"site-title/style.css\";i:423;s:24:\"site-title/style.min.css\";i:424;s:26:\"social-link/editor-rtl.css\";i:425;s:30:\"social-link/editor-rtl.min.css\";i:426;s:22:\"social-link/editor.css\";i:427;s:26:\"social-link/editor.min.css\";i:428;s:27:\"social-links/editor-rtl.css\";i:429;s:31:\"social-links/editor-rtl.min.css\";i:430;s:23:\"social-links/editor.css\";i:431;s:27:\"social-links/editor.min.css\";i:432;s:26:\"social-links/style-rtl.css\";i:433;s:30:\"social-links/style-rtl.min.css\";i:434;s:22:\"social-links/style.css\";i:435;s:26:\"social-links/style.min.css\";i:436;s:21:\"spacer/editor-rtl.css\";i:437;s:25:\"spacer/editor-rtl.min.css\";i:438;s:17:\"spacer/editor.css\";i:439;s:21:\"spacer/editor.min.css\";i:440;s:20:\"spacer/style-rtl.css\";i:441;s:24:\"spacer/style-rtl.min.css\";i:442;s:16:\"spacer/style.css\";i:443;s:20:\"spacer/style.min.css\";i:444;s:20:\"table/editor-rtl.css\";i:445;s:24:\"table/editor-rtl.min.css\";i:446;s:16:\"table/editor.css\";i:447;s:20:\"table/editor.min.css\";i:448;s:19:\"table/style-rtl.css\";i:449;s:23:\"table/style-rtl.min.css\";i:450;s:15:\"table/style.css\";i:451;s:19:\"table/style.min.css\";i:452;s:19:\"table/theme-rtl.css\";i:453;s:23:\"table/theme-rtl.min.css\";i:454;s:15:\"table/theme.css\";i:455;s:19:\"table/theme.min.css\";i:456;s:23:\"tag-cloud/style-rtl.css\";i:457;s:27:\"tag-cloud/style-rtl.min.css\";i:458;s:19:\"tag-cloud/style.css\";i:459;s:23:\"tag-cloud/style.min.css\";i:460;s:28:\"template-part/editor-rtl.css\";i:461;s:32:\"template-part/editor-rtl.min.css\";i:462;s:24:\"template-part/editor.css\";i:463;s:28:\"template-part/editor.min.css\";i:464;s:27:\"template-part/theme-rtl.css\";i:465;s:31:\"template-part/theme-rtl.min.css\";i:466;s:23:\"template-part/theme.css\";i:467;s:27:\"template-part/theme.min.css\";i:468;s:30:\"term-description/style-rtl.css\";i:469;s:34:\"term-description/style-rtl.min.css\";i:470;s:26:\"term-description/style.css\";i:471;s:30:\"term-description/style.min.css\";i:472;s:27:\"text-columns/editor-rtl.css\";i:473;s:31:\"text-columns/editor-rtl.min.css\";i:474;s:23:\"text-columns/editor.css\";i:475;s:27:\"text-columns/editor.min.css\";i:476;s:26:\"text-columns/style-rtl.css\";i:477;s:30:\"text-columns/style-rtl.min.css\";i:478;s:22:\"text-columns/style.css\";i:479;s:26:\"text-columns/style.min.css\";i:480;s:19:\"verse/style-rtl.css\";i:481;s:23:\"verse/style-rtl.min.css\";i:482;s:15:\"verse/style.css\";i:483;s:19:\"verse/style.min.css\";i:484;s:20:\"video/editor-rtl.css\";i:485;s:24:\"video/editor-rtl.min.css\";i:486;s:16:\"video/editor.css\";i:487;s:20:\"video/editor.min.css\";i:488;s:19:\"video/style-rtl.css\";i:489;s:23:\"video/style-rtl.min.css\";i:490;s:15:\"video/style.css\";i:491;s:19:\"video/style.min.css\";i:492;s:19:\"video/theme-rtl.css\";i:493;s:23:\"video/theme-rtl.min.css\";i:494;s:15:\"video/theme.css\";i:495;s:19:\"video/theme.min.css\";}}','on'),(124,'tribe_last_updated_option','1736344163.1553','auto'),(126,'_transient_timeout__tribe_events_activation_redirect','1736344185','off'),(127,'_transient__tribe_events_activation_redirect','1','off'),(128,'tribe_events_calendar_options','a:16:{s:8:\"did_init\";b:1;s:19:\"tribeEventsTemplate\";s:0:\"\";s:16:\"tribeEnableViews\";a:3:{i:0;s:4:\"list\";i:1;s:5:\"month\";i:2;s:3:\"day\";}s:10:\"viewOption\";s:4:\"list\";s:14:\"schema-version\";s:5:\"6.9.0\";s:21:\"previous_ecp_versions\";a:1:{i:0;s:1:\"0\";}s:18:\"latest_ecp_version\";s:5:\"6.9.0\";s:18:\"dateWithYearFormat\";s:6:\"F j, Y\";s:24:\"recurrenceMaxMonthsAfter\";i:24;s:22:\"google_maps_js_api_key\";s:39:\"AIzaSyDNsicAsP6-VuGtAb1O9riI3oc_NOb7IOU\";s:25:\"ticket-enabled-post-types\";a:2:{i:0;s:12:\"tribe_events\";i:1;s:4:\"page\";}s:28:\"event-tickets-schema-version\";s:6:\"5.18.0\";s:31:\"previous_event_tickets_versions\";a:1:{i:0;s:1:\"0\";}s:28:\"latest_event_tickets_version\";s:6:\"5.18.0\";s:36:\"previous_event_tickets_plus_versions\";a:1:{i:0;s:1:\"0\";}s:33:\"latest_event_tickets_plus_version\";s:5:\"6.1.2\";}','auto'),(129,'schema-ActionScheduler_StoreSchema','7.0.1736344156','auto'),(130,'schema-ActionScheduler_LoggerSchema','3.0.1736344156','auto'),(133,'tribe_last_save_post','1736344295.2401','auto'),(134,'widget_tribe-widget-events-list','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(135,'stellarwp_telemetry_last_send','','auto'),(136,'stellarwp_telemetry','a:1:{s:7:\"plugins\";a:1:{s:19:\"the-events-calendar\";a:2:{s:7:\"wp_slug\";s:43:\"the-events-calendar/the-events-calendar.php\";s:5:\"optin\";b:0;}}}','auto'),(137,'stellarwp_telemetry_the-events-calendar_show_optin','1','auto'),(138,'_transient_timeout_as-post-store-dependencies-met','1736430556','off'),(139,'_transient_as-post-store-dependencies-met','yes','off'),(141,'widget_tribe-widget-event-countdown','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(142,'widget_tribe-widget-featured-venue','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(143,'widget_tribe-widget-events-month','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(144,'widget_tribe-widget-events-week','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(145,'_transient_timeout__tribe_tickets_activation_redirect','1736344186','off'),(146,'_transient__tribe_tickets_activation_redirect','1','off'),(147,'_transient_tec_tickets_commerce_setup_stripe_webhook','1','on'),(148,'stellar_schema_version_tec-order-modifiers','1.0.0','auto'),(149,'stellar_schema_version_tec-order-modifiers-meta','1.0.0','auto'),(150,'stellar_schema_version_tec-order-modifiers-relationships','1.0.0','auto'),(151,'stellar_schema_version_tec-slr-maps','1.0.0','auto'),(152,'stellar_schema_version_tec-slr-layouts','1.0.0','auto'),(153,'stellar_schema_version_tec-slr-seat-types','1.0.0','auto'),(154,'stellar_schema_version_tec-slr-sessions','1.1.0','auto'),(155,'edd_settings','a:6:{s:13:\"purchase_page\";i:4;s:12:\"success_page\";i:5;s:12:\"failure_page\";i:6;s:21:\"purchase_history_page\";i:7;s:17:\"confirmation_page\";i:8;s:16:\"sequential_start\";i:8011;}','auto'),(156,'edd_session_handling','db','auto'),(157,'tec_automator_zapier_secret_key','09ea4475c2ebd4a8e60adb72b74c592ab27dd9bca24624c5db3da442b2b0d38959ac6173ab8220152c42e34c792c2ba8a99f019569d88103727d56946fc4f06dfabd09c22d978c529d4c33eb1fb6176d551ee7d81de499198e09a0f2eb1bee0a3970b55f767ec526c660396a8e05b81093a9fa4abf4ecef5b65025fbe2a044b9','auto'),(158,'tec_automator_power_automate_secret_key','198954a733d37a76e10607f68a9c46110ed90dc14663fc788c9a22bcd85fa645cacad52ec6d5be56ea756a674563494b8e27e1365ba42cb0eadb0dc6d050e072c748ca5cb9ca2e458e7a82cc22bf8d0d1db1f2e22253be0494896fa412cf3f0735642b9a9b7ddf6f88a634f871aaab43f569188609d23b7f84f754410dcdcdf8','auto'),(159,'edd_activation_date','1736342429','auto'),(160,'_transient_timeout_edd_check_protection_files','1736430558','off'),(161,'_transient_edd_check_protection_files','1','off'),(162,'edd_default_api_version','v2','auto'),(163,'edd_completed_upgrades','a:17:{i:0;s:21:\"upgrade_payment_taxes\";i:1;s:37:\"upgrade_customer_payments_association\";i:2;s:21:\"upgrade_user_api_keys\";i:3;s:25:\"remove_refunded_sale_logs\";i:4;s:29:\"update_file_download_log_data\";i:5;s:26:\"migrate_order_actions_date\";i:6;s:19:\"discounts_start_end\";i:7;s:17:\"migrate_tax_rates\";i:8;s:17:\"migrate_discounts\";i:9;s:14:\"migrate_orders\";i:10;s:26:\"migrate_customer_addresses\";i:11;s:32:\"migrate_customer_email_addresses\";i:12;s:22:\"migrate_customer_notes\";i:13;s:12:\"migrate_logs\";i:14;s:19:\"migrate_order_notes\";i:15;s:23:\"v30_legacy_data_removed\";i:16;s:21:\"edd_emails_registered\";}','auto'),(164,'edd_version','3.3.5.2','auto'),(165,'_transient_timeout_edd_onboarding_redirect','1736344188','off'),(166,'_transient_edd_onboarding_redirect','1','off'),(167,'_transient_edd_cache_excluded_uris','a:4:{i:0;s:3:\"p=4\";i:1;s:3:\"p=5\";i:2;s:9:\"/checkout\";i:3;s:8:\"/receipt\";}','on'),(168,'widget_edd_cart_widget','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(169,'widget_edd_categories_tags_widget','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(170,'widget_edd_product_details','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(171,'wpdb_edd_emails_version','202310270','auto'),(172,'wpdb_edd_emailmeta_version','202311040','auto'),(175,'woocommerce_newly_installed','yes','auto'),(176,'woocommerce_schema_version','920','auto'),(177,'woocommerce_store_address','','on'),(178,'woocommerce_store_address_2','','on'),(179,'woocommerce_store_city','','on'),(180,'woocommerce_default_country','US:CA','on'),(181,'woocommerce_store_postcode','','on'),(182,'woocommerce_allowed_countries','all','on'),(183,'woocommerce_all_except_countries','','on'),(184,'woocommerce_specific_allowed_countries','','on'),(185,'woocommerce_ship_to_countries','','on'),(186,'woocommerce_specific_ship_to_countries','','on'),(187,'woocommerce_default_customer_address','base','on'),(188,'woocommerce_calc_taxes','no','on'),(189,'woocommerce_enable_coupons','yes','on'),(190,'woocommerce_calc_discounts_sequentially','no','off'),(191,'woocommerce_currency','USD','on'),(192,'woocommerce_currency_pos','left','on'),(193,'woocommerce_price_thousand_sep',',','on'),(194,'woocommerce_price_decimal_sep','.','on'),(195,'woocommerce_price_num_decimals','2','on'),(196,'woocommerce_shop_page_id','10','on'),(197,'woocommerce_cart_redirect_after_add','no','on'),(198,'woocommerce_enable_ajax_add_to_cart','yes','on'),(199,'woocommerce_placeholder_image','9','on'),(200,'woocommerce_weight_unit','kg','on'),(201,'woocommerce_dimension_unit','cm','on'),(202,'woocommerce_enable_reviews','yes','on'),(203,'woocommerce_review_rating_verification_label','yes','off'),(204,'woocommerce_review_rating_verification_required','no','off'),(205,'woocommerce_enable_review_rating','yes','on'),(206,'woocommerce_review_rating_required','yes','off'),(207,'woocommerce_manage_stock','yes','on'),(208,'woocommerce_hold_stock_minutes','60','off'),(209,'woocommerce_notify_low_stock','yes','off'),(210,'woocommerce_notify_no_stock','yes','off'),(211,'woocommerce_stock_email_recipient','admin@wordpress.test','off'),(212,'woocommerce_notify_low_stock_amount','2','off'),(213,'woocommerce_notify_no_stock_amount','0','on'),(214,'woocommerce_hide_out_of_stock_items','no','on'),(215,'woocommerce_stock_format','','on'),(216,'woocommerce_file_download_method','force','off'),(217,'woocommerce_downloads_redirect_fallback_allowed','no','off'),(218,'woocommerce_downloads_require_login','no','off'),(219,'woocommerce_downloads_grant_access_after_payment','yes','off'),(220,'woocommerce_downloads_deliver_inline','','off'),(221,'woocommerce_downloads_add_hash_to_filename','yes','on'),(222,'woocommerce_downloads_count_partial','yes','on'),(224,'woocommerce_attribute_lookup_direct_updates','no','on'),(225,'woocommerce_attribute_lookup_optimized_updates','no','on'),(226,'woocommerce_product_match_featured_image_by_sku','no','on'),(227,'woocommerce_prices_include_tax','no','on'),(228,'woocommerce_tax_based_on','shipping','on'),(229,'woocommerce_shipping_tax_class','inherit','on'),(230,'woocommerce_tax_round_at_subtotal','no','on'),(231,'woocommerce_tax_classes','','on'),(232,'woocommerce_tax_display_shop','excl','on'),(233,'woocommerce_tax_display_cart','excl','on'),(234,'woocommerce_price_display_suffix','','on'),(235,'woocommerce_tax_total_display','itemized','off'),(236,'woocommerce_enable_shipping_calc','yes','off'),(237,'woocommerce_shipping_cost_requires_address','no','on'),(238,'woocommerce_ship_to_destination','billing','off'),(239,'woocommerce_shipping_debug_mode','no','on'),(240,'woocommerce_enable_guest_checkout','yes','off'),(241,'woocommerce_enable_checkout_login_reminder','no','off'),(242,'woocommerce_enable_signup_and_login_from_checkout','no','off'),(243,'woocommerce_enable_delayed_account_creation','no','off'),(244,'woocommerce_enable_myaccount_registration','no','off'),(245,'woocommerce_registration_generate_password','yes','off'),(246,'woocommerce_registration_generate_username','yes','off'),(247,'woocommerce_erasure_request_removes_order_data','no','off'),(248,'woocommerce_erasure_request_removes_download_data','no','off'),(249,'woocommerce_allow_bulk_remove_personal_data','no','off'),(250,'woocommerce_registration_privacy_policy_text','Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our [privacy_policy].','on'),(251,'woocommerce_checkout_privacy_policy_text','Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our [privacy_policy].','on'),(252,'woocommerce_delete_inactive_accounts','a:2:{s:6:\"number\";s:0:\"\";s:4:\"unit\";s:6:\"months\";}','off'),(253,'woocommerce_trash_pending_orders','','off'),(254,'woocommerce_trash_failed_orders','','off'),(255,'woocommerce_trash_cancelled_orders','','off'),(256,'woocommerce_anonymize_completed_orders','a:2:{s:6:\"number\";s:0:\"\";s:4:\"unit\";s:6:\"months\";}','off'),(257,'woocommerce_email_from_name','EVA Integration Tests','off'),(258,'woocommerce_email_from_address','admin@wordpress.test','off'),(259,'woocommerce_email_header_image','','off'),(260,'woocommerce_email_base_color','#7f54b3','off'),(261,'woocommerce_email_background_color','#f7f7f7','off'),(262,'woocommerce_email_body_background_color','#ffffff','off'),(263,'woocommerce_email_text_color','#3c3c3c','off'),(264,'woocommerce_email_footer_text','{site_title} &mdash; Built with {WooCommerce}','off'),(265,'woocommerce_email_footer_text_color','#3c3c3c','off'),(266,'woocommerce_merchant_email_notifications','no','off'),(267,'woocommerce_cart_page_id','11','off'),(268,'woocommerce_checkout_page_id','12','off'),(269,'woocommerce_myaccount_page_id','13','off'),(270,'woocommerce_terms_page_id','','off'),(271,'woocommerce_force_ssl_checkout','no','on'),(272,'woocommerce_unforce_ssl_checkout','no','on'),(273,'woocommerce_checkout_pay_endpoint','order-pay','on'),(274,'woocommerce_checkout_order_received_endpoint','order-received','on'),(275,'woocommerce_myaccount_add_payment_method_endpoint','add-payment-method','on'),(276,'woocommerce_myaccount_delete_payment_method_endpoint','delete-payment-method','on'),(277,'woocommerce_myaccount_set_default_payment_method_endpoint','set-default-payment-method','on'),(278,'woocommerce_myaccount_orders_endpoint','orders','on'),(279,'woocommerce_myaccount_view_order_endpoint','view-order','on'),(280,'woocommerce_myaccount_downloads_endpoint','downloads','on'),(281,'woocommerce_myaccount_edit_account_endpoint','edit-account','on'),(282,'woocommerce_myaccount_edit_address_endpoint','edit-address','on'),(283,'woocommerce_myaccount_payment_methods_endpoint','payment-methods','on'),(284,'woocommerce_myaccount_lost_password_endpoint','lost-password','on'),(285,'woocommerce_logout_endpoint','customer-logout','on'),(286,'woocommerce_api_enabled','no','on'),(287,'woocommerce_allow_tracking','no','on'),(288,'woocommerce_show_marketplace_suggestions','yes','off'),(289,'woocommerce_custom_orders_table_enabled','no','on'),(290,'woocommerce_analytics_enabled','yes','on'),(291,'woocommerce_feature_order_attribution_enabled','yes','on'),(292,'woocommerce_feature_site_visibility_badge_enabled','yes','on'),(293,'woocommerce_feature_product_block_editor_enabled','no','on'),(294,'woocommerce_hpos_fts_index_enabled','no','on'),(295,'woocommerce_feature_cost_of_goods_sold_enabled','no','on'),(296,'woocommerce_single_image_width','600','on'),(297,'woocommerce_thumbnail_image_width','300','on'),(298,'woocommerce_checkout_highlight_required_fields','yes','on'),(299,'woocommerce_demo_store','no','off'),(300,'wc_downloads_approved_directories_mode','enabled','auto'),(301,'woocommerce_permalinks','a:5:{s:12:\"product_base\";s:7:\"product\";s:13:\"category_base\";s:16:\"product-category\";s:8:\"tag_base\";s:11:\"product-tag\";s:14:\"attribute_base\";s:0:\"\";s:22:\"use_verbose_page_rules\";b:0;}','auto'),(302,'current_theme_supports_woocommerce','no','auto'),(303,'woocommerce_queue_flush_rewrite_rules','no','auto'),(304,'_transient_wc_attribute_taxonomies','a:0:{}','on'),(305,'_transient_timeout_wc_term_counts','1738936160','off'),(306,'_transient_wc_term_counts','a:0:{}','off'),(307,'product_cat_children','a:0:{}','auto'),(308,'default_product_cat','15','auto'),(309,'woocommerce_refund_returns_page_created','14','off'),(310,'woocommerce_refund_returns_page_id','14','auto'),(311,'_transient_timeout__wc_activation_redirect','1736344190','off'),(312,'_transient__wc_activation_redirect','1','off'),(313,'woocommerce_paypal_settings','a:23:{s:7:\"enabled\";s:2:\"no\";s:5:\"title\";s:6:\"PayPal\";s:11:\"description\";s:85:\"Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.\";s:5:\"email\";s:20:\"admin@wordpress.test\";s:8:\"advanced\";s:0:\"\";s:8:\"testmode\";s:2:\"no\";s:5:\"debug\";s:2:\"no\";s:16:\"ipn_notification\";s:3:\"yes\";s:14:\"receiver_email\";s:20:\"admin@wordpress.test\";s:14:\"identity_token\";s:0:\"\";s:14:\"invoice_prefix\";s:3:\"WC-\";s:13:\"send_shipping\";s:3:\"yes\";s:16:\"address_override\";s:2:\"no\";s:13:\"paymentaction\";s:4:\"sale\";s:9:\"image_url\";s:0:\"\";s:11:\"api_details\";s:0:\"\";s:12:\"api_username\";s:0:\"\";s:12:\"api_password\";s:0:\"\";s:13:\"api_signature\";s:0:\"\";s:20:\"sandbox_api_username\";s:0:\"\";s:20:\"sandbox_api_password\";s:0:\"\";s:21:\"sandbox_api_signature\";s:0:\"\";s:12:\"_should_load\";s:2:\"no\";}','on'),(314,'woocommerce_version','9.5.1','auto'),(315,'woocommerce_db_version','9.5.1','auto'),(316,'woocommerce_store_id','cf078028-6e17-42b7-8c82-eb7994ffbdc9','auto'),(317,'woocommerce_admin_install_timestamp','1736344160','auto'),(318,'woocommerce_inbox_variant_assignment','3','auto'),(319,'woocommerce_remote_variant_assignment','112','auto'),(320,'woocommerce_attribute_lookup_enabled','no','auto'),(321,'_transient_timeout__woocommerce_upload_directory_status','1736430560','off'),(322,'_transient__woocommerce_upload_directory_status','protected','off'),(323,'_transient_woocommerce_activated_plugin','woocommerce/woocommerce.php','on'),(324,'_transient_jetpack_autoloader_plugin_paths','a:1:{i:0;s:29:\"{{WP_PLUGIN_DIR}}/woocommerce\";}','on'),(325,'woocommerce_admin_notices','a:2:{i:0;s:20:\"no_secure_connection\";i:1;s:14:\"template_files\";}','auto'),(326,'wc_blocks_version','11.8.0-dev','auto'),(327,'woocommerce_maxmind_geolocation_settings','a:1:{s:15:\"database_prefix\";s:32:\"1uDXkYEjrJnSUBUiPx2tyPGSPebNLVU6\";}','on'),(328,'_transient_woocommerce_webhook_ids_status_active','a:0:{}','on'),(329,'widget_woocommerce_widget_cart','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(330,'widget_woocommerce_layered_nav_filters','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(331,'widget_woocommerce_layered_nav','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(332,'widget_woocommerce_price_filter','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(333,'widget_woocommerce_product_categories','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(334,'widget_woocommerce_product_search','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(335,'widget_woocommerce_product_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(336,'widget_woocommerce_products','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(337,'widget_woocommerce_recently_viewed_products','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(338,'widget_woocommerce_top_rated_products','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(339,'widget_woocommerce_recent_reviews','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(340,'widget_woocommerce_rating_filter','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(341,'_site_transient_timeout_woocommerce_blocks_patterns','1738936161','off'),(342,'_site_transient_woocommerce_blocks_patterns','a:2:{s:7:\"version\";s:5:\"9.5.1\";s:8:\"patterns\";a:38:{i:0;a:11:{s:5:\"title\";s:6:\"Banner\";s:4:\"slug\";s:25:\"woocommerce-blocks/banner\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:64:\"/var/www/html/wp-content/plugins/woocommerce/patterns/banner.php\";}i:1;a:11:{s:5:\"title\";s:23:\"Coming Soon Entire Site\";s:4:\"slug\";s:35:\"woocommerce/coming-soon-entire-site\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:5:\"false\";s:11:\"featureFlag\";s:17:\"launch-your-store\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:81:\"/var/www/html/wp-content/plugins/woocommerce/patterns/coming-soon-entire-site.php\";}i:2;a:11:{s:5:\"title\";s:22:\"Coming Soon Store Only\";s:4:\"slug\";s:34:\"woocommerce/coming-soon-store-only\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:5:\"false\";s:11:\"featureFlag\";s:17:\"launch-your-store\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:80:\"/var/www/html/wp-content/plugins/woocommerce/patterns/coming-soon-store-only.php\";}i:3;a:11:{s:5:\"title\";s:11:\"Coming Soon\";s:4:\"slug\";s:23:\"woocommerce/coming-soon\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:5:\"false\";s:11:\"featureFlag\";s:17:\"launch-your-store\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:69:\"/var/www/html/wp-content/plugins/woocommerce/patterns/coming-soon.php\";}i:4;a:11:{s:5:\"title\";s:29:\"Content right with image left\";s:4:\"slug\";s:48:\"woocommerce-blocks/content-right-with-image-left\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:18:\"WooCommerce, About\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:82:\"/var/www/html/wp-content/plugins/woocommerce/patterns/content-right-image-left.php\";}i:5;a:11:{s:5:\"title\";s:29:\"Featured Category Cover Image\";s:4:\"slug\";s:48:\"woocommerce-blocks/featured-category-cover-image\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:18:\"WooCommerce, Intro\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:87:\"/var/www/html/wp-content/plugins/woocommerce/patterns/featured-category-cover-image.php\";}i:6;a:11:{s:5:\"title\";s:24:\"Featured Category Triple\";s:4:\"slug\";s:43:\"woocommerce-blocks/featured-category-triple\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:82:\"/var/www/html/wp-content/plugins/woocommerce/patterns/featured-category-triple.php\";}i:7;a:11:{s:5:\"title\";s:15:\"Product Filters\";s:4:\"slug\";s:34:\"woocommerce-blocks/product-filters\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:108:\"woocommerce/active-filters, woocommerce/price-filter, woocommerce/attribute-filter, woocommerce/stock-filter\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:65:\"/var/www/html/wp-content/plugins/woocommerce/patterns/filters.php\";}i:8;a:11:{s:5:\"title\";s:12:\"Large Footer\";s:4:\"slug\";s:31:\"woocommerce-blocks/footer-large\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/footer\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:70:\"/var/www/html/wp-content/plugins/woocommerce/patterns/footer-large.php\";}i:9;a:11:{s:5:\"title\";s:23:\"Footer with Simple Menu\";s:4:\"slug\";s:37:\"woocommerce-blocks/footer-simple-menu\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/footer\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:76:\"/var/www/html/wp-content/plugins/woocommerce/patterns/footer-simple-menu.php\";}i:10;a:11:{s:5:\"title\";s:17:\"Footer with menus\";s:4:\"slug\";s:38:\"woocommerce-blocks/footer-with-3-menus\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/footer\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:77:\"/var/www/html/wp-content/plugins/woocommerce/patterns/footer-with-3-menus.php\";}i:11;a:11:{s:5:\"title\";s:28:\"Four Image Grid Content Left\";s:4:\"slug\";s:47:\"woocommerce-blocks/form-image-grid-content-left\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:18:\"WooCommerce, About\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:86:\"/var/www/html/wp-content/plugins/woocommerce/patterns/four-image-grid-content-left.php\";}i:12;a:11:{s:5:\"title\";s:20:\"Centered Header Menu\";s:4:\"slug\";s:39:\"woocommerce-blocks/header-centered-menu\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/header\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:81:\"/var/www/html/wp-content/plugins/woocommerce/patterns/header-centered-pattern.php\";}i:13;a:11:{s:5:\"title\";s:23:\"Distraction Free Header\";s:4:\"slug\";s:42:\"woocommerce-blocks/header-distraction-free\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/header\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:81:\"/var/www/html/wp-content/plugins/woocommerce/patterns/header-distraction-free.php\";}i:14;a:11:{s:5:\"title\";s:16:\"Essential Header\";s:4:\"slug\";s:35:\"woocommerce-blocks/header-essential\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/header\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:74:\"/var/www/html/wp-content/plugins/woocommerce/patterns/header-essential.php\";}i:15;a:11:{s:5:\"title\";s:12:\"Large Header\";s:4:\"slug\";s:31:\"woocommerce-blocks/header-large\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/header\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:70:\"/var/www/html/wp-content/plugins/woocommerce/patterns/header-large.php\";}i:16;a:11:{s:5:\"title\";s:14:\"Minimal Header\";s:4:\"slug\";s:33:\"woocommerce-blocks/header-minimal\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:25:\"core/template-part/header\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:72:\"/var/www/html/wp-content/plugins/woocommerce/patterns/header-minimal.php\";}i:17;a:11:{s:5:\"title\";s:47:\"Heading with three columns of content with link\";s:4:\"slug\";s:66:\"woocommerce-blocks/heading-with-three-columns-of-content-with-link\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:21:\"WooCommerce, Services\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:105:\"/var/www/html/wp-content/plugins/woocommerce/patterns/heading-with-three-columns-of-content-with-link.php\";}i:18;a:11:{s:5:\"title\";s:20:\"Hero Product 3 Split\";s:4:\"slug\";s:39:\"woocommerce-blocks/hero-product-3-split\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:78:\"/var/www/html/wp-content/plugins/woocommerce/patterns/hero-product-3-split.php\";}i:19;a:11:{s:5:\"title\";s:23:\"Hero Product Chessboard\";s:4:\"slug\";s:42:\"woocommerce-blocks/hero-product-chessboard\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:81:\"/var/www/html/wp-content/plugins/woocommerce/patterns/hero-product-chessboard.php\";}i:20;a:11:{s:5:\"title\";s:18:\"Hero Product Split\";s:4:\"slug\";s:37:\"woocommerce-blocks/hero-product-split\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:18:\"WooCommerce, Intro\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:76:\"/var/www/html/wp-content/plugins/woocommerce/patterns/hero-product-split.php\";}i:21;a:11:{s:5:\"title\";s:33:\"Centered content with image below\";s:4:\"slug\";s:52:\"woocommerce-blocks/centered-content-with-image-below\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:18:\"WooCommerce, Intro\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:97:\"/var/www/html/wp-content/plugins/woocommerce/patterns/intro-centered-content-with-image-below.php\";}i:22;a:11:{s:5:\"title\";s:22:\"Just Arrived Full Hero\";s:4:\"slug\";s:41:\"woocommerce-blocks/just-arrived-full-hero\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:18:\"WooCommerce, Intro\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:80:\"/var/www/html/wp-content/plugins/woocommerce/patterns/just-arrived-full-hero.php\";}i:23;a:11:{s:5:\"title\";s:33:\"No Products Found - Clear Filters\";s:4:\"slug\";s:43:\"woocommerce/no-products-found-clear-filters\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:2:\"no\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:83:\"/var/www/html/wp-content/plugins/woocommerce/patterns/no-products-found-filters.php\";}i:24;a:11:{s:5:\"title\";s:17:\"No Products Found\";s:4:\"slug\";s:29:\"woocommerce/no-products-found\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:2:\"no\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:75:\"/var/www/html/wp-content/plugins/woocommerce/patterns/no-products-found.php\";}i:25;a:11:{s:5:\"title\";s:19:\"Default Coming Soon\";s:4:\"slug\";s:36:\"woocommerce/page-coming-soon-default\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:5:\"false\";s:11:\"featureFlag\";s:31:\"coming-soon-newsletter-template\";s:13:\"templateTypes\";s:11:\"coming-soon\";s:6:\"source\";s:82:\"/var/www/html/wp-content/plugins/woocommerce/patterns/page-coming-soon-default.php\";}i:26;a:11:{s:5:\"title\";s:34:\"Coming Soon With Header and Footer\";s:4:\"slug\";s:47:\"woocommerce/page-coming-soon-with-header-footer\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:5:\"false\";s:11:\"featureFlag\";s:31:\"coming-soon-newsletter-template\";s:13:\"templateTypes\";s:11:\"coming-soon\";s:6:\"source\";s:93:\"/var/www/html/wp-content/plugins/woocommerce/patterns/page-coming-soon-with-header-footer.php\";}i:27;a:11:{s:5:\"title\";s:28:\"Product Collection 3 Columns\";s:4:\"slug\";s:47:\"woocommerce-blocks/product-collection-3-columns\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:86:\"/var/www/html/wp-content/plugins/woocommerce/patterns/product-collection-3-columns.php\";}i:28;a:11:{s:5:\"title\";s:28:\"Product Collection 4 Columns\";s:4:\"slug\";s:47:\"woocommerce-blocks/product-collection-4-columns\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:86:\"/var/www/html/wp-content/plugins/woocommerce/patterns/product-collection-4-columns.php\";}i:29;a:11:{s:5:\"title\";s:28:\"Product Collection 5 Columns\";s:4:\"slug\";s:47:\"woocommerce-blocks/product-collection-5-columns\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:86:\"/var/www/html/wp-content/plugins/woocommerce/patterns/product-collection-5-columns.php\";}i:30;a:11:{s:5:\"title\";s:47:\"Product Collection: Featured Products 5 Columns\";s:4:\"slug\";s:65:\"woocommerce-blocks/product-collection-featured-products-5-columns\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:104:\"/var/www/html/wp-content/plugins/woocommerce/patterns/product-collection-featured-products-5-columns.php\";}i:31;a:11:{s:5:\"title\";s:15:\"Product Gallery\";s:4:\"slug\";s:48:\"woocommerce-blocks/product-query-product-gallery\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:29:\"WooCommerce, featured-selling\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:36:\"core/query/woocommerce/product-query\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:87:\"/var/www/html/wp-content/plugins/woocommerce/patterns/product-query-product-gallery.php\";}i:32;a:11:{s:5:\"title\";s:14:\"Product Search\";s:4:\"slug\";s:31:\"woocommerce/product-search-form\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:2:\"no\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:77:\"/var/www/html/wp-content/plugins/woocommerce/patterns/product-search-form.php\";}i:33;a:11:{s:5:\"title\";s:16:\"Related Products\";s:4:\"slug\";s:35:\"woocommerce-blocks/related-products\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:11:\"WooCommerce\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:5:\"false\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:74:\"/var/www/html/wp-content/plugins/woocommerce/patterns/related-products.php\";}i:34;a:11:{s:5:\"title\";s:33:\"Social: Follow us on social media\";s:4:\"slug\";s:51:\"woocommerce-blocks/social-follow-us-in-social-media\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:25:\"WooCommerce, social-media\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:90:\"/var/www/html/wp-content/plugins/woocommerce/patterns/social-follow-us-in-social-media.php\";}i:35;a:11:{s:5:\"title\";s:22:\"Testimonials 3 Columns\";s:4:\"slug\";s:41:\"woocommerce-blocks/testimonials-3-columns\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:20:\"WooCommerce, Reviews\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:80:\"/var/www/html/wp-content/plugins/woocommerce/patterns/testimonials-3-columns.php\";}i:36;a:11:{s:5:\"title\";s:19:\"Testimonials Single\";s:4:\"slug\";s:38:\"woocommerce-blocks/testimonials-single\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:20:\"WooCommerce, Reviews\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:77:\"/var/www/html/wp-content/plugins/woocommerce/patterns/testimonials-single.php\";}i:37;a:11:{s:5:\"title\";s:37:\"Three columns with images and content\";s:4:\"slug\";s:56:\"woocommerce-blocks/three-columns-with-images-and-content\";s:11:\"description\";s:0:\"\";s:13:\"viewportWidth\";s:0:\"\";s:10:\"categories\";s:21:\"WooCommerce, Services\";s:8:\"keywords\";s:0:\"\";s:10:\"blockTypes\";s:0:\"\";s:8:\"inserter\";s:0:\"\";s:11:\"featureFlag\";s:0:\"\";s:13:\"templateTypes\";s:0:\"\";s:6:\"source\";s:95:\"/var/www/html/wp-content/plugins/woocommerce/patterns/three-columns-with-images-and-content.php\";}}}','off'),(343,'theme_mods_twentytwentyfour','a:1:{s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1736344161;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}}}}','off'),(344,'current_theme','Twenty Twenty-Four','auto'),(345,'theme_mods_','a:2:{s:19:\"wp_classic_sidebars\";a:0:{}s:18:\"nav_menu_locations\";a:0:{}}','on'),(346,'theme_switched','','auto'),(347,'_transient_timeout_woocommerce_blocks_asset_api_script_data','1738936161','off'),(348,'_transient_woocommerce_blocks_asset_api_script_data','{\"script_data\":{\"assets\\/client\\/blocks\\/wc-settings.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-settings.js\",\"version\":\"eb5ac71a827c4c81fed8\",\"dependencies\":[\"wp-hooks\",\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-types.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-types.js\",\"version\":\"bda84b1be3361607d04a\",\"dependencies\":[\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-blocks-middleware.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-middleware.js\",\"version\":\"e3f189e7e5007fb14fff\",\"dependencies\":[\"wp-api-fetch\",\"wp-polyfill\",\"wp-url\"]},\"assets\\/client\\/blocks\\/wc-blocks-data.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-data.js\",\"version\":\"bf6b6808e29dd19e91b9\",\"dependencies\":[\"wc-blocks-registry\",\"wc-settings\",\"wc-types\",\"wp-api-fetch\",\"wp-data\",\"wp-data-controls\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-notices\",\"wp-polyfill\",\"wp-url\"]},\"assets\\/client\\/blocks\\/wc-blocks-vendors.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-vendors.js\",\"version\":\"b11f59edc6d52068b7a2\",\"dependencies\":[\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-blocks-registry.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-registry.js\",\"version\":\"8db91d7a57c47e3eb6f6\",\"dependencies\":[\"react\",\"wc-settings\",\"wp-data\",\"wp-deprecated\",\"wp-element\",\"wp-hooks\",\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-blocks.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks.js\",\"version\":\"8eba41362d1dafa41905\",\"dependencies\":[\"react\",\"wc-types\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-dom-ready\",\"wp-element\",\"wp-escape-html\",\"wp-hooks\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"assets\\/client\\/blocks\\/wc-blocks-shared-context.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-shared-context.js\",\"version\":\"6eb6865831aa5a75475d\",\"dependencies\":[\"react\",\"wp-element\",\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-blocks-shared-hocs.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-shared-hocs.js\",\"version\":\"cba59eca47d9101980bf\",\"dependencies\":[\"react\",\"wc-blocks-data-store\",\"wc-blocks-shared-context\",\"wc-types\",\"wp-data\",\"wp-element\",\"wp-is-shallow-equal\",\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/price-format.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/price-format.js\",\"version\":\"483d2180eda1f53dc60d\",\"dependencies\":[\"wc-settings\",\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-blocks-frontend-vendors-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-blocks-frontend-vendors-frontend.js\",\"version\":\"310fb0b97cc939547132\",\"dependencies\":[\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-cart-checkout-vendors-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-cart-checkout-vendors-frontend.js\",\"version\":\"7c1cbc00f89f395a28b2\",\"dependencies\":[\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-cart-checkout-base-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-cart-checkout-base-frontend.js\",\"version\":\"c785ec96148aa09a247a\",\"dependencies\":[\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/blocks-checkout.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/blocks-checkout.js\",\"version\":\"b00178cb31b1aeee8038\",\"dependencies\":[\"wc-cart-checkout-base\",\"wc-cart-checkout-vendors\",\"react\",\"react-dom\",\"wc-blocks-components\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-settings\",\"wc-types\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-warning\"]},\"assets\\/client\\/blocks\\/blocks-components.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/blocks-components.js\",\"version\":\"5f11d5c440e62fef7bb5\",\"dependencies\":[\"wc-cart-checkout-base\",\"wc-cart-checkout-vendors\",\"react\",\"react-dom\",\"wc-blocks-data-store\",\"wc-settings\",\"wc-types\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"assets\\/client\\/blocks\\/wc-interactivity-dropdown.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-interactivity-dropdown.js\",\"version\":\"8997b5406dcf18064a4e\",\"dependencies\":[\"wc-interactivity\",\"wp-polyfill\"]},\"assets\\/client\\/blocks\\/wc-interactivity-checkbox-list.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/assets\\/client\\/blocks\\/wc-interactivity-checkbox-list.js\",\"version\":\"9f910c57a575d29e7f8e\",\"dependencies\":[\"wc-interactivity\",\"wp-polyfill\"]}},\"version\":\"wc-9.5.1\",\"hash\":\"3f9a85aab2581e18e668adda54bf178e\"}','off'),(350,'_site_transient_timeout_wp_theme_files_patterns-63e91e49d98a7ea601e2be15a46d7932','1736345962','off'),(351,'_site_transient_wp_theme_files_patterns-63e91e49d98a7ea601e2be15a46d7932','a:2:{s:7:\"version\";b:0;s:8:\"patterns\";a:0:{}}','off'),(352,'woocommerce_maybe_regenerate_images_hash','991b1ca641921cf0f5baf7a2fe85861b','auto'),(353,'wp_1_wc_regenerate_images_batch_cddea0324cf55e72d568d312f37b2500','a:1:{i:0;a:1:{s:13:\"attachment_id\";s:1:\"9\";}}','off'),(354,'_transient_product_query-transient-version','1736344295','on'),(355,'wp_calendar_block_has_published_posts','1','auto'),(356,'_site_transient_update_core','O:8:\"stdClass\":2:{s:12:\"last_checked\";i:1736344163;s:15:\"version_checked\";s:3:\"6.6\";}','off'),(357,'external_updates-event-tickets-plus','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1736344163;s:14:\"checkedVersion\";s:5:\"6.1.2\";s:6:\"update\";N;}','off'),(358,'tribe_pue_key_notices','a:0:{}','auto'),(359,'external_updates-events-calendar-pro','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1736344163;s:14:\"checkedVersion\";s:5:\"7.3.1\";s:6:\"update\";N;}','off'),(360,'stellarwp_uplink_update_status_tec-seating','O:8:\"stdClass\":3:{s:10:\"last_check\";i:1736344163;s:15:\"checked_version\";s:6:\"5.18.0\";s:6:\"update\";O:8:\"stdClass\":0:{}}','auto'),(361,'stellarwp_uplink_update_status_event-tickets-plus','O:8:\"stdClass\":3:{s:10:\"last_check\";i:1736344163;s:15:\"checked_version\";s:5:\"6.1.2\";s:6:\"update\";O:8:\"stdClass\":0:{}}','auto'),(362,'_transient_timeout__woocommerce_helper_subscriptions','1736345063','off'),(363,'_transient__woocommerce_helper_subscriptions','a:0:{}','off'),(364,'_site_transient_timeout_theme_roots','1736345963','off'),(365,'_site_transient_theme_roots','a:6:{s:12:\"twentytwenty\";s:7:\"/themes\";s:16:\"twentytwentyfive\";s:7:\"/themes\";s:16:\"twentytwentyfour\";s:7:\"/themes\";s:15:\"twentytwentyone\";s:7:\"/themes\";s:17:\"twentytwentythree\";s:7:\"/themes\";s:15:\"twentytwentytwo\";s:7:\"/themes\";}','off'),(366,'_site_transient_update_plugins','O:8:\"stdClass\":3:{s:12:\"last_checked\";i:1736344163;s:15:\"version_checked\";s:3:\"6.6\";s:12:\"translations\";a:0:{}}','off'),(367,'_site_transient_update_themes','O:8:\"stdClass\":3:{s:12:\"last_checked\";i:1736344163;s:15:\"version_checked\";s:3:\"6.6\";s:12:\"translations\";a:0:{}}','off'),(368,'theme','twentytwenty','auto'),(369,'wpdb_edd_customers_version','202303220','auto'),(370,'wpdb_edd_customermeta_version','201807111','auto'),(371,'wpdb_edd_customer_addresses_version','202004051','auto'),(372,'wpdb_edd_customer_email_addresses_version','202002141','auto'),(373,'wpdb_edd_adjustments_version','202307311','auto'),(374,'wpdb_edd_adjustmentmeta_version','201806142','auto'),(375,'wpdb_edd_notes_version','202002141','auto'),(376,'wpdb_edd_notemeta_version','201805221','auto'),(377,'wpdb_edd_orders_version','202307111','auto'),(378,'wpdb_edd_ordermeta_version','201805221','auto'),(379,'wpdb_edd_order_items_version','202110141','auto'),(380,'wpdb_edd_order_itemmeta_version','201805221','auto'),(381,'wpdb_edd_order_adjustments_version','202105221','auto'),(382,'wpdb_edd_order_adjustmentmeta_version','201805221','auto'),(383,'wpdb_edd_order_addresses_version','202002141','auto'),(384,'wpdb_edd_order_transactions_version','202205241','auto'),(385,'wpdb_edd_logs_version','202002141','auto'),(386,'wpdb_edd_logmeta_version','201805221','auto'),(387,'wpdb_edd_logs_api_requests_version','202002141','auto'),(388,'wpdb_edd_logs_api_requestmeta_version','201907291','auto'),(389,'wpdb_edd_logs_file_downloads_version','202002141','auto'),(390,'wpdb_edd_logs_file_downloadmeta_version','201907291','auto'),(391,'wpdb_edd_notifications_version','202303220','auto'),(392,'wpdb_edd_logs_emails_version','202311100','auto'),(393,'wpdb_edd_logs_emailmeta_version','202311100','auto'),(394,'wpdb_edd_sessions_version','202311090','auto'),(461,'_transient__tec_power_automate_queue_attendees','a:15:{i:0;i:5613;i:1;i:5607;i:2;i:5601;i:3;i:5593;i:4;i:5589;i:5;i:5583;i:6;i:5582;i:7;i:5581;i:8;i:5576;i:9;i:5573;i:10;i:5572;i:11;i:5567;i:12;i:5566;i:13;i:5565;i:14;i:5558;}','on'),(462,'_transient__tec_zapier_queue_new_events','a:15:{i:0;i:5641;i:1;i:5637;i:2;i:5634;i:3;i:5631;i:4;i:5620;i:5;i:5617;i:6;i:5604;i:7;i:5598;i:8;i:5591;i:9;i:5587;i:10;i:5569;i:11;i:5562;i:12;i:5553;i:13;i:5549;i:14;i:5540;}','on'),(463,'_transient__tec_power_automate_queue_new_events','a:15:{i:0;i:5645;i:1;i:5641;i:2;i:5637;i:3;i:5634;i:4;i:5631;i:5;i:5620;i:6;i:5617;i:7;i:5610;i:8;i:5604;i:9;i:5598;i:10;i:5591;i:11;i:5587;i:12;i:5578;i:13;i:5569;i:14;i:5562;}','on'),(464,'_transient_tribe_ticket_prefix_pool','a:78:{s:9:\"TESTEVENT\";i:5097;s:3:\"TE2\";i:5100;s:3:\"TE3\";i:5103;s:3:\"TE4\";i:5118;s:3:\"TE5\";i:5121;s:3:\"TE6\";i:5126;s:3:\"TE7\";i:5130;s:3:\"TE8\";i:5138;s:3:\"TE9\";i:5151;s:4:\"TE10\";i:5154;s:4:\"TE11\";i:5169;s:4:\"TE12\";i:5172;s:4:\"TE13\";i:5177;s:4:\"TE14\";i:5181;s:4:\"TE15\";i:5189;s:4:\"TE16\";i:5193;s:4:\"TE17\";i:5206;s:4:\"TE18\";i:5210;s:4:\"TE19\";i:5219;s:4:\"TE20\";i:5230;s:4:\"TE21\";i:5241;s:4:\"TE22\";i:5247;s:4:\"TE23\";i:5251;s:4:\"TE24\";i:5258;s:4:\"TE25\";i:5266;s:4:\"TE26\";i:5272;s:4:\"TE27\";i:5281;s:4:\"TE28\";i:5284;s:4:\"TE29\";i:5295;s:4:\"TE30\";i:5298;s:4:\"TE31\";i:5301;s:4:\"TE32\";i:5305;s:4:\"TE33\";i:5309;s:4:\"TE34\";i:5326;s:4:\"TE35\";i:5329;s:4:\"TE36\";i:5332;s:4:\"TE37\";i:5335;s:4:\"TE38\";i:5339;s:4:\"TE39\";i:5343;s:4:\"TE40\";i:5347;s:4:\"TE41\";i:5351;s:4:\"TE45\";i:5379;s:4:\"TE46\";i:5386;s:4:\"TE47\";i:5391;s:4:\"TE48\";i:5398;s:4:\"TE49\";i:5431;s:4:\"TE50\";i:5434;s:4:\"TE51\";i:5437;s:4:\"TE52\";i:5452;s:4:\"TE53\";i:5455;s:4:\"TE54\";i:5460;s:4:\"TE55\";i:5464;s:4:\"TE56\";i:5472;s:4:\"TE57\";i:5485;s:4:\"TE58\";i:5488;s:4:\"TE59\";i:5503;s:4:\"TE60\";i:5506;s:4:\"TE61\";i:5511;s:4:\"TE62\";i:5515;s:4:\"TE63\";i:5523;s:4:\"TE64\";i:5527;s:4:\"TE66\";i:5549;s:4:\"TE67\";i:5553;s:4:\"TE68\";i:5562;s:4:\"TE69\";i:5569;s:4:\"TE70\";i:5578;s:4:\"TE71\";i:5587;s:4:\"TE72\";i:5591;s:4:\"TE73\";i:5598;s:4:\"TE74\";i:5604;s:4:\"TE75\";i:5610;s:4:\"TE76\";i:5617;s:4:\"TE77\";i:5620;s:4:\"TE78\";i:5631;s:4:\"TE79\";i:5634;s:4:\"TE80\";i:5637;s:4:\"TE81\";i:5641;s:4:\"TE82\";i:5645;}','on'),(467,'_transient__tec_zapier_queue_attendees','a:15:{i:0;i:5607;i:1;i:5601;i:2;i:5593;i:3;i:5589;i:4;i:5576;i:5;i:5573;i:6;i:5572;i:7;i:5567;i:8;i:5566;i:9;i:5565;i:10;i:5558;i:11;i:5556;i:12;i:5555;i:13;i:5551;i:14;i:5547;}','on'),(472,'download_category_children','a:0:{}','auto'),(473,'_transient_product-transient-version','1736344295','on'),(474,'_transient_orders-transient-version','1736344295','on'),(475,'_transient_timeout_wc_order_5128_needs_processing','1736430565','off'),(476,'_transient_wc_order_5128_needs_processing','1','off'),(479,'_transient_timeout_wc_order_5132_needs_processing','1736430565','off'),(480,'_transient_wc_order_5132_needs_processing','1','off'),(481,'_transient__tec_zapier_queue_orders','a:4:{i:0;i:5606;i:1;i:5600;i:2;i:99;i:3;i:97;}','on'),(482,'_transient__tec_power_automate_queue_orders','a:15:{i:0;i:5612;i:1;i:5606;i:2;i:5600;i:3;i:99;i:4;i:97;i:5;i:5580;i:6;i:5575;i:7;i:5571;i:8;i:5564;i:9;i:94;i:10;i:93;i:11;i:92;i:12;i:5542;i:13;i:5519;i:14;i:5517;}','on'),(483,'_transient_timeout_wc_order_5134_needs_processing','1736430565','off'),(484,'_transient_wc_order_5134_needs_processing','1','off'),(485,'_transient_timeout_wc_order_5136_needs_processing','1736430565','off'),(486,'_transient_wc_order_5136_needs_processing','1','off'),(487,'_transient_timeout_wc_order_5140_needs_processing','1736430566','off'),(488,'_transient_wc_order_5140_needs_processing','1','off'),(499,'_transient__tec_power_automate_queue_checkin','a:0:{}','on'),(506,'_transient_timeout_wc_order_5179_needs_processing','1736430567','off'),(507,'_transient_wc_order_5179_needs_processing','1','off'),(509,'_transient_timeout_wc_order_5183_needs_processing','1736430567','off'),(510,'_transient_wc_order_5183_needs_processing','1','off'),(511,'_transient_timeout_wc_order_5185_needs_processing','1736430568','off'),(512,'_transient_wc_order_5185_needs_processing','1','off'),(513,'_transient_timeout_wc_order_5187_needs_processing','1736430568','off'),(514,'_transient_wc_order_5187_needs_processing','1','off'),(516,'_transient_timeout_wc_order_5191_needs_processing','1736430568','off'),(517,'_transient_wc_order_5191_needs_processing','1','off'),(525,'edd_earnings_total','944','off'),(539,'_transient_shipping-transient-version','1736344184','on'),(540,'_transient_timeout_wc_shipping_method_count_legacy','1738936184','off'),(541,'_transient_wc_shipping_method_count_legacy','a:2:{s:7:\"version\";s:10:\"1736344184\";s:5:\"value\";i:0;}','off'),(544,'_transient_timeout_wc_order_5232_needs_processing','1736430585','off'),(545,'_transient_wc_order_5232_needs_processing','1','off'),(554,'_transient__tec_power_automate_queue_refunded_orders','a:0:{}','on'),(577,'_transient__tec_power_automate_queue_updated_attendees','a:0:{}','on'),(581,'_transient_timeout_wc_order_5303_needs_processing','1736430615','off'),(582,'_transient_wc_order_5303_needs_processing','1','off'),(583,'_transient_timeout_wc_order_5307_needs_processing','1736430615','off'),(584,'_transient_wc_order_5307_needs_processing','1','off'),(586,'_transient_timeout_wc_order_5311_needs_processing','1736430616','off'),(587,'_transient_wc_order_5311_needs_processing','1','off'),(598,'_transient_timeout_wc_order_5337_needs_processing','1736430617','off'),(599,'_transient_wc_order_5337_needs_processing','1','off'),(689,'_transient_timeout_wc_order_5462_needs_processing','1736430640','off'),(690,'_transient_wc_order_5462_needs_processing','1','off'),(691,'_transient_timeout_wc_order_5466_needs_processing','1736430641','off'),(692,'_transient_wc_order_5466_needs_processing','1','off'),(693,'_transient_timeout_wc_order_5468_needs_processing','1736430641','off'),(694,'_transient_wc_order_5468_needs_processing','1','off'),(695,'_transient_timeout_wc_order_5470_needs_processing','1736430641','off'),(696,'_transient_wc_order_5470_needs_processing','1','off'),(697,'_transient_timeout_wc_order_5474_needs_processing','1736430641','off'),(698,'_transient_wc_order_5474_needs_processing','1','off'),(709,'_transient__tec_zapier_queue_checkin','a:0:{}','on'),(710,'_transient_timeout_wc_order_5513_needs_processing','1736430643','off'),(711,'_transient_wc_order_5513_needs_processing','1','off'),(712,'_transient_timeout_wc_order_5517_needs_processing','1736430643','off'),(713,'_transient_wc_order_5517_needs_processing','1','off'),(714,'_transient_timeout_wc_order_5519_needs_processing','1736430644','off'),(715,'_transient_wc_order_5519_needs_processing','1','off'),(716,'_transient_timeout_wc_order_5521_needs_processing','1736430644','off'),(717,'_transient_wc_order_5521_needs_processing','1','off'),(718,'_transient_timeout_wc_order_5525_needs_processing','1736430644','off'),(719,'_transient_wc_order_5525_needs_processing','1','off'),(720,'_transient_timeout_wc_order_5529_needs_processing','1736430644','off'),(721,'_transient_wc_order_5529_needs_processing','1','off'),(738,'_transient_timeout_wc_order_5571_needs_processing','1736430664','off'),(739,'_transient_wc_order_5571_needs_processing','1','off'),(748,'_transient__tec_zapier_queue_refunded_orders','a:0:{}','on'),(751,'_transient_timeout_edd_earnings_total','1736430682','off'),(752,'_transient_edd_earnings_total','944','off'),(766,'_transient__tec_zapier_queue_updated_attendees','a:0:{}','on'),(767,'_transient_timeout_wc_order_5639_needs_processing','1736430694','off'),(768,'_transient_wc_order_5639_needs_processing','1','off'),(769,'_transient_timeout_wc_order_5643_needs_processing','1736430694','off'),(770,'_transient_wc_order_5643_needs_processing','1','off');
/*!40000 ALTER TABLE `wptests_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_postmeta`
--

DROP TABLE IF EXISTS `wptests_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=9460 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_postmeta`
--

LOCK TABLES `wptests_postmeta` WRITE;
/*!40000 ALTER TABLE `wptests_postmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_posts`
--

DROP TABLE IF EXISTS `wptests_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT 0,
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(255) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT 0,
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=5661 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_posts`
--

LOCK TABLES `wptests_posts` WRITE;
/*!40000 ALTER TABLE `wptests_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_events`
--

DROP TABLE IF EXISTS `wptests_tec_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_events` (
  `event_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `start_date` varchar(19) NOT NULL,
  `end_date` varchar(19) DEFAULT NULL,
  `timezone` varchar(30) NOT NULL DEFAULT 'UTC',
  `start_date_utc` varchar(19) NOT NULL,
  `end_date_utc` varchar(19) DEFAULT NULL,
  `duration` mediumint(30) DEFAULT 7200,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hash` varchar(40) NOT NULL,
  `rset` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_events`
--

LOCK TABLES `wptests_tec_events` WRITE;
/*!40000 ALTER TABLE `wptests_tec_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_occurrences`
--

DROP TABLE IF EXISTS `wptests_tec_occurrences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_occurrences` (
  `occurrence_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `start_date` varchar(19) NOT NULL,
  `start_date_utc` varchar(19) NOT NULL,
  `end_date` varchar(19) NOT NULL,
  `end_date_utc` varchar(19) NOT NULL,
  `duration` mediumint(30) DEFAULT 7200,
  `hash` varchar(40) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `has_recurrence` tinyint(1) DEFAULT 0,
  `sequence` bigint(20) unsigned DEFAULT 0,
  `is_rdate` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`occurrence_id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_occurrences`
--

LOCK TABLES `wptests_tec_occurrences` WRITE;
/*!40000 ALTER TABLE `wptests_tec_occurrences` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_occurrences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_order_modifier_relationships`
--

DROP TABLE IF EXISTS `wptests_tec_order_modifier_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_order_modifier_relationships` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modifier_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `post_type` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tec_order_modifier_relationship_index_modifier_id` (`modifier_id`),
  KEY `tec_order_modifier_relationship_index_post_id` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_order_modifier_relationships`
--

LOCK TABLES `wptests_tec_order_modifier_relationships` WRITE;
/*!40000 ALTER TABLE `wptests_tec_order_modifier_relationships` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_order_modifier_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_order_modifiers`
--

DROP TABLE IF EXISTS `wptests_tec_order_modifiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_order_modifiers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `modifier_type` varchar(150) NOT NULL,
  `sub_type` varchar(255) NOT NULL,
  `raw_amount` decimal(18,6) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tec_order_modifier_index_slug` (`slug`),
  KEY `tec_order_modifier_index_modifier_type` (`modifier_type`),
  KEY `tec_order_modifier_index_status_modifier_type` (`status`,`modifier_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_order_modifiers`
--

LOCK TABLES `wptests_tec_order_modifiers` WRITE;
/*!40000 ALTER TABLE `wptests_tec_order_modifiers` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_order_modifiers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_order_modifiers_meta`
--

DROP TABLE IF EXISTS `wptests_tec_order_modifiers_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_order_modifiers_meta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_modifier_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(100) NOT NULL,
  `meta_value` text NOT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `tec_order_modifier_meta_index_order_modifier_id` (`order_modifier_id`),
  KEY `tec_order_modifier_meta_index_meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_order_modifiers_meta`
--

LOCK TABLES `wptests_tec_order_modifiers_meta` WRITE;
/*!40000 ALTER TABLE `wptests_tec_order_modifiers_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_order_modifiers_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_posts_and_ticket_groups`
--

DROP TABLE IF EXISTS `wptests_tec_posts_and_ticket_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_posts_and_ticket_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_posts_and_ticket_groups`
--

LOCK TABLES `wptests_tec_posts_and_ticket_groups` WRITE;
/*!40000 ALTER TABLE `wptests_tec_posts_and_ticket_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_posts_and_ticket_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_series_relationships`
--

DROP TABLE IF EXISTS `wptests_tec_series_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_series_relationships` (
  `relationship_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `series_post_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `event_post_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `series_post_id` (`series_post_id`),
  KEY `event_post_id` (`event_post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_series_relationships`
--

LOCK TABLES `wptests_tec_series_relationships` WRITE;
/*!40000 ALTER TABLE `wptests_tec_series_relationships` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_series_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_slr_layouts`
--

DROP TABLE IF EXISTS `wptests_tec_slr_layouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_slr_layouts` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `map` varchar(36) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  `screenshot_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_slr_layouts`
--

LOCK TABLES `wptests_tec_slr_layouts` WRITE;
/*!40000 ALTER TABLE `wptests_tec_slr_layouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_slr_layouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_slr_maps`
--

DROP TABLE IF EXISTS `wptests_tec_slr_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_slr_maps` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  `screenshot_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_slr_maps`
--

LOCK TABLES `wptests_tec_slr_maps` WRITE;
/*!40000 ALTER TABLE `wptests_tec_slr_maps` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_slr_maps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_slr_seat_types`
--

DROP TABLE IF EXISTS `wptests_tec_slr_seat_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_slr_seat_types` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `map` varchar(36) NOT NULL,
  `layout` varchar(36) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_slr_seat_types`
--

LOCK TABLES `wptests_tec_slr_seat_types` WRITE;
/*!40000 ALTER TABLE `wptests_tec_slr_seat_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_slr_seat_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_slr_sessions`
--

DROP TABLE IF EXISTS `wptests_tec_slr_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_slr_sessions` (
  `token` varchar(150) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `expiration` int(11) NOT NULL,
  `reservations` longblob DEFAULT NULL,
  `expiration_lock` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_slr_sessions`
--

LOCK TABLES `wptests_tec_slr_sessions` WRITE;
/*!40000 ALTER TABLE `wptests_tec_slr_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_slr_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_tec_ticket_groups`
--

DROP TABLE IF EXISTS `wptests_tec_ticket_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_tec_ticket_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_tec_ticket_groups`
--

LOCK TABLES `wptests_tec_ticket_groups` WRITE;
/*!40000 ALTER TABLE `wptests_tec_ticket_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_tec_ticket_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_term_relationships`
--

DROP TABLE IF EXISTS `wptests_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `term_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_term_relationships`
--

LOCK TABLES `wptests_term_relationships` WRITE;
/*!40000 ALTER TABLE `wptests_term_relationships` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_term_taxonomy`
--

DROP TABLE IF EXISTS `wptests_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT 0,
  `count` bigint(20) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_term_taxonomy`
--

LOCK TABLES `wptests_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wptests_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wptests_term_taxonomy` VALUES (1,1,'category','',0,0);
/*!40000 ALTER TABLE `wptests_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_termmeta`
--

DROP TABLE IF EXISTS `wptests_termmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_termmeta`
--

LOCK TABLES `wptests_termmeta` WRITE;
/*!40000 ALTER TABLE `wptests_termmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_termmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_terms`
--

DROP TABLE IF EXISTS `wptests_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_terms`
--

LOCK TABLES `wptests_terms` WRITE;
/*!40000 ALTER TABLE `wptests_terms` DISABLE KEYS */;
INSERT INTO `wptests_terms` VALUES (1,'Uncategorized','uncategorized',0);
/*!40000 ALTER TABLE `wptests_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_usermeta`
--

DROP TABLE IF EXISTS `wptests_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_usermeta`
--

LOCK TABLES `wptests_usermeta` WRITE;
/*!40000 ALTER TABLE `wptests_usermeta` DISABLE KEYS */;
INSERT INTO `wptests_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'syntax_highlighting','true'),(7,1,'comment_shortcuts','false'),(8,1,'admin_color','fresh'),(9,1,'use_ssl','0'),(10,1,'show_admin_bar_front','true'),(11,1,'locale',''),(12,1,'wptests_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(13,1,'wptests_user_level','10'),(14,1,'dismissed_wp_pointers',''),(15,1,'show_welcome_panel','1'),(19,1,'_woocommerce_persistent_cart_1','a:1:{s:4:\"cart\";a:1:{s:32:\"9704a4fc48ae88598dcbdcdf57f3fdef\";a:6:{s:3:\"key\";s:32:\"9704a4fc48ae88598dcbdcdf57f3fdef\";s:10:\"product_id\";i:5399;s:12:\"variation_id\";i:0;s:9:\"variation\";a:0:{}s:8:\"quantity\";i:2;s:9:\"data_hash\";s:32:\"b5c1d5ca8bae6d4896cf1807cdf763f0\";}}}');
/*!40000 ALTER TABLE `wptests_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_users`
--

DROP TABLE IF EXISTS `wptests_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(255) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT 0,
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_users`
--

LOCK TABLES `wptests_users` WRITE;
/*!40000 ALTER TABLE `wptests_users` DISABLE KEYS */;
INSERT INTO `wptests_users` VALUES (1,'admin','$P$B6OuxvuSFzDiVpGjMb5j4roAOdBBsR1','admin','admin@wordpress.test','http://wordpress.test','2025-01-08 13:49:14','',0,'admin');
/*!40000 ALTER TABLE `wptests_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_admin_note_actions`
--

DROP TABLE IF EXISTS `wptests_wc_admin_note_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_admin_note_actions` (
  `action_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `note_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `query` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `actioned_text` varchar(255) NOT NULL,
  `nonce_action` varchar(255) DEFAULT NULL,
  `nonce_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`action_id`),
  KEY `note_id` (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_admin_note_actions`
--

LOCK TABLES `wptests_wc_admin_note_actions` WRITE;
/*!40000 ALTER TABLE `wptests_wc_admin_note_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_admin_note_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_admin_notes`
--

DROP TABLE IF EXISTS `wptests_wc_admin_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_admin_notes` (
  `note_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `locale` varchar(20) NOT NULL,
  `title` longtext NOT NULL,
  `content` longtext NOT NULL,
  `content_data` longtext DEFAULT NULL,
  `status` varchar(200) NOT NULL,
  `source` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_reminder` datetime DEFAULT NULL,
  `is_snoozable` tinyint(1) NOT NULL DEFAULT 0,
  `layout` varchar(20) NOT NULL DEFAULT '',
  `image` varchar(200) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `icon` varchar(200) NOT NULL DEFAULT 'info',
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_admin_notes`
--

LOCK TABLES `wptests_wc_admin_notes` WRITE;
/*!40000 ALTER TABLE `wptests_wc_admin_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_admin_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_category_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_category_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_category_lookup` (
  `category_tree_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`category_tree_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_category_lookup`
--

LOCK TABLES `wptests_wc_category_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_category_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_category_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_customer_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_customer_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_customer_lookup` (
  `customer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(60) NOT NULL DEFAULT '',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_last_active` timestamp NULL DEFAULT NULL,
  `date_registered` timestamp NULL DEFAULT NULL,
  `country` char(2) NOT NULL DEFAULT '',
  `postcode` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_customer_lookup`
--

LOCK TABLES `wptests_wc_customer_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_customer_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_customer_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_download_log`
--

DROP TABLE IF EXISTS `wptests_wc_download_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_download_log` (
  `download_log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `user_ip_address` varchar(100) DEFAULT '',
  PRIMARY KEY (`download_log_id`),
  KEY `permission_id` (`permission_id`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_download_log`
--

LOCK TABLES `wptests_wc_download_log` WRITE;
/*!40000 ALTER TABLE `wptests_wc_download_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_download_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_order_coupon_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_order_coupon_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_order_coupon_lookup` (
  `order_id` bigint(20) unsigned NOT NULL,
  `coupon_id` bigint(20) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discount_amount` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_id`,`coupon_id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_order_coupon_lookup`
--

LOCK TABLES `wptests_wc_order_coupon_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_order_coupon_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_order_coupon_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_order_product_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_order_product_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_order_product_lookup` (
  `order_item_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `variation_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `product_qty` int(11) NOT NULL,
  `product_net_revenue` double NOT NULL DEFAULT 0,
  `product_gross_revenue` double NOT NULL DEFAULT 0,
  `coupon_amount` double NOT NULL DEFAULT 0,
  `tax_amount` double NOT NULL DEFAULT 0,
  `shipping_amount` double NOT NULL DEFAULT 0,
  `shipping_tax_amount` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_order_product_lookup`
--

LOCK TABLES `wptests_wc_order_product_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_order_product_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_order_product_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_order_stats`
--

DROP TABLE IF EXISTS `wptests_wc_order_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_order_stats` (
  `order_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_paid` datetime DEFAULT '0000-00-00 00:00:00',
  `date_completed` datetime DEFAULT '0000-00-00 00:00:00',
  `num_items_sold` int(11) NOT NULL DEFAULT 0,
  `total_sales` double NOT NULL DEFAULT 0,
  `tax_total` double NOT NULL DEFAULT 0,
  `shipping_total` double NOT NULL DEFAULT 0,
  `net_total` double NOT NULL DEFAULT 0,
  `returning_customer` tinyint(1) DEFAULT NULL,
  `status` varchar(200) NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `date_created` (`date_created`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_order_stats`
--

LOCK TABLES `wptests_wc_order_stats` WRITE;
/*!40000 ALTER TABLE `wptests_wc_order_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_order_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_order_tax_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_order_tax_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_order_tax_lookup` (
  `order_id` bigint(20) unsigned NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shipping_tax` double NOT NULL DEFAULT 0,
  `order_tax` double NOT NULL DEFAULT 0,
  `total_tax` double NOT NULL DEFAULT 0,
  PRIMARY KEY (`order_id`,`tax_rate_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `date_created` (`date_created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_order_tax_lookup`
--

LOCK TABLES `wptests_wc_order_tax_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_order_tax_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_order_tax_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_product_attributes_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_product_attributes_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_product_attributes_lookup` (
  `product_id` bigint(20) NOT NULL,
  `product_or_parent_id` bigint(20) NOT NULL,
  `taxonomy` varchar(32) NOT NULL,
  `term_id` bigint(20) NOT NULL,
  `is_variation_attribute` tinyint(1) NOT NULL,
  `in_stock` tinyint(1) NOT NULL,
  PRIMARY KEY (`product_or_parent_id`,`term_id`,`product_id`,`taxonomy`),
  KEY `is_variation_attribute_term_id` (`is_variation_attribute`,`term_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_product_attributes_lookup`
--

LOCK TABLES `wptests_wc_product_attributes_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_product_attributes_lookup` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_product_attributes_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_product_download_directories`
--

DROP TABLE IF EXISTS `wptests_wc_product_download_directories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_product_download_directories` (
  `url_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`url_id`),
  KEY `url` (`url`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_product_download_directories`
--

LOCK TABLES `wptests_wc_product_download_directories` WRITE;
/*!40000 ALTER TABLE `wptests_wc_product_download_directories` DISABLE KEYS */;
INSERT INTO `wptests_wc_product_download_directories` VALUES (1,'file:///var/www/html/wp-content/uploads/woocommerce_uploads/',1),(2,'http://wordpress.test/wp-content/uploads/woocommerce_uploads/',1);
/*!40000 ALTER TABLE `wptests_wc_product_download_directories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_product_meta_lookup`
--

DROP TABLE IF EXISTS `wptests_wc_product_meta_lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_product_meta_lookup` (
  `product_id` bigint(20) NOT NULL,
  `sku` varchar(100) DEFAULT '',
  `global_unique_id` varchar(100) DEFAULT '',
  `virtual` tinyint(1) DEFAULT 0,
  `downloadable` tinyint(1) DEFAULT 0,
  `min_price` decimal(19,4) DEFAULT NULL,
  `max_price` decimal(19,4) DEFAULT NULL,
  `onsale` tinyint(1) DEFAULT 0,
  `stock_quantity` double DEFAULT NULL,
  `stock_status` varchar(100) DEFAULT 'instock',
  `rating_count` bigint(20) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `total_sales` bigint(20) DEFAULT 0,
  `tax_status` varchar(100) DEFAULT 'taxable',
  `tax_class` varchar(100) DEFAULT '',
  PRIMARY KEY (`product_id`),
  KEY `virtual` (`virtual`),
  KEY `downloadable` (`downloadable`),
  KEY `stock_status` (`stock_status`),
  KEY `stock_quantity` (`stock_quantity`),
  KEY `onsale` (`onsale`),
  KEY `min_max_price` (`min_price`,`max_price`),
  KEY `sku` (`sku`(50))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_product_meta_lookup`
--

LOCK TABLES `wptests_wc_product_meta_lookup` WRITE;
/*!40000 ALTER TABLE `wptests_wc_product_meta_lookup` DISABLE KEYS */;
INSERT INTO `wptests_wc_product_meta_lookup` VALUES (5097,'TEST-TKT-5096','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5102,'TEST-TKT-5101','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5107,'TEST-TKT-5106','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5112,'TEST-TKT-5111','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5127,'TEST-TKT-5126','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,4,'taxable',''),(5131,'TEST-TKT-5130','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,12,'taxable',''),(5139,'TEST-TKT-5138','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,4,'taxable',''),(5178,'TEST-TKT-5177','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,4,'taxable',''),(5182,'TEST-TKT-5181','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,12,'taxable',''),(5190,'TEST-TKT-5189','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,4,'taxable',''),(5220,'TEST-TKT-5219','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5225,'TEST-TKT-5224','',1,0,8.0000,8.0000,0,98,'instock',0,0.00,2,'taxable',''),(5227,'TEST-TKT-5226','',1,0,8.0000,8.0000,0,98,'instock',0,0.00,2,'taxable',''),(5229,'TEST-TKT-5228','',1,0,8.0000,8.0000,0,96,'instock',0,0.00,4,'taxable',''),(5231,'TEST-TKT-5230','',1,0,8.0000,8.0000,0,96,'instock',0,0.00,4,'taxable',''),(5235,'TEST-TKT-5228','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5237,'TEST-TKT-5230','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5251,'TEST-TKT-5250','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5253,'TEST-TKT-5252','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5257,'TEST-TKT-5256','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5259,'TEST-TKT-5258','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5263,'TEST-TKT-5262','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5265,'TEST-TKT-5264','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5267,'TEST-TKT-5266','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5273,'TEST-TKT-5272','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5290,'TEST-TKT-5289','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5292,'TEST-TKT-5291','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5294,'TEST-TKT-5293','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5296,'TEST-TKT-5295','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5298,'TEST-TKT-5297','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5300,'TEST-TKT-5299','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,2,'taxable',''),(5302,'TEST-TKT-5301','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5304,'TEST-TKT-5303','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5306,'TEST-TKT-5305','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5308,'TEST-TKT-5307','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5310,'TEST-TKT-5309','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5324,'TEST-TKT-5323','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5326,'TEST-TKT-5325','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5334,'TEST-TKT-5333','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5336,'TEST-TKT-5335','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5368,'TEST-TKT-5367','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5370,'TEST-TKT-5369','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5373,'TEST-TKT-5372','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5375,'TEST-TKT-5374','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5378,'TEST-TKT-5377','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5380,'TEST-TKT-5379','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5383,'TEST-TKT-5382','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5385,'TEST-TKT-5384','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5387,'TEST-TKT-5386','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5392,'TEST-TKT-5391','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5397,'TEST-TKT-5396','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5399,'TEST-TKT-5398','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5445,'TEST-TKT-5444','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5447,'TEST-TKT-5446','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5449,'TEST-TKT-5448','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5451,'TEST-TKT-5450','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5457,'TEST-TKT-5456','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5459,'TEST-TKT-5458','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,2,'taxable',''),(5461,'TEST-TKT-5460','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5463,'TEST-TKT-5462','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5465,'TEST-TKT-5464','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5471,'TEST-TKT-5470','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5473,'TEST-TKT-5472','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5496,'TEST-TKT-5495','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5498,'TEST-TKT-5497','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5500,'TEST-TKT-5499','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5502,'TEST-TKT-5501','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5508,'TEST-TKT-5507','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5510,'TEST-TKT-5509','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,2,'taxable',''),(5512,'TEST-TKT-5511','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,2,'taxable',''),(5514,'TEST-TKT-5513','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,4,'taxable',''),(5516,'TEST-TKT-5515','',1,0,11.0000,11.0000,0,97,'instock',0,0.00,3,'taxable',''),(5522,'TEST-TKT-5521','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5524,'TEST-TKT-5523','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5526,'TEST-TKT-5525','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5528,'TEST-TKT-5527','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5547,'TEST-TKT-5546','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5549,'TEST-TKT-5548','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5552,'TEST-TKT-5551','',1,0,8.0000,8.0000,0,98,'instock',0,0.00,2,'taxable',''),(5554,'TEST-TKT-5553','',1,0,8.0000,8.0000,0,98,'instock',0,0.00,2,'taxable',''),(5556,'TEST-TKT-5551','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5558,'TEST-TKT-5553','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5561,'TEST-TKT-5560','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5563,'TEST-TKT-5562','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5568,'TEST-TKT-5567','',1,0,8.0000,8.0000,0,98,'instock',0,0.00,2,'taxable',''),(5570,'TEST-TKT-5569','',1,0,8.0000,8.0000,0,98,'instock',0,0.00,2,'taxable',''),(5572,'TEST-TKT-5567','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5574,'TEST-TKT-5569','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5577,'TEST-TKT-5576','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5579,'TEST-TKT-5578','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5581,'TEST-TKT-5580','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5585,'TEST-TKT-5584','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5587,'TEST-TKT-5586','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5591,'TEST-TKT-5590','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5593,'TEST-TKT-5592','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5595,'TEST-TKT-5594','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5599,'TEST-TKT-5598','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5601,'TEST-TKT-5600','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5605,'TEST-TKT-5604','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5607,'TEST-TKT-5606','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5611,'TEST-TKT-5610','',1,0,8.0000,8.0000,0,NULL,'instock',0,0.00,0,'taxable',''),(5618,'TEST-TKT-5617','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5620,'TEST-TKT-5619','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5622,'TEST-TKT-5621','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5624,'TEST-TKT-5623','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5634,'TEST-TKT-5633','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable',''),(5638,'TEST-TKT-5637','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,2,'taxable',''),(5642,'TEST-TKT-5641','',1,0,9.0000,9.0000,0,99,'instock',0,0.00,1,'taxable','');
/*!40000 ALTER TABLE `wptests_wc_product_meta_lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_rate_limits`
--

DROP TABLE IF EXISTS `wptests_wc_rate_limits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_rate_limits` (
  `rate_limit_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `rate_limit_key` varchar(200) NOT NULL,
  `rate_limit_expiry` bigint(20) unsigned NOT NULL,
  `rate_limit_remaining` smallint(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rate_limit_id`),
  UNIQUE KEY `rate_limit_key` (`rate_limit_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_rate_limits`
--

LOCK TABLES `wptests_wc_rate_limits` WRITE;
/*!40000 ALTER TABLE `wptests_wc_rate_limits` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_rate_limits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_reserved_stock`
--

DROP TABLE IF EXISTS `wptests_wc_reserved_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_reserved_stock` (
  `order_id` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `stock_quantity` double NOT NULL DEFAULT 0,
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expires` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_reserved_stock`
--

LOCK TABLES `wptests_wc_reserved_stock` WRITE;
/*!40000 ALTER TABLE `wptests_wc_reserved_stock` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_reserved_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_tax_rate_classes`
--

DROP TABLE IF EXISTS `wptests_wc_tax_rate_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_tax_rate_classes` (
  `tax_rate_class_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_class_id`),
  UNIQUE KEY `slug` (`slug`(191))
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_tax_rate_classes`
--

LOCK TABLES `wptests_wc_tax_rate_classes` WRITE;
/*!40000 ALTER TABLE `wptests_wc_tax_rate_classes` DISABLE KEYS */;
INSERT INTO `wptests_wc_tax_rate_classes` VALUES (1,'Reduced rate','reduced-rate'),(2,'Zero rate','zero-rate');
/*!40000 ALTER TABLE `wptests_wc_tax_rate_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_wc_webhooks`
--

DROP TABLE IF EXISTS `wptests_wc_webhooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_wc_webhooks` (
  `webhook_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(200) NOT NULL,
  `name` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `delivery_url` text NOT NULL,
  `secret` text NOT NULL,
  `topic` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `api_version` smallint(4) NOT NULL,
  `failure_count` smallint(10) NOT NULL DEFAULT 0,
  `pending_delivery` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`webhook_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_wc_webhooks`
--

LOCK TABLES `wptests_wc_webhooks` WRITE;
/*!40000 ALTER TABLE `wptests_wc_webhooks` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_wc_webhooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_api_keys`
--

DROP TABLE IF EXISTS `wptests_woocommerce_api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_api_keys` (
  `key_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `permissions` varchar(10) NOT NULL,
  `consumer_key` char(64) NOT NULL,
  `consumer_secret` char(43) NOT NULL,
  `nonces` longtext DEFAULT NULL,
  `truncated_key` char(7) NOT NULL,
  `last_access` datetime DEFAULT NULL,
  PRIMARY KEY (`key_id`),
  KEY `consumer_key` (`consumer_key`),
  KEY `consumer_secret` (`consumer_secret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_api_keys`
--

LOCK TABLES `wptests_woocommerce_api_keys` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_api_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_api_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_attribute_taxonomies`
--

DROP TABLE IF EXISTS `wptests_woocommerce_attribute_taxonomies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_attribute_taxonomies` (
  `attribute_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attribute_name` varchar(200) NOT NULL,
  `attribute_label` varchar(200) DEFAULT NULL,
  `attribute_type` varchar(20) NOT NULL,
  `attribute_orderby` varchar(20) NOT NULL,
  `attribute_public` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`attribute_id`),
  KEY `attribute_name` (`attribute_name`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_attribute_taxonomies`
--

LOCK TABLES `wptests_woocommerce_attribute_taxonomies` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_attribute_taxonomies` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_attribute_taxonomies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_downloadable_product_permissions`
--

DROP TABLE IF EXISTS `wptests_woocommerce_downloadable_product_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_downloadable_product_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `download_id` varchar(36) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `order_key` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `downloads_remaining` varchar(9) DEFAULT NULL,
  `access_granted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access_expires` datetime DEFAULT NULL,
  `download_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`permission_id`),
  KEY `download_order_key_product` (`product_id`,`order_id`,`order_key`(16),`download_id`),
  KEY `download_order_product` (`download_id`,`order_id`,`product_id`),
  KEY `order_id` (`order_id`),
  KEY `user_order_remaining_expires` (`user_id`,`order_id`,`downloads_remaining`,`access_expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_downloadable_product_permissions`
--

LOCK TABLES `wptests_woocommerce_downloadable_product_permissions` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_downloadable_product_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_downloadable_product_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_log`
--

DROP TABLE IF EXISTS `wptests_woocommerce_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_log` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `level` smallint(4) NOT NULL,
  `source` varchar(200) NOT NULL,
  `message` longtext NOT NULL,
  `context` longtext DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `level` (`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_log`
--

LOCK TABLES `wptests_woocommerce_log` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_order_itemmeta`
--

DROP TABLE IF EXISTS `wptests_woocommerce_order_itemmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_order_itemmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB AUTO_INCREMENT=2393 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_order_itemmeta`
--

LOCK TABLES `wptests_woocommerce_order_itemmeta` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_order_itemmeta` DISABLE KEYS */;
INSERT INTO `wptests_woocommerce_order_itemmeta` VALUES (1,1,'_product_id','5127'),(2,1,'_variation_id','0'),(3,1,'_qty','1'),(4,1,'_tax_class',''),(5,1,'_line_subtotal','0'),(6,1,'_line_subtotal_tax','0'),(7,1,'_line_total','0'),(8,1,'_line_tax','0'),(9,1,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(10,1,'_reduced_stock','1'),(11,2,'_product_id','5131'),(12,2,'_variation_id','0'),(13,2,'_qty','1'),(14,2,'_tax_class',''),(15,2,'_line_subtotal','0'),(16,2,'_line_subtotal_tax','0'),(17,2,'_line_total','0'),(18,2,'_line_tax','0'),(19,2,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(20,2,'_reduced_stock','1'),(21,3,'_product_id','5131'),(22,3,'_variation_id','0'),(23,3,'_qty','1'),(24,3,'_tax_class',''),(25,3,'_line_subtotal','0'),(26,3,'_line_subtotal_tax','0'),(27,3,'_line_total','0'),(28,3,'_line_tax','0'),(29,3,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(30,3,'_reduced_stock','1'),(31,4,'_product_id','5131'),(32,4,'_variation_id','0'),(33,4,'_qty','1'),(34,4,'_tax_class',''),(35,4,'_line_subtotal','0'),(36,4,'_line_subtotal_tax','0'),(37,4,'_line_total','0'),(38,4,'_line_tax','0'),(39,4,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(40,4,'_reduced_stock','1'),(41,5,'_product_id','5139'),(42,5,'_variation_id','0'),(43,5,'_qty','1'),(44,5,'_tax_class',''),(45,5,'_line_subtotal','0'),(46,5,'_line_subtotal_tax','0'),(47,5,'_line_total','0'),(48,5,'_line_tax','0'),(49,5,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(50,5,'_reduced_stock','1'),(51,6,'_product_id','5178'),(52,6,'_variation_id','0'),(53,6,'_qty','1'),(54,6,'_tax_class',''),(55,6,'_line_subtotal','0'),(56,6,'_line_subtotal_tax','0'),(57,6,'_line_total','0'),(58,6,'_line_tax','0'),(59,6,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(60,6,'_reduced_stock','1'),(61,7,'_product_id','5182'),(62,7,'_variation_id','0'),(63,7,'_qty','1'),(64,7,'_tax_class',''),(65,7,'_line_subtotal','0'),(66,7,'_line_subtotal_tax','0'),(67,7,'_line_total','0'),(68,7,'_line_tax','0'),(69,7,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(70,7,'_reduced_stock','1'),(71,8,'_product_id','5182'),(72,8,'_variation_id','0'),(73,8,'_qty','1'),(74,8,'_tax_class',''),(75,8,'_line_subtotal','0'),(76,8,'_line_subtotal_tax','0'),(77,8,'_line_total','0'),(78,8,'_line_tax','0'),(79,8,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(80,8,'_reduced_stock','1'),(81,9,'_product_id','5182'),(82,9,'_variation_id','0'),(83,9,'_qty','1'),(84,9,'_tax_class',''),(85,9,'_line_subtotal','0'),(86,9,'_line_subtotal_tax','0'),(87,9,'_line_total','0'),(88,9,'_line_tax','0'),(89,9,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(90,9,'_reduced_stock','1'),(91,10,'_product_id','5190'),(92,10,'_variation_id','0'),(93,10,'_qty','1'),(94,10,'_tax_class',''),(95,10,'_line_subtotal','0'),(96,10,'_line_subtotal_tax','0'),(97,10,'_line_total','0'),(98,10,'_line_tax','0'),(99,10,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(100,10,'_reduced_stock','1'),(110,12,'_product_id','5220'),(111,12,'_variation_id','0'),(112,12,'_qty','2'),(113,12,'_tax_class',''),(114,12,'_line_subtotal','16'),(115,12,'_line_subtotal_tax','0'),(116,12,'_line_total','16'),(117,12,'_line_tax','0'),(118,12,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(120,13,'_product_id','5225'),(121,13,'_variation_id','0'),(122,13,'_qty','2'),(123,13,'_tax_class',''),(124,13,'_line_subtotal','16'),(125,13,'_line_subtotal_tax','0'),(126,13,'_line_total','16'),(127,13,'_line_tax','0'),(128,13,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(129,13,'_reduced_stock','2'),(130,14,'_product_id','5229'),(131,14,'_variation_id','0'),(132,14,'_qty','2'),(133,14,'_tax_class',''),(134,14,'_line_subtotal','16'),(135,14,'_line_subtotal_tax','0'),(136,14,'_line_total','16'),(137,14,'_line_tax','0'),(138,14,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(159,17,'_product_id','5251'),(160,17,'_variation_id','0'),(161,17,'_qty','2'),(162,17,'_tax_class',''),(163,17,'_line_subtotal','16'),(164,17,'_line_subtotal_tax','0'),(165,17,'_line_total','16'),(166,17,'_line_tax','0'),(167,17,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(180,19,'_product_id','5257'),(181,19,'_variation_id','0'),(182,19,'_qty','2'),(183,19,'_tax_class',''),(184,19,'_line_subtotal','16'),(185,19,'_line_subtotal_tax','0'),(186,19,'_line_total','16'),(187,19,'_line_tax','0'),(188,19,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(201,21,'_product_id','5263'),(202,21,'_variation_id','0'),(203,21,'_qty','2'),(204,21,'_tax_class',''),(205,21,'_line_subtotal','16'),(206,21,'_line_subtotal_tax','0'),(207,21,'_line_total','16'),(208,21,'_line_tax','0'),(209,21,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(222,23,'_product_id','5290'),(223,23,'_variation_id','0'),(224,23,'_qty','1'),(225,23,'_tax_class',''),(226,23,'_line_subtotal','0'),(227,23,'_line_subtotal_tax','0'),(228,23,'_line_total','0'),(229,23,'_line_tax','0'),(230,23,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(231,23,'_reduced_stock','1'),(232,24,'_product_id','5294'),(233,24,'_variation_id','0'),(234,24,'_qty','1'),(235,24,'_tax_class',''),(236,24,'_line_subtotal','0'),(237,24,'_line_subtotal_tax','0'),(238,24,'_line_total','0'),(239,24,'_line_tax','0'),(240,24,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(241,24,'_reduced_stock','1'),(242,25,'_product_id','5298'),(243,25,'_variation_id','0'),(244,25,'_qty','1'),(245,25,'_tax_class',''),(246,25,'_line_subtotal','0'),(247,25,'_line_subtotal_tax','0'),(248,25,'_line_total','0'),(249,25,'_line_tax','0'),(250,25,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(251,25,'_reduced_stock','1'),(252,26,'_product_id','5324'),(253,26,'_variation_id','0'),(254,26,'_qty','1'),(255,26,'_tax_class',''),(256,26,'_line_subtotal','0'),(257,26,'_line_subtotal_tax','0'),(258,26,'_line_total','0'),(259,26,'_line_tax','0'),(260,26,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(261,26,'_reduced_stock','1'),(262,27,'_product_id','5368'),(263,27,'_variation_id','0'),(264,27,'_qty','2'),(265,27,'_tax_class',''),(266,27,'_line_subtotal','16'),(267,27,'_line_subtotal_tax','0'),(268,27,'_line_total','16'),(269,27,'_line_tax','0'),(270,27,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(272,28,'_product_id','5373'),(273,28,'_variation_id','0'),(274,28,'_qty','2'),(275,28,'_tax_class',''),(276,28,'_line_subtotal','16'),(277,28,'_line_subtotal_tax','0'),(278,28,'_line_total','16'),(279,28,'_line_tax','0'),(280,28,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(282,29,'_product_id','5378'),(283,29,'_variation_id','0'),(284,29,'_qty','2'),(285,29,'_tax_class',''),(286,29,'_line_subtotal','16'),(287,29,'_line_subtotal_tax','0'),(288,29,'_line_total','16'),(289,29,'_line_tax','0'),(290,29,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(292,30,'_product_id','5383'),(293,30,'_variation_id','0'),(294,30,'_qty','2'),(295,30,'_tax_class',''),(296,30,'_line_subtotal','16'),(297,30,'_line_subtotal_tax','0'),(298,30,'_line_total','16'),(299,30,'_line_tax','0'),(300,30,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(302,31,'_product_id','5445'),(303,31,'_variation_id','0'),(304,31,'_qty','1'),(305,31,'_tax_class',''),(306,31,'_line_subtotal','0'),(307,31,'_line_subtotal_tax','0'),(308,31,'_line_total','0'),(309,31,'_line_tax','0'),(310,31,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(311,31,'_reduced_stock','1'),(312,32,'_product_id','5449'),(313,32,'_variation_id','0'),(314,32,'_qty','1'),(315,32,'_tax_class',''),(316,32,'_line_subtotal','0'),(317,32,'_line_subtotal_tax','0'),(318,32,'_line_total','0'),(319,32,'_line_tax','0'),(320,32,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(321,32,'_reduced_stock','1'),(322,33,'_product_id','5449'),(323,33,'_variation_id','0'),(324,33,'_qty','1'),(325,33,'_tax_class',''),(326,33,'_line_subtotal','0'),(327,33,'_line_subtotal_tax','0'),(328,33,'_line_total','0'),(329,33,'_line_tax','0'),(330,33,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(331,33,'_reduced_stock','1'),(332,34,'_product_id','5449'),(333,34,'_variation_id','0'),(334,34,'_qty','1'),(335,34,'_tax_class',''),(336,34,'_line_subtotal','0'),(337,34,'_line_subtotal_tax','0'),(338,34,'_line_total','0'),(339,34,'_line_tax','0'),(340,34,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(341,34,'_reduced_stock','1'),(342,35,'_product_id','5457'),(343,35,'_variation_id','0'),(344,35,'_qty','1'),(345,35,'_tax_class',''),(346,35,'_line_subtotal','0'),(347,35,'_line_subtotal_tax','0'),(348,35,'_line_total','0'),(349,35,'_line_tax','0'),(350,35,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(351,35,'_reduced_stock','1'),(352,36,'_product_id','5496'),(353,36,'_variation_id','0'),(354,36,'_qty','1'),(355,36,'_tax_class',''),(356,36,'_line_subtotal','0'),(357,36,'_line_subtotal_tax','0'),(358,36,'_line_total','0'),(359,36,'_line_tax','0'),(360,36,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(361,36,'_reduced_stock','1'),(362,37,'_product_id','5500'),(363,37,'_variation_id','0'),(364,37,'_qty','1'),(365,37,'_tax_class',''),(366,37,'_line_subtotal','0'),(367,37,'_line_subtotal_tax','0'),(368,37,'_line_total','0'),(369,37,'_line_tax','0'),(370,37,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(371,37,'_reduced_stock','1'),(372,38,'_product_id','5500'),(373,38,'_variation_id','0'),(374,38,'_qty','1'),(375,38,'_tax_class',''),(376,38,'_line_subtotal','0'),(377,38,'_line_subtotal_tax','0'),(378,38,'_line_total','0'),(379,38,'_line_tax','0'),(380,38,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(381,38,'_reduced_stock','1'),(382,39,'_product_id','5500'),(383,39,'_variation_id','0'),(384,39,'_qty','1'),(385,39,'_tax_class',''),(386,39,'_line_subtotal','0'),(387,39,'_line_subtotal_tax','0'),(388,39,'_line_total','0'),(389,39,'_line_tax','0'),(390,39,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(391,39,'_reduced_stock','1'),(392,40,'_product_id','5508'),(393,40,'_variation_id','0'),(394,40,'_qty','1'),(395,40,'_tax_class',''),(396,40,'_line_subtotal','0'),(397,40,'_line_subtotal_tax','0'),(398,40,'_line_total','0'),(399,40,'_line_tax','0'),(400,40,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(401,40,'_reduced_stock','1'),(402,41,'_product_id','5512'),(403,41,'_variation_id','0'),(404,41,'_qty','1'),(405,41,'_tax_class',''),(406,41,'_line_subtotal','0'),(407,41,'_line_subtotal_tax','0'),(408,41,'_line_total','0'),(409,41,'_line_tax','0'),(410,41,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(411,41,'_reduced_stock','1'),(421,43,'_product_id','5547'),(422,43,'_variation_id','0'),(423,43,'_qty','2'),(424,43,'_tax_class',''),(425,43,'_line_subtotal','16'),(426,43,'_line_subtotal_tax','0'),(427,43,'_line_total','16'),(428,43,'_line_tax','0'),(429,43,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(431,44,'_product_id','5552'),(432,44,'_variation_id','0'),(433,44,'_qty','2'),(434,44,'_tax_class',''),(435,44,'_line_subtotal','16'),(436,44,'_line_subtotal_tax','0'),(437,44,'_line_total','16'),(438,44,'_line_tax','0'),(439,44,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(440,44,'_reduced_stock','2'),(441,45,'_product_id','5556'),(442,45,'_variation_id','0'),(443,45,'_qty','2'),(444,45,'_tax_class',''),(445,45,'_line_subtotal','16'),(446,45,'_line_subtotal_tax','0'),(447,45,'_line_total','16'),(448,45,'_line_tax','0'),(449,45,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(451,46,'_product_id','5561'),(452,46,'_variation_id','0'),(453,46,'_qty','2'),(454,46,'_tax_class',''),(455,46,'_line_subtotal','16'),(456,46,'_line_subtotal_tax','0'),(457,46,'_line_total','16'),(458,46,'_line_tax','0'),(459,46,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(480,49,'_product_id','5579'),(481,49,'_variation_id','0'),(482,49,'_qty','2'),(483,49,'_tax_class',''),(484,49,'_line_subtotal','16'),(485,49,'_line_subtotal_tax','0'),(486,49,'_line_total','16'),(487,49,'_line_tax','0'),(488,49,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(501,51,'_product_id','5585'),(502,51,'_variation_id','0'),(503,51,'_qty','2'),(504,51,'_tax_class',''),(505,51,'_line_subtotal','16'),(506,51,'_line_subtotal_tax','0'),(507,51,'_line_total','16'),(508,51,'_line_tax','0'),(509,51,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(522,53,'_product_id','5591'),(523,53,'_variation_id','0'),(524,53,'_qty','2'),(525,53,'_tax_class',''),(526,53,'_line_subtotal','16'),(527,53,'_line_subtotal_tax','0'),(528,53,'_line_total','16'),(529,53,'_line_tax','0'),(530,53,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(543,55,'_product_id','5618'),(544,55,'_variation_id','0'),(545,55,'_qty','1'),(546,55,'_tax_class',''),(547,55,'_line_subtotal','0'),(548,55,'_line_subtotal_tax','0'),(549,55,'_line_total','0'),(550,55,'_line_tax','0'),(551,55,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(552,55,'_reduced_stock','1'),(553,56,'_product_id','5622'),(554,56,'_variation_id','0'),(555,56,'_qty','1'),(556,56,'_tax_class',''),(557,56,'_line_subtotal','0'),(558,56,'_line_subtotal_tax','0'),(559,56,'_line_total','0'),(560,56,'_line_tax','0'),(561,56,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(562,56,'_reduced_stock','1'),(563,57,'_product_id','5097'),(564,57,'_variation_id','0'),(565,57,'_qty','2'),(566,57,'_tax_class',''),(567,57,'_line_subtotal','16'),(568,57,'_line_subtotal_tax','0'),(569,57,'_line_total','16'),(570,57,'_line_tax','0'),(571,57,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(573,58,'_product_id','5102'),(574,58,'_variation_id','0'),(575,58,'_qty','2'),(576,58,'_tax_class',''),(577,58,'_line_subtotal','16'),(578,58,'_line_subtotal_tax','0'),(579,58,'_line_total','16'),(580,58,'_line_tax','0'),(581,58,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(583,59,'_product_id','5107'),(584,59,'_variation_id','0'),(585,59,'_qty','2'),(586,59,'_tax_class',''),(587,59,'_line_subtotal','16'),(588,59,'_line_subtotal_tax','0'),(589,59,'_line_total','16'),(590,59,'_line_tax','0'),(591,59,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(593,60,'_product_id','5112'),(594,60,'_variation_id','0'),(595,60,'_qty','2'),(596,60,'_tax_class',''),(597,60,'_line_subtotal','16'),(598,60,'_line_subtotal_tax','0'),(599,60,'_line_total','16'),(600,60,'_line_tax','0'),(601,60,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(603,61,'_product_id','5097'),(604,61,'_variation_id','0'),(605,61,'_qty','2'),(606,61,'_tax_class',''),(607,61,'_line_subtotal','16'),(608,61,'_line_subtotal_tax','0'),(609,61,'_line_total','16'),(610,61,'_line_tax','0'),(611,61,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(614,62,'_product_id','5127'),(615,62,'_variation_id','0'),(616,62,'_qty','1'),(617,62,'_tax_class',''),(618,62,'_line_subtotal','0'),(619,62,'_line_subtotal_tax','0'),(620,62,'_line_total','0'),(621,62,'_line_tax','0'),(622,62,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(623,62,'_reduced_stock','1'),(624,63,'_product_id','5131'),(625,63,'_variation_id','0'),(626,63,'_qty','1'),(627,63,'_tax_class',''),(628,63,'_line_subtotal','0'),(629,63,'_line_subtotal_tax','0'),(630,63,'_line_total','0'),(631,63,'_line_tax','0'),(632,63,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(633,63,'_reduced_stock','1'),(634,64,'_product_id','5131'),(635,64,'_variation_id','0'),(636,64,'_qty','1'),(637,64,'_tax_class',''),(638,64,'_line_subtotal','0'),(639,64,'_line_subtotal_tax','0'),(640,64,'_line_total','0'),(641,64,'_line_tax','0'),(642,64,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(643,64,'_reduced_stock','1'),(644,65,'_product_id','5131'),(645,65,'_variation_id','0'),(646,65,'_qty','1'),(647,65,'_tax_class',''),(648,65,'_line_subtotal','0'),(649,65,'_line_subtotal_tax','0'),(650,65,'_line_total','0'),(651,65,'_line_tax','0'),(652,65,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(653,65,'_reduced_stock','1'),(654,66,'_product_id','5139'),(655,66,'_variation_id','0'),(656,66,'_qty','1'),(657,66,'_tax_class',''),(658,66,'_line_subtotal','0'),(659,66,'_line_subtotal_tax','0'),(660,66,'_line_total','0'),(661,66,'_line_tax','0'),(662,66,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(663,66,'_reduced_stock','1'),(664,67,'_product_id','5178'),(665,67,'_variation_id','0'),(666,67,'_qty','1'),(667,67,'_tax_class',''),(668,67,'_line_subtotal','0'),(669,67,'_line_subtotal_tax','0'),(670,67,'_line_total','0'),(671,67,'_line_tax','0'),(672,67,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(673,67,'_reduced_stock','1'),(674,68,'_product_id','5182'),(675,68,'_variation_id','0'),(676,68,'_qty','1'),(677,68,'_tax_class',''),(678,68,'_line_subtotal','0'),(679,68,'_line_subtotal_tax','0'),(680,68,'_line_total','0'),(681,68,'_line_tax','0'),(682,68,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(683,68,'_reduced_stock','1'),(684,69,'_product_id','5182'),(685,69,'_variation_id','0'),(686,69,'_qty','1'),(687,69,'_tax_class',''),(688,69,'_line_subtotal','0'),(689,69,'_line_subtotal_tax','0'),(690,69,'_line_total','0'),(691,69,'_line_tax','0'),(692,69,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(693,69,'_reduced_stock','1'),(694,70,'_product_id','5182'),(695,70,'_variation_id','0'),(696,70,'_qty','1'),(697,70,'_tax_class',''),(698,70,'_line_subtotal','0'),(699,70,'_line_subtotal_tax','0'),(700,70,'_line_total','0'),(701,70,'_line_tax','0'),(702,70,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(703,70,'_reduced_stock','1'),(704,71,'_product_id','5190'),(705,71,'_variation_id','0'),(706,71,'_qty','1'),(707,71,'_tax_class',''),(708,71,'_line_subtotal','0'),(709,71,'_line_subtotal_tax','0'),(710,71,'_line_total','0'),(711,71,'_line_tax','0'),(712,71,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(713,71,'_reduced_stock','1'),(723,73,'_product_id','5220'),(724,73,'_variation_id','0'),(725,73,'_qty','2'),(726,73,'_tax_class',''),(727,73,'_line_subtotal','16'),(728,73,'_line_subtotal_tax','0'),(729,73,'_line_total','16'),(730,73,'_line_tax','0'),(731,73,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(734,74,'_product_id','5227'),(735,74,'_variation_id','0'),(736,74,'_qty','2'),(737,74,'_tax_class',''),(738,74,'_line_subtotal','16'),(739,74,'_line_subtotal_tax','0'),(740,74,'_line_total','16'),(741,74,'_line_tax','0'),(742,74,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(743,74,'_reduced_stock','2'),(744,75,'_product_id','5231'),(745,75,'_variation_id','0'),(746,75,'_qty','2'),(747,75,'_tax_class',''),(748,75,'_line_subtotal','16'),(749,75,'_line_subtotal_tax','0'),(750,75,'_line_total','16'),(751,75,'_line_tax','0'),(752,75,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(773,78,'_product_id','5253'),(774,78,'_variation_id','0'),(775,78,'_qty','2'),(776,78,'_tax_class',''),(777,78,'_line_subtotal','16'),(778,78,'_line_subtotal_tax','0'),(779,78,'_line_total','16'),(780,78,'_line_tax','0'),(781,78,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(794,80,'_product_id','5259'),(795,80,'_variation_id','0'),(796,80,'_qty','2'),(797,80,'_tax_class',''),(798,80,'_line_subtotal','16'),(799,80,'_line_subtotal_tax','0'),(800,80,'_line_total','16'),(801,80,'_line_tax','0'),(802,80,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(815,82,'_product_id','5265'),(816,82,'_variation_id','0'),(817,82,'_qty','2'),(818,82,'_tax_class',''),(819,82,'_line_subtotal','16'),(820,82,'_line_subtotal_tax','0'),(821,82,'_line_total','16'),(822,82,'_line_tax','0'),(823,82,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(836,84,'_product_id','5292'),(837,84,'_variation_id','0'),(838,84,'_qty','1'),(839,84,'_tax_class',''),(840,84,'_line_subtotal','0'),(841,84,'_line_subtotal_tax','0'),(842,84,'_line_total','0'),(843,84,'_line_tax','0'),(844,84,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(845,84,'_reduced_stock','1'),(846,85,'_product_id','5296'),(847,85,'_variation_id','0'),(848,85,'_qty','1'),(849,85,'_tax_class',''),(850,85,'_line_subtotal','0'),(851,85,'_line_subtotal_tax','0'),(852,85,'_line_total','0'),(853,85,'_line_tax','0'),(854,85,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(855,85,'_reduced_stock','1'),(856,86,'_product_id','5300'),(857,86,'_variation_id','0'),(858,86,'_qty','1'),(859,86,'_tax_class',''),(860,86,'_line_subtotal','0'),(861,86,'_line_subtotal_tax','0'),(862,86,'_line_total','0'),(863,86,'_line_tax','0'),(864,86,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(865,86,'_reduced_stock','1'),(866,87,'_product_id','5326'),(867,87,'_variation_id','0'),(868,87,'_qty','1'),(869,87,'_tax_class',''),(870,87,'_line_subtotal','0'),(871,87,'_line_subtotal_tax','0'),(872,87,'_line_total','0'),(873,87,'_line_tax','0'),(874,87,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(875,87,'_reduced_stock','1'),(876,88,'_product_id','5370'),(877,88,'_variation_id','0'),(878,88,'_qty','2'),(879,88,'_tax_class',''),(880,88,'_line_subtotal','16'),(881,88,'_line_subtotal_tax','0'),(882,88,'_line_total','16'),(883,88,'_line_tax','0'),(884,88,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(886,89,'_product_id','5375'),(887,89,'_variation_id','0'),(888,89,'_qty','2'),(889,89,'_tax_class',''),(890,89,'_line_subtotal','16'),(891,89,'_line_subtotal_tax','0'),(892,89,'_line_total','16'),(893,89,'_line_tax','0'),(894,89,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(896,90,'_product_id','5380'),(897,90,'_variation_id','0'),(898,90,'_qty','2'),(899,90,'_tax_class',''),(900,90,'_line_subtotal','16'),(901,90,'_line_subtotal_tax','0'),(902,90,'_line_total','16'),(903,90,'_line_tax','0'),(904,90,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(906,91,'_product_id','5385'),(907,91,'_variation_id','0'),(908,91,'_qty','2'),(909,91,'_tax_class',''),(910,91,'_line_subtotal','16'),(911,91,'_line_subtotal_tax','0'),(912,91,'_line_total','16'),(913,91,'_line_tax','0'),(914,91,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(916,92,'_product_id','5447'),(917,92,'_variation_id','0'),(918,92,'_qty','1'),(919,92,'_tax_class',''),(920,92,'_line_subtotal','0'),(921,92,'_line_subtotal_tax','0'),(922,92,'_line_total','0'),(923,92,'_line_tax','0'),(924,92,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(925,92,'_reduced_stock','1'),(926,93,'_product_id','5451'),(927,93,'_variation_id','0'),(928,93,'_qty','1'),(929,93,'_tax_class',''),(930,93,'_line_subtotal','0'),(931,93,'_line_subtotal_tax','0'),(932,93,'_line_total','0'),(933,93,'_line_tax','0'),(934,93,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(935,93,'_reduced_stock','1'),(936,94,'_product_id','5451'),(937,94,'_variation_id','0'),(938,94,'_qty','1'),(939,94,'_tax_class',''),(940,94,'_line_subtotal','0'),(941,94,'_line_subtotal_tax','0'),(942,94,'_line_total','0'),(943,94,'_line_tax','0'),(944,94,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(945,94,'_reduced_stock','1'),(946,95,'_product_id','5451'),(947,95,'_variation_id','0'),(948,95,'_qty','1'),(949,95,'_tax_class',''),(950,95,'_line_subtotal','0'),(951,95,'_line_subtotal_tax','0'),(952,95,'_line_total','0'),(953,95,'_line_tax','0'),(954,95,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(955,95,'_reduced_stock','1'),(956,96,'_product_id','5459'),(957,96,'_variation_id','0'),(958,96,'_qty','1'),(959,96,'_tax_class',''),(960,96,'_line_subtotal','0'),(961,96,'_line_subtotal_tax','0'),(962,96,'_line_total','0'),(963,96,'_line_tax','0'),(964,96,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(965,96,'_reduced_stock','1'),(966,97,'_product_id','5498'),(967,97,'_variation_id','0'),(968,97,'_qty','1'),(969,97,'_tax_class',''),(970,97,'_line_subtotal','0'),(971,97,'_line_subtotal_tax','0'),(972,97,'_line_total','0'),(973,97,'_line_tax','0'),(974,97,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(975,97,'_reduced_stock','1'),(976,98,'_product_id','5502'),(977,98,'_variation_id','0'),(978,98,'_qty','1'),(979,98,'_tax_class',''),(980,98,'_line_subtotal','0'),(981,98,'_line_subtotal_tax','0'),(982,98,'_line_total','0'),(983,98,'_line_tax','0'),(984,98,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(985,98,'_reduced_stock','1'),(986,99,'_product_id','5502'),(987,99,'_variation_id','0'),(988,99,'_qty','1'),(989,99,'_tax_class',''),(990,99,'_line_subtotal','0'),(991,99,'_line_subtotal_tax','0'),(992,99,'_line_total','0'),(993,99,'_line_tax','0'),(994,99,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(995,99,'_reduced_stock','1'),(996,100,'_product_id','5502'),(997,100,'_variation_id','0'),(998,100,'_qty','1'),(999,100,'_tax_class',''),(1000,100,'_line_subtotal','0'),(1001,100,'_line_subtotal_tax','0'),(1002,100,'_line_total','0'),(1003,100,'_line_tax','0'),(1004,100,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1005,100,'_reduced_stock','1'),(1006,101,'_product_id','5510'),(1007,101,'_variation_id','0'),(1008,101,'_qty','1'),(1009,101,'_tax_class',''),(1010,101,'_line_subtotal','0'),(1011,101,'_line_subtotal_tax','0'),(1012,101,'_line_total','0'),(1013,101,'_line_tax','0'),(1014,101,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1015,101,'_reduced_stock','1'),(1016,102,'_product_id','5514'),(1017,102,'_variation_id','0'),(1018,102,'_qty','1'),(1019,102,'_tax_class',''),(1020,102,'_line_subtotal','0'),(1021,102,'_line_subtotal_tax','0'),(1022,102,'_line_total','0'),(1023,102,'_line_tax','0'),(1024,102,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1025,102,'_reduced_stock','1'),(1035,104,'_product_id','5549'),(1036,104,'_variation_id','0'),(1037,104,'_qty','2'),(1038,104,'_tax_class',''),(1039,104,'_line_subtotal','16'),(1040,104,'_line_subtotal_tax','0'),(1041,104,'_line_total','16'),(1042,104,'_line_tax','0'),(1043,104,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1045,105,'_product_id','5554'),(1046,105,'_variation_id','0'),(1047,105,'_qty','2'),(1048,105,'_tax_class',''),(1049,105,'_line_subtotal','16'),(1050,105,'_line_subtotal_tax','0'),(1051,105,'_line_total','16'),(1052,105,'_line_tax','0'),(1053,105,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1054,105,'_reduced_stock','2'),(1055,106,'_product_id','5558'),(1056,106,'_variation_id','0'),(1057,106,'_qty','2'),(1058,106,'_tax_class',''),(1059,106,'_line_subtotal','16'),(1060,106,'_line_subtotal_tax','0'),(1061,106,'_line_total','16'),(1062,106,'_line_tax','0'),(1063,106,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1065,107,'_product_id','5563'),(1066,107,'_variation_id','0'),(1067,107,'_qty','2'),(1068,107,'_tax_class',''),(1069,107,'_line_subtotal','16'),(1070,107,'_line_subtotal_tax','0'),(1071,107,'_line_total','16'),(1072,107,'_line_tax','0'),(1073,107,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1094,110,'_product_id','5581'),(1095,110,'_variation_id','0'),(1096,110,'_qty','2'),(1097,110,'_tax_class',''),(1098,110,'_line_subtotal','16'),(1099,110,'_line_subtotal_tax','0'),(1100,110,'_line_total','16'),(1101,110,'_line_tax','0'),(1102,110,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1115,112,'_product_id','5587'),(1116,112,'_variation_id','0'),(1117,112,'_qty','2'),(1118,112,'_tax_class',''),(1119,112,'_line_subtotal','16'),(1120,112,'_line_subtotal_tax','0'),(1121,112,'_line_total','16'),(1122,112,'_line_tax','0'),(1123,112,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1136,114,'_product_id','5593'),(1137,114,'_variation_id','0'),(1138,114,'_qty','2'),(1139,114,'_tax_class',''),(1140,114,'_line_subtotal','16'),(1141,114,'_line_subtotal_tax','0'),(1142,114,'_line_total','16'),(1143,114,'_line_tax','0'),(1144,114,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1157,116,'_product_id','5620'),(1158,116,'_variation_id','0'),(1159,116,'_qty','1'),(1160,116,'_tax_class',''),(1161,116,'_line_subtotal','0'),(1162,116,'_line_subtotal_tax','0'),(1163,116,'_line_total','0'),(1164,116,'_line_tax','0'),(1165,116,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1166,116,'_reduced_stock','1'),(1167,117,'_product_id','5624'),(1168,117,'_variation_id','0'),(1169,117,'_qty','1'),(1170,117,'_tax_class',''),(1171,117,'_line_subtotal','0'),(1172,117,'_line_subtotal_tax','0'),(1173,117,'_line_total','0'),(1174,117,'_line_tax','0'),(1175,117,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1176,117,'_reduced_stock','1'),(1177,118,'_product_id','5127'),(1178,118,'_variation_id','0'),(1179,118,'_qty','1'),(1180,118,'_tax_class',''),(1181,118,'_line_subtotal','0'),(1182,118,'_line_subtotal_tax','0'),(1183,118,'_line_total','0'),(1184,118,'_line_tax','0'),(1185,118,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1186,118,'_reduced_stock','1'),(1187,119,'_product_id','5131'),(1188,119,'_variation_id','0'),(1189,119,'_qty','1'),(1190,119,'_tax_class',''),(1191,119,'_line_subtotal','0'),(1192,119,'_line_subtotal_tax','0'),(1193,119,'_line_total','0'),(1194,119,'_line_tax','0'),(1195,119,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1196,119,'_reduced_stock','1'),(1197,120,'_product_id','5131'),(1198,120,'_variation_id','0'),(1199,120,'_qty','1'),(1200,120,'_tax_class',''),(1201,120,'_line_subtotal','0'),(1202,120,'_line_subtotal_tax','0'),(1203,120,'_line_total','0'),(1204,120,'_line_tax','0'),(1205,120,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1206,120,'_reduced_stock','1'),(1207,121,'_product_id','5131'),(1208,121,'_variation_id','0'),(1209,121,'_qty','1'),(1210,121,'_tax_class',''),(1211,121,'_line_subtotal','0'),(1212,121,'_line_subtotal_tax','0'),(1213,121,'_line_total','0'),(1214,121,'_line_tax','0'),(1215,121,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1216,121,'_reduced_stock','1'),(1217,122,'_product_id','5139'),(1218,122,'_variation_id','0'),(1219,122,'_qty','1'),(1220,122,'_tax_class',''),(1221,122,'_line_subtotal','0'),(1222,122,'_line_subtotal_tax','0'),(1223,122,'_line_total','0'),(1224,122,'_line_tax','0'),(1225,122,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1226,122,'_reduced_stock','1'),(1227,123,'_product_id','5178'),(1228,123,'_variation_id','0'),(1229,123,'_qty','1'),(1230,123,'_tax_class',''),(1231,123,'_line_subtotal','0'),(1232,123,'_line_subtotal_tax','0'),(1233,123,'_line_total','0'),(1234,123,'_line_tax','0'),(1235,123,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1236,123,'_reduced_stock','1'),(1237,124,'_product_id','5182'),(1238,124,'_variation_id','0'),(1239,124,'_qty','1'),(1240,124,'_tax_class',''),(1241,124,'_line_subtotal','0'),(1242,124,'_line_subtotal_tax','0'),(1243,124,'_line_total','0'),(1244,124,'_line_tax','0'),(1245,124,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1246,124,'_reduced_stock','1'),(1247,125,'_product_id','5182'),(1248,125,'_variation_id','0'),(1249,125,'_qty','1'),(1250,125,'_tax_class',''),(1251,125,'_line_subtotal','0'),(1252,125,'_line_subtotal_tax','0'),(1253,125,'_line_total','0'),(1254,125,'_line_tax','0'),(1255,125,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1256,125,'_reduced_stock','1'),(1257,126,'_product_id','5182'),(1258,126,'_variation_id','0'),(1259,126,'_qty','1'),(1260,126,'_tax_class',''),(1261,126,'_line_subtotal','0'),(1262,126,'_line_subtotal_tax','0'),(1263,126,'_line_total','0'),(1264,126,'_line_tax','0'),(1265,126,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1266,126,'_reduced_stock','1'),(1267,127,'_product_id','5190'),(1268,127,'_variation_id','0'),(1269,127,'_qty','1'),(1270,127,'_tax_class',''),(1271,127,'_line_subtotal','0'),(1272,127,'_line_subtotal_tax','0'),(1273,127,'_line_total','0'),(1274,127,'_line_tax','0'),(1275,127,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1276,127,'_reduced_stock','1'),(1286,129,'_product_id','5220'),(1287,129,'_variation_id','0'),(1288,129,'_qty','2'),(1289,129,'_tax_class',''),(1290,129,'_line_subtotal','16'),(1291,129,'_line_subtotal_tax','0'),(1292,129,'_line_total','16'),(1293,129,'_line_tax','0'),(1294,129,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1298,130,'_product_id','5229'),(1299,130,'_variation_id','0'),(1300,130,'_qty','2'),(1301,130,'_tax_class',''),(1302,130,'_line_subtotal','16'),(1303,130,'_line_subtotal_tax','0'),(1304,130,'_line_total','16'),(1305,130,'_line_tax','0'),(1306,130,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1307,14,'_reduced_stock','2'),(1308,130,'_reduced_stock','2'),(1309,131,'_product_id','5235'),(1310,131,'_variation_id','0'),(1311,131,'_qty','2'),(1312,131,'_tax_class',''),(1313,131,'_line_subtotal','16'),(1314,131,'_line_subtotal_tax','0'),(1315,131,'_line_total','16'),(1316,131,'_line_tax','0'),(1317,131,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1348,135,'_product_id','5257'),(1349,135,'_variation_id','0'),(1350,135,'_qty','2'),(1351,135,'_tax_class',''),(1352,135,'_line_subtotal','16'),(1353,135,'_line_subtotal_tax','0'),(1354,135,'_line_total','16'),(1355,135,'_line_tax','0'),(1356,135,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1381,138,'_product_id','5265'),(1382,138,'_variation_id','0'),(1383,138,'_qty','2'),(1384,138,'_tax_class',''),(1385,138,'_line_subtotal','16'),(1386,138,'_line_subtotal_tax','0'),(1387,138,'_line_total','16'),(1388,138,'_line_tax','0'),(1389,138,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1414,141,'_product_id','5273'),(1415,141,'_variation_id','0'),(1416,141,'_qty','2'),(1417,141,'_tax_class',''),(1418,141,'_line_subtotal','16'),(1419,141,'_line_subtotal_tax','0'),(1420,141,'_line_total','16'),(1421,141,'_line_tax','0'),(1422,141,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1435,143,'_product_id','5300'),(1436,143,'_variation_id','0'),(1437,143,'_qty','1'),(1438,143,'_tax_class',''),(1439,143,'_line_subtotal','0'),(1440,143,'_line_subtotal_tax','0'),(1441,143,'_line_total','0'),(1442,143,'_line_tax','0'),(1443,143,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1444,143,'_reduced_stock','1'),(1445,144,'_product_id','5304'),(1446,144,'_variation_id','0'),(1447,144,'_qty','1'),(1448,144,'_tax_class',''),(1449,144,'_line_subtotal','0'),(1450,144,'_line_subtotal_tax','0'),(1451,144,'_line_total','0'),(1452,144,'_line_tax','0'),(1453,144,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1454,144,'_reduced_stock','1'),(1455,145,'_product_id','5308'),(1456,145,'_variation_id','0'),(1457,145,'_qty','1'),(1458,145,'_tax_class',''),(1459,145,'_line_subtotal','0'),(1460,145,'_line_subtotal_tax','0'),(1461,145,'_line_total','0'),(1462,145,'_line_tax','0'),(1463,145,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1464,145,'_reduced_stock','1'),(1465,146,'_product_id','5334'),(1466,146,'_variation_id','0'),(1467,146,'_qty','1'),(1468,146,'_tax_class',''),(1469,146,'_line_subtotal','0'),(1470,146,'_line_subtotal_tax','0'),(1471,146,'_line_total','0'),(1472,146,'_line_tax','0'),(1473,146,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1474,146,'_reduced_stock','1'),(1475,147,'_product_id','5378'),(1476,147,'_variation_id','0'),(1477,147,'_qty','2'),(1478,147,'_tax_class',''),(1479,147,'_line_subtotal','16'),(1480,147,'_line_subtotal_tax','0'),(1481,147,'_line_total','16'),(1482,147,'_line_tax','0'),(1483,147,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1486,148,'_product_id','5385'),(1487,148,'_variation_id','0'),(1488,148,'_qty','2'),(1489,148,'_tax_class',''),(1490,148,'_line_subtotal','16'),(1491,148,'_line_subtotal_tax','0'),(1492,148,'_line_total','16'),(1493,148,'_line_tax','0'),(1494,148,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1497,149,'_product_id','5392'),(1498,149,'_variation_id','0'),(1499,149,'_qty','2'),(1500,149,'_tax_class',''),(1501,149,'_line_subtotal','16'),(1502,149,'_line_subtotal_tax','0'),(1503,149,'_line_total','16'),(1504,149,'_line_tax','0'),(1505,149,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1507,150,'_product_id','5397'),(1508,150,'_variation_id','0'),(1509,150,'_qty','2'),(1510,150,'_tax_class',''),(1511,150,'_line_subtotal','16'),(1512,150,'_line_subtotal_tax','0'),(1513,150,'_line_total','16'),(1514,150,'_line_tax','0'),(1515,150,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1517,151,'_product_id','5459'),(1518,151,'_variation_id','0'),(1519,151,'_qty','1'),(1520,151,'_tax_class',''),(1521,151,'_line_subtotal','0'),(1522,151,'_line_subtotal_tax','0'),(1523,151,'_line_total','0'),(1524,151,'_line_tax','0'),(1525,151,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1526,151,'_reduced_stock','1'),(1527,152,'_product_id','5463'),(1528,152,'_variation_id','0'),(1529,152,'_qty','1'),(1530,152,'_tax_class',''),(1531,152,'_line_subtotal','0'),(1532,152,'_line_subtotal_tax','0'),(1533,152,'_line_total','0'),(1534,152,'_line_tax','0'),(1535,152,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1536,152,'_reduced_stock','1'),(1537,153,'_product_id','5463'),(1538,153,'_variation_id','0'),(1539,153,'_qty','1'),(1540,153,'_tax_class',''),(1541,153,'_line_subtotal','0'),(1542,153,'_line_subtotal_tax','0'),(1543,153,'_line_total','0'),(1544,153,'_line_tax','0'),(1545,153,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1546,153,'_reduced_stock','1'),(1547,154,'_product_id','5463'),(1548,154,'_variation_id','0'),(1549,154,'_qty','1'),(1550,154,'_tax_class',''),(1551,154,'_line_subtotal','0'),(1552,154,'_line_subtotal_tax','0'),(1553,154,'_line_total','0'),(1554,154,'_line_tax','0'),(1555,154,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1556,154,'_reduced_stock','1'),(1557,155,'_product_id','5471'),(1558,155,'_variation_id','0'),(1559,155,'_qty','1'),(1560,155,'_tax_class',''),(1561,155,'_line_subtotal','0'),(1562,155,'_line_subtotal_tax','0'),(1563,155,'_line_total','0'),(1564,155,'_line_tax','0'),(1565,155,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1566,155,'_reduced_stock','1'),(1567,156,'_product_id','5510'),(1568,156,'_variation_id','0'),(1569,156,'_qty','1'),(1570,156,'_tax_class',''),(1571,156,'_line_subtotal','0'),(1572,156,'_line_subtotal_tax','0'),(1573,156,'_line_total','0'),(1574,156,'_line_tax','0'),(1575,156,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1576,156,'_reduced_stock','1'),(1577,157,'_product_id','5514'),(1578,157,'_variation_id','0'),(1579,157,'_qty','1'),(1580,157,'_tax_class',''),(1581,157,'_line_subtotal','0'),(1582,157,'_line_subtotal_tax','0'),(1583,157,'_line_total','0'),(1584,157,'_line_tax','0'),(1585,157,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1586,157,'_reduced_stock','1'),(1587,158,'_product_id','5514'),(1588,158,'_variation_id','0'),(1589,158,'_qty','1'),(1590,158,'_tax_class',''),(1591,158,'_line_subtotal','0'),(1592,158,'_line_subtotal_tax','0'),(1593,158,'_line_total','0'),(1594,158,'_line_tax','0'),(1595,158,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1596,158,'_reduced_stock','1'),(1597,159,'_product_id','5514'),(1598,159,'_variation_id','0'),(1599,159,'_qty','1'),(1600,159,'_tax_class',''),(1601,159,'_line_subtotal','0'),(1602,159,'_line_subtotal_tax','0'),(1603,159,'_line_total','0'),(1604,159,'_line_tax','0'),(1605,159,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1606,159,'_reduced_stock','1'),(1607,160,'_product_id','5522'),(1608,160,'_variation_id','0'),(1609,160,'_qty','1'),(1610,160,'_tax_class',''),(1611,160,'_line_subtotal','0'),(1612,160,'_line_subtotal_tax','0'),(1613,160,'_line_total','0'),(1614,160,'_line_tax','0'),(1615,160,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1616,160,'_reduced_stock','1'),(1617,161,'_product_id','5526'),(1618,161,'_variation_id','0'),(1619,161,'_qty','1'),(1620,161,'_tax_class',''),(1621,161,'_line_subtotal','0'),(1622,161,'_line_subtotal_tax','0'),(1623,161,'_line_total','0'),(1624,161,'_line_tax','0'),(1625,161,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1626,161,'_reduced_stock','1'),(1636,163,'_product_id','5561'),(1637,163,'_variation_id','0'),(1638,163,'_qty','2'),(1639,163,'_tax_class',''),(1640,163,'_line_subtotal','16'),(1641,163,'_line_subtotal_tax','0'),(1642,163,'_line_total','16'),(1643,163,'_line_tax','0'),(1644,163,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1647,164,'_product_id','5568'),(1648,164,'_variation_id','0'),(1649,164,'_qty','2'),(1650,164,'_tax_class',''),(1651,164,'_line_subtotal','16'),(1652,164,'_line_subtotal_tax','0'),(1653,164,'_line_total','16'),(1654,164,'_line_tax','0'),(1655,164,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1656,164,'_reduced_stock','2'),(1657,165,'_product_id','5572'),(1658,165,'_variation_id','0'),(1659,165,'_qty','2'),(1660,165,'_tax_class',''),(1661,165,'_line_subtotal','16'),(1662,165,'_line_subtotal_tax','0'),(1663,165,'_line_total','16'),(1664,165,'_line_tax','0'),(1665,165,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1667,166,'_product_id','5577'),(1668,166,'_variation_id','0'),(1669,166,'_qty','2'),(1670,166,'_tax_class',''),(1671,166,'_line_subtotal','16'),(1672,166,'_line_subtotal_tax','0'),(1673,166,'_line_total','16'),(1674,166,'_line_tax','0'),(1675,166,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1706,170,'_product_id','5595'),(1707,170,'_variation_id','0'),(1708,170,'_qty','2'),(1709,170,'_tax_class',''),(1710,170,'_line_subtotal','16'),(1711,170,'_line_subtotal_tax','0'),(1712,170,'_line_total','16'),(1713,170,'_line_tax','0'),(1714,170,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1727,172,'_product_id','5601'),(1728,172,'_variation_id','0'),(1729,172,'_qty','2'),(1730,172,'_tax_class',''),(1731,172,'_line_subtotal','16'),(1732,172,'_line_subtotal_tax','0'),(1733,172,'_line_total','16'),(1734,172,'_line_tax','0'),(1735,172,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1748,174,'_product_id','5607'),(1749,174,'_variation_id','0'),(1750,174,'_qty','2'),(1751,174,'_tax_class',''),(1752,174,'_line_subtotal','16'),(1753,174,'_line_subtotal_tax','0'),(1754,174,'_line_total','16'),(1755,174,'_line_tax','0'),(1756,174,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1769,176,'_product_id','5634'),(1770,176,'_variation_id','0'),(1771,176,'_qty','1'),(1772,176,'_tax_class',''),(1773,176,'_line_subtotal','0'),(1774,176,'_line_subtotal_tax','0'),(1775,176,'_line_total','0'),(1776,176,'_line_tax','0'),(1777,176,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1778,176,'_reduced_stock','1'),(1779,177,'_product_id','5638'),(1780,177,'_variation_id','0'),(1781,177,'_qty','1'),(1782,177,'_tax_class',''),(1783,177,'_line_subtotal','0'),(1784,177,'_line_subtotal_tax','0'),(1785,177,'_line_total','0'),(1786,177,'_line_tax','0'),(1787,177,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1788,177,'_reduced_stock','1'),(1789,178,'_product_id','5127'),(1790,178,'_variation_id','0'),(1791,178,'_qty','1'),(1792,178,'_tax_class',''),(1793,178,'_line_subtotal','0'),(1794,178,'_line_subtotal_tax','0'),(1795,178,'_line_total','0'),(1796,178,'_line_tax','0'),(1797,178,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1798,178,'_reduced_stock','1'),(1799,179,'_product_id','5131'),(1800,179,'_variation_id','0'),(1801,179,'_qty','1'),(1802,179,'_tax_class',''),(1803,179,'_line_subtotal','0'),(1804,179,'_line_subtotal_tax','0'),(1805,179,'_line_total','0'),(1806,179,'_line_tax','0'),(1807,179,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1808,179,'_reduced_stock','1'),(1809,180,'_product_id','5131'),(1810,180,'_variation_id','0'),(1811,180,'_qty','1'),(1812,180,'_tax_class',''),(1813,180,'_line_subtotal','0'),(1814,180,'_line_subtotal_tax','0'),(1815,180,'_line_total','0'),(1816,180,'_line_tax','0'),(1817,180,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1818,180,'_reduced_stock','1'),(1819,181,'_product_id','5131'),(1820,181,'_variation_id','0'),(1821,181,'_qty','1'),(1822,181,'_tax_class',''),(1823,181,'_line_subtotal','0'),(1824,181,'_line_subtotal_tax','0'),(1825,181,'_line_total','0'),(1826,181,'_line_tax','0'),(1827,181,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1828,181,'_reduced_stock','1'),(1829,182,'_product_id','5139'),(1830,182,'_variation_id','0'),(1831,182,'_qty','1'),(1832,182,'_tax_class',''),(1833,182,'_line_subtotal','0'),(1834,182,'_line_subtotal_tax','0'),(1835,182,'_line_total','0'),(1836,182,'_line_tax','0'),(1837,182,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1838,182,'_reduced_stock','1'),(1839,183,'_product_id','5178'),(1840,183,'_variation_id','0'),(1841,183,'_qty','1'),(1842,183,'_tax_class',''),(1843,183,'_line_subtotal','0'),(1844,183,'_line_subtotal_tax','0'),(1845,183,'_line_total','0'),(1846,183,'_line_tax','0'),(1847,183,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1848,183,'_reduced_stock','1'),(1849,184,'_product_id','5182'),(1850,184,'_variation_id','0'),(1851,184,'_qty','1'),(1852,184,'_tax_class',''),(1853,184,'_line_subtotal','0'),(1854,184,'_line_subtotal_tax','0'),(1855,184,'_line_total','0'),(1856,184,'_line_tax','0'),(1857,184,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1858,184,'_reduced_stock','1'),(1859,185,'_product_id','5182'),(1860,185,'_variation_id','0'),(1861,185,'_qty','1'),(1862,185,'_tax_class',''),(1863,185,'_line_subtotal','0'),(1864,185,'_line_subtotal_tax','0'),(1865,185,'_line_total','0'),(1866,185,'_line_tax','0'),(1867,185,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1868,185,'_reduced_stock','1'),(1869,186,'_product_id','5182'),(1870,186,'_variation_id','0'),(1871,186,'_qty','1'),(1872,186,'_tax_class',''),(1873,186,'_line_subtotal','0'),(1874,186,'_line_subtotal_tax','0'),(1875,186,'_line_total','0'),(1876,186,'_line_tax','0'),(1877,186,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1878,186,'_reduced_stock','1'),(1879,187,'_product_id','5190'),(1880,187,'_variation_id','0'),(1881,187,'_qty','1'),(1882,187,'_tax_class',''),(1883,187,'_line_subtotal','0'),(1884,187,'_line_subtotal_tax','0'),(1885,187,'_line_total','0'),(1886,187,'_line_tax','0'),(1887,187,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1888,187,'_reduced_stock','1'),(1898,189,'_product_id','5220'),(1899,189,'_variation_id','0'),(1900,189,'_qty','2'),(1901,189,'_tax_class',''),(1902,189,'_line_subtotal','16'),(1903,189,'_line_subtotal_tax','0'),(1904,189,'_line_total','16'),(1905,189,'_line_tax','0'),(1906,189,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1911,190,'_product_id','5231'),(1912,190,'_variation_id','0'),(1913,190,'_qty','2'),(1914,190,'_tax_class',''),(1915,190,'_line_subtotal','16'),(1916,190,'_line_subtotal_tax','0'),(1917,190,'_line_total','16'),(1918,190,'_line_tax','0'),(1919,190,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1920,75,'_reduced_stock','2'),(1921,190,'_reduced_stock','2'),(1922,191,'_product_id','5237'),(1923,191,'_variation_id','0'),(1924,191,'_qty','2'),(1925,191,'_tax_class',''),(1926,191,'_line_subtotal','16'),(1927,191,'_line_subtotal_tax','0'),(1928,191,'_line_total','16'),(1929,191,'_line_tax','0'),(1930,191,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1951,194,'_product_id','5259'),(1952,194,'_variation_id','0'),(1953,194,'_qty','2'),(1954,194,'_tax_class',''),(1955,194,'_line_subtotal','16'),(1956,194,'_line_subtotal_tax','0'),(1957,194,'_line_total','16'),(1958,194,'_line_tax','0'),(1959,194,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(1984,197,'_product_id','5267'),(1985,197,'_variation_id','0'),(1986,197,'_qty','2'),(1987,197,'_tax_class',''),(1988,197,'_line_subtotal','16'),(1989,197,'_line_subtotal_tax','0'),(1990,197,'_line_total','16'),(1991,197,'_line_tax','0'),(1992,197,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2005,199,'_product_id','5273'),(2006,199,'_variation_id','0'),(2007,199,'_qty','2'),(2008,199,'_tax_class',''),(2009,199,'_line_subtotal','16'),(2010,199,'_line_subtotal_tax','0'),(2011,199,'_line_total','16'),(2012,199,'_line_tax','0'),(2013,199,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2038,202,'_product_id','5302'),(2039,202,'_variation_id','0'),(2040,202,'_qty','1'),(2041,202,'_tax_class',''),(2042,202,'_line_subtotal','0'),(2043,202,'_line_subtotal_tax','0'),(2044,202,'_line_total','0'),(2045,202,'_line_tax','0'),(2046,202,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2047,202,'_reduced_stock','1'),(2048,203,'_product_id','5306'),(2049,203,'_variation_id','0'),(2050,203,'_qty','1'),(2051,203,'_tax_class',''),(2052,203,'_line_subtotal','0'),(2053,203,'_line_subtotal_tax','0'),(2054,203,'_line_total','0'),(2055,203,'_line_tax','0'),(2056,203,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2057,203,'_reduced_stock','1'),(2058,204,'_product_id','5310'),(2059,204,'_variation_id','0'),(2060,204,'_qty','1'),(2061,204,'_tax_class',''),(2062,204,'_line_subtotal','0'),(2063,204,'_line_subtotal_tax','0'),(2064,204,'_line_total','0'),(2065,204,'_line_tax','0'),(2066,204,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2067,204,'_reduced_stock','1'),(2068,205,'_product_id','5336'),(2069,205,'_variation_id','0'),(2070,205,'_qty','1'),(2071,205,'_tax_class',''),(2072,205,'_line_subtotal','0'),(2073,205,'_line_subtotal_tax','0'),(2074,205,'_line_total','0'),(2075,205,'_line_tax','0'),(2076,205,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2077,205,'_reduced_stock','1'),(2078,206,'_product_id','5380'),(2079,206,'_variation_id','0'),(2080,206,'_qty','2'),(2081,206,'_tax_class',''),(2082,206,'_line_subtotal','16'),(2083,206,'_line_subtotal_tax','0'),(2084,206,'_line_total','16'),(2085,206,'_line_tax','0'),(2086,206,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2089,207,'_product_id','5387'),(2090,207,'_variation_id','0'),(2091,207,'_qty','2'),(2092,207,'_tax_class',''),(2093,207,'_line_subtotal','16'),(2094,207,'_line_subtotal_tax','0'),(2095,207,'_line_total','16'),(2096,207,'_line_tax','0'),(2097,207,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2099,208,'_product_id','5392'),(2100,208,'_variation_id','0'),(2101,208,'_qty','2'),(2102,208,'_tax_class',''),(2103,208,'_line_subtotal','16'),(2104,208,'_line_subtotal_tax','0'),(2105,208,'_line_total','16'),(2106,208,'_line_tax','0'),(2107,208,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2110,209,'_product_id','5399'),(2111,209,'_variation_id','0'),(2112,209,'_qty','2'),(2113,209,'_tax_class',''),(2114,209,'_line_subtotal','16'),(2115,209,'_line_subtotal_tax','0'),(2116,209,'_line_total','16'),(2117,209,'_line_tax','0'),(2118,209,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2120,210,'_product_id','5461'),(2121,210,'_variation_id','0'),(2122,210,'_qty','1'),(2123,210,'_tax_class',''),(2124,210,'_line_subtotal','0'),(2125,210,'_line_subtotal_tax','0'),(2126,210,'_line_total','0'),(2127,210,'_line_tax','0'),(2128,210,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2129,210,'_reduced_stock','1'),(2130,211,'_product_id','5465'),(2131,211,'_variation_id','0'),(2132,211,'_qty','1'),(2133,211,'_tax_class',''),(2134,211,'_line_subtotal','0'),(2135,211,'_line_subtotal_tax','0'),(2136,211,'_line_total','0'),(2137,211,'_line_tax','0'),(2138,211,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2139,211,'_reduced_stock','1'),(2140,212,'_product_id','5465'),(2141,212,'_variation_id','0'),(2142,212,'_qty','1'),(2143,212,'_tax_class',''),(2144,212,'_line_subtotal','0'),(2145,212,'_line_subtotal_tax','0'),(2146,212,'_line_total','0'),(2147,212,'_line_tax','0'),(2148,212,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2149,212,'_reduced_stock','1'),(2150,213,'_product_id','5465'),(2151,213,'_variation_id','0'),(2152,213,'_qty','1'),(2153,213,'_tax_class',''),(2154,213,'_line_subtotal','0'),(2155,213,'_line_subtotal_tax','0'),(2156,213,'_line_total','0'),(2157,213,'_line_tax','0'),(2158,213,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2159,213,'_reduced_stock','1'),(2160,214,'_product_id','5473'),(2161,214,'_variation_id','0'),(2162,214,'_qty','1'),(2163,214,'_tax_class',''),(2164,214,'_line_subtotal','0'),(2165,214,'_line_subtotal_tax','0'),(2166,214,'_line_total','0'),(2167,214,'_line_tax','0'),(2168,214,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2169,214,'_reduced_stock','1'),(2170,215,'_product_id','5512'),(2171,215,'_variation_id','0'),(2172,215,'_qty','1'),(2173,215,'_tax_class',''),(2174,215,'_line_subtotal','0'),(2175,215,'_line_subtotal_tax','0'),(2176,215,'_line_total','0'),(2177,215,'_line_tax','0'),(2178,215,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2179,215,'_reduced_stock','1'),(2180,216,'_product_id','5516'),(2181,216,'_variation_id','0'),(2182,216,'_qty','1'),(2183,216,'_tax_class',''),(2184,216,'_line_subtotal','0'),(2185,216,'_line_subtotal_tax','0'),(2186,216,'_line_total','0'),(2187,216,'_line_tax','0'),(2188,216,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2189,216,'_reduced_stock','1'),(2190,217,'_product_id','5516'),(2191,217,'_variation_id','0'),(2192,217,'_qty','1'),(2193,217,'_tax_class',''),(2194,217,'_line_subtotal','0'),(2195,217,'_line_subtotal_tax','0'),(2196,217,'_line_total','0'),(2197,217,'_line_tax','0'),(2198,217,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2199,217,'_reduced_stock','1'),(2200,218,'_product_id','5516'),(2201,218,'_variation_id','0'),(2202,218,'_qty','1'),(2203,218,'_tax_class',''),(2204,218,'_line_subtotal','0'),(2205,218,'_line_subtotal_tax','0'),(2206,218,'_line_total','0'),(2207,218,'_line_tax','0'),(2208,218,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2209,218,'_reduced_stock','1'),(2210,219,'_product_id','5524'),(2211,219,'_variation_id','0'),(2212,219,'_qty','1'),(2213,219,'_tax_class',''),(2214,219,'_line_subtotal','0'),(2215,219,'_line_subtotal_tax','0'),(2216,219,'_line_total','0'),(2217,219,'_line_tax','0'),(2218,219,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2219,219,'_reduced_stock','1'),(2220,220,'_product_id','5528'),(2221,220,'_variation_id','0'),(2222,220,'_qty','1'),(2223,220,'_tax_class',''),(2224,220,'_line_subtotal','0'),(2225,220,'_line_subtotal_tax','0'),(2226,220,'_line_total','0'),(2227,220,'_line_tax','0'),(2228,220,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2229,220,'_reduced_stock','1'),(2239,222,'_product_id','5563'),(2240,222,'_variation_id','0'),(2241,222,'_qty','2'),(2242,222,'_tax_class',''),(2243,222,'_line_subtotal','16'),(2244,222,'_line_subtotal_tax','0'),(2245,222,'_line_total','16'),(2246,222,'_line_tax','0'),(2247,222,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2250,223,'_product_id','5570'),(2251,223,'_variation_id','0'),(2252,223,'_qty','2'),(2253,223,'_tax_class',''),(2254,223,'_line_subtotal','16'),(2255,223,'_line_subtotal_tax','0'),(2256,223,'_line_total','16'),(2257,223,'_line_tax','0'),(2258,223,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2259,223,'_reduced_stock','2'),(2260,224,'_product_id','5574'),(2261,224,'_variation_id','0'),(2262,224,'_qty','2'),(2263,224,'_tax_class',''),(2264,224,'_line_subtotal','16'),(2265,224,'_line_subtotal_tax','0'),(2266,224,'_line_total','16'),(2267,224,'_line_tax','0'),(2268,224,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2270,225,'_product_id','5579'),(2271,225,'_variation_id','0'),(2272,225,'_qty','2'),(2273,225,'_tax_class',''),(2274,225,'_line_subtotal','16'),(2275,225,'_line_subtotal_tax','0'),(2276,225,'_line_total','16'),(2277,225,'_line_tax','0'),(2278,225,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2310,229,'_product_id','5599'),(2311,229,'_variation_id','0'),(2312,229,'_qty','2'),(2313,229,'_tax_class',''),(2314,229,'_line_subtotal','16'),(2315,229,'_line_subtotal_tax','0'),(2316,229,'_line_total','16'),(2317,229,'_line_tax','0'),(2318,229,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2331,231,'_product_id','5605'),(2332,231,'_variation_id','0'),(2333,231,'_qty','2'),(2334,231,'_tax_class',''),(2335,231,'_line_subtotal','16'),(2336,231,'_line_subtotal_tax','0'),(2337,231,'_line_total','16'),(2338,231,'_line_tax','0'),(2339,231,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2352,233,'_product_id','5611'),(2353,233,'_variation_id','0'),(2354,233,'_qty','2'),(2355,233,'_tax_class',''),(2356,233,'_line_subtotal','16'),(2357,233,'_line_subtotal_tax','0'),(2358,233,'_line_total','16'),(2359,233,'_line_tax','0'),(2360,233,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2373,235,'_product_id','5638'),(2374,235,'_variation_id','0'),(2375,235,'_qty','1'),(2376,235,'_tax_class',''),(2377,235,'_line_subtotal','0'),(2378,235,'_line_subtotal_tax','0'),(2379,235,'_line_total','0'),(2380,235,'_line_tax','0'),(2381,235,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2382,235,'_reduced_stock','1'),(2383,236,'_product_id','5642'),(2384,236,'_variation_id','0'),(2385,236,'_qty','1'),(2386,236,'_tax_class',''),(2387,236,'_line_subtotal','0'),(2388,236,'_line_subtotal_tax','0'),(2389,236,'_line_total','0'),(2390,236,'_line_tax','0'),(2391,236,'_line_tax_data','a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),(2392,236,'_reduced_stock','1');
/*!40000 ALTER TABLE `wptests_woocommerce_order_itemmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_order_items`
--

DROP TABLE IF EXISTS `wptests_woocommerce_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_order_items` (
  `order_item_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_item_name` text NOT NULL,
  `order_item_type` varchar(200) NOT NULL DEFAULT '',
  `order_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_order_items`
--

LOCK TABLES `wptests_woocommerce_order_items` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_order_items` DISABLE KEYS */;
INSERT INTO `wptests_woocommerce_order_items` VALUES (1,'Test WooCommerce ticket for 5126','line_item',5128),(2,'Test WooCommerce ticket for 5130','line_item',5132),(3,'Test WooCommerce ticket for 5130','line_item',5134),(4,'Test WooCommerce ticket for 5130','line_item',5136),(5,'Test WooCommerce ticket for 5138','line_item',5140),(6,'Test WooCommerce ticket for 5177','line_item',5179),(7,'Test WooCommerce ticket for 5181','line_item',5183),(8,'Test WooCommerce ticket for 5181','line_item',5185),(9,'Test WooCommerce ticket for 5181','line_item',5187),(10,'Test WooCommerce ticket for 5189','line_item',5191),(12,'Test WooCommerce ticket for 5219','line_item',5221),(13,'Test WooCommerce ticket for 5224','line_item',5226),(14,'Test WooCommerce ticket for 5224','line_item',5230),(17,'Test WooCommerce ticket for 5250','line_item',5252),(19,'Test WooCommerce ticket for 5256','line_item',5258),(21,'Test WooCommerce ticket for 5262','line_item',5264),(23,'Test WooCommerce ticket for 5289','line_item',5291),(24,'Test WooCommerce ticket for 5293','line_item',5295),(25,'Test WooCommerce ticket for 5297','line_item',5299),(26,'Test WooCommerce ticket for 5323','line_item',5325),(27,'Test WooCommerce ticket for 5367','line_item',5369),(28,'Test WooCommerce ticket for 5372','line_item',5374),(29,'Test WooCommerce ticket for 5377','line_item',5379),(30,'Test WooCommerce ticket for 5382','line_item',5384),(31,'Test WooCommerce ticket for 5444','line_item',5446),(32,'Test WooCommerce ticket for 5448','line_item',5450),(33,'Test WooCommerce ticket for 5448','line_item',5452),(34,'Test WooCommerce ticket for 5448','line_item',5454),(35,'Test WooCommerce ticket for 5456','line_item',5458),(36,'Test WooCommerce ticket for 5495','line_item',5497),(37,'Test WooCommerce ticket for 5499','line_item',5501),(38,'Test WooCommerce ticket for 5499','line_item',5503),(39,'Test WooCommerce ticket for 5499','line_item',5505),(40,'Test WooCommerce ticket for 5507','line_item',5509),(41,'Test WooCommerce ticket for 5511','line_item',5513),(43,'Test WooCommerce ticket for 5546','line_item',5548),(44,'Test WooCommerce ticket for 5551','line_item',5553),(45,'Test WooCommerce ticket for 5551','line_item',5557),(46,'Test WooCommerce ticket for 5560','line_item',5562),(49,'Test WooCommerce ticket for 5578','line_item',5580),(51,'Test WooCommerce ticket for 5584','line_item',5586),(53,'Test WooCommerce ticket for 5590','line_item',5592),(55,'Test WooCommerce ticket for 5617','line_item',5619),(56,'Test WooCommerce ticket for 5621','line_item',5623),(57,'Test WooCommerce ticket for 5096','line_item',5098),(58,'Test WooCommerce ticket for 5101','line_item',5103),(59,'Test WooCommerce ticket for 5106','line_item',5108),(60,'Test WooCommerce ticket for 5111','line_item',5113),(61,'Test WooCommerce ticket for 5096','line_item',5098),(62,'Test WooCommerce ticket for 5126','line_item',5128),(63,'Test WooCommerce ticket for 5130','line_item',5132),(64,'Test WooCommerce ticket for 5130','line_item',5134),(65,'Test WooCommerce ticket for 5130','line_item',5136),(66,'Test WooCommerce ticket for 5138','line_item',5140),(67,'Test WooCommerce ticket for 5177','line_item',5179),(68,'Test WooCommerce ticket for 5181','line_item',5183),(69,'Test WooCommerce ticket for 5181','line_item',5185),(70,'Test WooCommerce ticket for 5181','line_item',5187),(71,'Test WooCommerce ticket for 5189','line_item',5191),(73,'Test WooCommerce ticket for 5219','line_item',5221),(74,'Test WooCommerce ticket for 5226','line_item',5228),(75,'Test WooCommerce ticket for 5226','line_item',5232),(78,'Test WooCommerce ticket for 5252','line_item',5254),(80,'Test WooCommerce ticket for 5258','line_item',5260),(82,'Test WooCommerce ticket for 5264','line_item',5266),(84,'Test WooCommerce ticket for 5291','line_item',5293),(85,'Test WooCommerce ticket for 5295','line_item',5297),(86,'Test WooCommerce ticket for 5299','line_item',5301),(87,'Test WooCommerce ticket for 5325','line_item',5327),(88,'Test WooCommerce ticket for 5369','line_item',5371),(89,'Test WooCommerce ticket for 5374','line_item',5376),(90,'Test WooCommerce ticket for 5379','line_item',5381),(91,'Test WooCommerce ticket for 5384','line_item',5386),(92,'Test WooCommerce ticket for 5446','line_item',5448),(93,'Test WooCommerce ticket for 5450','line_item',5452),(94,'Test WooCommerce ticket for 5450','line_item',5454),(95,'Test WooCommerce ticket for 5450','line_item',5456),(96,'Test WooCommerce ticket for 5458','line_item',5460),(97,'Test WooCommerce ticket for 5497','line_item',5499),(98,'Test WooCommerce ticket for 5501','line_item',5503),(99,'Test WooCommerce ticket for 5501','line_item',5505),(100,'Test WooCommerce ticket for 5501','line_item',5507),(101,'Test WooCommerce ticket for 5509','line_item',5511),(102,'Test WooCommerce ticket for 5513','line_item',5515),(104,'Test WooCommerce ticket for 5548','line_item',5550),(105,'Test WooCommerce ticket for 5553','line_item',5555),(106,'Test WooCommerce ticket for 5553','line_item',5559),(107,'Test WooCommerce ticket for 5562','line_item',5564),(110,'Test WooCommerce ticket for 5580','line_item',5582),(112,'Test WooCommerce ticket for 5586','line_item',5588),(114,'Test WooCommerce ticket for 5592','line_item',5594),(116,'Test WooCommerce ticket for 5619','line_item',5621),(117,'Test WooCommerce ticket for 5623','line_item',5625),(118,'Test WooCommerce ticket for 5126','line_item',5128),(119,'Test WooCommerce ticket for 5130','line_item',5132),(120,'Test WooCommerce ticket for 5130','line_item',5134),(121,'Test WooCommerce ticket for 5130','line_item',5136),(122,'Test WooCommerce ticket for 5138','line_item',5140),(123,'Test WooCommerce ticket for 5177','line_item',5179),(124,'Test WooCommerce ticket for 5181','line_item',5183),(125,'Test WooCommerce ticket for 5181','line_item',5185),(126,'Test WooCommerce ticket for 5181','line_item',5187),(127,'Test WooCommerce ticket for 5189','line_item',5191),(129,'Test WooCommerce ticket for 5219','line_item',5221),(130,'Test WooCommerce ticket for 5228','line_item',5230),(131,'Test WooCommerce ticket for 5228','line_item',5236),(135,'Test WooCommerce ticket for 5256','line_item',5258),(138,'Test WooCommerce ticket for 5264','line_item',5266),(141,'Test WooCommerce ticket for 5272','line_item',5274),(143,'Test WooCommerce ticket for 5299','line_item',5301),(144,'Test WooCommerce ticket for 5303','line_item',5305),(145,'Test WooCommerce ticket for 5307','line_item',5309),(146,'Test WooCommerce ticket for 5333','line_item',5335),(147,'Test WooCommerce ticket for 5377','line_item',5379),(148,'Test WooCommerce ticket for 5384','line_item',5386),(149,'Test WooCommerce ticket for 5391','line_item',5393),(150,'Test WooCommerce ticket for 5396','line_item',5398),(151,'Test WooCommerce ticket for 5458','line_item',5460),(152,'Test WooCommerce ticket for 5462','line_item',5464),(153,'Test WooCommerce ticket for 5462','line_item',5466),(154,'Test WooCommerce ticket for 5462','line_item',5468),(155,'Test WooCommerce ticket for 5470','line_item',5472),(156,'Test WooCommerce ticket for 5509','line_item',5511),(157,'Test WooCommerce ticket for 5513','line_item',5515),(158,'Test WooCommerce ticket for 5513','line_item',5517),(159,'Test WooCommerce ticket for 5513','line_item',5519),(160,'Test WooCommerce ticket for 5521','line_item',5523),(161,'Test WooCommerce ticket for 5525','line_item',5527),(163,'Test WooCommerce ticket for 5560','line_item',5562),(164,'Test WooCommerce ticket for 5567','line_item',5569),(165,'Test WooCommerce ticket for 5567','line_item',5573),(166,'Test WooCommerce ticket for 5576','line_item',5578),(170,'Test WooCommerce ticket for 5594','line_item',5596),(172,'Test WooCommerce ticket for 5600','line_item',5602),(174,'Test WooCommerce ticket for 5606','line_item',5608),(176,'Test WooCommerce ticket for 5633','line_item',5635),(177,'Test WooCommerce ticket for 5637','line_item',5639),(178,'Test WooCommerce ticket for 5126','line_item',5128),(179,'Test WooCommerce ticket for 5130','line_item',5132),(180,'Test WooCommerce ticket for 5130','line_item',5134),(181,'Test WooCommerce ticket for 5130','line_item',5136),(182,'Test WooCommerce ticket for 5138','line_item',5140),(183,'Test WooCommerce ticket for 5177','line_item',5179),(184,'Test WooCommerce ticket for 5181','line_item',5183),(185,'Test WooCommerce ticket for 5181','line_item',5185),(186,'Test WooCommerce ticket for 5181','line_item',5187),(187,'Test WooCommerce ticket for 5189','line_item',5191),(189,'Test WooCommerce ticket for 5219','line_item',5221),(190,'Test WooCommerce ticket for 5230','line_item',5232),(191,'Test WooCommerce ticket for 5230','line_item',5238),(194,'Test WooCommerce ticket for 5258','line_item',5260),(197,'Test WooCommerce ticket for 5266','line_item',5268),(199,'Test WooCommerce ticket for 5272','line_item',5274),(202,'Test WooCommerce ticket for 5301','line_item',5303),(203,'Test WooCommerce ticket for 5305','line_item',5307),(204,'Test WooCommerce ticket for 5309','line_item',5311),(205,'Test WooCommerce ticket for 5335','line_item',5337),(206,'Test WooCommerce ticket for 5379','line_item',5381),(207,'Test WooCommerce ticket for 5386','line_item',5388),(208,'Test WooCommerce ticket for 5391','line_item',5393),(209,'Test WooCommerce ticket for 5398','line_item',5400),(210,'Test WooCommerce ticket for 5460','line_item',5462),(211,'Test WooCommerce ticket for 5464','line_item',5466),(212,'Test WooCommerce ticket for 5464','line_item',5468),(213,'Test WooCommerce ticket for 5464','line_item',5470),(214,'Test WooCommerce ticket for 5472','line_item',5474),(215,'Test WooCommerce ticket for 5511','line_item',5513),(216,'Test WooCommerce ticket for 5515','line_item',5517),(217,'Test WooCommerce ticket for 5515','line_item',5519),(218,'Test WooCommerce ticket for 5515','line_item',5521),(219,'Test WooCommerce ticket for 5523','line_item',5525),(220,'Test WooCommerce ticket for 5527','line_item',5529),(222,'Test WooCommerce ticket for 5562','line_item',5564),(223,'Test WooCommerce ticket for 5569','line_item',5571),(224,'Test WooCommerce ticket for 5569','line_item',5575),(225,'Test WooCommerce ticket for 5578','line_item',5580),(229,'Test WooCommerce ticket for 5598','line_item',5600),(231,'Test WooCommerce ticket for 5604','line_item',5606),(233,'Test WooCommerce ticket for 5610','line_item',5612),(235,'Test WooCommerce ticket for 5637','line_item',5639),(236,'Test WooCommerce ticket for 5641','line_item',5643);
/*!40000 ALTER TABLE `wptests_woocommerce_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_payment_tokenmeta`
--

DROP TABLE IF EXISTS `wptests_woocommerce_payment_tokenmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_payment_tokenmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_token_id` bigint(20) unsigned NOT NULL,
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext DEFAULT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `payment_token_id` (`payment_token_id`),
  KEY `meta_key` (`meta_key`(32))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_payment_tokenmeta`
--

LOCK TABLES `wptests_woocommerce_payment_tokenmeta` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_payment_tokenmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_payment_tokenmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_payment_tokens`
--

DROP TABLE IF EXISTS `wptests_woocommerce_payment_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_payment_tokens` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gateway_id` varchar(200) NOT NULL,
  `token` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` varchar(200) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`token_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_payment_tokens`
--

LOCK TABLES `wptests_woocommerce_payment_tokens` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_payment_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_payment_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_sessions`
--

DROP TABLE IF EXISTS `wptests_woocommerce_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_sessions` (
  `session_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_key` char(32) NOT NULL,
  `session_value` longtext NOT NULL,
  `session_expiry` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_key` (`session_key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_sessions`
--

LOCK TABLES `wptests_woocommerce_sessions` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_shipping_zone_locations`
--

DROP TABLE IF EXISTS `wptests_woocommerce_shipping_zone_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_shipping_zone_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_id` bigint(20) unsigned NOT NULL,
  `location_code` varchar(200) NOT NULL,
  `location_type` varchar(40) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `zone_id` (`zone_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_shipping_zone_locations`
--

LOCK TABLES `wptests_woocommerce_shipping_zone_locations` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_shipping_zone_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_shipping_zone_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_shipping_zone_methods`
--

DROP TABLE IF EXISTS `wptests_woocommerce_shipping_zone_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_shipping_zone_methods` (
  `zone_id` bigint(20) unsigned NOT NULL,
  `instance_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `method_id` varchar(200) NOT NULL,
  `method_order` bigint(20) unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`instance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_shipping_zone_methods`
--

LOCK TABLES `wptests_woocommerce_shipping_zone_methods` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_shipping_zone_methods` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_shipping_zone_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_shipping_zones`
--

DROP TABLE IF EXISTS `wptests_woocommerce_shipping_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_shipping_zones` (
  `zone_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(200) NOT NULL,
  `zone_order` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_shipping_zones`
--

LOCK TABLES `wptests_woocommerce_shipping_zones` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_shipping_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_shipping_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_tax_rate_locations`
--

DROP TABLE IF EXISTS `wptests_woocommerce_tax_rate_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_tax_rate_locations` (
  `location_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `location_code` varchar(200) NOT NULL,
  `tax_rate_id` bigint(20) unsigned NOT NULL,
  `location_type` varchar(40) NOT NULL,
  PRIMARY KEY (`location_id`),
  KEY `tax_rate_id` (`tax_rate_id`),
  KEY `location_type_code` (`location_type`(10),`location_code`(20))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_tax_rate_locations`
--

LOCK TABLES `wptests_woocommerce_tax_rate_locations` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_tax_rate_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_tax_rate_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wptests_woocommerce_tax_rates`
--

DROP TABLE IF EXISTS `wptests_woocommerce_tax_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wptests_woocommerce_tax_rates` (
  `tax_rate_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tax_rate_country` varchar(2) NOT NULL DEFAULT '',
  `tax_rate_state` varchar(200) NOT NULL DEFAULT '',
  `tax_rate` varchar(8) NOT NULL DEFAULT '',
  `tax_rate_name` varchar(200) NOT NULL DEFAULT '',
  `tax_rate_priority` bigint(20) unsigned NOT NULL,
  `tax_rate_compound` int(1) NOT NULL DEFAULT 0,
  `tax_rate_shipping` int(1) NOT NULL DEFAULT 1,
  `tax_rate_order` bigint(20) unsigned NOT NULL,
  `tax_rate_class` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`tax_rate_id`),
  KEY `tax_rate_country` (`tax_rate_country`),
  KEY `tax_rate_state` (`tax_rate_state`(2)),
  KEY `tax_rate_class` (`tax_rate_class`(10)),
  KEY `tax_rate_priority` (`tax_rate_priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wptests_woocommerce_tax_rates`
--

LOCK TABLES `wptests_woocommerce_tax_rates` WRITE;
/*!40000 ALTER TABLE `wptests_woocommerce_tax_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `wptests_woocommerce_tax_rates` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-08 14:03:07
