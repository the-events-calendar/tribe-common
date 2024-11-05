-- MariaDB dump 10.19  Distrib 10.5.23-MariaDB, for debian-linux-gnu (aarch64)
--
-- Host: db    Database: test
-- ------------------------------------------------------
-- Server version	10.7.8-MariaDB-1:10.7.8+maria~ubu2004

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
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
  `args` varchar(191) DEFAULT NULL,
  `schedule` longtext DEFAULT NULL,
  `group_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `last_attempt_gmt` datetime DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `extended_args` varchar(8000) DEFAULT NULL,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT 10,
  PRIMARY KEY (`action_id`),
  KEY `hook` (`hook`),
  KEY `status` (`status`),
  KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  KEY `args` (`args`),
  KEY `group_id` (`group_id`),
  KEY `last_attempt_gmt` (`last_attempt_gmt`),
  KEY `claim_id_status_scheduled_date_gmt` (`claim_id`,`status`,`scheduled_date_gmt`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_actions`
--

LOCK TABLES `wp_actionscheduler_actions` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_actions` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_actions` VALUES (4,'action_scheduler/migration_hook','complete','2022-12-06 11:25:48','2022-12-06 11:25:48','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1670325948;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1670325948;}',1,1,'2023-01-05 13:11:28','2023-01-05 13:11:28',0,NULL,10),(5,'woocommerce_cleanup_draft_orders','failed','2023-01-05 13:11:56','2023-01-05 13:11:56','[]','O:32:\"ActionScheduler_IntervalSchedule\":5:{s:22:\"\0*\0scheduled_timestamp\";i:1672924316;s:18:\"\0*\0first_timestamp\";i:1672924316;s:13:\"\0*\0recurrence\";i:86400;s:49:\"\0ActionScheduler_IntervalSchedule\0start_timestamp\";i:1672924316;s:53:\"\0ActionScheduler_IntervalSchedule\0interval_in_seconds\";i:86400;}',0,1,'2023-01-05 13:11:56','2023-01-05 13:11:56',0,NULL,10),(6,'woocommerce_cleanup_draft_orders','complete','2023-01-06 13:11:56','2023-01-06 13:11:56','[]','O:32:\"ActionScheduler_IntervalSchedule\":5:{s:22:\"\0*\0scheduled_timestamp\";i:1673010716;s:18:\"\0*\0first_timestamp\";i:1672924316;s:13:\"\0*\0recurrence\";i:86400;s:49:\"\0ActionScheduler_IntervalSchedule\0start_timestamp\";i:1673010716;s:53:\"\0ActionScheduler_IntervalSchedule\0interval_in_seconds\";i:86400;}',0,1,'2023-01-09 14:29:21','2023-01-09 14:29:21',0,NULL,10),(7,'woocommerce_cleanup_draft_orders','complete','2023-01-10 14:29:21','2023-01-10 14:29:21','[]','O:32:\"ActionScheduler_IntervalSchedule\":5:{s:22:\"\0*\0scheduled_timestamp\";i:1673360961;s:18:\"\0*\0first_timestamp\";i:1672924316;s:13:\"\0*\0recurrence\";i:86400;s:49:\"\0ActionScheduler_IntervalSchedule\0start_timestamp\";i:1673360961;s:53:\"\0ActionScheduler_IntervalSchedule\0interval_in_seconds\";i:86400;}',0,1,'2023-11-17 13:20:23','2023-11-17 08:20:23',0,NULL,10),(8,'woocommerce_run_product_attribute_lookup_regeneration_callback','complete','2023-11-17 12:51:37','2023-11-17 07:51:37','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700225497;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700225497;}',2,1,'2023-11-17 13:57:26','2023-11-17 08:57:26',0,NULL,10),(9,'woocommerce_run_on_woocommerce_admin_updated','complete','2023-11-17 12:51:36','2023-11-17 07:51:36','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700225496;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700225496;}',3,1,'2023-11-17 13:57:15','2023-11-17 08:57:15',0,NULL,10),(10,'woocommerce_run_update_callback','complete','2023-11-17 13:10:23','2023-11-17 08:10:23','{\"update_callback\":\"wc_update_750_add_columns_to_order_stats_table\"}','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700226623;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700226623;}',2,1,'2023-11-17 13:54:10','2023-11-17 08:54:10',0,NULL,10),(11,'woocommerce_run_update_callback','complete','2023-11-17 13:10:24','2023-11-17 08:10:24','{\"update_callback\":\"wc_update_750_disable_new_product_management_experience\"}','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700226624;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700226624;}',2,1,'2023-11-17 13:54:22','2023-11-17 08:54:22',0,NULL,10),(12,'woocommerce_run_update_callback','complete','2023-11-17 13:10:25','2023-11-17 08:10:25','{\"update_callback\":\"wc_update_770_remove_multichannel_marketing_feature_options\"}','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700226625;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700226625;}',2,1,'2023-11-17 13:54:36','2023-11-17 08:54:36',0,NULL,10),(13,'woocommerce_run_update_callback','complete','2023-11-17 13:10:26','2023-11-17 08:10:26','{\"update_callback\":\"wc_update_810_migrate_transactional_metadata_for_hpos\"}','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700226626;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700226626;}',2,1,'2023-11-17 13:54:48','2023-11-17 08:54:48',0,NULL,10),(14,'woocommerce_update_db_to_current_version','complete','2023-11-17 13:10:27','2023-11-17 08:10:27','{\"version\":\"8.2.2\"}','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700226627;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700226627;}',2,1,'2023-11-17 13:57:37','2023-11-17 08:57:37',0,NULL,10),(15,'woocommerce_cleanup_draft_orders','canceled','2023-11-18 13:20:23','2023-11-18 08:20:23','[]','O:32:\"ActionScheduler_IntervalSchedule\":5:{s:22:\"\0*\0scheduled_timestamp\";i:1700313623;s:18:\"\0*\0first_timestamp\";i:1672924316;s:13:\"\0*\0recurrence\";i:86400;s:49:\"\0ActionScheduler_IntervalSchedule\0start_timestamp\";i:1700313623;s:53:\"\0ActionScheduler_IntervalSchedule\0interval_in_seconds\";i:86400;}',4,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL,10),(17,'woocommerce_cleanup_draft_orders','pending','2023-11-17 13:58:44','2023-11-17 08:58:44','[]','O:32:\"ActionScheduler_IntervalSchedule\":5:{s:22:\"\0*\0scheduled_timestamp\";i:1700229524;s:18:\"\0*\0first_timestamp\";i:1700229524;s:13:\"\0*\0recurrence\";i:86400;s:49:\"\0ActionScheduler_IntervalSchedule\0start_timestamp\";i:1700229524;s:53:\"\0ActionScheduler_IntervalSchedule\0interval_in_seconds\";i:86400;}',4,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL,10),(18,'action_scheduler/migration_hook','pending','2023-11-17 14:05:59','2023-11-17 09:05:59','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1700229959;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1700229959;}',1,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL,10),(19,'woocommerce_run_product_attribute_lookup_regeneration_callback','pending','2024-07-16 19:32:14','2024-07-16 15:32:14','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1721158334;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1721158334;}',2,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL,10),(20,'woocommerce_run_on_woocommerce_admin_updated','pending','2024-07-16 19:32:13','2024-07-16 15:32:13','[]','O:30:\"ActionScheduler_SimpleSchedule\":2:{s:22:\"\0*\0scheduled_timestamp\";i:1721158333;s:41:\"\0ActionScheduler_SimpleSchedule\0timestamp\";i:1721158333;}',3,0,'0000-00-00 00:00:00','0000-00-00 00:00:00',0,NULL,10);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_groups`
--

LOCK TABLES `wp_actionscheduler_groups` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_groups` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_groups` VALUES (1,'action-scheduler-migration'),(2,'woocommerce-db-updates'),(3,'woocommerce-remote-inbox-engine'),(4,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_actionscheduler_logs`
--

LOCK TABLES `wp_actionscheduler_logs` WRITE;
/*!40000 ALTER TABLE `wp_actionscheduler_logs` DISABLE KEYS */;
INSERT INTO `wp_actionscheduler_logs` VALUES (7,4,'action created','2022-12-06 11:24:48','2022-12-06 11:24:48'),(8,4,'action started via Async Request','2023-01-05 13:11:28','2023-01-05 13:11:28'),(9,4,'action complete via Async Request','2023-01-05 13:11:28','2023-01-05 13:11:28'),(10,5,'action created','2023-01-05 13:11:56','2023-01-05 13:11:56'),(11,5,'action started via WP Cron','2023-01-05 13:11:56','2023-01-05 13:11:56'),(12,5,'action failed via WP Cron: Scheduled action for woocommerce_cleanup_draft_orders will not be executed as no callbacks are registered.','2023-01-05 13:11:56','2023-01-05 13:11:56'),(13,6,'action created','2023-01-05 13:11:56','2023-01-05 13:11:56'),(14,6,'action started via Async Request','2023-01-09 14:29:21','2023-01-09 14:29:21'),(15,6,'action complete via Async Request','2023-01-09 14:29:21','2023-01-09 14:29:21'),(16,7,'action created','2023-01-09 14:29:21','2023-01-09 14:29:21'),(17,8,'action created','2023-11-17 12:51:36','2023-11-17 07:51:36'),(18,9,'action created','2023-11-17 12:51:36','2023-11-17 07:51:36'),(19,10,'action created','2023-11-17 13:10:23','2023-11-17 08:10:23'),(20,11,'action created','2023-11-17 13:10:23','2023-11-17 08:10:23'),(21,12,'action created','2023-11-17 13:10:23','2023-11-17 08:10:23'),(22,13,'action created','2023-11-17 13:10:23','2023-11-17 08:10:23'),(23,14,'action created','2023-11-17 13:10:23','2023-11-17 08:10:23'),(24,7,'action started via Admin List Table','2023-11-17 13:20:23','2023-11-17 08:20:23'),(25,7,'action complete via Admin List Table','2023-11-17 13:20:23','2023-11-17 08:20:23'),(26,15,'action created','2023-11-17 13:20:23','2023-11-17 08:20:23'),(27,10,'action started via Admin List Table','2023-11-17 13:54:10','2023-11-17 08:54:10'),(28,10,'action complete via Admin List Table','2023-11-17 13:54:10','2023-11-17 08:54:10'),(29,11,'action started via Admin List Table','2023-11-17 13:54:22','2023-11-17 08:54:22'),(30,11,'action complete via Admin List Table','2023-11-17 13:54:22','2023-11-17 08:54:22'),(31,12,'action started via Admin List Table','2023-11-17 13:54:36','2023-11-17 08:54:36'),(32,12,'action complete via Admin List Table','2023-11-17 13:54:36','2023-11-17 08:54:36'),(33,13,'action started via Admin List Table','2023-11-17 13:54:48','2023-11-17 08:54:48'),(34,13,'action complete via Admin List Table','2023-11-17 13:54:48','2023-11-17 08:54:48'),(35,9,'action started via Admin List Table','2023-11-17 13:57:14','2023-11-17 08:57:14'),(36,9,'action complete via Admin List Table','2023-11-17 13:57:15','2023-11-17 08:57:15'),(37,8,'action started via Admin List Table','2023-11-17 13:57:26','2023-11-17 08:57:26'),(38,8,'action complete via Admin List Table','2023-11-17 13:57:26','2023-11-17 08:57:26'),(39,14,'action started via Admin List Table','2023-11-17 13:57:37','2023-11-17 08:57:37'),(40,14,'action complete via Admin List Table','2023-11-17 13:57:37','2023-11-17 08:57:37'),(41,15,'action canceled','2023-11-17 13:58:07','2023-11-17 08:58:07'),(43,17,'action created','2023-11-17 13:58:44','2023-11-17 08:58:44'),(44,18,'action created','2023-11-17 14:04:59','2023-11-17 09:04:59'),(45,19,'action created','2024-07-16 19:32:13','2024-07-16 15:32:13'),(46,20,'action created','2024-07-16 19:32:13','2024-07-16 15:32:13');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  KEY `comment_author_email` (`comment_author_email`(10)),
  KEY `woo_idx_comment_type` (`comment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=1112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (1,'siteurl','http://wordpress.test','yes'),(2,'home','http://wordpress.test','yes'),(3,'blogname','Wordpress Test','yes'),(4,'blogdescription','','yes'),(5,'users_can_register','0','yes'),(6,'admin_email','admin@wordpress.test','yes'),(7,'start_of_week','1','yes'),(8,'use_balanceTags','0','yes'),(9,'use_smilies','1','yes'),(10,'require_name_email','1','yes'),(11,'comments_notify','1','yes'),(12,'posts_per_rss','10','yes'),(13,'rss_use_excerpt','0','yes'),(14,'mailserver_url','mail.example.com','yes'),(15,'mailserver_login','login@example.com','yes'),(16,'mailserver_pass','password','yes'),(17,'mailserver_port','110','yes'),(18,'default_category','1','yes'),(19,'default_comment_status','open','yes'),(20,'default_ping_status','open','yes'),(21,'default_pingback_flag','1','yes'),(22,'posts_per_page','10','yes'),(23,'date_format','F j, Y','yes'),(24,'time_format','g:i a','yes'),(25,'links_updated_date_format','F j, Y g:i a','yes'),(26,'comment_moderation','0','yes'),(27,'moderation_notify','1','yes'),(28,'permalink_structure','/%year%/%monthnum%/%day%/%postname%/','yes'),(30,'hack_file','0','yes'),(31,'blog_charset','UTF-8','yes'),(32,'moderation_keys','','no'),(33,'active_plugins','a:5:{i:0;s:49:\"easy-digital-downloads/easy-digital-downloads.php\";i:1;s:41:\"event-tickets-plus/event-tickets-plus.php\";i:2;s:31:\"event-tickets/event-tickets.php\";i:3;s:43:\"the-events-calendar/the-events-calendar.php\";i:4;s:27:\"woocommerce/woocommerce.php\";}','yes'),(34,'category_base','','yes'),(35,'ping_sites','http://rpc.pingomatic.com/','yes'),(36,'comment_max_links','2','yes'),(37,'gmt_offset','0','yes'),(38,'default_email_category','1','yes'),(39,'recently_edited','','no'),(40,'template','twentyseventeen','yes'),(41,'stylesheet','twentyseventeen','yes'),(44,'comment_registration','0','yes'),(45,'html_type','text/html','yes'),(46,'use_trackback','0','yes'),(47,'default_role','subscriber','yes'),(48,'db_version','56657','yes'),(49,'uploads_use_yearmonth_folders','1','yes'),(50,'upload_path','','yes'),(51,'blog_public','1','yes'),(52,'default_link_category','2','yes'),(53,'show_on_front','posts','yes'),(54,'tag_base','','yes'),(55,'show_avatars','1','yes'),(56,'avatar_rating','G','yes'),(57,'upload_url_path','','yes'),(58,'thumbnail_size_w','150','yes'),(59,'thumbnail_size_h','150','yes'),(60,'thumbnail_crop','1','yes'),(61,'medium_size_w','300','yes'),(62,'medium_size_h','300','yes'),(63,'avatar_default','mystery','yes'),(64,'large_size_w','1024','yes'),(65,'large_size_h','1024','yes'),(66,'image_default_link_type','none','yes'),(67,'image_default_size','','yes'),(68,'image_default_align','','yes'),(69,'close_comments_for_old_posts','0','yes'),(70,'close_comments_days_old','14','yes'),(71,'thread_comments','1','yes'),(72,'thread_comments_depth','5','yes'),(73,'page_comments','0','yes'),(74,'comments_per_page','50','yes'),(75,'default_comments_page','newest','yes'),(76,'comment_order','asc','yes'),(77,'sticky_posts','a:0:{}','yes'),(78,'widget_categories','a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(79,'widget_text','a:0:{}','yes'),(80,'widget_rss','a:0:{}','yes'),(81,'uninstall_plugins','a:3:{s:43:\"the-events-calendar/the-events-calendar.php\";a:2:{i:0;s:8:\"Freemius\";i:1;s:22:\"_uninstall_plugin_hook\";}s:31:\"event-tickets/event-tickets.php\";a:2:{i:0;s:8:\"Freemius\";i:1;s:22:\"_uninstall_plugin_hook\";}s:35:\"event-automator/event-automator.php\";s:23:\"tec_automator_uninstall\";}','no'),(82,'timezone_string','America/New_York','yes'),(83,'page_for_posts','0','yes'),(84,'page_on_front','0','yes'),(85,'default_post_format','0','yes'),(86,'link_manager_enabled','0','yes'),(87,'finished_splitting_shared_terms','1','yes'),(88,'site_icon','0','yes'),(89,'medium_large_size_w','768','yes'),(90,'medium_large_size_h','0','yes'),(91,'initial_db_version','38590','yes'),(92,'wp_user_roles','a:10:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:199:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:31:\"read_private_aggregator-records\";b:1;s:23:\"edit_aggregator-records\";b:1;s:30:\"edit_others_aggregator-records\";b:1;s:31:\"edit_private_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:32:\"delete_others_aggregator-records\";b:1;s:33:\"delete_private_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;s:17:\"view_shop_reports\";b:1;s:24:\"view_shop_sensitive_data\";b:1;s:19:\"export_shop_reports\";b:1;s:21:\"manage_shop_discounts\";b:1;s:20:\"manage_shop_settings\";b:1;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:18:\"view_product_stats\";b:1;s:15:\"import_products\";b:1;s:17:\"edit_shop_payment\";b:1;s:17:\"read_shop_payment\";b:1;s:19:\"delete_shop_payment\";b:1;s:18:\"edit_shop_payments\";b:1;s:25:\"edit_others_shop_payments\";b:1;s:21:\"publish_shop_payments\";b:1;s:26:\"read_private_shop_payments\";b:1;s:20:\"delete_shop_payments\";b:1;s:28:\"delete_private_shop_payments\";b:1;s:30:\"delete_published_shop_payments\";b:1;s:27:\"delete_others_shop_payments\";b:1;s:26:\"edit_private_shop_payments\";b:1;s:28:\"edit_published_shop_payments\";b:1;s:25:\"manage_shop_payment_terms\";b:1;s:23:\"edit_shop_payment_terms\";b:1;s:25:\"delete_shop_payment_terms\";b:1;s:25:\"assign_shop_payment_terms\";b:1;s:23:\"view_shop_payment_stats\";b:1;s:20:\"import_shop_payments\";b:1;s:18:\"edit_shop_discount\";b:1;s:18:\"read_shop_discount\";b:1;s:20:\"delete_shop_discount\";b:1;s:19:\"edit_shop_discounts\";b:1;s:26:\"edit_others_shop_discounts\";b:1;s:22:\"publish_shop_discounts\";b:1;s:27:\"read_private_shop_discounts\";b:1;s:21:\"delete_shop_discounts\";b:1;s:29:\"delete_private_shop_discounts\";b:1;s:31:\"delete_published_shop_discounts\";b:1;s:28:\"delete_others_shop_discounts\";b:1;s:27:\"edit_private_shop_discounts\";b:1;s:29:\"edit_published_shop_discounts\";b:1;s:26:\"manage_shop_discount_terms\";b:1;s:24:\"edit_shop_discount_terms\";b:1;s:26:\"delete_shop_discount_terms\";b:1;s:26:\"assign_shop_discount_terms\";b:1;s:24:\"view_shop_discount_stats\";b:1;s:21:\"import_shop_discounts\";b:1;s:18:\"manage_woocommerce\";b:1;s:24:\"view_woocommerce_reports\";b:1;s:15:\"edit_shop_order\";b:1;s:15:\"read_shop_order\";b:1;s:17:\"delete_shop_order\";b:1;s:16:\"edit_shop_orders\";b:1;s:23:\"edit_others_shop_orders\";b:1;s:19:\"publish_shop_orders\";b:1;s:24:\"read_private_shop_orders\";b:1;s:18:\"delete_shop_orders\";b:1;s:26:\"delete_private_shop_orders\";b:1;s:28:\"delete_published_shop_orders\";b:1;s:25:\"delete_others_shop_orders\";b:1;s:24:\"edit_private_shop_orders\";b:1;s:26:\"edit_published_shop_orders\";b:1;s:23:\"manage_shop_order_terms\";b:1;s:21:\"edit_shop_order_terms\";b:1;s:23:\"delete_shop_order_terms\";b:1;s:23:\"assign_shop_order_terms\";b:1;s:16:\"edit_shop_coupon\";b:1;s:16:\"read_shop_coupon\";b:1;s:18:\"delete_shop_coupon\";b:1;s:17:\"edit_shop_coupons\";b:1;s:24:\"edit_others_shop_coupons\";b:1;s:20:\"publish_shop_coupons\";b:1;s:25:\"read_private_shop_coupons\";b:1;s:19:\"delete_shop_coupons\";b:1;s:27:\"delete_private_shop_coupons\";b:1;s:29:\"delete_published_shop_coupons\";b:1;s:26:\"delete_others_shop_coupons\";b:1;s:25:\"edit_private_shop_coupons\";b:1;s:27:\"edit_published_shop_coupons\";b:1;s:24:\"manage_shop_coupon_terms\";b:1;s:22:\"edit_shop_coupon_terms\";b:1;s:24:\"delete_shop_coupon_terms\";b:1;s:24:\"assign_shop_coupon_terms\";b:1;s:25:\"read_private_tribe_events\";b:1;s:17:\"edit_tribe_events\";b:1;s:24:\"edit_others_tribe_events\";b:1;s:25:\"edit_private_tribe_events\";b:1;s:27:\"edit_published_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:26:\"delete_others_tribe_events\";b:1;s:27:\"delete_private_tribe_events\";b:1;s:29:\"delete_published_tribe_events\";b:1;s:20:\"publish_tribe_events\";b:1;s:25:\"read_private_tribe_venues\";b:1;s:17:\"edit_tribe_venues\";b:1;s:24:\"edit_others_tribe_venues\";b:1;s:25:\"edit_private_tribe_venues\";b:1;s:27:\"edit_published_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:26:\"delete_others_tribe_venues\";b:1;s:27:\"delete_private_tribe_venues\";b:1;s:29:\"delete_published_tribe_venues\";b:1;s:20:\"publish_tribe_venues\";b:1;s:29:\"read_private_tribe_organizers\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:28:\"edit_others_tribe_organizers\";b:1;s:29:\"edit_private_tribe_organizers\";b:1;s:31:\"edit_published_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:30:\"delete_others_tribe_organizers\";b:1;s:31:\"delete_private_tribe_organizers\";b:1;s:33:\"delete_published_tribe_organizers\";b:1;s:24:\"publish_tribe_organizers\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:74:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:31:\"read_private_aggregator-records\";b:1;s:23:\"edit_aggregator-records\";b:1;s:30:\"edit_others_aggregator-records\";b:1;s:31:\"edit_private_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:32:\"delete_others_aggregator-records\";b:1;s:33:\"delete_private_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;s:25:\"read_private_tribe_events\";b:1;s:17:\"edit_tribe_events\";b:1;s:24:\"edit_others_tribe_events\";b:1;s:25:\"edit_private_tribe_events\";b:1;s:27:\"edit_published_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:26:\"delete_others_tribe_events\";b:1;s:27:\"delete_private_tribe_events\";b:1;s:29:\"delete_published_tribe_events\";b:1;s:20:\"publish_tribe_events\";b:1;s:25:\"read_private_tribe_venues\";b:1;s:17:\"edit_tribe_venues\";b:1;s:24:\"edit_others_tribe_venues\";b:1;s:25:\"edit_private_tribe_venues\";b:1;s:27:\"edit_published_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:26:\"delete_others_tribe_venues\";b:1;s:27:\"delete_private_tribe_venues\";b:1;s:29:\"delete_published_tribe_venues\";b:1;s:20:\"publish_tribe_venues\";b:1;s:29:\"read_private_tribe_organizers\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:28:\"edit_others_tribe_organizers\";b:1;s:29:\"edit_private_tribe_organizers\";b:1;s:31:\"edit_published_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:30:\"delete_others_tribe_organizers\";b:1;s:31:\"delete_private_tribe_organizers\";b:1;s:33:\"delete_published_tribe_organizers\";b:1;s:24:\"publish_tribe_organizers\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:30:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:23:\"edit_aggregator-records\";b:1;s:33:\"edit_published_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:35:\"delete_published_aggregator-records\";b:1;s:26:\"publish_aggregator-records\";b:1;s:17:\"edit_tribe_events\";b:1;s:27:\"edit_published_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:29:\"delete_published_tribe_events\";b:1;s:20:\"publish_tribe_events\";b:1;s:17:\"edit_tribe_venues\";b:1;s:27:\"edit_published_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:29:\"delete_published_tribe_venues\";b:1;s:20:\"publish_tribe_venues\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:31:\"edit_published_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;s:33:\"delete_published_tribe_organizers\";b:1;s:24:\"publish_tribe_organizers\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:13:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:23:\"edit_aggregator-records\";b:1;s:25:\"delete_aggregator-records\";b:1;s:17:\"edit_tribe_events\";b:1;s:19:\"delete_tribe_events\";b:1;s:17:\"edit_tribe_venues\";b:1;s:19:\"delete_tribe_venues\";b:1;s:21:\"edit_tribe_organizers\";b:1;s:23:\"delete_tribe_organizers\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}s:12:\"shop_manager\";a:2:{s:4:\"name\";s:12:\"Shop Manager\";s:12:\"capabilities\";a:126:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:1;s:12:\"delete_posts\";b:1;s:15:\"unfiltered_html\";b:1;s:12:\"upload_files\";b:1;s:6:\"export\";b:1;s:6:\"import\";b:1;s:19:\"delete_others_pages\";b:1;s:19:\"delete_others_posts\";b:1;s:12:\"delete_pages\";b:1;s:20:\"delete_private_pages\";b:1;s:20:\"delete_private_posts\";b:1;s:22:\"delete_published_pages\";b:1;s:22:\"delete_published_posts\";b:1;s:17:\"edit_others_pages\";b:1;s:17:\"edit_others_posts\";b:1;s:10:\"edit_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"edit_private_posts\";b:1;s:20:\"edit_published_pages\";b:1;s:20:\"edit_published_posts\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:17:\"moderate_comments\";b:1;s:13:\"publish_pages\";b:1;s:13:\"publish_posts\";b:1;s:18:\"read_private_pages\";b:1;s:18:\"read_private_posts\";b:1;s:17:\"view_shop_reports\";b:1;s:24:\"view_shop_sensitive_data\";b:1;s:19:\"export_shop_reports\";b:1;s:20:\"manage_shop_settings\";b:1;s:21:\"manage_shop_discounts\";b:1;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:18:\"view_product_stats\";b:1;s:15:\"import_products\";b:1;s:17:\"edit_shop_payment\";b:1;s:17:\"read_shop_payment\";b:1;s:19:\"delete_shop_payment\";b:1;s:18:\"edit_shop_payments\";b:1;s:25:\"edit_others_shop_payments\";b:1;s:21:\"publish_shop_payments\";b:1;s:26:\"read_private_shop_payments\";b:1;s:20:\"delete_shop_payments\";b:1;s:28:\"delete_private_shop_payments\";b:1;s:30:\"delete_published_shop_payments\";b:1;s:27:\"delete_others_shop_payments\";b:1;s:26:\"edit_private_shop_payments\";b:1;s:28:\"edit_published_shop_payments\";b:1;s:25:\"manage_shop_payment_terms\";b:1;s:23:\"edit_shop_payment_terms\";b:1;s:25:\"delete_shop_payment_terms\";b:1;s:25:\"assign_shop_payment_terms\";b:1;s:23:\"view_shop_payment_stats\";b:1;s:20:\"import_shop_payments\";b:1;s:18:\"edit_shop_discount\";b:1;s:18:\"read_shop_discount\";b:1;s:20:\"delete_shop_discount\";b:1;s:19:\"edit_shop_discounts\";b:1;s:26:\"edit_others_shop_discounts\";b:1;s:22:\"publish_shop_discounts\";b:1;s:27:\"read_private_shop_discounts\";b:1;s:21:\"delete_shop_discounts\";b:1;s:29:\"delete_private_shop_discounts\";b:1;s:31:\"delete_published_shop_discounts\";b:1;s:28:\"delete_others_shop_discounts\";b:1;s:27:\"edit_private_shop_discounts\";b:1;s:29:\"edit_published_shop_discounts\";b:1;s:26:\"manage_shop_discount_terms\";b:1;s:24:\"edit_shop_discount_terms\";b:1;s:26:\"delete_shop_discount_terms\";b:1;s:26:\"assign_shop_discount_terms\";b:1;s:24:\"view_shop_discount_stats\";b:1;s:21:\"import_shop_discounts\";b:1;s:18:\"manage_woocommerce\";b:1;s:24:\"view_woocommerce_reports\";b:1;s:15:\"edit_shop_order\";b:1;s:15:\"read_shop_order\";b:1;s:17:\"delete_shop_order\";b:1;s:16:\"edit_shop_orders\";b:1;s:23:\"edit_others_shop_orders\";b:1;s:19:\"publish_shop_orders\";b:1;s:24:\"read_private_shop_orders\";b:1;s:18:\"delete_shop_orders\";b:1;s:26:\"delete_private_shop_orders\";b:1;s:28:\"delete_published_shop_orders\";b:1;s:25:\"delete_others_shop_orders\";b:1;s:24:\"edit_private_shop_orders\";b:1;s:26:\"edit_published_shop_orders\";b:1;s:23:\"manage_shop_order_terms\";b:1;s:21:\"edit_shop_order_terms\";b:1;s:23:\"delete_shop_order_terms\";b:1;s:23:\"assign_shop_order_terms\";b:1;s:16:\"edit_shop_coupon\";b:1;s:16:\"read_shop_coupon\";b:1;s:18:\"delete_shop_coupon\";b:1;s:17:\"edit_shop_coupons\";b:1;s:24:\"edit_others_shop_coupons\";b:1;s:20:\"publish_shop_coupons\";b:1;s:25:\"read_private_shop_coupons\";b:1;s:19:\"delete_shop_coupons\";b:1;s:27:\"delete_private_shop_coupons\";b:1;s:29:\"delete_published_shop_coupons\";b:1;s:26:\"delete_others_shop_coupons\";b:1;s:25:\"edit_private_shop_coupons\";b:1;s:27:\"edit_published_shop_coupons\";b:1;s:24:\"manage_shop_coupon_terms\";b:1;s:22:\"edit_shop_coupon_terms\";b:1;s:24:\"delete_shop_coupon_terms\";b:1;s:24:\"assign_shop_coupon_terms\";b:1;}}s:15:\"shop_accountant\";a:2:{s:4:\"name\";s:15:\"Shop Accountant\";s:12:\"capabilities\";a:8:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"delete_posts\";b:0;s:13:\"edit_products\";b:1;s:21:\"read_private_products\";b:1;s:17:\"view_shop_reports\";b:1;s:19:\"export_shop_reports\";b:1;s:18:\"edit_shop_payments\";b:1;}}s:11:\"shop_worker\";a:2:{s:4:\"name\";s:11:\"Shop Worker\";s:12:\"capabilities\";a:61:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"upload_files\";b:1;s:12:\"delete_posts\";b:0;s:12:\"edit_product\";b:1;s:12:\"read_product\";b:1;s:14:\"delete_product\";b:1;s:13:\"edit_products\";b:1;s:20:\"edit_others_products\";b:1;s:16:\"publish_products\";b:1;s:21:\"read_private_products\";b:1;s:15:\"delete_products\";b:1;s:23:\"delete_private_products\";b:1;s:25:\"delete_published_products\";b:1;s:22:\"delete_others_products\";b:1;s:21:\"edit_private_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"manage_product_terms\";b:1;s:18:\"edit_product_terms\";b:1;s:20:\"delete_product_terms\";b:1;s:20:\"assign_product_terms\";b:1;s:18:\"view_product_stats\";b:1;s:15:\"import_products\";b:1;s:17:\"edit_shop_payment\";b:1;s:17:\"read_shop_payment\";b:1;s:19:\"delete_shop_payment\";b:1;s:18:\"edit_shop_payments\";b:1;s:25:\"edit_others_shop_payments\";b:1;s:21:\"publish_shop_payments\";b:1;s:26:\"read_private_shop_payments\";b:1;s:20:\"delete_shop_payments\";b:1;s:28:\"delete_private_shop_payments\";b:1;s:30:\"delete_published_shop_payments\";b:1;s:27:\"delete_others_shop_payments\";b:1;s:26:\"edit_private_shop_payments\";b:1;s:28:\"edit_published_shop_payments\";b:1;s:25:\"manage_shop_payment_terms\";b:1;s:23:\"edit_shop_payment_terms\";b:1;s:25:\"delete_shop_payment_terms\";b:1;s:25:\"assign_shop_payment_terms\";b:1;s:23:\"view_shop_payment_stats\";b:1;s:20:\"import_shop_payments\";b:1;s:18:\"edit_shop_discount\";b:1;s:18:\"read_shop_discount\";b:1;s:20:\"delete_shop_discount\";b:1;s:19:\"edit_shop_discounts\";b:1;s:26:\"edit_others_shop_discounts\";b:1;s:22:\"publish_shop_discounts\";b:1;s:27:\"read_private_shop_discounts\";b:1;s:21:\"delete_shop_discounts\";b:1;s:29:\"delete_private_shop_discounts\";b:1;s:31:\"delete_published_shop_discounts\";b:1;s:28:\"delete_others_shop_discounts\";b:1;s:27:\"edit_private_shop_discounts\";b:1;s:29:\"edit_published_shop_discounts\";b:1;s:26:\"manage_shop_discount_terms\";b:1;s:24:\"edit_shop_discount_terms\";b:1;s:26:\"delete_shop_discount_terms\";b:1;s:26:\"assign_shop_discount_terms\";b:1;s:24:\"view_shop_discount_stats\";b:1;s:21:\"import_shop_discounts\";b:1;}}s:11:\"shop_vendor\";a:2:{s:4:\"name\";s:11:\"Shop Vendor\";s:12:\"capabilities\";a:11:{s:4:\"read\";b:1;s:10:\"edit_posts\";b:0;s:12:\"upload_files\";b:1;s:12:\"delete_posts\";b:0;s:12:\"edit_product\";b:1;s:13:\"edit_products\";b:1;s:14:\"delete_product\";b:1;s:15:\"delete_products\";b:1;s:16:\"publish_products\";b:1;s:23:\"edit_published_products\";b:1;s:20:\"assign_product_terms\";b:1;}}s:8:\"customer\";a:2:{s:4:\"name\";s:8:\"Customer\";s:12:\"capabilities\";a:1:{s:4:\"read\";b:1;}}}','yes'),(93,'fresh_site','0','yes'),(94,'widget_search','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(95,'widget_recent-posts','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(96,'widget_recent-comments','a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}','yes'),(97,'widget_archives','a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}','yes'),(98,'widget_meta','a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}','yes'),(99,'sidebars_widgets','a:5:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:6:{i:0;s:8:\"search-2\";i:1;s:14:\"recent-posts-2\";i:2;s:17:\"recent-comments-2\";i:3;s:10:\"archives-2\";i:4;s:12:\"categories-2\";i:5;s:6:\"meta-2\";}s:9:\"sidebar-2\";a:0:{}s:9:\"sidebar-3\";a:0:{}s:13:\"array_version\";i:3;}','yes'),(100,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(101,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(102,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(103,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(104,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(105,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(106,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(107,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(108,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(109,'cron','a:27:{i:1673274618;a:1:{s:26:\"action_scheduler_run_queue\";a:1:{s:32:\"0d04ed39571b55704c122d726248bbac\";a:3:{s:8:\"schedule\";s:12:\"every_minute\";s:4:\"args\";a:1:{i:0;s:7:\"WP Cron\";}s:8:\"interval\";i:60;}}}i:1673275404;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1673277103;a:1:{s:30:\"edds_cleanup_rate_limiting_log\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1673277119;a:1:{s:33:\"wc_admin_process_orders_milestone\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1673277183;a:1:{s:29:\"wc_admin_unsnooze_admin_notes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1673300624;a:1:{s:21:\"wp_update_user_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1673313245;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1673343804;a:2:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:16:\"tribe_daily_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1673343824;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1673343826;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1673349849;a:1:{s:24:\"tribe_common_log_cleanup\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1673360959;a:1:{s:14:\"wc_admin_daily\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1673516604;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1700225491;a:2:{s:20:\"jetpack_clean_nonces\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:20:\"jetpack_v2_heartbeat\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1700225506;a:3:{s:30:\"generate_category_lookup_table\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}s:27:\"edd_weekly_scheduled_events\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}s:26:\"edd_daily_scheduled_events\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1700225536;a:1:{s:46:\"tec_tickets_update_glance_item_attendee_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1700227553;a:1:{s:30:\"wp_delete_temp_updater_backups\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1700230951;a:1:{s:30:\"tribe_schedule_transient_purge\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1700485200;a:1:{s:22:\"edd_email_summary_cron\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1721158332;a:1:{s:26:\"tribe_tickets_migrate_4_12\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1721158343;a:2:{s:33:\"woocommerce_cleanup_personal_data\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:30:\"woocommerce_tracker_send_event\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1721158393;a:1:{s:25:\"woocommerce_geoip_updater\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:11:\"fifteendays\";s:4:\"args\";a:0:{}s:8:\"interval\";i:1296000;}}}i:1721161933;a:1:{s:32:\"woocommerce_cancel_unpaid_orders\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1721169133;a:2:{s:24:\"woocommerce_cleanup_logs\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:31:\"woocommerce_cleanup_rate_limits\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1721179933;a:1:{s:28:\"woocommerce_cleanup_sessions\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1721188800;a:1:{s:27:\"woocommerce_scheduled_sales\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}s:7:\"version\";i:2;}','yes'),(110,'theme_mods_twentyseventeen','a:1:{s:18:\"custom_css_post_id\";i:-1;}','yes'),(123,'tribe_last_updated_option','1727267593.5199','yes'),(124,'tribe_events_calendar_options','a:38:{s:8:\"did_init\";b:1;s:19:\"tribeEventsTemplate\";s:0:\"\";s:16:\"tribeEnableViews\";a:3:{i:0;s:4:\"list\";i:1;s:5:\"month\";i:2;s:3:\"day\";}s:10:\"viewOption\";s:4:\"list\";s:14:\"schema-version\";s:7:\"6.6.4.2\";s:21:\"previous_ecp_versions\";a:7:{i:0;s:1:\"0\";i:1;s:5:\"6.0.2\";i:2;s:7:\"6.0.3.1\";i:3;s:5:\"6.0.5\";i:4;s:7:\"6.0.6.2\";i:5;s:5:\"6.2.7\";i:6;s:5:\"6.2.8\";}s:18:\"latest_ecp_version\";s:7:\"6.6.4.2\";s:18:\"dateWithYearFormat\";s:6:\"F j, Y\";s:24:\"recurrenceMaxMonthsAfter\";i:60;s:22:\"google_maps_js_api_key\";s:39:\"AIzaSyDNsicAsP6-VuGtAb1O9riI3oc_NOb7IOU\";s:39:\"last-update-message-the-events-calendar\";s:5:\"6.2.8\";s:25:\"ticket-enabled-post-types\";a:2:{i:0;s:12:\"tribe_events\";i:1;s:4:\"page\";}s:28:\"event-tickets-schema-version\";s:8:\"5.13.3.1\";s:31:\"previous_event_tickets_versions\";a:6:{i:0;s:1:\"0\";i:1;s:5:\"5.5.4\";i:2;s:5:\"5.5.5\";i:3;s:7:\"5.6.8.1\";i:4;s:5:\"5.7.0\";i:5;s:6:\"5.12.0\";}s:28:\"latest_event_tickets_version\";s:8:\"5.13.3.1\";s:29:\"tribe_tickets_migrate_offset_\";s:8:\"complete\";s:33:\"last-update-message-event-tickets\";s:5:\"5.5.4\";s:36:\"previous_event_tickets_plus_versions\";a:5:{i:0;s:1:\"0\";i:1;s:5:\"5.6.4\";i:2;s:5:\"5.7.7\";i:3;s:5:\"5.8.0\";i:4;s:5:\"6.0.0\";}s:33:\"latest_event_tickets_plus_version\";s:5:\"6.0.2\";s:33:\"event-tickets-plus-schema-version\";s:5:\"6.0.2\";s:31:\"tickets-plus-qr-options-api-key\";s:8:\"fb89abb8\";s:24:\"tickets_commerce_enabled\";b:1;s:30:\"tickets-commerce-checkout-page\";s:2:\"16\";s:29:\"tickets-commerce-success-page\";s:2:\"17\";s:24:\"tickets-commerce-sandbox\";b:1;s:31:\"tickets-commerce-stock-handling\";s:7:\"pending\";s:30:\"tickets-commerce-currency-code\";s:3:\"USD\";s:34:\"tickets-commerce-currency-position\";s:6:\"prefix\";s:48:\"tickets-commerce-confirmation-email-sender-email\";s:20:\"admin@wordpress.test\";s:47:\"tickets-commerce-confirmation-email-sender-name\";s:5:\"admin\";s:43:\"tickets-commerce-confirmation-email-subject\";s:17:\"You have tickets!\";s:13:\"earliest_date\";s:19:\"2019-01-01 15:00:00\";s:21:\"earliest_date_markers\";a:1:{i:0;i:18;}s:11:\"latest_date\";s:19:\"2019-01-01 18:00:00\";s:19:\"latest_date_markers\";a:1:{i:0;i:18;}s:30:\"event-automator-schema-version\";s:5:\"1.7.0\";s:13:\"opt-in-status\";b:0;s:43:\"tec_tickets_commerce_stripe_webhook_version\";s:8:\"5.13.3.1\";}','yes'),(125,'action_scheduler_hybrid_store_demarkation','1','yes'),(126,'schema-ActionScheduler_StoreSchema','7.0.1700225495','yes'),(127,'schema-ActionScheduler_LoggerSchema','3.0.1666777404','yes'),(130,'tribe_last_save_post','1727267593.52','yes'),(131,'widget_block','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(132,'widget_tribe-widget-events-list','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(135,'tec_ct1_events_table_schema_version','1.0.1','yes'),(136,'tec_ct1_occurrences_table_schema_version','1.0.2','yes'),(137,'tec_ct1_migration_state','a:3:{s:18:\"complete_timestamp\";N;s:5:\"phase\";s:22:\"migration-not-required\";s:19:\"preview_unsupported\";b:0;}','yes'),(140,'tribe_last_generate_rewrite_rules','1727267593.4878','yes'),(142,'action_scheduler_lock_async-request-runner','66f402e4ad0f44.69465166|1727267616','yes'),(144,'fs_active_plugins','O:8:\"stdClass\":2:{s:7:\"plugins\";a:0:{}s:7:\"abspath\";s:54:\"/Users/brianjessee/Local Sites/tribe/tests/app/public/\";}','yes'),(145,'fs_debug_mode','','yes'),(146,'fs_accounts','a:6:{s:21:\"id_slug_type_path_map\";a:2:{i:3069;a:3:{s:4:\"slug\";s:19:\"the-events-calendar\";s:4:\"type\";s:6:\"plugin\";s:4:\"path\";s:43:\"the-events-calendar/the-events-calendar.php\";}i:3841;a:3:{s:4:\"slug\";s:13:\"event-tickets\";s:4:\"type\";s:6:\"plugin\";s:4:\"path\";s:31:\"event-tickets/event-tickets.php\";}}s:11:\"plugin_data\";a:2:{s:19:\"the-events-calendar\";a:16:{s:16:\"plugin_main_file\";O:8:\"stdClass\":1:{s:4:\"path\";s:43:\"the-events-calendar/the-events-calendar.php\";}s:20:\"is_network_activated\";b:0;s:17:\"install_timestamp\";i:1666777406;s:17:\"was_plugin_loaded\";b:1;s:21:\"is_plugin_new_install\";b:0;s:16:\"sdk_last_version\";N;s:11:\"sdk_version\";s:5:\"2.4.4\";s:16:\"sdk_upgrade_mode\";b:1;s:18:\"sdk_downgrade_mode\";b:0;s:19:\"plugin_last_version\";s:5:\"6.0.5\";s:14:\"plugin_version\";s:7:\"6.0.6.2\";s:19:\"plugin_upgrade_mode\";b:1;s:21:\"plugin_downgrade_mode\";b:0;s:17:\"connectivity_test\";a:6:{s:12:\"is_connected\";b:1;s:4:\"host\";s:14:\"wordpress.test\";s:9:\"server_ip\";s:3:\"::1\";s:9:\"is_active\";b:1;s:9:\"timestamp\";i:1666777406;s:7:\"version\";s:5:\"6.0.2\";}s:15:\"prev_is_premium\";b:0;s:12:\"is_anonymous\";a:3:{s:2:\"is\";b:1;s:9:\"timestamp\";i:1667820280;s:7:\"version\";s:7:\"6.0.3.1\";}}s:13:\"event-tickets\";a:16:{s:16:\"plugin_main_file\";O:8:\"stdClass\":1:{s:4:\"path\";s:31:\"event-tickets/event-tickets.php\";}s:20:\"is_network_activated\";b:0;s:17:\"install_timestamp\";i:1670232855;s:17:\"was_plugin_loaded\";b:1;s:21:\"is_plugin_new_install\";b:0;s:16:\"sdk_last_version\";N;s:11:\"sdk_version\";s:5:\"2.4.4\";s:16:\"sdk_upgrade_mode\";b:1;s:18:\"sdk_downgrade_mode\";b:0;s:19:\"plugin_last_version\";s:5:\"5.5.4\";s:14:\"plugin_version\";s:5:\"5.5.5\";s:19:\"plugin_upgrade_mode\";b:1;s:21:\"plugin_downgrade_mode\";b:0;s:17:\"connectivity_test\";a:6:{s:12:\"is_connected\";b:1;s:4:\"host\";s:14:\"wordpress.test\";s:9:\"server_ip\";s:9:\"127.0.0.1\";s:9:\"is_active\";b:1;s:9:\"timestamp\";i:1670232855;s:7:\"version\";s:5:\"5.5.4\";}s:15:\"prev_is_premium\";b:0;s:12:\"is_anonymous\";a:3:{s:2:\"is\";b:1;s:9:\"timestamp\";i:1670232858;s:7:\"version\";s:5:\"5.5.4\";}}}s:13:\"file_slug_map\";a:2:{s:43:\"the-events-calendar/the-events-calendar.php\";s:19:\"the-events-calendar\";s:31:\"event-tickets/event-tickets.php\";s:13:\"event-tickets\";}s:7:\"plugins\";a:2:{s:19:\"the-events-calendar\";O:9:\"FS_Plugin\":23:{s:16:\"parent_plugin_id\";N;s:5:\"title\";s:19:\"The Events Calendar\";s:4:\"slug\";s:19:\"the-events-calendar\";s:12:\"premium_slug\";s:27:\"the-events-calendar-premium\";s:4:\"type\";s:6:\"plugin\";s:20:\"affiliate_moderation\";b:0;s:19:\"is_wp_org_compliant\";b:1;s:22:\"premium_releases_count\";N;s:4:\"file\";s:43:\"the-events-calendar/the-events-calendar.php\";s:7:\"version\";s:7:\"6.0.6.2\";s:11:\"auto_update\";N;s:4:\"info\";N;s:10:\"is_premium\";b:0;s:14:\"premium_suffix\";s:9:\"(Premium)\";s:7:\"is_live\";b:1;s:9:\"bundle_id\";N;s:17:\"bundle_public_key\";N;s:10:\"public_key\";s:32:\"pk_e32061abc28cfedf231f3e5c4e626\";s:10:\"secret_key\";N;s:2:\"id\";s:4:\"3069\";s:7:\"updated\";N;s:7:\"created\";N;s:22:\"\0FS_Entity\0_is_updated\";b:1;}s:13:\"event-tickets\";O:9:\"FS_Plugin\":23:{s:16:\"parent_plugin_id\";N;s:5:\"title\";s:13:\"Event Tickets\";s:4:\"slug\";s:13:\"event-tickets\";s:12:\"premium_slug\";s:21:\"event-tickets-premium\";s:4:\"type\";s:6:\"plugin\";s:20:\"affiliate_moderation\";b:0;s:19:\"is_wp_org_compliant\";b:1;s:22:\"premium_releases_count\";N;s:4:\"file\";s:31:\"event-tickets/event-tickets.php\";s:7:\"version\";s:5:\"5.5.5\";s:11:\"auto_update\";N;s:4:\"info\";N;s:10:\"is_premium\";b:0;s:14:\"premium_suffix\";s:9:\"(Premium)\";s:7:\"is_live\";b:1;s:9:\"bundle_id\";N;s:17:\"bundle_public_key\";N;s:10:\"public_key\";s:32:\"pk_6dd9310b57c62871c59e58b8e739e\";s:10:\"secret_key\";N;s:2:\"id\";s:4:\"3841\";s:7:\"updated\";N;s:7:\"created\";N;s:22:\"\0FS_Entity\0_is_updated\";b:1;}}s:9:\"unique_id\";s:32:\"f780b4baade26d5c042675aa17df5c05\";s:13:\"admin_notices\";a:2:{s:19:\"the-events-calendar\";a:0:{}s:13:\"event-tickets\";a:0:{}}}','yes'),(147,'fs_gdpr','a:2:{s:2:\"u0\";a:1:{s:8:\"required\";b:0;}s:2:\"u1\";a:1:{s:8:\"required\";b:0;}}','yes'),(150,'wp_page_for_privacy_policy','0','yes'),(151,'show_comments_cookies_opt_in','1','yes'),(153,'disallowed_keys','','no'),(154,'comment_previously_approved','1','yes'),(155,'auto_plugin_theme_update_emails','a:0:{}','no'),(156,'auto_update_core_dev','enabled','yes'),(157,'auto_update_core_minor','enabled','yes'),(158,'auto_update_core_major','unset','yes'),(159,'wp_force_deactivated_plugins','a:0:{}','yes'),(160,'finished_updating_comment_type','1','yes'),(161,'user_count','1','no'),(162,'db_upgraded','','yes'),(171,'auto_core_update_notified','a:4:{s:4:\"type\";s:7:\"success\";s:5:\"email\";s:20:\"admin@wordpress.test\";s:7:\"version\";s:5:\"6.1.1\";s:9:\"timestamp\";i:1670232824;}','no'),(172,'recovery_keys','a:1:{s:22:\"XtMzHsMEnupZW70KCLjeTw\";a:2:{s:10:\"hashed_key\";s:34:\"$P$B.xHePRjPeG/X2X7GWPTRH5yp7qCdS/\";s:10:\"created_at\";i:1700228528;}}','yes'),(177,'https_detection_errors','a:1:{s:23:\"ssl_verification_failed\";a:1:{i:0;s:24:\"SSL verification failed.\";}}','yes'),(226,'_transient_health-check-site-status-result','{\"good\":14,\"recommended\":3,\"critical\":1}','yes'),(260,'recently_activated','a:0:{}','yes'),(295,'fs_api_cache','a:0:{}','no'),(397,'tec_timed_tribe_supports_async_process','a:3:{s:3:\"key\";s:28:\"tribe_supports_async_process\";s:5:\"value\";N;s:10:\"expiration\";i:1727267604;}','yes'),(430,'edd_settings','a:6:{s:13:\"purchase_page\";i:4;s:12:\"success_page\";i:5;s:12:\"failure_page\";i:6;s:21:\"purchase_history_page\";i:7;s:17:\"confirmation_page\";i:8;s:20:\"stripe_elements_mode\";s:16:\"payment-elements\";}','yes'),(431,'edd_use_php_sessions','1','yes'),(433,'edd_activation_date','1672924302','yes'),(436,'edd_default_api_version','v2','yes'),(437,'edd_completed_upgrades','a:17:{i:0;s:21:\"upgrade_payment_taxes\";i:1;s:37:\"upgrade_customer_payments_association\";i:2;s:21:\"upgrade_user_api_keys\";i:3;s:25:\"remove_refunded_sale_logs\";i:4;s:29:\"update_file_download_log_data\";i:5;s:17:\"migrate_tax_rates\";i:6;s:17:\"migrate_discounts\";i:7;s:14:\"migrate_orders\";i:8;s:26:\"migrate_customer_addresses\";i:9;s:32:\"migrate_customer_email_addresses\";i:10;s:22:\"migrate_customer_notes\";i:11;s:12:\"migrate_logs\";i:12;s:19:\"migrate_order_notes\";i:13;s:23:\"v30_legacy_data_removed\";i:14;s:28:\"stripe_customer_id_migration\";i:15;s:26:\"migrate_order_actions_date\";i:16;s:19:\"discounts_start_end\";}','yes'),(438,'edd_version','3.2.6','yes'),(439,'_transient_edd_cache_excluded_uris','a:4:{i:0;s:3:\"p=4\";i:1;s:3:\"p=5\";i:2;s:9:\"/checkout\";i:3;s:8:\"/receipt\";}','yes'),(440,'widget_edd_cart_widget','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(441,'widget_edd_categories_tags_widget','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(442,'widget_edd_product_details','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(444,'_transient_edd-sc-receipt-check','86400','yes'),(445,'edds_stripe_version','2.8.13.1','yes'),(446,'wpdb_edd_customers_version','202303220','yes'),(447,'wpdb_edd_customermeta_version','201807111','yes'),(448,'wpdb_edd_customer_addresses_version','202004051','yes'),(449,'wpdb_edd_customer_email_addresses_version','202002141','yes'),(450,'wpdb_edd_adjustments_version','202307311','yes'),(451,'wpdb_edd_adjustmentmeta_version','201806142','yes'),(452,'wpdb_edd_notes_version','202002141','yes'),(454,'wpdb_edd_notemeta_version','201805221','yes'),(455,'wpdb_edd_orders_version','202307111','yes'),(456,'wpdb_edd_ordermeta_version','201805221','yes'),(457,'wpdb_edd_order_items_version','202110141','yes'),(458,'wpdb_edd_order_itemmeta_version','201805221','yes'),(459,'wpdb_edd_order_adjustments_version','202105221','yes'),(460,'wpdb_edd_order_adjustmentmeta_version','201805221','yes'),(461,'wpdb_edd_order_addresses_version','202002141','yes'),(462,'wpdb_edd_order_transactions_version','202205241','yes'),(463,'wpdb_edd_logs_version','202002141','yes'),(464,'wpdb_edd_logmeta_version','201805221','yes'),(465,'wpdb_edd_logs_api_requests_version','202002141','yes'),(466,'wpdb_edd_logs_api_requestmeta_version','201907291','yes'),(467,'wpdb_edd_logs_file_downloads_version','202002141','yes'),(468,'wpdb_edd_logs_file_downloadmeta_version','201907291','yes'),(469,'edd_licensed_extensions','{\"timeout\":1727353993,\"products\":[]}','no'),(470,'edd_tracking_notice','1','yes'),(475,'woocommerce_schema_version','430','yes'),(476,'woocommerce_store_address','101 South Street','yes'),(477,'woocommerce_store_address_2','','yes'),(478,'woocommerce_store_city','New York','yes'),(479,'woocommerce_default_country','US:NY','yes'),(480,'woocommerce_store_postcode','10001','yes'),(481,'woocommerce_allowed_countries','all','yes'),(482,'woocommerce_all_except_countries','a:0:{}','yes'),(483,'woocommerce_specific_allowed_countries','a:0:{}','yes'),(484,'woocommerce_ship_to_countries','','yes'),(485,'woocommerce_specific_ship_to_countries','a:0:{}','yes'),(486,'woocommerce_default_customer_address','base','yes'),(487,'woocommerce_calc_taxes','no','yes'),(488,'woocommerce_enable_coupons','yes','yes'),(489,'woocommerce_calc_discounts_sequentially','no','no'),(490,'woocommerce_currency','USD','yes'),(491,'woocommerce_currency_pos','left','yes'),(492,'woocommerce_price_thousand_sep',',','yes'),(493,'woocommerce_price_decimal_sep','.','yes'),(494,'woocommerce_price_num_decimals','2','yes'),(495,'woocommerce_shop_page_id','10','yes'),(496,'woocommerce_cart_redirect_after_add','no','yes'),(497,'woocommerce_enable_ajax_add_to_cart','yes','yes'),(498,'woocommerce_placeholder_image','9','yes'),(499,'woocommerce_weight_unit','kg','yes'),(500,'woocommerce_dimension_unit','cm','yes'),(501,'woocommerce_enable_reviews','yes','yes'),(502,'woocommerce_review_rating_verification_label','yes','no'),(503,'woocommerce_review_rating_verification_required','no','no'),(504,'woocommerce_enable_review_rating','yes','yes'),(505,'woocommerce_review_rating_required','yes','no'),(506,'woocommerce_manage_stock','yes','yes'),(507,'woocommerce_hold_stock_minutes','60','no'),(508,'woocommerce_notify_low_stock','yes','no'),(509,'woocommerce_notify_no_stock','yes','no'),(510,'woocommerce_stock_email_recipient','admin@wordpress.test','no'),(511,'woocommerce_notify_low_stock_amount','2','no'),(512,'woocommerce_notify_no_stock_amount','0','yes'),(513,'woocommerce_hide_out_of_stock_items','no','yes'),(514,'woocommerce_stock_format','','yes'),(515,'woocommerce_file_download_method','force','no'),(516,'woocommerce_downloads_redirect_fallback_allowed','no','no'),(517,'woocommerce_downloads_require_login','no','no'),(518,'woocommerce_downloads_grant_access_after_payment','yes','no'),(519,'woocommerce_downloads_deliver_inline','','no'),(520,'woocommerce_downloads_add_hash_to_filename','yes','yes'),(522,'woocommerce_attribute_lookup_direct_updates','no','yes'),(523,'woocommerce_prices_include_tax','no','yes'),(524,'woocommerce_tax_based_on','shipping','yes'),(525,'woocommerce_shipping_tax_class','inherit','yes'),(526,'woocommerce_tax_round_at_subtotal','no','yes'),(527,'woocommerce_tax_classes','','yes'),(528,'woocommerce_tax_display_shop','excl','yes'),(529,'woocommerce_tax_display_cart','excl','yes'),(530,'woocommerce_price_display_suffix','','yes'),(531,'woocommerce_tax_total_display','itemized','no'),(532,'woocommerce_enable_shipping_calc','yes','no'),(533,'woocommerce_shipping_cost_requires_address','no','yes'),(534,'woocommerce_ship_to_destination','billing','no'),(535,'woocommerce_shipping_debug_mode','no','yes'),(536,'woocommerce_enable_guest_checkout','yes','no'),(537,'woocommerce_enable_checkout_login_reminder','no','no'),(538,'woocommerce_enable_signup_and_login_from_checkout','no','no'),(539,'woocommerce_enable_myaccount_registration','no','no'),(540,'woocommerce_registration_generate_username','yes','no'),(541,'woocommerce_registration_generate_password','yes','no'),(542,'woocommerce_erasure_request_removes_order_data','no','no'),(543,'woocommerce_erasure_request_removes_download_data','no','no'),(544,'woocommerce_allow_bulk_remove_personal_data','no','no'),(545,'woocommerce_registration_privacy_policy_text','Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our [privacy_policy].','yes'),(546,'woocommerce_checkout_privacy_policy_text','Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our [privacy_policy].','yes'),(547,'woocommerce_delete_inactive_accounts','a:2:{s:6:\"number\";s:0:\"\";s:4:\"unit\";s:6:\"months\";}','no'),(548,'woocommerce_trash_pending_orders','','no'),(549,'woocommerce_trash_failed_orders','','no'),(550,'woocommerce_trash_cancelled_orders','','no'),(551,'woocommerce_anonymize_completed_orders','a:2:{s:6:\"number\";s:0:\"\";s:4:\"unit\";s:6:\"months\";}','no'),(552,'woocommerce_email_from_name','Wordpress Test','no'),(553,'woocommerce_email_from_address','admin@wordpress.test','no'),(554,'woocommerce_email_header_image','','no'),(555,'woocommerce_email_footer_text','{site_title} &mdash; Built with {WooCommerce}','no'),(556,'woocommerce_email_base_color','#7f54b3','no'),(557,'woocommerce_email_background_color','#f7f7f7','no'),(558,'woocommerce_email_body_background_color','#ffffff','no'),(559,'woocommerce_email_text_color','#3c3c3c','no'),(560,'woocommerce_merchant_email_notifications','no','no'),(561,'woocommerce_cart_page_id','11','no'),(562,'woocommerce_checkout_page_id','12','no'),(563,'woocommerce_myaccount_page_id','13','no'),(564,'woocommerce_terms_page_id','','no'),(565,'woocommerce_force_ssl_checkout','no','yes'),(566,'woocommerce_unforce_ssl_checkout','no','yes'),(567,'woocommerce_checkout_pay_endpoint','order-pay','yes'),(568,'woocommerce_checkout_order_received_endpoint','order-received','yes'),(569,'woocommerce_myaccount_add_payment_method_endpoint','add-payment-method','yes'),(570,'woocommerce_myaccount_delete_payment_method_endpoint','delete-payment-method','yes'),(571,'woocommerce_myaccount_set_default_payment_method_endpoint','set-default-payment-method','yes'),(572,'woocommerce_myaccount_orders_endpoint','orders','yes'),(573,'woocommerce_myaccount_view_order_endpoint','view-order','yes'),(574,'woocommerce_myaccount_downloads_endpoint','downloads','yes'),(575,'woocommerce_myaccount_edit_account_endpoint','edit-account','yes'),(576,'woocommerce_myaccount_edit_address_endpoint','edit-address','yes'),(577,'woocommerce_myaccount_payment_methods_endpoint','payment-methods','yes'),(578,'woocommerce_myaccount_lost_password_endpoint','lost-password','yes'),(579,'woocommerce_logout_endpoint','customer-logout','yes'),(580,'woocommerce_api_enabled','no','yes'),(581,'woocommerce_allow_tracking','no','no'),(582,'woocommerce_show_marketplace_suggestions','yes','no'),(583,'woocommerce_analytics_enabled','yes','yes'),(584,'woocommerce_navigation_enabled','no','yes'),(585,'woocommerce_feature_custom_order_tables_enabled','no','yes'),(586,'woocommerce_single_image_width','600','yes'),(587,'woocommerce_thumbnail_image_width','300','yes'),(588,'woocommerce_checkout_highlight_required_fields','yes','yes'),(589,'woocommerce_demo_store','no','no'),(590,'wc_downloads_approved_directories_mode','enabled','yes'),(591,'woocommerce_permalinks','a:5:{s:12:\"product_base\";s:7:\"product\";s:13:\"category_base\";s:16:\"product-category\";s:8:\"tag_base\";s:11:\"product-tag\";s:14:\"attribute_base\";s:0:\"\";s:22:\"use_verbose_page_rules\";b:0;}','yes'),(592,'current_theme_supports_woocommerce','yes','yes'),(593,'woocommerce_queue_flush_rewrite_rules','no','yes'),(595,'product_cat_children','a:0:{}','yes'),(596,'default_product_cat','15','yes'),(598,'woocommerce_refund_returns_page_id','14','yes'),(601,'woocommerce_paypal_settings','a:23:{s:7:\"enabled\";s:2:\"no\";s:5:\"title\";s:6:\"PayPal\";s:11:\"description\";s:85:\"Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.\";s:5:\"email\";s:20:\"admin@wordpress.test\";s:8:\"advanced\";s:0:\"\";s:8:\"testmode\";s:2:\"no\";s:5:\"debug\";s:2:\"no\";s:16:\"ipn_notification\";s:3:\"yes\";s:14:\"receiver_email\";s:20:\"admin@wordpress.test\";s:14:\"identity_token\";s:0:\"\";s:14:\"invoice_prefix\";s:3:\"WC-\";s:13:\"send_shipping\";s:3:\"yes\";s:16:\"address_override\";s:2:\"no\";s:13:\"paymentaction\";s:4:\"sale\";s:9:\"image_url\";s:0:\"\";s:11:\"api_details\";s:0:\"\";s:12:\"api_username\";s:0:\"\";s:12:\"api_password\";s:0:\"\";s:13:\"api_signature\";s:0:\"\";s:20:\"sandbox_api_username\";s:0:\"\";s:20:\"sandbox_api_password\";s:0:\"\";s:21:\"sandbox_api_signature\";s:0:\"\";s:12:\"_should_load\";s:2:\"no\";}','yes'),(602,'woocommerce_version','9.0.2','yes'),(603,'woocommerce_db_version','8.2.2','yes'),(604,'woocommerce_admin_install_timestamp','1672924314','yes'),(605,'woocommerce_inbox_variant_assignment','3','yes'),(609,'_transient_jetpack_autoloader_plugin_paths','a:1:{i:0;s:29:\"{{WP_PLUGIN_DIR}}/woocommerce\";}','yes'),(610,'woocommerce_admin_notices','a:2:{i:0;s:6:\"update\";i:1;s:20:\"no_secure_connection\";}','yes'),(611,'woocommerce_maxmind_geolocation_settings','a:1:{s:15:\"database_prefix\";s:32:\"0jxv9z7mXPj1LeMzhbPKfp8fWZJxPthz\";}','yes'),(612,'_transient_woocommerce_webhook_ids_status_active','a:0:{}','yes'),(613,'widget_woocommerce_widget_cart','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(614,'widget_woocommerce_layered_nav_filters','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(615,'widget_woocommerce_layered_nav','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(616,'widget_woocommerce_price_filter','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(617,'widget_woocommerce_product_categories','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(618,'widget_woocommerce_product_search','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(619,'widget_woocommerce_product_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(620,'widget_woocommerce_products','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(621,'widget_woocommerce_recently_viewed_products','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(622,'widget_woocommerce_top_rated_products','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(623,'widget_woocommerce_recent_reviews','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(624,'widget_woocommerce_rating_filter','a:1:{s:12:\"_multiwidget\";i:1;}','yes'),(625,'_transient_wc_count_comments','O:8:\"stdClass\":7:{s:14:\"total_comments\";i:0;s:3:\"all\";i:0;s:9:\"moderated\";i:0;s:8:\"approved\";i:0;s:4:\"spam\";i:0;s:5:\"trash\";i:0;s:12:\"post-trashed\";i:0;}','yes'),(630,'_transient_woocommerce_shipping_task_zone_count_transient','0','yes'),(631,'wc_blocks_db_schema_version','260','yes'),(632,'wc_remote_inbox_notifications_stored_state','O:8:\"stdClass\":2:{s:22:\"there_were_no_products\";b:1;s:22:\"there_are_now_products\";b:0;}','no'),(635,'_transient_woocommerce_reports-transient-version','1672924354','yes'),(643,'woocommerce_task_list_tracked_completed_tasks','a:3:{i:0;s:8:\"purchase\";i:1;s:13:\"store_details\";i:2;s:8:\"products\";}','yes'),(645,'woocommerce_onboarding_profile','a:10:{s:18:\"is_agree_marketing\";b:0;s:11:\"store_email\";s:20:\"admin@wordpress.test\";s:20:\"is_store_country_set\";b:1;s:8:\"industry\";a:1:{i:0;a:1:{s:4:\"slug\";s:5:\"other\";}}s:13:\"product_types\";a:1:{i:0;s:9:\"downloads\";}s:13:\"product_count\";s:1:\"0\";s:14:\"selling_venues\";s:2:\"no\";s:12:\"setup_client\";b:1;s:19:\"business_extensions\";a:0:{}s:9:\"completed\";b:1;}','yes'),(647,'woocommerce_task_list_dismissed_tasks','a:0:{}','yes'),(651,'woocommerce_task_list_prompt_shown','1','yes'),(660,'woocommerce_default_homepage_layout','two_columns','yes'),(661,'woocommerce_task_list_hidden_lists','a:2:{i:0;s:5:\"setup\";i:1;s:8:\"extended\";}','yes'),(744,'external_updates-event-tickets-plus','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1727267593;s:14:\"checkedVersion\";s:5:\"6.0.2\";s:6:\"update\";N;}','no'),(745,'tribe_pue_key_notices','a:0:{}','yes'),(774,'_transient_product_query-transient-version','1700223858','yes'),(820,'edd_version_upgraded_from','3.2.5','no'),(842,'edds_notice_edd-stripe-core_dismissed','1','yes'),(852,'tec_automator_power_automate_secret_key','884cc3ff27ae261b9ede80398ec4f9716058623b02ba6207570a618fdf0af157817bf099d649883f6eb308b2abfc1c131675620e728abc5e99bc731578730a634466e6d855aba8e5962f66b5a81b07720a66435726c4d8c11b19b9878c9554323266baeda87a58f510ddabac230fd9144e8964e3c4e6a303646b33e937996351','yes'),(855,'_transient_product-transient-version','1700223858','yes'),(858,'wc_blocks_use_blockified_product_grid_block_as_template','no','yes'),(859,'wc_blocks_version','11.1.3','yes'),(860,'jetpack_connection_active_plugins','a:1:{s:11:\"woocommerce\";a:1:{s:4:\"name\";s:11:\"WooCommerce\";}}','yes'),(861,'tec_freemius_accounts_archive','s:3742:\"a:6:{s:21:\"id_slug_type_path_map\";a:2:{i:3069;a:3:{s:4:\"slug\";s:19:\"the-events-calendar\";s:4:\"type\";s:6:\"plugin\";s:4:\"path\";s:43:\"the-events-calendar/the-events-calendar.php\";}i:3841;a:3:{s:4:\"slug\";s:13:\"event-tickets\";s:4:\"type\";s:6:\"plugin\";s:4:\"path\";s:31:\"event-tickets/event-tickets.php\";}}s:11:\"plugin_data\";a:2:{s:19:\"the-events-calendar\";a:16:{s:16:\"plugin_main_file\";O:8:\"stdClass\":1:{s:4:\"path\";s:43:\"the-events-calendar/the-events-calendar.php\";}s:20:\"is_network_activated\";b:0;s:17:\"install_timestamp\";i:1666777406;s:17:\"was_plugin_loaded\";b:1;s:21:\"is_plugin_new_install\";b:0;s:16:\"sdk_last_version\";N;s:11:\"sdk_version\";s:5:\"2.4.4\";s:16:\"sdk_upgrade_mode\";b:1;s:18:\"sdk_downgrade_mode\";b:0;s:19:\"plugin_last_version\";s:5:\"6.0.5\";s:14:\"plugin_version\";s:7:\"6.0.6.2\";s:19:\"plugin_upgrade_mode\";b:1;s:21:\"plugin_downgrade_mode\";b:0;s:17:\"connectivity_test\";a:6:{s:12:\"is_connected\";b:1;s:4:\"host\";s:14:\"wordpress.test\";s:9:\"server_ip\";s:3:\"::1\";s:9:\"is_active\";b:1;s:9:\"timestamp\";i:1666777406;s:7:\"version\";s:5:\"6.0.2\";}s:15:\"prev_is_premium\";b:0;s:12:\"is_anonymous\";a:3:{s:2:\"is\";b:1;s:9:\"timestamp\";i:1667820280;s:7:\"version\";s:7:\"6.0.3.1\";}}s:13:\"event-tickets\";a:16:{s:16:\"plugin_main_file\";O:8:\"stdClass\":1:{s:4:\"path\";s:31:\"event-tickets/event-tickets.php\";}s:20:\"is_network_activated\";b:0;s:17:\"install_timestamp\";i:1670232855;s:17:\"was_plugin_loaded\";b:1;s:21:\"is_plugin_new_install\";b:0;s:16:\"sdk_last_version\";N;s:11:\"sdk_version\";s:5:\"2.4.4\";s:16:\"sdk_upgrade_mode\";b:1;s:18:\"sdk_downgrade_mode\";b:0;s:19:\"plugin_last_version\";s:5:\"5.5.4\";s:14:\"plugin_version\";s:5:\"5.5.5\";s:19:\"plugin_upgrade_mode\";b:1;s:21:\"plugin_downgrade_mode\";b:0;s:17:\"connectivity_test\";a:6:{s:12:\"is_connected\";b:1;s:4:\"host\";s:14:\"wordpress.test\";s:9:\"server_ip\";s:9:\"127.0.0.1\";s:9:\"is_active\";b:1;s:9:\"timestamp\";i:1670232855;s:7:\"version\";s:5:\"5.5.4\";}s:15:\"prev_is_premium\";b:0;s:12:\"is_anonymous\";a:3:{s:2:\"is\";b:1;s:9:\"timestamp\";i:1670232858;s:7:\"version\";s:5:\"5.5.4\";}}}s:13:\"file_slug_map\";a:2:{s:43:\"the-events-calendar/the-events-calendar.php\";s:19:\"the-events-calendar\";s:31:\"event-tickets/event-tickets.php\";s:13:\"event-tickets\";}s:7:\"plugins\";a:2:{s:19:\"the-events-calendar\";O:9:\"FS_Plugin\":23:{s:16:\"parent_plugin_id\";N;s:5:\"title\";s:19:\"The Events Calendar\";s:4:\"slug\";s:19:\"the-events-calendar\";s:12:\"premium_slug\";s:27:\"the-events-calendar-premium\";s:4:\"type\";s:6:\"plugin\";s:20:\"affiliate_moderation\";b:0;s:19:\"is_wp_org_compliant\";b:1;s:22:\"premium_releases_count\";N;s:4:\"file\";s:43:\"the-events-calendar/the-events-calendar.php\";s:7:\"version\";s:7:\"6.0.6.2\";s:11:\"auto_update\";N;s:4:\"info\";N;s:10:\"is_premium\";b:0;s:14:\"premium_suffix\";s:9:\"(Premium)\";s:7:\"is_live\";b:1;s:9:\"bundle_id\";N;s:17:\"bundle_public_key\";N;s:10:\"public_key\";s:32:\"pk_e32061abc28cfedf231f3e5c4e626\";s:10:\"secret_key\";N;s:2:\"id\";s:4:\"3069\";s:7:\"updated\";N;s:7:\"created\";N;s:22:\"\0FS_Entity\0_is_updated\";b:1;}s:13:\"event-tickets\";O:9:\"FS_Plugin\":23:{s:16:\"parent_plugin_id\";N;s:5:\"title\";s:13:\"Event Tickets\";s:4:\"slug\";s:13:\"event-tickets\";s:12:\"premium_slug\";s:21:\"event-tickets-premium\";s:4:\"type\";s:6:\"plugin\";s:20:\"affiliate_moderation\";b:0;s:19:\"is_wp_org_compliant\";b:1;s:22:\"premium_releases_count\";N;s:4:\"file\";s:31:\"event-tickets/event-tickets.php\";s:7:\"version\";s:5:\"5.5.5\";s:11:\"auto_update\";N;s:4:\"info\";N;s:10:\"is_premium\";b:0;s:14:\"premium_suffix\";s:9:\"(Premium)\";s:7:\"is_live\";b:1;s:9:\"bundle_id\";N;s:17:\"bundle_public_key\";N;s:10:\"public_key\";s:32:\"pk_6dd9310b57c62871c59e58b8e739e\";s:10:\"secret_key\";N;s:2:\"id\";s:4:\"3841\";s:7:\"updated\";N;s:7:\"created\";N;s:22:\"\0FS_Entity\0_is_updated\";b:1;}}s:9:\"unique_id\";s:32:\"f780b4baade26d5c042675aa17df5c05\";s:13:\"admin_notices\";a:2:{s:19:\"the-events-calendar\";a:0:{}s:13:\"event-tickets\";a:0:{}}}\";','yes'),(862,'tec_freemius_accounts_data_archive','a:6:{s:21:\"id_slug_type_path_map\";a:2:{i:3069;a:3:{s:4:\"slug\";s:19:\"the-events-calendar\";s:4:\"type\";s:6:\"plugin\";s:4:\"path\";s:43:\"the-events-calendar/the-events-calendar.php\";}i:3841;a:3:{s:4:\"slug\";s:13:\"event-tickets\";s:4:\"type\";s:6:\"plugin\";s:4:\"path\";s:31:\"event-tickets/event-tickets.php\";}}s:11:\"plugin_data\";a:2:{s:19:\"the-events-calendar\";a:16:{s:16:\"plugin_main_file\";O:8:\"stdClass\":1:{s:4:\"path\";s:43:\"the-events-calendar/the-events-calendar.php\";}s:20:\"is_network_activated\";b:0;s:17:\"install_timestamp\";i:1666777406;s:17:\"was_plugin_loaded\";b:1;s:21:\"is_plugin_new_install\";b:0;s:16:\"sdk_last_version\";N;s:11:\"sdk_version\";s:5:\"2.4.4\";s:16:\"sdk_upgrade_mode\";b:1;s:18:\"sdk_downgrade_mode\";b:0;s:19:\"plugin_last_version\";s:5:\"6.0.5\";s:14:\"plugin_version\";s:7:\"6.0.6.2\";s:19:\"plugin_upgrade_mode\";b:1;s:21:\"plugin_downgrade_mode\";b:0;s:17:\"connectivity_test\";a:6:{s:12:\"is_connected\";b:1;s:4:\"host\";s:14:\"wordpress.test\";s:9:\"server_ip\";s:3:\"::1\";s:9:\"is_active\";b:1;s:9:\"timestamp\";i:1666777406;s:7:\"version\";s:5:\"6.0.2\";}s:15:\"prev_is_premium\";b:0;s:12:\"is_anonymous\";a:3:{s:2:\"is\";b:1;s:9:\"timestamp\";i:1667820280;s:7:\"version\";s:7:\"6.0.3.1\";}}s:13:\"event-tickets\";a:16:{s:16:\"plugin_main_file\";O:8:\"stdClass\":1:{s:4:\"path\";s:31:\"event-tickets/event-tickets.php\";}s:20:\"is_network_activated\";b:0;s:17:\"install_timestamp\";i:1670232855;s:17:\"was_plugin_loaded\";b:1;s:21:\"is_plugin_new_install\";b:0;s:16:\"sdk_last_version\";N;s:11:\"sdk_version\";s:5:\"2.4.4\";s:16:\"sdk_upgrade_mode\";b:1;s:18:\"sdk_downgrade_mode\";b:0;s:19:\"plugin_last_version\";s:5:\"5.5.4\";s:14:\"plugin_version\";s:5:\"5.5.5\";s:19:\"plugin_upgrade_mode\";b:1;s:21:\"plugin_downgrade_mode\";b:0;s:17:\"connectivity_test\";a:6:{s:12:\"is_connected\";b:1;s:4:\"host\";s:14:\"wordpress.test\";s:9:\"server_ip\";s:9:\"127.0.0.1\";s:9:\"is_active\";b:1;s:9:\"timestamp\";i:1670232855;s:7:\"version\";s:5:\"5.5.4\";}s:15:\"prev_is_premium\";b:0;s:12:\"is_anonymous\";a:3:{s:2:\"is\";b:1;s:9:\"timestamp\";i:1670232858;s:7:\"version\";s:5:\"5.5.4\";}}}s:13:\"file_slug_map\";a:2:{s:43:\"the-events-calendar/the-events-calendar.php\";s:19:\"the-events-calendar\";s:31:\"event-tickets/event-tickets.php\";s:13:\"event-tickets\";}s:7:\"plugins\";a:2:{s:19:\"the-events-calendar\";a:24:{s:10:\"tec_fs_key\";s:9:\"FS_Plugin\";s:16:\"parent_plugin_id\";N;s:5:\"title\";s:19:\"The Events Calendar\";s:4:\"slug\";s:19:\"the-events-calendar\";s:12:\"premium_slug\";s:27:\"the-events-calendar-premium\";s:4:\"type\";s:6:\"plugin\";s:20:\"affiliate_moderation\";b:0;s:19:\"is_wp_org_compliant\";b:1;s:22:\"premium_releases_count\";N;s:4:\"file\";s:43:\"the-events-calendar/the-events-calendar.php\";s:7:\"version\";s:7:\"6.0.6.2\";s:11:\"auto_update\";N;s:4:\"info\";N;s:10:\"is_premium\";b:0;s:14:\"premium_suffix\";s:9:\"(Premium)\";s:7:\"is_live\";b:1;s:9:\"bundle_id\";N;s:17:\"bundle_public_key\";N;s:10:\"public_key\";s:32:\"pk_e32061abc28cfedf231f3e5c4e626\";s:10:\"secret_key\";N;s:2:\"id\";s:4:\"3069\";s:7:\"updated\";N;s:7:\"created\";N;s:22:\"\0FS_Entity\0_is_updated\";b:1;}s:13:\"event-tickets\";a:24:{s:10:\"tec_fs_key\";s:9:\"FS_Plugin\";s:16:\"parent_plugin_id\";N;s:5:\"title\";s:13:\"Event Tickets\";s:4:\"slug\";s:13:\"event-tickets\";s:12:\"premium_slug\";s:21:\"event-tickets-premium\";s:4:\"type\";s:6:\"plugin\";s:20:\"affiliate_moderation\";b:0;s:19:\"is_wp_org_compliant\";b:1;s:22:\"premium_releases_count\";N;s:4:\"file\";s:31:\"event-tickets/event-tickets.php\";s:7:\"version\";s:5:\"5.5.5\";s:11:\"auto_update\";N;s:4:\"info\";N;s:10:\"is_premium\";b:0;s:14:\"premium_suffix\";s:9:\"(Premium)\";s:7:\"is_live\";b:1;s:9:\"bundle_id\";N;s:17:\"bundle_public_key\";N;s:10:\"public_key\";s:32:\"pk_6dd9310b57c62871c59e58b8e739e\";s:10:\"secret_key\";N;s:2:\"id\";s:4:\"3841\";s:7:\"updated\";N;s:7:\"created\";N;s:22:\"\0FS_Entity\0_is_updated\";b:1;}}s:9:\"unique_id\";s:32:\"f780b4baade26d5c042675aa17df5c05\";s:13:\"admin_notices\";a:2:{s:19:\"the-events-calendar\";a:0:{}s:13:\"event-tickets\";a:0:{}}}','yes'),(863,'stellarwp_telemetry','a:1:{s:7:\"plugins\";a:2:{s:19:\"the-events-calendar\";a:2:{s:7:\"wp_slug\";s:43:\"the-events-calendar/the-events-calendar.php\";s:5:\"optin\";b:0;}s:13:\"event-tickets\";a:2:{s:7:\"wp_slug\";s:31:\"event-tickets/event-tickets.php\";s:5:\"optin\";b:0;}}}','yes'),(864,'stellarwp_telemetry_event-tickets_show_optin','0','yes'),(865,'stellarwp_telemetry_the-events-calendar_show_optin','0','yes'),(866,'tec_freemius_plugins_archive','O:8:\"stdClass\":3:{s:7:\"plugins\";a:1:{s:36:\"event-tickets/common/vendor/freemius\";O:8:\"stdClass\":4:{s:7:\"version\";s:5:\"2.4.4\";s:4:\"type\";s:6:\"plugin\";s:9:\"timestamp\";i:1670325882;s:11:\"plugin_path\";s:31:\"event-tickets/event-tickets.php\";}}s:7:\"abspath\";s:54:\"/Users/brianjessee/Local Sites/tribe/tests/app/public/\";s:6:\"newest\";O:8:\"stdClass\":5:{s:11:\"plugin_path\";s:31:\"event-tickets/event-tickets.php\";s:8:\"sdk_path\";s:36:\"event-tickets/common/vendor/freemius\";s:7:\"version\";s:5:\"2.4.4\";s:13:\"in_activation\";b:0;s:9:\"timestamp\";i:1670325882;}}','yes'),(867,'woocommerce_task_list_reminder_bar_hidden','yes','yes'),(870,'woocommerce_custom_orders_table_enabled','no','yes'),(871,'woocommerce_custom_orders_table_data_sync_enabled','no','yes'),(872,'woocommerce_custom_orders_table_created','yes','yes'),(873,'woocommerce_feature_marketplace_enabled','yes','yes'),(874,'woocommerce_feature_product_block_editor_enabled','no','yes'),(880,'tec_timed_tec_custom_tables_v1_initialized','a:3:{s:3:\"key\";s:32:\"tec_custom_tables_v1_initialized\";s:5:\"value\";i:1;s:10:\"expiration\";i:1727353993;}','yes'),(881,'stellarwp_telemetry_last_send','','yes'),(885,'wcpay_was_in_use','no','yes'),(888,'external_updates-event-automator','O:8:\"stdClass\":3:{s:9:\"lastCheck\";i:1700226153;s:14:\"checkedVersion\";N;s:6:\"update\";O:19:\"Tribe__PUE__Utility\":12:{s:2:\"id\";i:0;s:6:\"plugin\";s:35:\"event-automator/event-automator.php\";s:4:\"slug\";s:15:\"event-automator\";s:7:\"version\";s:5:\"1.4.0\";s:8:\"homepage\";s:20:\"https://evnt.is/1bc7\";s:12:\"download_url\";s:245:\"https://pue.theeventscalendar.com/api/plugins/v2/download?plugin=event-automator&version=1.4.0&installed_version&domain=localhost&multisite=0&network_activated=0&active_sites=1&wp_version=6.2.2&key=5f53261eb16f7ddf4fe24b69918c8804c82ea817&dk&o=o\";s:8:\"sections\";O:8:\"stdClass\":3:{s:11:\"description\";s:245:\"Event Automator, a premium add-on to the open source The Events Calendar plugin or Event Tickets (at least one required), that lets you connect your apps and automate workflows for lead management, communication outreach, and internal processes.\";s:12:\"installation\";s:366:\"Installing Event Automator is easy: just back up your site, download/install The Events Calendar and/or Event Tickets from the WordPress.org repo, and download/install Event Automator from theeventscalendar.com. Activate them both and you\'ll be good to go! If you\'re still confused or encounter problems, check out part 1 of our new user primer (http://m.tri.be/4i).\";s:9:\"changelog\";s:515:\"<p>= [1.4.0] 2023-10-19 =</p>\r\n\r\n<ul>\r\n<li>Feature - Add Support for Microsoft Power Automate with Event Tickets and the new attendee, updated attendee, checkin, new orders, and refunded orders endpoints [EVA-96]</li>\r\n<li>Feature - Add Support for Microsoft Power Automate with The Events Calendar and the new event, updated event, canceled event, and action to create events endpoints [EVA-88]</li>\r\n<li>Fix - Prevent multiple attendees from being added to the queue when doing bulk checkin. [EVA-102]</li>\r\n</ul>\";}s:14:\"upgrade_notice\";s:0:\"\";s:13:\"custom_update\";O:8:\"stdClass\":1:{s:5:\"icons\";O:8:\"stdClass\":1:{s:3:\"svg\";s:76:\"https://images.theeventscalendar.com/uploads/2023/01/EventAutomator-icon.svg\";}}s:13:\"license_error\";N;s:11:\"api_expired\";b:0;s:11:\"api_upgrade\";b:0;}}','no'),(895,'pue_install_key_event_automator','5f53261eb16f7ddf4fe24b69918c8804c82ea817','yes'),(901,'edd_onboarding_completed','1','no'),(929,'wpdb_edd_notifications_version','202303220','yes'),(955,'_site_transient_update_plugins','O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1727267593;s:8:\"response\";a:1:{s:27:\"woocommerce/woocommerce.php\";O:8:\"stdClass\":12:{s:2:\"id\";s:25:\"w.org/plugins/woocommerce\";s:4:\"slug\";s:11:\"woocommerce\";s:6:\"plugin\";s:27:\"woocommerce/woocommerce.php\";s:11:\"new_version\";s:5:\"8.3.0\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/woocommerce/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/woocommerce.8.3.0.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/woocommerce/assets/icon-256x256.gif?rev=2869506\";s:2:\"1x\";s:64:\"https://ps.w.org/woocommerce/assets/icon-128x128.gif?rev=2869506\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/woocommerce/assets/banner-1544x500.png?rev=2366418\";s:2:\"1x\";s:66:\"https://ps.w.org/woocommerce/assets/banner-772x250.png?rev=2366418\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.3\";s:6:\"tested\";s:5:\"6.4.1\";s:12:\"requires_php\";s:3:\"7.4\";}}s:12:\"translations\";a:0:{}s:9:\"no_update\";a:5:{s:33:\"coupon-creator/coupon_creator.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:28:\"w.org/plugins/coupon-creator\";s:4:\"slug\";s:14:\"coupon-creator\";s:6:\"plugin\";s:33:\"coupon-creator/coupon_creator.php\";s:11:\"new_version\";s:5:\"3.3.0\";s:3:\"url\";s:45:\"https://wordpress.org/plugins/coupon-creator/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/plugin/coupon-creator.3.3.0.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:59:\"https://ps.w.org/coupon-creator/assets/icon.svg?rev=2688941\";s:3:\"svg\";s:59:\"https://ps.w.org/coupon-creator/assets/icon.svg?rev=2688941\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:70:\"https://ps.w.org/coupon-creator/assets/banner-1544x500.png?rev=2688941\";s:2:\"1x\";s:69:\"https://ps.w.org/coupon-creator/assets/banner-772x250.png?rev=2688941\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.9\";}s:49:\"easy-digital-downloads/easy-digital-downloads.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:36:\"w.org/plugins/easy-digital-downloads\";s:4:\"slug\";s:22:\"easy-digital-downloads\";s:6:\"plugin\";s:49:\"easy-digital-downloads/easy-digital-downloads.php\";s:11:\"new_version\";s:5:\"3.2.5\";s:3:\"url\";s:53:\"https://wordpress.org/plugins/easy-digital-downloads/\";s:7:\"package\";s:71:\"https://downloads.wordpress.org/plugin/easy-digital-downloads.3.2.5.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:66:\"https://ps.w.org/easy-digital-downloads/assets/icon.svg?rev=971968\";s:3:\"svg\";s:66:\"https://ps.w.org/easy-digital-downloads/assets/icon.svg?rev=971968\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:78:\"https://ps.w.org/easy-digital-downloads/assets/banner-1544x500.png?rev=2636140\";s:2:\"1x\";s:77:\"https://ps.w.org/easy-digital-downloads/assets/banner-772x250.png?rev=2636140\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"5.8\";}s:31:\"event-tickets/event-tickets.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:27:\"w.org/plugins/event-tickets\";s:4:\"slug\";s:13:\"event-tickets\";s:6:\"plugin\";s:31:\"event-tickets/event-tickets.php\";s:11:\"new_version\";s:5:\"5.7.0\";s:3:\"url\";s:44:\"https://wordpress.org/plugins/event-tickets/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/plugin/event-tickets.5.7.0.zip\";s:5:\"icons\";a:2:{s:2:\"1x\";s:58:\"https://ps.w.org/event-tickets/assets/icon.svg?rev=2259340\";s:3:\"svg\";s:58:\"https://ps.w.org/event-tickets/assets/icon.svg?rev=2259340\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:69:\"https://ps.w.org/event-tickets/assets/banner-1544x500.png?rev=2257626\";s:2:\"1x\";s:68:\"https://ps.w.org/event-tickets/assets/banner-772x250.png?rev=2257626\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"6.2\";}s:27:\"redis-cache/redis-cache.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:25:\"w.org/plugins/redis-cache\";s:4:\"slug\";s:11:\"redis-cache\";s:6:\"plugin\";s:27:\"redis-cache/redis-cache.php\";s:11:\"new_version\";s:5:\"2.5.0\";s:3:\"url\";s:42:\"https://wordpress.org/plugins/redis-cache/\";s:7:\"package\";s:60:\"https://downloads.wordpress.org/plugin/redis-cache.2.5.0.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:64:\"https://ps.w.org/redis-cache/assets/icon-256x256.gif?rev=2568513\";s:2:\"1x\";s:64:\"https://ps.w.org/redis-cache/assets/icon-128x128.gif?rev=2568513\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:67:\"https://ps.w.org/redis-cache/assets/banner-1544x500.png?rev=2315420\";s:2:\"1x\";s:66:\"https://ps.w.org/redis-cache/assets/banner-772x250.png?rev=2315420\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:3:\"4.6\";}s:43:\"the-events-calendar/the-events-calendar.php\";O:8:\"stdClass\":10:{s:2:\"id\";s:33:\"w.org/plugins/the-events-calendar\";s:4:\"slug\";s:19:\"the-events-calendar\";s:6:\"plugin\";s:43:\"the-events-calendar/the-events-calendar.php\";s:11:\"new_version\";s:5:\"6.2.8\";s:3:\"url\";s:50:\"https://wordpress.org/plugins/the-events-calendar/\";s:7:\"package\";s:68:\"https://downloads.wordpress.org/plugin/the-events-calendar.6.2.8.zip\";s:5:\"icons\";a:2:{s:2:\"2x\";s:72:\"https://ps.w.org/the-events-calendar/assets/icon-256x256.gif?rev=2516440\";s:2:\"1x\";s:72:\"https://ps.w.org/the-events-calendar/assets/icon-128x128.gif?rev=2516440\";}s:7:\"banners\";a:2:{s:2:\"2x\";s:75:\"https://ps.w.org/the-events-calendar/assets/banner-1544x500.png?rev=2257622\";s:2:\"1x\";s:74:\"https://ps.w.org/the-events-calendar/assets/banner-772x250.png?rev=2257622\";}s:11:\"banners_rtl\";a:0:{}s:8:\"requires\";s:5:\"6.2.0\";}}s:7:\"checked\";a:15:{s:33:\"ai-development/ai-development.php\";s:5:\"0.1.0\";s:33:\"coupon-creator/coupon_creator.php\";s:5:\"3.3.0\";s:49:\"coupon-creator-add-ons/coupon-creator-add-ons.php\";s:5:\"3.3.0\";s:41:\"coupon-creator-pro/coupon-creator-pro.php\";s:5:\"3.3.0\";s:49:\"easy-digital-downloads/easy-digital-downloads.php\";s:5:\"3.2.5\";s:31:\"event-tickets/event-tickets.php\";s:5:\"5.7.0\";s:41:\"event-tickets-plus/event-tickets-plus.php\";s:5:\"5.8.0\";s:27:\"redis-cache/redis-cache.php\";s:5:\"2.5.0\";s:43:\"the-events-calendar/the-events-calendar.php\";s:5:\"6.2.8\";s:35:\"event-automator/event-automator.php\";s:5:\"1.3.1\";s:33:\"events-virtual/events-virtual.php\";s:6:\"1.15.5\";s:34:\"events-pro/events-calendar-pro.php\";s:5:\"6.2.4\";s:29:\"volt-vectors/volt-vectors.php\";s:5:\"0.1.0\";s:37:\"volt-vectors-pro/volt-vectors-pro.php\";s:5:\"0.1.0\";s:27:\"woocommerce/woocommerce.php\";s:5:\"8.2.2\";}}','no'),(987,'_site_transient_update_core','O:8:\"stdClass\":4:{s:7:\"updates\";a:1:{i:0;O:8:\"stdClass\":10:{s:8:\"response\";s:6:\"latest\";s:8:\"download\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.4.1.zip\";s:6:\"locale\";s:5:\"en_US\";s:8:\"packages\";O:8:\"stdClass\":5:{s:4:\"full\";s:59:\"https://downloads.wordpress.org/release/wordpress-6.4.1.zip\";s:10:\"no_content\";s:70:\"https://downloads.wordpress.org/release/wordpress-6.4.1-no-content.zip\";s:11:\"new_bundled\";s:71:\"https://downloads.wordpress.org/release/wordpress-6.4.1-new-bundled.zip\";s:7:\"partial\";s:0:\"\";s:8:\"rollback\";s:0:\"\";}s:7:\"current\";s:5:\"6.4.1\";s:7:\"version\";s:5:\"6.4.1\";s:11:\"php_version\";s:5:\"7.0.0\";s:13:\"mysql_version\";s:3:\"5.0\";s:11:\"new_bundled\";s:3:\"6.4\";s:15:\"partial_version\";s:0:\"\";}}s:12:\"last_checked\";i:1727267587;s:15:\"version_checked\";s:5:\"6.4.1\";s:12:\"translations\";a:0:{}}','no'),(988,'wp_attachment_pages_enabled','1','yes'),(992,'can_compress_scripts','0','yes'),(993,'_site_transient_update_themes','O:8:\"stdClass\":5:{s:12:\"last_checked\";i:1727267587;s:7:\"checked\";a:4:{s:16:\"twentytwentyfour\";s:3:\"1.0\";s:15:\"twentytwentyone\";s:3:\"2.0\";s:17:\"twentytwentythree\";s:3:\"1.3\";s:15:\"twentytwentytwo\";s:3:\"1.6\";}s:8:\"response\";a:0:{}s:9:\"no_update\";a:4:{s:16:\"twentytwentyfour\";a:6:{s:5:\"theme\";s:16:\"twentytwentyfour\";s:11:\"new_version\";s:3:\"1.0\";s:3:\"url\";s:46:\"https://wordpress.org/themes/twentytwentyfour/\";s:7:\"package\";s:62:\"https://downloads.wordpress.org/theme/twentytwentyfour.1.0.zip\";s:8:\"requires\";s:3:\"6.4\";s:12:\"requires_php\";s:3:\"7.0\";}s:15:\"twentytwentyone\";a:6:{s:5:\"theme\";s:15:\"twentytwentyone\";s:11:\"new_version\";s:3:\"2.0\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentyone/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentyone.2.0.zip\";s:8:\"requires\";s:3:\"5.3\";s:12:\"requires_php\";s:3:\"5.6\";}s:17:\"twentytwentythree\";a:6:{s:5:\"theme\";s:17:\"twentytwentythree\";s:11:\"new_version\";s:3:\"1.3\";s:3:\"url\";s:47:\"https://wordpress.org/themes/twentytwentythree/\";s:7:\"package\";s:63:\"https://downloads.wordpress.org/theme/twentytwentythree.1.3.zip\";s:8:\"requires\";s:3:\"6.1\";s:12:\"requires_php\";s:3:\"5.6\";}s:15:\"twentytwentytwo\";a:6:{s:5:\"theme\";s:15:\"twentytwentytwo\";s:11:\"new_version\";s:3:\"1.6\";s:3:\"url\";s:45:\"https://wordpress.org/themes/twentytwentytwo/\";s:7:\"package\";s:61:\"https://downloads.wordpress.org/theme/twentytwentytwo.1.6.zip\";s:8:\"requires\";s:3:\"5.9\";s:12:\"requires_php\";s:3:\"5.6\";}}s:12:\"translations\";a:0:{}}','no'),(1004,'recovery_mode_email_last_sent','1700228527','yes'),(1019,'wc_remote_inbox_notifications_wca_updated','','no'),(1036,'tec_timed_events_timezone_update_needed','a:3:{s:3:\"key\";s:29:\"events_timezone_update_needed\";s:5:\"value\";b:0;s:10:\"expiration\";i:1700316213;}','yes'),(1047,'tribe_feature_support_check_lock','1','yes'),(1058,'woocommerce_product_match_featured_image_by_sku','no','yes'),(1059,'woocommerce_feature_order_attribution_enabled','yes','yes'),(1060,'woocommerce_hpos_fts_index_enabled','no','yes'),(1061,'woocommerce_store_id','96c28bc3-48bf-41eb-9737-dbb695f270ce','yes'),(1062,'woocommerce_remote_variant_assignment','59','yes'),(1063,'woocommerce_attribute_lookup_regeneration_in_progress','yes','yes'),(1064,'woocommerce_attribute_lookup_last_product_id_to_process','19','yes'),(1065,'woocommerce_attribute_lookup_processed_count','0','yes'),(1066,'_transient_timeout__woocommerce_upload_directory_status','1721244733','no'),(1067,'_transient__woocommerce_upload_directory_status','protected','no'),(1072,'rewrite_rules','a:498:{s:21:\"tickets/([0-9]{1,})/?\";s:43:\"index.php?p=$matches[1]&tribe-edit-orders=1\";s:29:\"(?:attendee\\-registration)/?$\";s:33:\"index.php?attendee-registration=1\";s:28:\"tribe/events/kitchen-sink/?$\";s:69:\"index.php?post_type=tribe_events&tribe_events_views_kitchen_sink=page\";s:93:\"tribe/events/kitchen-sink/(page|grid|typographical|elements|events-bar|navigation|manager)/?$\";s:76:\"index.php?post_type=tribe_events&tribe_events_views_kitchen_sink=$matches[1]\";s:28:\"event-aggregator/(insert)/?$\";s:53:\"index.php?tribe-aggregator=1&tribe-action=$matches[1]\";s:25:\"(?:event)/([^/]+)/ical/?$\";s:56:\"index.php?ical=1&name=$matches[1]&post_type=tribe_events\";s:28:\"(?:events)/(?:page)/(\\d+)/?$\";s:71:\"index.php?post_type=tribe_events&eventDisplay=default&paged=$matches[1]\";s:41:\"(?:events)/(?:featured)/(?:page)/(\\d+)/?$\";s:79:\"index.php?post_type=tribe_events&featured=1&eventDisplay=list&paged=$matches[1]\";s:38:\"(?:events)/(feed|rdf|rss|rss2|atom)/?$\";s:67:\"index.php?post_type=tribe_events&eventDisplay=list&feed=$matches[1]\";s:51:\"(?:events)/(?:featured)/(feed|rdf|rss|rss2|atom)/?$\";s:78:\"index.php?post_type=tribe_events&featured=1&eventDisplay=list&feed=$matches[1]\";s:23:\"(?:events)/(?:month)/?$\";s:51:\"index.php?post_type=tribe_events&eventDisplay=month\";s:36:\"(?:events)/(?:month)/(?:featured)/?$\";s:62:\"index.php?post_type=tribe_events&eventDisplay=month&featured=1\";s:37:\"(?:events)/(?:month)/(\\d{4}-\\d{2})/?$\";s:73:\"index.php?post_type=tribe_events&eventDisplay=month&eventDate=$matches[1]\";s:37:\"(?:events)/(?:list)/(?:page)/(\\d+)/?$\";s:68:\"index.php?post_type=tribe_events&eventDisplay=list&paged=$matches[1]\";s:50:\"(?:events)/(?:list)/(?:featured)/(?:page)/(\\d+)/?$\";s:79:\"index.php?post_type=tribe_events&eventDisplay=list&featured=1&paged=$matches[1]\";s:22:\"(?:events)/(?:list)/?$\";s:50:\"index.php?post_type=tribe_events&eventDisplay=list\";s:35:\"(?:events)/(?:list)/(?:featured)/?$\";s:61:\"index.php?post_type=tribe_events&eventDisplay=list&featured=1\";s:23:\"(?:events)/(?:today)/?$\";s:49:\"index.php?post_type=tribe_events&eventDisplay=day\";s:36:\"(?:events)/(?:today)/(?:featured)/?$\";s:60:\"index.php?post_type=tribe_events&eventDisplay=day&featured=1\";s:27:\"(?:events)/(\\d{4}-\\d{2})/?$\";s:73:\"index.php?post_type=tribe_events&eventDisplay=month&eventDate=$matches[1]\";s:40:\"(?:events)/(\\d{4}-\\d{2})/(?:featured)/?$\";s:84:\"index.php?post_type=tribe_events&eventDisplay=month&eventDate=$matches[1]&featured=1\";s:33:\"(?:events)/(\\d{4}-\\d{2}-\\d{2})/?$\";s:71:\"index.php?post_type=tribe_events&eventDisplay=day&eventDate=$matches[1]\";s:46:\"(?:events)/(\\d{4}-\\d{2}-\\d{2})/(?:featured)/?$\";s:82:\"index.php?post_type=tribe_events&eventDisplay=day&eventDate=$matches[1]&featured=1\";s:26:\"(?:events)/(?:featured)/?$\";s:43:\"index.php?post_type=tribe_events&featured=1\";s:13:\"(?:events)/?$\";s:53:\"index.php?post_type=tribe_events&eventDisplay=default\";s:18:\"(?:events)/ical/?$\";s:39:\"index.php?post_type=tribe_events&ical=1\";s:31:\"(?:events)/(?:featured)/ical/?$\";s:50:\"index.php?post_type=tribe_events&ical=1&featured=1\";s:38:\"(?:events)/(\\d{4}-\\d{2}-\\d{2})/ical/?$\";s:78:\"index.php?post_type=tribe_events&ical=1&eventDisplay=day&eventDate=$matches[1]\";s:51:\"(?:events)/(\\d{4}-\\d{2}-\\d{2})/ical/(?:featured)/?$\";s:89:\"index.php?post_type=tribe_events&ical=1&eventDisplay=day&eventDate=$matches[1]&featured=1\";s:60:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:page)/(\\d+)/?$\";s:97:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list&paged=$matches[2]\";s:73:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:featured)/(?:page)/(\\d+)/?$\";s:108:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&featured=1&eventDisplay=list&paged=$matches[2]\";s:55:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:month)/?$\";s:80:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=month\";s:68:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:month)/(?:featured)/?$\";s:91:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=month&featured=1\";s:69:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:list)/(?:page)/(\\d+)/?$\";s:97:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list&paged=$matches[2]\";s:82:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:list)/(?:featured)/(?:page)/(\\d+)/?$\";s:108:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list&featured=1&paged=$matches[2]\";s:54:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:list)/?$\";s:79:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list\";s:67:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:list)/(?:featured)/?$\";s:90:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list&featured=1\";s:55:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:today)/?$\";s:78:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=day\";s:68:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:today)/(?:featured)/?$\";s:89:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=day&featured=1\";s:73:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:day)/(\\d{4}-\\d{2}-\\d{2})/?$\";s:100:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=day&eventDate=$matches[2]\";s:86:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:day)/(\\d{4}-\\d{2}-\\d{2})/(?:featured)/?$\";s:111:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=day&eventDate=$matches[2]&featured=1\";s:59:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(\\d{4}-\\d{2})/?$\";s:102:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=month&eventDate=$matches[2]\";s:72:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(\\d{4}-\\d{2})/(?:featured)/?$\";s:113:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=month&eventDate=$matches[2]&featured=1\";s:65:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(\\d{4}-\\d{2}-\\d{2})/?$\";s:100:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=day&eventDate=$matches[2]\";s:78:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(\\d{4}-\\d{2}-\\d{2})/(?:featured)/?$\";s:111:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=day&eventDate=$matches[2]&featured=1\";s:50:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/feed/?$\";s:89:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list&feed=rss2\";s:63:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:featured)/feed/?$\";s:100:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&featured=1&eventDisplay=list&feed=rss2\";s:50:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/ical/?$\";s:68:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&ical=1\";s:63:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:featured)/ical/?$\";s:79:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&featured=1&ical=1\";s:75:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:78:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&feed=$matches[2]\";s:88:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:featured)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:89:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&featured=1&feed=$matches[2]\";s:58:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/(?:featured)/?$\";s:93:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&featured=1&eventDisplay=default\";s:45:\"(?:events)/(?:category)/(?:[^/]+/)*([^/]+)/?$\";s:82:\"index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=default\";s:44:\"(?:events)/(?:tag)/([^/]+)/(?:page)/(\\d+)/?$\";s:84:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&paged=$matches[2]\";s:57:\"(?:events)/(?:tag)/([^/]+)/(?:featured)/(?:page)/(\\d+)/?$\";s:95:\"index.php?post_type=tribe_events&tag=$matches[1]&featured=1&eventDisplay=list&paged=$matches[2]\";s:39:\"(?:events)/(?:tag)/([^/]+)/(?:month)/?$\";s:67:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=month\";s:52:\"(?:events)/(?:tag)/([^/]+)/(?:month)/(?:featured)/?$\";s:78:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=month&featured=1\";s:53:\"(?:events)/(?:tag)/([^/]+)/(?:list)/(?:page)/(\\d+)/?$\";s:84:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&paged=$matches[2]\";s:66:\"(?:events)/(?:tag)/([^/]+)/(?:list)/(?:featured)/(?:page)/(\\d+)/?$\";s:95:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&featured=1&paged=$matches[2]\";s:38:\"(?:events)/(?:tag)/([^/]+)/(?:list)/?$\";s:66:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list\";s:51:\"(?:events)/(?:tag)/([^/]+)/(?:list)/(?:featured)/?$\";s:77:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&featured=1\";s:39:\"(?:events)/(?:tag)/([^/]+)/(?:today)/?$\";s:65:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=day\";s:52:\"(?:events)/(?:tag)/([^/]+)/(?:today)/(?:featured)/?$\";s:76:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=day&featured=1\";s:57:\"(?:events)/(?:tag)/([^/]+)/(?:day)/(\\d{4}-\\d{2}-\\d{2})/?$\";s:87:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=day&eventDate=$matches[2]\";s:70:\"(?:events)/(?:tag)/([^/]+)/(?:day)/(\\d{4}-\\d{2}-\\d{2})/(?:featured)/?$\";s:98:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=day&eventDate=$matches[2]&featured=1\";s:43:\"(?:events)/(?:tag)/([^/]+)/(\\d{4}-\\d{2})/?$\";s:89:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=month&eventDate=$matches[2]\";s:56:\"(?:events)/(?:tag)/([^/]+)/(\\d{4}-\\d{2})/(?:featured)/?$\";s:100:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=month&eventDate=$matches[2]&featured=1\";s:49:\"(?:events)/(?:tag)/([^/]+)/(\\d{4}-\\d{2}-\\d{2})/?$\";s:87:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=day&eventDate=$matches[2]\";s:62:\"(?:events)/(?:tag)/([^/]+)/(\\d{4}-\\d{2}-\\d{2})/(?:featured)/?$\";s:98:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=day&eventDate=$matches[2]&featured=1\";s:34:\"(?:events)/(?:tag)/([^/]+)/feed/?$\";s:76:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&feed=rss2\";s:47:\"(?:events)/(?:tag)/([^/]+)/(?:featured)/feed/?$\";s:87:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&feed=rss2&featured=1\";s:34:\"(?:events)/(?:tag)/([^/]+)/ical/?$\";s:55:\"index.php?post_type=tribe_events&tag=$matches[1]&ical=1\";s:47:\"(?:events)/(?:tag)/([^/]+)/(?:featured)/ical/?$\";s:66:\"index.php?post_type=tribe_events&tag=$matches[1]&featured=1&ical=1\";s:59:\"(?:events)/(?:tag)/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:65:\"index.php?post_type=tribe_events&tag=$matches[1]&feed=$matches[2]\";s:72:\"(?:events)/(?:tag)/([^/]+)/(?:featured)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:76:\"index.php?post_type=tribe_events&tag=$matches[1]&featured=1&feed=$matches[2]\";s:42:\"(?:events)/(?:tag)/([^/]+)/(?:featured)/?$\";s:59:\"index.php?post_type=tribe_events&tag=$matches[1]&featured=1\";s:29:\"(?:events)/(?:tag)/([^/]+)/?$\";s:69:\"index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=default\";s:32:\"(?:event)/([^/]+)/(?:tickets)/?$\";s:78:\"index.php?tribe_events=$matches[1]&post_type=tribe_events&eventDisplay=tickets\";s:52:\"(?:event)/([^/]+)/(\\d{4}-\\d{2}-\\d{2})/(?:tickets)/?$\";s:100:\"index.php?tribe_events=$matches[1]&eventDate=$matches[2]&post_type=tribe_events&eventDisplay=tickets\";s:24:\"^wc-auth/v([1]{1})/(.*)?\";s:63:\"index.php?wc-auth-version=$matches[1]&wc-auth-route=$matches[2]\";s:22:\"^wc-api/v([1-3]{1})/?$\";s:51:\"index.php?wc-api-version=$matches[1]&wc-api-route=/\";s:24:\"^wc-api/v([1-3]{1})(.*)?\";s:61:\"index.php?wc-api-version=$matches[1]&wc-api-route=$matches[2]\";s:12:\"downloads/?$\";s:28:\"index.php?post_type=download\";s:42:\"downloads/feed/(feed|rdf|rss|rss2|atom)/?$\";s:45:\"index.php?post_type=download&feed=$matches[1]\";s:37:\"downloads/(feed|rdf|rss|rss2|atom)/?$\";s:45:\"index.php?post_type=download&feed=$matches[1]\";s:29:\"downloads/page/([0-9]{1,})/?$\";s:46:\"index.php?post_type=download&paged=$matches[1]\";s:7:\"shop/?$\";s:27:\"index.php?post_type=product\";s:37:\"shop/feed/(feed|rdf|rss|rss2|atom)/?$\";s:44:\"index.php?post_type=product&feed=$matches[1]\";s:32:\"shop/(feed|rdf|rss|rss2|atom)/?$\";s:44:\"index.php?post_type=product&feed=$matches[1]\";s:24:\"shop/page/([0-9]{1,})/?$\";s:45:\"index.php?post_type=product&paged=$matches[1]\";s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:21:\"^index.php/wp-json/?$\";s:22:\"index.php?rest_route=/\";s:24:\"^index.php/wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:17:\"^wp-sitemap\\.xml$\";s:23:\"index.php?sitemap=index\";s:17:\"^wp-sitemap\\.xsl$\";s:36:\"index.php?sitemap-stylesheet=sitemap\";s:23:\"^wp-sitemap-index\\.xsl$\";s:34:\"index.php?sitemap-stylesheet=index\";s:48:\"^wp-sitemap-([a-z]+?)-([a-z\\d_-]+?)-(\\d+?)\\.xml$\";s:75:\"index.php?sitemap=$matches[1]&sitemap-subtype=$matches[2]&paged=$matches[3]\";s:34:\"^wp-sitemap-([a-z]+?)-(\\d+?)\\.xml$\";s:47:\"index.php?sitemap=$matches[1]&paged=$matches[2]\";s:22:\"tribe-promoter-auth/?$\";s:37:\"index.php?tribe-promoter-auth-check=1\";s:8:\"event/?$\";s:32:\"index.php?post_type=tribe_events\";s:38:\"event/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?post_type=tribe_events&feed=$matches[1]\";s:33:\"event/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?post_type=tribe_events&feed=$matches[1]\";s:25:\"event/page/([0-9]{1,})/?$\";s:50:\"index.php?post_type=tribe_events&paged=$matches[1]\";s:47:\"category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:42:\"category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:23:\"category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:35:\"category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:32:\"category/(.+?)/wc-api(/(.*))?/?$\";s:54:\"index.php?category_name=$matches[1]&wc-api=$matches[3]\";s:33:\"category/(.+?)/edd-add(/(.*))?/?$\";s:55:\"index.php?category_name=$matches[1]&edd-add=$matches[3]\";s:36:\"category/(.+?)/edd-remove(/(.*))?/?$\";s:58:\"index.php?category_name=$matches[1]&edd-remove=$matches[3]\";s:33:\"category/(.+?)/edd-api(/(.*))?/?$\";s:55:\"index.php?category_name=$matches[1]&edd-api=$matches[3]\";s:17:\"category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:44:\"tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:39:\"tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:20:\"tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:32:\"tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:29:\"tag/([^/]+)/wc-api(/(.*))?/?$\";s:44:\"index.php?tag=$matches[1]&wc-api=$matches[3]\";s:30:\"tag/([^/]+)/edd-add(/(.*))?/?$\";s:45:\"index.php?tag=$matches[1]&edd-add=$matches[3]\";s:33:\"tag/([^/]+)/edd-remove(/(.*))?/?$\";s:48:\"index.php?tag=$matches[1]&edd-remove=$matches[3]\";s:30:\"tag/([^/]+)/edd-api(/(.*))?/?$\";s:45:\"index.php?tag=$matches[1]&edd-api=$matches[3]\";s:14:\"tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:45:\"type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:40:\"type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:21:\"type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:33:\"type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:15:\"type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:57:\"downloads/category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:56:\"index.php?download_category=$matches[1]&feed=$matches[2]\";s:52:\"downloads/category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:56:\"index.php?download_category=$matches[1]&feed=$matches[2]\";s:33:\"downloads/category/(.+?)/embed/?$\";s:50:\"index.php?download_category=$matches[1]&embed=true\";s:45:\"downloads/category/(.+?)/page/?([0-9]{1,})/?$\";s:57:\"index.php?download_category=$matches[1]&paged=$matches[2]\";s:27:\"downloads/category/(.+?)/?$\";s:39:\"index.php?download_category=$matches[1]\";s:54:\"downloads/tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:51:\"index.php?download_tag=$matches[1]&feed=$matches[2]\";s:49:\"downloads/tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:51:\"index.php?download_tag=$matches[1]&feed=$matches[2]\";s:30:\"downloads/tag/([^/]+)/embed/?$\";s:45:\"index.php?download_tag=$matches[1]&embed=true\";s:42:\"downloads/tag/([^/]+)/page/?([0-9]{1,})/?$\";s:52:\"index.php?download_tag=$matches[1]&paged=$matches[2]\";s:24:\"downloads/tag/([^/]+)/?$\";s:34:\"index.php?download_tag=$matches[1]\";s:37:\"downloads/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:47:\"downloads/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:67:\"downloads/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"downloads/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"downloads/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:43:\"downloads/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:26:\"downloads/([^/]+)/embed/?$\";s:41:\"index.php?download=$matches[1]&embed=true\";s:30:\"downloads/([^/]+)/trackback/?$\";s:35:\"index.php?download=$matches[1]&tb=1\";s:50:\"downloads/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?download=$matches[1]&feed=$matches[2]\";s:45:\"downloads/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?download=$matches[1]&feed=$matches[2]\";s:38:\"downloads/([^/]+)/page/?([0-9]{1,})/?$\";s:48:\"index.php?download=$matches[1]&paged=$matches[2]\";s:45:\"downloads/([^/]+)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?download=$matches[1]&cpage=$matches[2]\";s:35:\"downloads/([^/]+)/wc-api(/(.*))?/?$\";s:49:\"index.php?download=$matches[1]&wc-api=$matches[3]\";s:36:\"downloads/([^/]+)/edd-add(/(.*))?/?$\";s:50:\"index.php?download=$matches[1]&edd-add=$matches[3]\";s:39:\"downloads/([^/]+)/edd-remove(/(.*))?/?$\";s:53:\"index.php?download=$matches[1]&edd-remove=$matches[3]\";s:36:\"downloads/([^/]+)/edd-api(/(.*))?/?$\";s:50:\"index.php?download=$matches[1]&edd-api=$matches[3]\";s:41:\"downloads/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:52:\"downloads/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:42:\"downloads/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:53:\"downloads/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:45:\"downloads/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:56:\"downloads/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:42:\"downloads/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:53:\"downloads/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:34:\"downloads/([^/]+)(?:/([0-9]+))?/?$\";s:47:\"index.php?download=$matches[1]&page=$matches[2]\";s:26:\"downloads/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:36:\"downloads/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:56:\"downloads/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"downloads/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"downloads/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:32:\"downloads/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:55:\"product-category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_cat=$matches[1]&feed=$matches[2]\";s:50:\"product-category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_cat=$matches[1]&feed=$matches[2]\";s:31:\"product-category/(.+?)/embed/?$\";s:44:\"index.php?product_cat=$matches[1]&embed=true\";s:43:\"product-category/(.+?)/page/?([0-9]{1,})/?$\";s:51:\"index.php?product_cat=$matches[1]&paged=$matches[2]\";s:25:\"product-category/(.+?)/?$\";s:33:\"index.php?product_cat=$matches[1]\";s:52:\"product-tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_tag=$matches[1]&feed=$matches[2]\";s:47:\"product-tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?product_tag=$matches[1]&feed=$matches[2]\";s:28:\"product-tag/([^/]+)/embed/?$\";s:44:\"index.php?product_tag=$matches[1]&embed=true\";s:40:\"product-tag/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?product_tag=$matches[1]&paged=$matches[2]\";s:22:\"product-tag/([^/]+)/?$\";s:33:\"index.php?product_tag=$matches[1]\";s:35:\"product/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:45:\"product/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:65:\"product/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:60:\"product/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:60:\"product/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:41:\"product/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:24:\"product/([^/]+)/embed/?$\";s:40:\"index.php?product=$matches[1]&embed=true\";s:28:\"product/([^/]+)/trackback/?$\";s:34:\"index.php?product=$matches[1]&tb=1\";s:48:\"product/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:46:\"index.php?product=$matches[1]&feed=$matches[2]\";s:43:\"product/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:46:\"index.php?product=$matches[1]&feed=$matches[2]\";s:36:\"product/([^/]+)/page/?([0-9]{1,})/?$\";s:47:\"index.php?product=$matches[1]&paged=$matches[2]\";s:43:\"product/([^/]+)/comment-page-([0-9]{1,})/?$\";s:47:\"index.php?product=$matches[1]&cpage=$matches[2]\";s:33:\"product/([^/]+)/wc-api(/(.*))?/?$\";s:48:\"index.php?product=$matches[1]&wc-api=$matches[3]\";s:34:\"product/([^/]+)/edd-add(/(.*))?/?$\";s:49:\"index.php?product=$matches[1]&edd-add=$matches[3]\";s:37:\"product/([^/]+)/edd-remove(/(.*))?/?$\";s:52:\"index.php?product=$matches[1]&edd-remove=$matches[3]\";s:34:\"product/([^/]+)/edd-api(/(.*))?/?$\";s:49:\"index.php?product=$matches[1]&edd-api=$matches[3]\";s:39:\"product/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:50:\"product/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:40:\"product/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:51:\"product/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:43:\"product/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:54:\"product/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:40:\"product/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:51:\"product/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:32:\"product/([^/]+)(?:/([0-9]+))?/?$\";s:46:\"index.php?product=$matches[1]&page=$matches[2]\";s:24:\"product/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:34:\"product/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:54:\"product/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:49:\"product/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:49:\"product/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:30:\"product/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:48:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:58:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:78:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:73:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:73:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:54:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:37:\"ticket-meta-fieldset/([^/]+)/embed/?$\";s:53:\"index.php?ticket-meta-fieldset=$matches[1]&embed=true\";s:41:\"ticket-meta-fieldset/([^/]+)/trackback/?$\";s:47:\"index.php?ticket-meta-fieldset=$matches[1]&tb=1\";s:49:\"ticket-meta-fieldset/([^/]+)/page/?([0-9]{1,})/?$\";s:60:\"index.php?ticket-meta-fieldset=$matches[1]&paged=$matches[2]\";s:56:\"ticket-meta-fieldset/([^/]+)/comment-page-([0-9]{1,})/?$\";s:60:\"index.php?ticket-meta-fieldset=$matches[1]&cpage=$matches[2]\";s:46:\"ticket-meta-fieldset/([^/]+)/wc-api(/(.*))?/?$\";s:61:\"index.php?ticket-meta-fieldset=$matches[1]&wc-api=$matches[3]\";s:47:\"ticket-meta-fieldset/([^/]+)/edd-add(/(.*))?/?$\";s:62:\"index.php?ticket-meta-fieldset=$matches[1]&edd-add=$matches[3]\";s:50:\"ticket-meta-fieldset/([^/]+)/edd-remove(/(.*))?/?$\";s:65:\"index.php?ticket-meta-fieldset=$matches[1]&edd-remove=$matches[3]\";s:47:\"ticket-meta-fieldset/([^/]+)/edd-api(/(.*))?/?$\";s:62:\"index.php?ticket-meta-fieldset=$matches[1]&edd-api=$matches[3]\";s:52:\"ticket-meta-fieldset/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:63:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:53:\"ticket-meta-fieldset/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:64:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:56:\"ticket-meta-fieldset/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:67:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:53:\"ticket-meta-fieldset/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:64:\"ticket-meta-fieldset/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:45:\"ticket-meta-fieldset/([^/]+)(?:/([0-9]+))?/?$\";s:59:\"index.php?ticket-meta-fieldset=$matches[1]&page=$matches[2]\";s:37:\"ticket-meta-fieldset/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:47:\"ticket-meta-fieldset/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:67:\"ticket-meta-fieldset/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"ticket-meta-fieldset/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"ticket-meta-fieldset/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:43:\"ticket-meta-fieldset/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:33:\"venue/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:43:\"venue/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:63:\"venue/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:58:\"venue/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:58:\"venue/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:39:\"venue/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:22:\"venue/([^/]+)/embed/?$\";s:44:\"index.php?tribe_venue=$matches[1]&embed=true\";s:26:\"venue/([^/]+)/trackback/?$\";s:38:\"index.php?tribe_venue=$matches[1]&tb=1\";s:34:\"venue/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?tribe_venue=$matches[1]&paged=$matches[2]\";s:41:\"venue/([^/]+)/comment-page-([0-9]{1,})/?$\";s:51:\"index.php?tribe_venue=$matches[1]&cpage=$matches[2]\";s:31:\"venue/([^/]+)/wc-api(/(.*))?/?$\";s:52:\"index.php?tribe_venue=$matches[1]&wc-api=$matches[3]\";s:32:\"venue/([^/]+)/edd-add(/(.*))?/?$\";s:53:\"index.php?tribe_venue=$matches[1]&edd-add=$matches[3]\";s:35:\"venue/([^/]+)/edd-remove(/(.*))?/?$\";s:56:\"index.php?tribe_venue=$matches[1]&edd-remove=$matches[3]\";s:32:\"venue/([^/]+)/edd-api(/(.*))?/?$\";s:53:\"index.php?tribe_venue=$matches[1]&edd-api=$matches[3]\";s:37:\"venue/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:48:\"venue/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:38:\"venue/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:49:\"venue/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:41:\"venue/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:52:\"venue/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:38:\"venue/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:49:\"venue/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:30:\"venue/([^/]+)(?:/([0-9]+))?/?$\";s:50:\"index.php?tribe_venue=$matches[1]&page=$matches[2]\";s:22:\"venue/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:32:\"venue/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:52:\"venue/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:47:\"venue/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:47:\"venue/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:28:\"venue/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:37:\"organizer/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:47:\"organizer/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:67:\"organizer/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"organizer/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"organizer/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:43:\"organizer/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:26:\"organizer/([^/]+)/embed/?$\";s:48:\"index.php?tribe_organizer=$matches[1]&embed=true\";s:30:\"organizer/([^/]+)/trackback/?$\";s:42:\"index.php?tribe_organizer=$matches[1]&tb=1\";s:38:\"organizer/([^/]+)/page/?([0-9]{1,})/?$\";s:55:\"index.php?tribe_organizer=$matches[1]&paged=$matches[2]\";s:45:\"organizer/([^/]+)/comment-page-([0-9]{1,})/?$\";s:55:\"index.php?tribe_organizer=$matches[1]&cpage=$matches[2]\";s:35:\"organizer/([^/]+)/wc-api(/(.*))?/?$\";s:56:\"index.php?tribe_organizer=$matches[1]&wc-api=$matches[3]\";s:36:\"organizer/([^/]+)/edd-add(/(.*))?/?$\";s:57:\"index.php?tribe_organizer=$matches[1]&edd-add=$matches[3]\";s:39:\"organizer/([^/]+)/edd-remove(/(.*))?/?$\";s:60:\"index.php?tribe_organizer=$matches[1]&edd-remove=$matches[3]\";s:36:\"organizer/([^/]+)/edd-api(/(.*))?/?$\";s:57:\"index.php?tribe_organizer=$matches[1]&edd-api=$matches[3]\";s:41:\"organizer/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:52:\"organizer/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:42:\"organizer/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:53:\"organizer/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:45:\"organizer/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:56:\"organizer/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:42:\"organizer/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:53:\"organizer/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:34:\"organizer/([^/]+)(?:/([0-9]+))?/?$\";s:54:\"index.php?tribe_organizer=$matches[1]&page=$matches[2]\";s:26:\"organizer/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:36:\"organizer/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:56:\"organizer/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"organizer/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"organizer/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:32:\"organizer/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:33:\"event/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:43:\"event/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:63:\"event/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:58:\"event/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:58:\"event/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:39:\"event/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:22:\"event/([^/]+)/embed/?$\";s:45:\"index.php?tribe_events=$matches[1]&embed=true\";s:26:\"event/([^/]+)/trackback/?$\";s:39:\"index.php?tribe_events=$matches[1]&tb=1\";s:46:\"event/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:51:\"index.php?tribe_events=$matches[1]&feed=$matches[2]\";s:41:\"event/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:51:\"index.php?tribe_events=$matches[1]&feed=$matches[2]\";s:34:\"event/([^/]+)/page/?([0-9]{1,})/?$\";s:52:\"index.php?tribe_events=$matches[1]&paged=$matches[2]\";s:41:\"event/([^/]+)/comment-page-([0-9]{1,})/?$\";s:52:\"index.php?tribe_events=$matches[1]&cpage=$matches[2]\";s:31:\"event/([^/]+)/wc-api(/(.*))?/?$\";s:53:\"index.php?tribe_events=$matches[1]&wc-api=$matches[3]\";s:32:\"event/([^/]+)/edd-add(/(.*))?/?$\";s:54:\"index.php?tribe_events=$matches[1]&edd-add=$matches[3]\";s:35:\"event/([^/]+)/edd-remove(/(.*))?/?$\";s:57:\"index.php?tribe_events=$matches[1]&edd-remove=$matches[3]\";s:32:\"event/([^/]+)/edd-api(/(.*))?/?$\";s:54:\"index.php?tribe_events=$matches[1]&edd-api=$matches[3]\";s:37:\"event/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:48:\"event/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:38:\"event/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:49:\"event/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:41:\"event/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:52:\"event/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:38:\"event/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:49:\"event/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:30:\"event/([^/]+)(?:/([0-9]+))?/?$\";s:51:\"index.php?tribe_events=$matches[1]&page=$matches[2]\";s:22:\"event/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:32:\"event/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:52:\"event/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:47:\"event/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:47:\"event/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:28:\"event/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:54:\"events/category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:55:\"index.php?tribe_events_cat=$matches[1]&feed=$matches[2]\";s:49:\"events/category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:55:\"index.php?tribe_events_cat=$matches[1]&feed=$matches[2]\";s:30:\"events/category/(.+?)/embed/?$\";s:49:\"index.php?tribe_events_cat=$matches[1]&embed=true\";s:42:\"events/category/(.+?)/page/?([0-9]{1,})/?$\";s:56:\"index.php?tribe_events_cat=$matches[1]&paged=$matches[2]\";s:24:\"events/category/(.+?)/?$\";s:38:\"index.php?tribe_events_cat=$matches[1]\";s:41:\"deleted_event/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:51:\"deleted_event/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:71:\"deleted_event/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:66:\"deleted_event/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:66:\"deleted_event/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:47:\"deleted_event/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:30:\"deleted_event/([^/]+)/embed/?$\";s:46:\"index.php?deleted_event=$matches[1]&embed=true\";s:34:\"deleted_event/([^/]+)/trackback/?$\";s:40:\"index.php?deleted_event=$matches[1]&tb=1\";s:42:\"deleted_event/([^/]+)/page/?([0-9]{1,})/?$\";s:53:\"index.php?deleted_event=$matches[1]&paged=$matches[2]\";s:49:\"deleted_event/([^/]+)/comment-page-([0-9]{1,})/?$\";s:53:\"index.php?deleted_event=$matches[1]&cpage=$matches[2]\";s:39:\"deleted_event/([^/]+)/wc-api(/(.*))?/?$\";s:54:\"index.php?deleted_event=$matches[1]&wc-api=$matches[3]\";s:40:\"deleted_event/([^/]+)/edd-add(/(.*))?/?$\";s:55:\"index.php?deleted_event=$matches[1]&edd-add=$matches[3]\";s:43:\"deleted_event/([^/]+)/edd-remove(/(.*))?/?$\";s:58:\"index.php?deleted_event=$matches[1]&edd-remove=$matches[3]\";s:40:\"deleted_event/([^/]+)/edd-api(/(.*))?/?$\";s:55:\"index.php?deleted_event=$matches[1]&edd-api=$matches[3]\";s:45:\"deleted_event/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:56:\"deleted_event/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:46:\"deleted_event/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:57:\"deleted_event/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:49:\"deleted_event/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:60:\"deleted_event/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:46:\"deleted_event/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:57:\"deleted_event/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:38:\"deleted_event/([^/]+)(?:/([0-9]+))?/?$\";s:52:\"index.php?deleted_event=$matches[1]&page=$matches[2]\";s:30:\"deleted_event/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:40:\"deleted_event/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:60:\"deleted_event/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:55:\"deleted_event/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:55:\"deleted_event/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:36:\"deleted_event/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:12:\"robots\\.txt$\";s:18:\"index.php?robots=1\";s:13:\"favicon\\.ico$\";s:19:\"index.php?favicon=1\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:17:\"wc-api(/(.*))?/?$\";s:29:\"index.php?&wc-api=$matches[2]\";s:18:\"edd-add(/(.*))?/?$\";s:30:\"index.php?&edd-add=$matches[2]\";s:21:\"edd-remove(/(.*))?/?$\";s:33:\"index.php?&edd-remove=$matches[2]\";s:18:\"edd-api(/(.*))?/?$\";s:30:\"index.php?&edd-api=$matches[2]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:26:\"comments/wc-api(/(.*))?/?$\";s:29:\"index.php?&wc-api=$matches[2]\";s:27:\"comments/edd-add(/(.*))?/?$\";s:30:\"index.php?&edd-add=$matches[2]\";s:30:\"comments/edd-remove(/(.*))?/?$\";s:33:\"index.php?&edd-remove=$matches[2]\";s:27:\"comments/edd-api(/(.*))?/?$\";s:30:\"index.php?&edd-api=$matches[2]\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:29:\"search/(.+)/wc-api(/(.*))?/?$\";s:42:\"index.php?s=$matches[1]&wc-api=$matches[3]\";s:30:\"search/(.+)/edd-add(/(.*))?/?$\";s:43:\"index.php?s=$matches[1]&edd-add=$matches[3]\";s:33:\"search/(.+)/edd-remove(/(.*))?/?$\";s:46:\"index.php?s=$matches[1]&edd-remove=$matches[3]\";s:30:\"search/(.+)/edd-api(/(.*))?/?$\";s:43:\"index.php?s=$matches[1]&edd-api=$matches[3]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:47:\"author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:42:\"author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:23:\"author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:35:\"author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:32:\"author/([^/]+)/wc-api(/(.*))?/?$\";s:52:\"index.php?author_name=$matches[1]&wc-api=$matches[3]\";s:33:\"author/([^/]+)/edd-add(/(.*))?/?$\";s:53:\"index.php?author_name=$matches[1]&edd-add=$matches[3]\";s:36:\"author/([^/]+)/edd-remove(/(.*))?/?$\";s:56:\"index.php?author_name=$matches[1]&edd-remove=$matches[3]\";s:33:\"author/([^/]+)/edd-api(/(.*))?/?$\";s:53:\"index.php?author_name=$matches[1]&edd-api=$matches[3]\";s:17:\"author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:69:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:45:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:54:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/wc-api(/(.*))?/?$\";s:82:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&wc-api=$matches[5]\";s:55:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/edd-add(/(.*))?/?$\";s:83:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&edd-add=$matches[5]\";s:58:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/edd-remove(/(.*))?/?$\";s:86:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&edd-remove=$matches[5]\";s:55:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/edd-api(/(.*))?/?$\";s:83:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&edd-api=$matches[5]\";s:39:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:56:\"([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:51:\"([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:32:\"([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:44:\"([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:41:\"([0-9]{4})/([0-9]{1,2})/wc-api(/(.*))?/?$\";s:66:\"index.php?year=$matches[1]&monthnum=$matches[2]&wc-api=$matches[4]\";s:42:\"([0-9]{4})/([0-9]{1,2})/edd-add(/(.*))?/?$\";s:67:\"index.php?year=$matches[1]&monthnum=$matches[2]&edd-add=$matches[4]\";s:45:\"([0-9]{4})/([0-9]{1,2})/edd-remove(/(.*))?/?$\";s:70:\"index.php?year=$matches[1]&monthnum=$matches[2]&edd-remove=$matches[4]\";s:42:\"([0-9]{4})/([0-9]{1,2})/edd-api(/(.*))?/?$\";s:67:\"index.php?year=$matches[1]&monthnum=$matches[2]&edd-api=$matches[4]\";s:26:\"([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:43:\"([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:38:\"([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:19:\"([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:31:\"([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:28:\"([0-9]{4})/wc-api(/(.*))?/?$\";s:45:\"index.php?year=$matches[1]&wc-api=$matches[3]\";s:29:\"([0-9]{4})/edd-add(/(.*))?/?$\";s:46:\"index.php?year=$matches[1]&edd-add=$matches[3]\";s:32:\"([0-9]{4})/edd-remove(/(.*))?/?$\";s:49:\"index.php?year=$matches[1]&edd-remove=$matches[3]\";s:29:\"([0-9]{4})/edd-api(/(.*))?/?$\";s:46:\"index.php?year=$matches[1]&edd-api=$matches[3]\";s:13:\"([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:58:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:68:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:88:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:83:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:83:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:64:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:53:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/embed/?$\";s:91:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&embed=true\";s:57:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/trackback/?$\";s:85:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&tb=1\";s:77:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:97:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]\";s:72:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:97:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&feed=$matches[5]\";s:65:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/page/?([0-9]{1,})/?$\";s:98:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&paged=$matches[5]\";s:72:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/comment-page-([0-9]{1,})/?$\";s:98:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&cpage=$matches[5]\";s:62:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/wc-api(/(.*))?/?$\";s:99:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&wc-api=$matches[6]\";s:63:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/edd-add(/(.*))?/?$\";s:100:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&edd-add=$matches[6]\";s:66:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/edd-remove(/(.*))?/?$\";s:103:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&edd-remove=$matches[6]\";s:63:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)/edd-api(/(.*))?/?$\";s:100:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&edd-api=$matches[6]\";s:62:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:73:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:63:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:74:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:66:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:77:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:63:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:74:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:61:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([^/]+)(?:/([0-9]+))?/?$\";s:97:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&name=$matches[4]&page=$matches[5]\";s:47:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:57:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:77:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:72:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:72:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:53:\"[0-9]{4}/[0-9]{1,2}/[0-9]{1,2}/[^/]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:64:\"([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/comment-page-([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&cpage=$matches[4]\";s:51:\"([0-9]{4})/([0-9]{1,2})/comment-page-([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&cpage=$matches[3]\";s:38:\"([0-9]{4})/comment-page-([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&cpage=$matches[2]\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:25:\"(.?.+?)/wc-api(/(.*))?/?$\";s:49:\"index.php?pagename=$matches[1]&wc-api=$matches[3]\";s:28:\"(.?.+?)/order-pay(/(.*))?/?$\";s:52:\"index.php?pagename=$matches[1]&order-pay=$matches[3]\";s:33:\"(.?.+?)/order-received(/(.*))?/?$\";s:57:\"index.php?pagename=$matches[1]&order-received=$matches[3]\";s:25:\"(.?.+?)/orders(/(.*))?/?$\";s:49:\"index.php?pagename=$matches[1]&orders=$matches[3]\";s:29:\"(.?.+?)/view-order(/(.*))?/?$\";s:53:\"index.php?pagename=$matches[1]&view-order=$matches[3]\";s:28:\"(.?.+?)/downloads(/(.*))?/?$\";s:52:\"index.php?pagename=$matches[1]&downloads=$matches[3]\";s:31:\"(.?.+?)/edit-account(/(.*))?/?$\";s:55:\"index.php?pagename=$matches[1]&edit-account=$matches[3]\";s:31:\"(.?.+?)/edit-address(/(.*))?/?$\";s:55:\"index.php?pagename=$matches[1]&edit-address=$matches[3]\";s:34:\"(.?.+?)/payment-methods(/(.*))?/?$\";s:58:\"index.php?pagename=$matches[1]&payment-methods=$matches[3]\";s:32:\"(.?.+?)/lost-password(/(.*))?/?$\";s:56:\"index.php?pagename=$matches[1]&lost-password=$matches[3]\";s:34:\"(.?.+?)/customer-logout(/(.*))?/?$\";s:58:\"index.php?pagename=$matches[1]&customer-logout=$matches[3]\";s:37:\"(.?.+?)/add-payment-method(/(.*))?/?$\";s:61:\"index.php?pagename=$matches[1]&add-payment-method=$matches[3]\";s:40:\"(.?.+?)/delete-payment-method(/(.*))?/?$\";s:64:\"index.php?pagename=$matches[1]&delete-payment-method=$matches[3]\";s:45:\"(.?.+?)/set-default-payment-method(/(.*))?/?$\";s:69:\"index.php?pagename=$matches[1]&set-default-payment-method=$matches[3]\";s:26:\"(.?.+?)/edd-add(/(.*))?/?$\";s:50:\"index.php?pagename=$matches[1]&edd-add=$matches[3]\";s:29:\"(.?.+?)/edd-remove(/(.*))?/?$\";s:53:\"index.php?pagename=$matches[1]&edd-remove=$matches[3]\";s:26:\"(.?.+?)/edd-api(/(.*))?/?$\";s:50:\"index.php?pagename=$matches[1]&edd-api=$matches[3]\";s:31:\".?.+?/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:42:\".?.+?/attachment/([^/]+)/wc-api(/(.*))?/?$\";s:51:\"index.php?attachment=$matches[1]&wc-api=$matches[3]\";s:32:\".?.+?/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:43:\".?.+?/attachment/([^/]+)/edd-add(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-add=$matches[3]\";s:35:\".?.+?/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:46:\".?.+?/attachment/([^/]+)/edd-remove(/(.*))?/?$\";s:55:\"index.php?attachment=$matches[1]&edd-remove=$matches[3]\";s:32:\".?.+?/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:43:\".?.+?/attachment/([^/]+)/edd-api(/(.*))?/?$\";s:52:\"index.php?attachment=$matches[1]&edd-api=$matches[3]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";}','yes'),(1075,'_transient_wc_attribute_taxonomies','a:0:{}','yes'),(1076,'_transient_timeout_as-post-store-dependencies-met','1727279091','no'),(1077,'_transient_as-post-store-dependencies-met','yes','no'),(1078,'_transient_timeout_woocommerce_blocks_asset_api_script_data','1729859604','no'),(1079,'_transient_woocommerce_blocks_asset_api_script_data','{\"script_data\":{\"build\\/wc-settings.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-settings.js\",\"version\":\"b31b6165ac2a07ada0ff536d52b1466d\",\"dependencies\":[\"wp-hooks\",\"wp-i18n\",\"wp-polyfill\"]},\"build\\/wc-blocks-middleware.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks-middleware.js\",\"version\":\"208988bfef8a0a939e506218fc806a2b\",\"dependencies\":[\"wp-api-fetch\",\"wp-polyfill\"]},\"build\\/wc-blocks-data.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks-data.js\",\"version\":\"6279193027a1b903984715859dfcbaab\",\"dependencies\":[\"wc-blocks-registry\",\"wc-settings\",\"wp-api-fetch\",\"wp-data\",\"wp-data-controls\",\"wp-deprecated\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-notices\",\"wp-polyfill\",\"wp-url\"]},\"build\\/wc-blocks-vendors.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks-vendors.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/wc-blocks-registry.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks-registry.js\",\"version\":\"ecaf398656735e56f2d30eafc248ef35\",\"dependencies\":[\"wp-data\",\"wp-deprecated\",\"wp-element\",\"wp-polyfill\"]},\"build\\/wc-blocks.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks.js\",\"version\":\"1159c1fb50bedb71fad49f8808017cfe\",\"dependencies\":[\"wp-blocks\",\"wp-compose\",\"wp-element\",\"wp-hooks\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/wc-blocks-shared-context.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks-shared-context.js\",\"version\":\"caa936d2b7c335001cfc366d96d8a569\",\"dependencies\":[\"wp-element\",\"wp-polyfill\"]},\"build\\/wc-blocks-shared-hocs.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/wc-blocks-shared-hocs.js\",\"version\":\"7243a9fa7264377a45413afae757013f\",\"dependencies\":[\"wc-blocks-data-store\",\"wc-blocks-shared-context\",\"wp-data\",\"wp-element\",\"wp-is-shallow-equal\",\"wp-polyfill\"]},\"build\\/price-format.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/price-format.js\",\"version\":\"46126111d3b46712d9c0f0dbd873b138\",\"dependencies\":[\"wc-settings\",\"wp-polyfill\"]},\"build\\/blocks-checkout.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/blocks-checkout.js\",\"version\":\"cb1e22af331a53010b79e1484a5344bd\",\"dependencies\":[\"lodash\",\"react\",\"react-dom\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-settings\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-warning\"]},\"build\\/active-filters.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/active-filters.js\",\"version\":\"ed942f2e4631590ba01d30e045367abf\",\"dependencies\":[\"wc-blocks-data-store\",\"wc-price-format\",\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/active-filters-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/active-filters-frontend.js\",\"version\":\"948664e8c5afa263b9c5dfda6dc2e83a\",\"dependencies\":[\"wc-blocks-data-store\",\"wc-price-format\",\"wc-settings\",\"wp-data\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/all-products.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/all-products.js\",\"version\":\"f478ea858b58d3507924bea2c4c214ad\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-blocks-shared-context\",\"wc-blocks-shared-hocs\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-autop\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-escape-html\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/all-products-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/all-products-frontend.js\",\"version\":\"d12119aa2d9e588535c80fe0310c869e\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-blocks-shared-context\",\"wc-blocks-shared-hocs\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-autop\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/all-reviews.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/all-reviews.js\",\"version\":\"d578e628b1e1f0148c7bf60cfe1c602f\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/reviews-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/reviews-frontend.js\",\"version\":\"f7574b7910cac2f35291759fd35ce254\",\"dependencies\":[\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-compose\",\"wp-element\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\"]},\"build\\/attribute-filter.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/attribute-filter.js\",\"version\":\"19c61fdb57ae1e3ee4c4465694e2e73f\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-settings\",\"wp-a11y\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\"]},\"build\\/attribute-filter-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/attribute-filter-frontend.js\",\"version\":\"10589e1dbed5b10fc6383d7730b8b091\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-settings\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\"]},\"build\\/breadcrumbs.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/breadcrumbs.js\",\"version\":\"779832775ebf460068025eadf0e90da7\",\"dependencies\":[\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/catalog-sorting.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/catalog-sorting.js\",\"version\":\"70d26793890b54364d1bbd5bce8a2e5f\",\"dependencies\":[\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/legacy-template.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/legacy-template.js\",\"version\":\"1deb4ed7bca43c65a86d8f5cd71d0f2e\",\"dependencies\":[\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-core-data\",\"wp-data\",\"wp-element\",\"wp-i18n\",\"wp-notices\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/customer-account.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/customer-account.js\",\"version\":\"cae8d20aaf0cf655dbdfdee65cb8690e\",\"dependencies\":[\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/featured-category.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/featured-category.js\",\"version\":\"3d12a7feebe4b68db6a36c9782d5ad32\",\"dependencies\":[\"react\",\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\"]},\"build\\/featured-product.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/featured-product.js\",\"version\":\"e4170e4eaef5b2cd3a1ecc1620f83f6e\",\"dependencies\":[\"react\",\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\"]},\"build\\/filter-wrapper.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/filter-wrapper.js\",\"version\":\"39a31dba3fd2d291ce2697866fa1c71d\",\"dependencies\":[\"wp-block-editor\",\"wp-blocks\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/filter-wrapper-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/filter-wrapper-frontend.js\",\"version\":\"1c42e853788847e4cb9b601a4b29ff97\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\",\"wp-warning\"]},\"build\\/handpicked-products.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/handpicked-products.js\",\"version\":\"a19a347add2b45d53540cfee4e85f35a\",\"dependencies\":[\"react\",\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/mini-cart.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart.js\",\"version\":\"dcafac4b01a7e074b1393897ae84166f\",\"dependencies\":[\"react\",\"wc-price-format\",\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-data\",\"wp-dom\",\"wp-element\",\"wp-hooks\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/mini-cart-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-frontend.js\",\"version\":\"b922d0f8fc627b4c7695288bc4eb7d38\",\"dependencies\":[\"wc-price-format\",\"wc-settings\",\"wp-i18n\",\"wp-polyfill\"]},\"build\\/store-notices.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/store-notices.js\",\"version\":\"b0410f962bb163ecf804f85945494a69\",\"dependencies\":[\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/price-filter.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/price-filter.js\",\"version\":\"d34658f1bc90f65917dff23469786540\",\"dependencies\":[\"react\",\"wc-blocks-data-store\",\"wc-price-format\",\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/price-filter-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/price-filter-frontend.js\",\"version\":\"4f2ef15ca232059ed0f17c7cfbea62f5\",\"dependencies\":[\"react\",\"wc-blocks-data-store\",\"wc-price-format\",\"wc-settings\",\"wp-data\",\"wp-element\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-url\"]},\"build\\/product-add-to-cart.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-add-to-cart.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-add-to-cart-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-add-to-cart-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-best-sellers.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-best-sellers.js\",\"version\":\"5e118444ec6ac5c2b3b9440f9a558d7f\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/product-button.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-button.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-button-interactivity-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-button-interactivity-frontend.js\",\"version\":\"f231c36d2e6154d2cf545a864568f5e4\",\"dependencies\":[\"lodash\",\"wc-blocks-data-store\",\"wc-interactivity\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-warning\"]},\"build\\/product-categories.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-categories.js\",\"version\":\"4c0c5bfa88e3bc2d34f8b7cd3089cbc1\",\"dependencies\":[\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-data\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\"]},\"build\\/product-category.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-category.js\",\"version\":\"bbc8aaa78e356a825d5c33c7e4fcce76\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/product-collection.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-collection.js\",\"version\":\"941432e3696193936d37736b8faf9837\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-core-data\",\"wp-data\",\"wp-element\",\"wp-escape-html\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/product-new.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-new.js\",\"version\":\"54fd948597e5afa211abe9b37a3382e8\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/product-on-sale.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-on-sale.js\",\"version\":\"b39b1e40568e2dd5b37320e6dfa17f57\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/product-template.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-template.js\",\"version\":\"b73d4ef43094fefd8eb5a09bc3178c82\",\"dependencies\":[\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-core-data\",\"wp-data\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\"]},\"build\\/product-query.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-query.js\",\"version\":\"b09016077f61d88bbd680cc641cef9ff\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-escape-html\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/product-query-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-query-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-results-count.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-results-count.js\",\"version\":\"62389880d09bea807af392c535996912\",\"dependencies\":[\"wp-block-editor\",\"wp-blocks\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/product-search.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-search.js\",\"version\":\"a32bcc8d5170d5a7d8e4c9854477f8f8\",\"dependencies\":[\"wc-settings\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\"]},\"build\\/product-summary.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-summary.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-tag.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-tag.js\",\"version\":\"74f9457abc552c377bfd00525d7f7ed6\",\"dependencies\":[\"react\",\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/product-title.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-title.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-title-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-title-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/product-top-rated.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/product-top-rated.js\",\"version\":\"635f63dff3d29cca693bd94a17659bda\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/products-by-attribute.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/products-by-attribute.js\",\"version\":\"73b9ca1ee1dfe920689b7c2ca1134bbb\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-polyfill\",\"wp-primitives\",\"wp-server-side-render\",\"wp-url\"]},\"build\\/rating-filter.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/rating-filter.js\",\"version\":\"8a60b8434a974c7d9e71c9863ee4a423\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-settings\",\"wp-a11y\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\"]},\"build\\/reviews-by-category.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/reviews-by-category.js\",\"version\":\"6ecfcf28de8997b8f93996a0bf52766c\",\"dependencies\":[\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/reviews-by-product.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/reviews-by-product.js\",\"version\":\"2a47a97286dbc7a2293f760ee2b21f6f\",\"dependencies\":[\"react\",\"wc-settings\",\"wp-api-fetch\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-element\",\"wp-escape-html\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\"]},\"build\\/single-product.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/single-product.js\",\"version\":\"7dc18ae07298254fb4c2c3f50f368333\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-blocks-shared-context\",\"wc-blocks-shared-hocs\",\"wc-price-format\",\"wc-settings\",\"wp-api-fetch\",\"wp-autop\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-escape-html\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/stock-filter.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/stock-filter.js\",\"version\":\"8bfac93830eed1003e87b254e7103dda\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-settings\",\"wp-a11y\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\"]},\"build\\/stock-filter-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/stock-filter-frontend.js\",\"version\":\"bb50cc5d94568fa66c1b2da4c7ab3060\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-settings\",\"wp-a11y\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\"]},\"build\\/cart.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart.js\",\"version\":\"23d484e326a0b12a525fb0d7beddac73\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-blocks-shared-context\",\"wc-blocks-shared-hocs\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-autop\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-core-data\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-editor\",\"wp-element\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-plugins\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/cart-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-frontend.js\",\"version\":\"89e864cb281577fa4668ce653ccc6f41\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-blocks-shared-context\",\"wc-blocks-shared-hocs\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-autop\",\"wp-blocks\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-plugins\",\"wp-polyfill\",\"wp-primitives\",\"wp-style-engine\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/cart-blocks\\/order-summary-discount-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-discount-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-totals-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-totals-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-coupon-form-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-coupon-form-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-line-items-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-line-items-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/proceed-to-checkout-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/proceed-to-checkout-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-order-summary-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-order-summary-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-items-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-items-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-taxes-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-taxes-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-items-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-items-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-order-summary-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-order-summary-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-cross-sells-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-cross-sells-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-coupon-form-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-coupon-form-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-subtotal-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-subtotal-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-cross-sells-products-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-cross-sells-products-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-line-items-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-line-items-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-express-payment--checkout-blocks\\/express-payment-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-express-payment--checkout-blocks\\/express-payment-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/empty-cart-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/empty-cart-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-subtotal-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-subtotal-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-accepted-payment-methods-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-accepted-payment-methods-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-accepted-payment-methods-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-accepted-payment-methods-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-cross-sells-products-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-cross-sells-products-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-shipping-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-shipping-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-heading-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-heading-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-discount-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-discount-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-fee-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-fee-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-line-items--mini-cart-contents-block\\/products-table-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-line-items--mini-cart-contents-block\\/products-table-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-taxes-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-taxes-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/proceed-to-checkout-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/proceed-to-checkout-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/filled-cart-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/filled-cart-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-fee-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-fee-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-shipping-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-shipping-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/empty-cart-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/empty-cart-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-express-payment-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-express-payment-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-cross-sells-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-cross-sells-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-cross-sells-products--product-price-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-cross-sells-products--product-price-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-express-payment-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-express-payment-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/cart-totals-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/cart-totals-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/filled-cart-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/filled-cart-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-blocks\\/order-summary-heading-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-blocks\\/order-summary-heading-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--cart-blocks\\/order-summary-shipping--checkout-blocks\\/billing-address--checkout-blocks\\/order--decc3dc6-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--cart-blocks\\/order-summary-shipping--checkout-blocks\\/billing-address--checkout-blocks\\/order--decc3dc6-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--cart-blocks\\/order-summary-shipping--checkout-blocks\\/order-summary-shipping--checkout-block--24d3fc0c-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--cart-blocks\\/order-summary-shipping--checkout-blocks\\/order-summary-shipping--checkout-block--24d3fc0c-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--cart-blocks\\/cart-cross-sells-products--cart-blocks\\/cart-line-items--cart-blocks\\/cart-order--3c5fe802-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--cart-blocks\\/cart-cross-sells-products--cart-blocks\\/cart-line-items--cart-blocks\\/cart-order--3c5fe802-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--cart-blocks\\/proceed-to-checkout-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--cart-blocks\\/proceed-to-checkout-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--cart-blocks\\/cart-line-items--checkout-blocks\\/order-summary-cart-items--mini-cart-contents---233ab542-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--cart-blocks\\/cart-line-items--checkout-blocks\\/order-summary-cart-items--mini-cart-contents---233ab542-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-taxes-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-taxes-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-subtotal-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-subtotal-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/filled-cart-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/filled-cart-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/empty-cart-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/empty-cart-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-totals-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-totals-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-items-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-items-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-line-items-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-line-items-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-express-payment-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-express-payment-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/proceed-to-checkout-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/proceed-to-checkout-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-accepted-payment-methods-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-accepted-payment-methods-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-coupon-form-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-coupon-form-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-discount-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-discount-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-fee-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-fee-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-heading-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-heading-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-order-summary-shipping-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-order-summary-shipping-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-cross-sells-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-cross-sells-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/cart-cross-sells-products-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/cart-cross-sells-products-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout.js\",\"version\":\"1b91965aaf315e8ca91923398605c910\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-autop\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-core-data\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-editor\",\"wp-element\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-plugins\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/checkout-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-frontend.js\",\"version\":\"a6427457ee65cfa508d1d3bcc982f8a8\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-blocks-shared-hocs\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-api-fetch\",\"wp-autop\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-plugins\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/checkout-blocks\\/actions-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/actions-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/terms-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/terms-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-discount-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-discount-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/shipping-methods-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/shipping-methods-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-cart-items-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-cart-items-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/shipping-method-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/shipping-method-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-coupon-form-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-coupon-form-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/shipping-method-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/shipping-method-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-taxes-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-taxes-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/actions--checkout-blocks\\/terms-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/actions--checkout-blocks\\/terms-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/fields-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/fields-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/shipping-methods-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/shipping-methods-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/pickup-options-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/pickup-options-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/billing-address-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/billing-address-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/billing-address-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/billing-address-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-coupon-form-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-coupon-form-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-subtotal-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-subtotal-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/shipping-address-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/shipping-address-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-note-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-note-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-subtotal-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-subtotal-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-cart-items-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-cart-items-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/shipping-address-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/shipping-address-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-shipping-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-shipping-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/contact-information-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/contact-information-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/pickup-options-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/pickup-options-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/contact-information-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/contact-information-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-discount-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-discount-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/payment-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/payment-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-fee-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-fee-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-taxes-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-taxes-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/express-payment-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/express-payment-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/fields-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/fields-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/totals-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/totals-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-fee-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-fee-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/terms-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/terms-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/order-summary-shipping-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/order-summary-shipping-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/totals-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/totals-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/payment-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/payment-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-blocks\\/actions-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-blocks\\/actions-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--checkout-blocks\\/shipping-method-style.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--checkout-blocks\\/shipping-method-style.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--checkout-blocks\\/shipping-method-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--checkout-blocks\\/shipping-method-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/vendors--checkout-blocks\\/billing-address--checkout-blocks\\/shipping-address-frontend.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/vendors--checkout-blocks\\/billing-address--checkout-blocks\\/shipping-address-frontend.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-actions-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-actions-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-billing-address-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-billing-address-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-contact-information-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-contact-information-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-express-payment-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-express-payment-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-fields-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-fields-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-note-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-note-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-cart-items-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-cart-items-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-coupon-form-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-coupon-form-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-discount-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-discount-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-fee-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-fee-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-shipping-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-shipping-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-subtotal-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-subtotal-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-order-summary-taxes-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-order-summary-taxes-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-payment-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-payment-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-shipping-address-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-shipping-address-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-shipping-methods-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-shipping-methods-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-shipping-method-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-shipping-method-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-pickup-options-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-pickup-options-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-terms-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-terms-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/checkout-totals-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/checkout-totals-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-contents.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-contents.js\",\"version\":\"e9456573a7b01f9120587bf7edc5a57d\",\"dependencies\":[\"lodash\",\"react\",\"wc-blocks-checkout\",\"wc-blocks-data-store\",\"wc-blocks-registry\",\"wc-price-format\",\"wc-settings\",\"wp-a11y\",\"wp-autop\",\"wp-block-editor\",\"wp-blocks\",\"wp-components\",\"wp-compose\",\"wp-data\",\"wp-deprecated\",\"wp-dom\",\"wp-element\",\"wp-hooks\",\"wp-html-entities\",\"wp-i18n\",\"wp-is-shallow-equal\",\"wp-keycodes\",\"wp-polyfill\",\"wp-primitives\",\"wp-url\",\"wp-warning\",\"wp-wordcount\"]},\"build\\/empty-mini-cart-contents-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/empty-mini-cart-contents-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/filled-mini-cart-contents-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/filled-mini-cart-contents-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-footer-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-footer-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-items-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-items-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-products-table-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-products-table-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-shopping-button-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-shopping-button-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-cart-button-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-cart-button-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-checkout-button-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-checkout-button-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-title-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-title-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-title-items-counter-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-title-items-counter-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]},\"build\\/mini-cart-title-label-block.js\":{\"src\":\"http:\\/\\/wordpress.test\\/wp-content\\/plugins\\/woocommerce\\/packages\\/woocommerce-blocks\\/build\\/mini-cart-title-label-block.js\",\"version\":\"11.1.3\",\"dependencies\":[]}},\"version\":\"11.1.3\",\"hash\":\"3adb2ead9e039f46ad573607fc0b4c0a\"}','no'),(1086,'_transient_timeout_edd_check_protection_files','1727353836','no'),(1087,'_transient_edd_check_protection_files','1','no'),(1088,'tec_automator_zapier_secret_key','d5a9a9f71472806969d56d56f307572cb25a589240effad4cfda6369610f4bcaf3222cfc106206a5c504790c234900aedc83ca4778858cdaeea3fb2782abee9567d1f6c7895bb29e580cab02c3ddcd83ea80e9b3452b573c073ff4db785fd653e494a15d689e2814a57229f433a55af3f0ac7c4ae9f660a4b9daa55bcc07b1c2','yes'),(1089,'_transient_timeout__tribe_admin_notices','1729859436','no'),(1090,'_transient__tribe_admin_notices','a:1:{s:44:\"updated-to-merge-version-consolidated-notice\";a:3:{i:0;s:200:\"<p>Thanks for upgrading Event Tickets Plus to 6.0.2 now with even more value! Learn more about the latest changes <a target=\"_blank\" href=\"https://evnt.is/1bdy\" rel=\"noopener noreferrer\">here</a>.</p>\";i:1;a:5:{s:4:\"type\";s:7:\"success\";s:7:\"dismiss\";b:1;s:6:\"action\";s:13:\"admin_notices\";s:8:\"priority\";i:1;s:15:\"active_callback\";s:80:\"TEC\\Common\\Integrations\\Plugin_Merge_Provider_Abstract::should_show_merge_notice\";}i:2;i:1758803436;}}','no'),(1091,'_transient_timeout_wcpay_welcome_page_incentive','1727289187','no'),(1092,'_transient_wcpay_welcome_page_incentive','O:8:\"WP_Error\":3:{s:6:\"errors\";a:1:{s:25:\"http_request_not_executed\";a:1:{i:0;s:39:\"User has blocked requests through HTTP.\";}}s:10:\"error_data\";a:0:{}s:18:\"\0*\0additional_data\";a:0:{}}','no'),(1093,'_transient_timeout__woocommerce_helper_subscriptions','1727268487','no'),(1094,'_transient__woocommerce_helper_subscriptions','a:0:{}','no'),(1095,'_site_transient_timeout_theme_roots','1727269387','no'),(1096,'_site_transient_theme_roots','a:1:{s:16:\"twentytwentyfour\";s:7:\"/themes\";}','no'),(1097,'_transient_timeout__woocommerce_helper_updates','1727310787','no'),(1098,'_transient__woocommerce_helper_updates','a:4:{s:4:\"hash\";s:32:\"d751713988987e9331980363e24189ce\";s:7:\"updated\";i:1727267587;s:8:\"products\";a:0:{}s:6:\"errors\";a:1:{i:0;s:10:\"http-error\";}}','no'),(1099,'_transient_timeout_wc_tracks_blog_details','1727353987','no'),(1100,'_transient_wc_tracks_blog_details','a:5:{s:3:\"url\";s:21:\"http://localhost:8888\";s:9:\"blog_lang\";s:5:\"en_US\";s:7:\"blog_id\";b:0;s:14:\"products_count\";s:1:\"1\";s:10:\"wc_version\";s:5:\"8.2.2\";}','no'),(1102,'_transient_timeout__tribe_events_activation_redirect','1727267623','no'),(1103,'_transient__tribe_events_activation_redirect','1','no'),(1104,'_transient_timeout_woocommerce_admin_remote_inbox_notifications_specs','1727872393','no'),(1105,'_transient_woocommerce_admin_remote_inbox_notifications_specs','a:1:{s:5:\"en_US\";a:0:{}}','no'),(1110,'_transient_timeout_tribe_aggregator_services_list','1727353993','no'),(1111,'_transient_tribe_aggregator_services_list','a:1:{s:6:\"origin\";a:1:{i:0;O:8:\"stdClass\":2:{s:2:\"id\";s:3:\"csv\";s:4:\"name\";s:8:\"CSV File\";}}}','no');
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
INSERT INTO `wp_postmeta` VALUES (1,9,'_wp_attached_file','woocommerce-placeholder.png'),(2,9,'_wp_attachment_metadata','a:6:{s:5:\"width\";i:1200;s:6:\"height\";i:1200;s:4:\"file\";s:27:\"woocommerce-placeholder.png\";s:8:\"filesize\";i:102644;s:5:\"sizes\";a:5:{s:6:\"medium\";a:5:{s:4:\"file\";s:35:\"woocommerce-placeholder-300x300.png\";s:5:\"width\";i:300;s:6:\"height\";i:300;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:12560;}s:5:\"large\";a:5:{s:4:\"file\";s:37:\"woocommerce-placeholder-1024x1024.png\";s:5:\"width\";i:1024;s:6:\"height\";i:1024;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:92182;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:35:\"woocommerce-placeholder-150x150.png\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:4228;}s:12:\"medium_large\";a:5:{s:4:\"file\";s:35:\"woocommerce-placeholder-768x768.png\";s:5:\"width\";i:768;s:6:\"height\";i:768;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:58715;}s:32:\"twentyseventeen-thumbnail-avatar\";a:5:{s:4:\"file\";s:35:\"woocommerce-placeholder-100x100.png\";s:5:\"width\";i:100;s:6:\"height\";i:100;s:9:\"mime-type\";s:9:\"image/png\";s:8:\"filesize\";i:2314;}}s:10:\"image_meta\";a:12:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}}}'),(3,16,'tec_tc_payments_page_created','tec_tickets_checkout'),(4,17,'tec_tc_payments_page_created','tec_tickets_success'),(5,18,'_tribe_modified_fields','a:11:{s:15:\"_EventStartDate\";i:1700205857;s:14:\"_EventTimezone\";i:1700205857;s:14:\"_EventDuration\";i:1700205857;s:18:\"_EventStartDateUTC\";i:1700205857;s:13:\"_EventEndDate\";i:1700205857;s:16:\"_EventEndDateUTC\";i:1700205857;s:18:\"_EventTimezoneAbbr\";i:1700205857;s:12:\"_EventOrigin\";i:1700205857;s:13:\"_EventShowMap\";i:1700205857;s:17:\"_EventShowMapLink\";i:1700205857;s:30:\"_tribe_default_ticket_provider\";i:1700205858;}'),(6,18,'_EventStartDate','2019-01-01 10:00:00'),(7,18,'_EventTimezone','America/New_York'),(8,18,'_EventDuration','10800'),(9,18,'_EventStartDateUTC','2019-01-01 15:00:00'),(10,18,'_EventEndDate','2019-01-01 13:00:00'),(11,18,'_EventEndDateUTC','2019-01-01 18:00:00'),(12,18,'_EventTimezoneAbbr','EST'),(13,18,'_EventOrigin','events-calendar'),(14,18,'_EventShowMap','1'),(15,18,'_EventShowMapLink','1'),(16,18,'_tribe_default_ticket_provider','Tribe__Tickets_Plus__Commerce__WooCommerce__Main'),(17,19,'_type','default'),(18,19,'total_sales','0'),(19,19,'_tax_status','taxable'),(20,19,'_tax_class',''),(21,19,'_manage_stock','no'),(22,19,'_backorders','no'),(23,19,'_sold_individually','no'),(24,19,'_virtual','yes'),(25,19,'_downloadable','no'),(26,19,'_download_limit','-1'),(27,19,'_download_expiry','-1'),(28,19,'_stock',''),(29,19,'_stock_status','instock'),(30,19,'_wc_average_rating','0'),(31,19,'_wc_review_count','0'),(32,19,'_product_version','8.2.2');
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (4,1,'2023-01-05 13:11:42','2023-01-05 13:11:42','<!-- wp:shortcode -->[download_checkout]<!-- /wp:shortcode -->','Checkout','','publish','closed','closed','','checkout','','','2023-01-05 13:11:42','2023-01-05 13:11:42','',0,'http://wordpress.test/checkout/',0,'page','',0),(5,1,'2023-01-05 13:11:42','2023-01-05 13:11:42','<!-- wp:edd/receipt /-->','Receipt','','publish','closed','closed','','receipt','','','2023-01-05 13:11:42','2023-01-05 13:11:42','',4,'http://wordpress.test/checkout/receipt/',0,'page','',0),(6,1,'2023-01-05 13:11:42','2023-01-05 13:11:42','<!-- wp:paragraph --><p>Your transaction failed; please try again or contact site support.</p><!-- /wp:paragraph -->','Transaction Failed','','publish','closed','closed','','transaction-failed','','','2023-01-05 13:11:42','2023-01-05 13:11:42','',4,'http://wordpress.test/checkout/transaction-failed/',0,'page','',0),(7,1,'2023-01-05 13:11:42','2023-01-05 13:11:42','<!-- wp:edd/order-history /-->','Order History','','publish','closed','closed','','order-history','','','2023-01-05 13:11:42','2023-01-05 13:11:42','',4,'http://wordpress.test/checkout/order-history/',0,'page','',0),(8,1,'2023-01-05 13:11:42','2023-01-05 13:11:42','<!-- wp:paragraph --><p>Thank you for your purchase!</p><!-- /wp:paragraph --><!-- wp:edd/confirmation /-->','Confirmation','','publish','closed','closed','','confirmation','','','2023-01-05 13:11:42','2023-01-05 13:11:42','',4,'http://wordpress.test/checkout/confirmation/',0,'page','',0),(9,1,'2023-01-05 13:11:54','2023-01-05 13:11:54','','woocommerce-placeholder','','inherit','open','closed','','woocommerce-placeholder','','','2023-01-05 13:11:54','2023-01-05 13:11:54','',0,'http://wordpress.test/wp-content/uploads/2023/01/woocommerce-placeholder.png',0,'attachment','image/png',0),(10,1,'2023-01-05 13:11:54','2023-01-05 13:11:54','','Shop','','publish','closed','closed','','shop','','','2023-01-05 13:11:54','2023-01-05 13:11:54','',0,'http://wordpress.test/shop/',0,'page','',0),(11,1,'2023-01-05 13:11:54','2023-01-05 13:11:54','<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->','Cart','','publish','closed','closed','','cart','','','2023-01-05 13:11:54','2023-01-05 13:11:54','',0,'http://wordpress.test/cart/',0,'page','',0),(12,1,'2023-01-05 13:11:54','2023-01-05 13:11:54','<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->','Checkout','','publish','closed','closed','','checkout-2','','','2023-01-05 13:11:54','2023-01-05 13:11:54','',0,'http://wordpress.test/checkout-2/',0,'page','',0),(13,1,'2023-01-05 13:11:54','2023-01-05 13:11:54','<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->','My account','','publish','closed','closed','','my-account','','','2023-01-05 13:11:54','2023-01-05 13:11:54','',0,'http://wordpress.test/my-account/',0,'page','',0),(14,1,'2023-01-05 13:11:54','0000-00-00 00:00:00','<!-- wp:paragraph -->\n<p><b>This is a sample page.</b></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<h3>Overview</h3>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Our refund and returns policy lasts 30 days. If 30 days have passed since your purchase, we can’t offer you a full refund or exchange.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Several types of goods are exempt from being returned. Perishable goods such as food, flowers, newspapers or magazines cannot be returned. We also do not accept products that are intimate or sanitary goods, hazardous materials, or flammable liquids or gases.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Additional non-returnable items:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:list -->\n<ul>\n<li>Gift cards</li>\n<li>Downloadable software products</li>\n<li>Some health and personal care items</li>\n</ul>\n<!-- /wp:list -->\n\n<!-- wp:paragraph -->\n<p>To complete your return, we require a receipt or proof of purchase.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Please do not send your purchase back to the manufacturer.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>There are certain situations where only partial refunds are granted:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:list -->\n<ul>\n<li>Book with obvious signs of use</li>\n<li>CD, DVD, VHS tape, software, video game, cassette tape, or vinyl record that has been opened.</li>\n<li>Any item not in its original condition, is damaged or missing parts for reasons not due to our error.</li>\n<li>Any item that is returned more than 30 days after delivery</li>\n</ul>\n<!-- /wp:list -->\n\n<!-- wp:paragraph -->\n<h2>Refunds</h2>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If you are approved, then your refund will be processed, and a credit will automatically be applied to your credit card or original method of payment, within a certain amount of days.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<b>Late or missing refunds</b>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If you haven’t received a refund yet, first check your bank account again.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Then contact your credit card company, it may take some time before your refund is officially posted.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Next contact your bank. There is often some processing time before a refund is posted.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If you’ve done all of this and you still have not received your refund yet, please contact us at {email address}.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<b>Sale items</b>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Only regular priced items may be refunded. Sale items cannot be refunded.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<h2>Exchanges</h2>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>We only replace items if they are defective or damaged. If you need to exchange it for the same item, send us an email at {email address} and send your item to: {physical address}.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<h2>Gifts</h2>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If the item was marked as a gift when purchased and shipped directly to you, you’ll receive a gift credit for the value of your return. Once the returned item is received, a gift certificate will be mailed to you.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If the item wasn’t marked as a gift when purchased, or the gift giver had the order shipped to themselves to give to you later, we will send a refund to the gift giver and they will find out about your return.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<h2>Shipping returns</h2>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>To return your product, you should mail your product to: {physical address}.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>You will be responsible for paying for your own shipping costs for returning your item. Shipping costs are non-refundable. If you receive a refund, the cost of return shipping will be deducted from your refund.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Depending on where you live, the time it may take for your exchanged product to reach you may vary.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If you are returning more expensive items, you may consider using a trackable shipping service or purchasing shipping insurance. We don’t guarantee that we will receive your returned item.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<h2>Need help?</h2>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Contact us at {email} for questions related to refunds and returns.</p>\n<!-- /wp:paragraph -->','Refund and Returns Policy','','draft','closed','closed','','refund_returns','','','2023-01-05 13:11:54','0000-00-00 00:00:00','',0,'http://wordpress.test/?page_id=14',0,'page','',0),(15,1,'2023-01-06 12:37:03','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2023-01-06 12:37:03','0000-00-00 00:00:00','',0,'http://wordpress.test/?p=15',0,'post','',0),(16,1,'2023-01-06 12:37:19','2023-01-06 12:37:19','<!-- wp:shortcode -->[tec_tickets_checkout]<!-- /wp:shortcode -->','Tickets Checkout','','publish','closed','closed','','tickets-checkout','','','2023-01-06 12:37:19','2023-01-06 12:37:19','',0,'http://wordpress.test/tickets-checkout/',0,'page','',0),(17,1,'2023-01-06 12:37:19','2023-01-06 12:37:19','<!-- wp:shortcode -->[tec_tickets_success]<!-- /wp:shortcode -->','Order Completed','','publish','closed','closed','','tickets-order','','','2023-01-06 12:37:19','2023-01-06 12:37:19','',0,'http://wordpress.test/tickets-order/',0,'page','',0),(18,0,'2023-11-17 07:24:17','2023-11-17 12:24:17','','Test Event','','publish','open','closed','','test-event','','','2023-11-17 07:24:17','2023-11-17 12:24:17','',0,'http://wordpress.test/event/test-event/',0,'tribe_events','',0),(19,0,'2023-11-17 07:24:18','2023-11-17 12:24:18','','Test WooCommerce ticket for 18','Test WooCommerce ticket description for 18','publish','open','closed','','test-woocommerce-ticket-for-18','','','2023-11-17 07:24:18','2023-11-17 12:24:18','',0,'http://wordpress.test/product/test-woocommerce-ticket-for-18/',-1,'product','',0);
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
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_events`
--

LOCK TABLES `wp_tec_events` WRITE;
/*!40000 ALTER TABLE `wp_tec_events` DISABLE KEYS */;
INSERT INTO `wp_tec_events` VALUES (1,18,'2019-01-01 10:00:00','2019-01-01 13:00:00','America/New_York','2019-01-01 15:00:00','2019-01-01 18:00:00',10800,'2023-11-17 12:24:17','');
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
  PRIMARY KEY (`occurrence_id`),
  UNIQUE KEY `hash` (`hash`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_tec_occurrences`
--

LOCK TABLES `wp_tec_occurrences` WRITE;
/*!40000 ALTER TABLE `wp_tec_occurrences` DISABLE KEYS */;
INSERT INTO `wp_tec_occurrences` VALUES (1,1,18,'2019-01-01 10:00:00','2019-01-01 15:00:00','2019-01-01 13:00:00','2019-01-01 18:00:00',10800,'4488dda4f18421f2229725fcd75413eb811f1538','2023-11-17 12:24:19');
/*!40000 ALTER TABLE `wp_tec_occurrences` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_relationships`
--

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (19,2,0),(19,6,0),(19,7,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_taxonomy`
--

LOCK TABLES `wp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES (1,1,'category','',0,1),(2,2,'product_type','',0,1),(3,3,'product_type','',0,0),(4,4,'product_type','',0,0),(5,5,'product_type','',0,0),(6,6,'product_visibility','',0,1),(7,7,'product_visibility','',0,1),(8,8,'product_visibility','',0,0),(9,9,'product_visibility','',0,0),(10,10,'product_visibility','',0,0),(11,11,'product_visibility','',0,0),(12,12,'product_visibility','',0,0),(13,13,'product_visibility','',0,0),(14,14,'product_visibility','',0,0),(15,15,'product_cat','',0,0);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_terms`
--

LOCK TABLES `wp_terms` WRITE;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
INSERT INTO `wp_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'simple','simple',0),(3,'grouped','grouped',0),(4,'variable','variable',0),(5,'external','external',0),(6,'exclude-from-search','exclude-from-search',0),(7,'exclude-from-catalog','exclude-from-catalog',0),(8,'featured','featured',0),(9,'outofstock','outofstock',0),(10,'rated-1','rated-1',0),(11,'rated-2','rated-2',0),(12,'rated-3','rated-3',0),(13,'rated-4','rated-4',0),(14,'rated-5','rated-5',0),(15,'Uncategorized','uncategorized',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'syntax_highlighting','true'),(7,1,'comment_shortcuts','false'),(8,1,'admin_color','fresh'),(9,1,'use_ssl','0'),(10,1,'show_admin_bar_front','true'),(11,1,'locale',''),(12,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(13,1,'wp_user_level','10'),(14,1,'dismissed_wp_pointers',''),(15,1,'show_welcome_panel','0'),(16,1,'session_tokens','a:1:{s:64:\"015d2b64b777ffc2afa5c942f95e3bc8be214e0647bdf724cc7fd5b7cd6c8b79\";a:4:{s:10:\"expiration\";i:1727440385;s:2:\"ip\";s:12:\"192.168.65.1\";s:2:\"ua\";s:84:\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:130.0) Gecko/20100101 Firefox/130.0\";s:5:\"login\";i:1727267585;}}'),(17,1,'wp_user-settings','mfold=o'),(18,1,'wp_user-settings-time','1666777420'),(19,1,'wp_dashboard_quick_press_last_post_id','15'),(20,1,'community-events-location','a:1:{s:2:\"ip\";s:10:\"172.18.0.0\";}'),(21,1,'wc_last_active','1727222400'),(23,1,'_woocommerce_tracks_anon_id','woo:5wYlp+5sdH3mKeoMABLSOktJ'),(24,1,'_woocommerce_persistent_cart_1','a:1:{s:4:\"cart\";a:0:{}}'),(25,1,'dismissed_no_secure_connection_notice','1'),(27,1,'tribe-dismiss-notice-time-event-tickets-plus-missing-easydigitaldownloads-support','1700226647'),(28,1,'tribe-dismiss-notice','event-tickets-plus-missing-easydigitaldownloads-support'),(29,1,'tribe-dismiss-notice-time-event-tickets-plus-missing-woocommerce-support','1700226652'),(30,1,'tribe-dismiss-notice','event-tickets-plus-missing-woocommerce-support'),(31,1,'dismissed_update_notice','1');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'admin','$P$BXTnf5Ms8OdVvNu2ToTsQvL1LsiHRC.','admin','admin@wordpress.test','','2018-04-03 13:14:05','',0,'admin');
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-25 12:33:24
