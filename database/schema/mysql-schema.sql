/*M!999999\- enable the sandbox mode */ 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `ai_assistant_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_assistant_calls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `call_id` char(36) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `caller_id` varchar(191) NOT NULL,
  `ai_assistant_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ai_bots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_bots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `voice_id` int(11) NOT NULL,
  `llm_provider_id` int(11) NOT NULL,
  `api_key` varchar(191) NOT NULL,
  `api_endpoint` varchar(191) DEFAULT NULL,
  `model` varchar(191) DEFAULT NULL,
  `resource` longtext NOT NULL,
  `max_interactions` int(11) NOT NULL,
  `max_silince` int(11) NOT NULL,
  `waiting_tone` int(11) DEFAULT NULL,
  `inaudible_voice` int(11) DEFAULT NULL,
  `listening_tone` int(11) DEFAULT NULL,
  `internal_directory` int(11) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `create_support_ticket` tinyint(4) NOT NULL DEFAULT 0,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `call_transfer_tone` int(11) DEFAULT NULL,
  `tts_profile_id` int(11) DEFAULT NULL,
  `stt_profile_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ai_conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_conversations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `call_id` char(36) NOT NULL,
  `message` text NOT NULL,
  `ai_msg` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `voice_id` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `api_access_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_access_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `api_key_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `ip_address` varchar(191) NOT NULL,
  `url` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `api_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_keys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `key` varchar(191) NOT NULL,
  `secret` varchar(191) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `api_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `api_key_id` int(11) NOT NULL,
  `ip_address` varchar(191) NOT NULL,
  `url` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `code` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `call_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_histories` (
  `call_id` char(36) NOT NULL,
  `bridge_call_id` char(36) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `record_file` varchar(191) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `call_parking_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_parking_logs` (
  `call_id` char(36) NOT NULL,
  `call_parking_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `parking_no` int(11) NOT NULL,
  `from` varchar(191) NOT NULL,
  `to` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `call_parkings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_parkings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `extension_no` int(11) NOT NULL,
  `no_of_slot` int(11) NOT NULL,
  `music_on_hold` int(11) NOT NULL,
  `timeout` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `record` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `call_queue_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_queue_extensions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `call_queue_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `member_type` int(11) NOT NULL,
  `allow_diversion` tinyint(1) NOT NULL DEFAULT 0,
  `last_ans` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_dial` timestamp NULL DEFAULT NULL,
  `dynamic_queue` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `call_queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_queues` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `strategy` tinyint(4) NOT NULL,
  `cid_name_prefix` varchar(191) DEFAULT NULL,
  `join_announcement` int(11) DEFAULT NULL,
  `agent_announcemnet` int(11) DEFAULT NULL,
  `service_level` varchar(191) DEFAULT NULL,
  `join_empty` tinyint(1) NOT NULL DEFAULT 1,
  `leave_when_empty` tinyint(1) NOT NULL DEFAULT 0,
  `timeout_priority` varchar(191) DEFAULT NULL,
  `queue_timeout` int(11) NOT NULL DEFAULT 30,
  `member_timeout` int(11) NOT NULL DEFAULT 15,
  `retry` int(11) NOT NULL DEFAULT 5,
  `wrap_up_time` int(11) NOT NULL DEFAULT 0,
  `queue_callback` tinyint(4) DEFAULT NULL,
  `music_on_hold` int(11) DEFAULT NULL,
  `ring_busy_agent` tinyint(1) NOT NULL DEFAULT 0,
  `record` tinyint(1) NOT NULL DEFAULT 0,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `agent_function_id` int(11) DEFAULT NULL,
  `agent_destination_id` int(11) DEFAULT NULL,
  `join_extension_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `call_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `call_records` (
  `call_id` char(36) NOT NULL,
  `dial_call_id` char(36) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `record_path` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calls` (
  `id` char(36) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `parent_call_id` char(36) DEFAULT NULL,
  `caller_id` varchar(191) NOT NULL,
  `sip_user_id` int(11) NOT NULL,
  `channel` varchar(191) DEFAULT NULL,
  `destination` varchar(191) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `connect_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ringing_time` timestamp NULL DEFAULT NULL,
  `establish_time` timestamp NULL DEFAULT NULL,
  `disconnect_time` timestamp NULL DEFAULT NULL,
  `disconnecct_code` int(11) DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `record_file` varchar(191) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `uas` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campaign_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_calls` (
  `id` char(36) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `call_id` char(36) DEFAULT NULL,
  `tel` varchar(191) NOT NULL,
  `retry` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `sms_history_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `duration` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campaign_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaign_sms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) NOT NULL,
  `sms_history_id` char(36) NOT NULL,
  `retry` varchar(191) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `contact` varchar(191) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `from` varchar(191) NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `tts` text DEFAULT NULL,
  `tts_lang` varchar(10) DEFAULT NULL,
  `on_queue` tinyint(4) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `contact_groups` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `max_retry` int(11) NOT NULL,
  `call_limit` int(11) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `schedule_days` varchar(100) NOT NULL,
  `total_sent` int(11) DEFAULT NULL,
  `total_successfull` int(11) DEFAULT NULL,
  `total_failed` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contact_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `cc` varchar(100) DEFAULT NULL,
  `tel_no` varchar(100) NOT NULL,
  `contact_groups` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(191) NOT NULL DEFAULT 'unnamed',
  `last_name` varchar(191) DEFAULT NULL,
  `gender` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `state` varchar(191) DEFAULT NULL,
  `post_code` varchar(191) DEFAULT NULL,
  `country` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `fields` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `custom_funcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custom_funcs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `func_lang` tinyint(4) NOT NULL,
  `func_body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dialer_campaign_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dialer_campaign_calls` (
  `id` char(36) NOT NULL,
  `dialer_campaign_id` int(11) NOT NULL,
  `call_id` char(36) DEFAULT NULL,
  `tel` varchar(191) NOT NULL,
  `retry` int(11) NOT NULL DEFAULT 1,
  `status` int(11) DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `form_data` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `record_file` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dialer_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dialer_campaigns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `contact_groups` varchar(100) NOT NULL,
  `agents` varchar(191) DEFAULT NULL,
  `timezone` varchar(100) NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `schedule_days` varchar(100) NOT NULL,
  `script_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `max_retry` int(11) DEFAULT 0,
  `call_interval` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `extension_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extension_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `extension_id` text NOT NULL,
  `algorithm` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extensions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` int(11) NOT NULL,
  `extension_type` int(11) NOT NULL DEFAULT 1,
  `function_id` int(11) NOT NULL DEFAULT 1,
  `destination_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `forwarding_number` varchar(255) DEFAULT NULL,
  `dynamic_queue` tinyint(1) DEFAULT NULL,
  `do_not_disturb` tinyint(1) NOT NULL DEFAULT 0,
  `forwarding` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `flow_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flow_actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `action_type` int(11) NOT NULL,
  `action_value` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `flows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flows` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `voice_file` varchar(191) DEFAULT NULL,
  `match_type` int(11) NOT NULL,
  `match_value` varchar(191) NOT NULL,
  `match_action_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `funcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `func_type` tinyint(4) NOT NULL,
  `func` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `hotdesks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hotdesks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `sip_user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inbound_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inbound_routes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `did_pattern` varchar(191) NOT NULL,
  `cid_pattern` varchar(191) DEFAULT NULL,
  `function_id` tinyint(4) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ip_black_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_black_lists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `ip` varchar(191) NOT NULL,
  `subnet` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ivr_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ivr_actions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `ivr_id` int(11) NOT NULL,
  `digit` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voice` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ivrs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ivrs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `welcome_voice` int(11) DEFAULT NULL,
  `instruction_voice` int(11) NOT NULL,
  `invalid_voice` int(11) NOT NULL,
  `timeout_voice` int(11) NOT NULL,
  `invalid_retry_voice` int(11) DEFAULT NULL,
  `timeout_retry_voice` int(11) DEFAULT NULL,
  `timeout` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `max_digit` int(11) DEFAULT NULL,
  `max_retry` int(11) DEFAULT NULL,
  `end_key` char(1) DEFAULT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `mode` tinyint(4) NOT NULL DEFAULT 0,
  `intent_analyzer` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `leads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `designation` varchar(191) DEFAULT NULL,
  `phone` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `website` varchar(191) DEFAULT NULL,
  `company` varchar(191) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `source` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mail_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `mail_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `provider` varchar(191) NOT NULL,
  `options` text NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`organization_id`,`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_permissions_permission_id_foreign` (`permission_id`),
  KEY `model_has_permissions_team_foreign_key_index` (`organization_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `organization_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`organization_id`,`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  KEY `model_has_roles_role_id_foreign` (`role_id`),
  KEY `model_has_roles_team_foreign_key_index` (`organization_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `organizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `domain` varchar(191) NOT NULL,
  `contact_no` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `address` varchar(191) DEFAULT NULL,
  `credit` double(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `max_extension` int(11) NOT NULL DEFAULT 0,
  `call_limit` int(11) NOT NULL DEFAULT 0,
  `expire_date` date DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `outbound_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `outbound_routes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `trunk_id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `pattern` text NOT NULL,
  `priority` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pin_list_id` int(11) DEFAULT NULL,
  `function_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `record` tinyint(1) DEFAULT NULL,
  `outbound_cid` varchar(191) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pin_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pin_lists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `pin_list` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `duration` int(11) NOT NULL,
  `price` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `queue_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue_calls` (
  `call_id` char(36) NOT NULL,
  `parent_call_id` char(36) DEFAULT NULL,
  `organization_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `extension_id` int(11) NOT NULL,
  `call_queue_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queues` (
  `call_id` char(36) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `sip_user_id` int(11) DEFAULT NULL,
  `bridge_call_id` char(36) DEFAULT NULL,
  `call_queue_id` int(11) DEFAULT NULL,
  `queue_name` varchar(191) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `waiting_duration` int(11) DEFAULT NULL,
  `recieved_by` int(11) DEFAULT NULL,
  `record_file` varchar(191) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ring_group_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ring_group_calls` (
  `ring_group_id` int(11) NOT NULL,
  `call_id` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ring_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ring_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `extension_id` int(11) NOT NULL,
  `description` varchar(191) NOT NULL,
  `ring_strategy` tinyint(4) NOT NULL,
  `ring_time` int(11) NOT NULL,
  `answer_channel` tinyint(1) NOT NULL DEFAULT 0,
  `skip_busy_extension` tinyint(1) NOT NULL DEFAULT 0,
  `allow_diversions` tinyint(1) NOT NULL DEFAULT 0,
  `ringback_tone` tinyint(1) NOT NULL DEFAULT 0,
  `extensions` text NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_organization_id_name_guard_name_unique` (`organization_id`,`name`,`guard_name`),
  KEY `roles_team_foreign_key_index` (`organization_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `scripts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `scripts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `content` mediumtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `group` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sip_channels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sip_channels` (
  `sip_user_id` int(11) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `location` varchar(191) NOT NULL,
  `expire` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ua` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sip_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sip_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `host` varchar(191) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `transport` tinyint(4) DEFAULT NULL,
  `peer` tinyint(1) NOT NULL DEFAULT 1,
  `record` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_type` tinyint(4) DEFAULT NULL,
  `call_limit` int(11) DEFAULT 0,
  `status` tinyint(4) DEFAULT 1,
  `allow_ip` text DEFAULT NULL,
  `overwrite_cid` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `title` varchar(191) NOT NULL,
  `content` text NOT NULL,
  `sms_count` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sms_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_histories` (
  `id` char(36) NOT NULL,
  `organization_id` int(11) NOT NULL,
  `trxid` varchar(191) DEFAULT NULL,
  `from` varchar(191) NOT NULL,
  `to` varchar(191) NOT NULL,
  `body` text NOT NULL,
  `sms_count` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sms_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sms_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `provider` varchar(191) NOT NULL,
  `options` text NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `stream_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stream_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `call_id` char(36) NOT NULL,
  `stream_id` varchar(191) NOT NULL,
  `caller_id` varchar(191) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 0,
  `record_file` varchar(191) DEFAULT NULL,
  `transcript` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `streams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `streams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `ws_url` varchar(191) NOT NULL,
  `prompt` text DEFAULT NULL,
  `greetings` text DEFAULT NULL,
  `extra_parameters` text DEFAULT NULL,
  `forwarding_number` varchar(191) DEFAULT NULL,
  `max_call_duration` int(11) NOT NULL DEFAULT 0,
  `record` tinyint(4) NOT NULL DEFAULT 0,
  `email` varchar(191) DEFAULT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `survey_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survey_results` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `call_id` char(36) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `caller_id` varchar(191) NOT NULL,
  `pressed_key` int(11) DEFAULT NULL,
  `record_file` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surveys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `voice_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `keys` text DEFAULT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `max_retry` int(11) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `intent_analyzer` text DEFAULT NULL,
  `timeout` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ticket_follow_ups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_follow_ups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `phone` varchar(191) NOT NULL,
  `subject` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `record` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `time_conditions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_conditions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `time_group_id` int(11) NOT NULL,
  `matched_function_id` int(11) NOT NULL,
  `matched_destination_id` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `time_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `time_zone` varchar(191) NOT NULL,
  `schedules` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trunks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trunks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `sip_user_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tts_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `tts_profile_id` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `input` text NOT NULL,
  `output` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `response_timeout` double(8,2) NOT NULL,
  `response_time` double(8,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tts_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tts_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `provider` varchar(191) NOT NULL,
  `language` varchar(191) DEFAULT NULL,
  `model` varchar(191) DEFAULT NULL,
  `config` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `role` varchar(191) NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `virtual_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `virtual_agents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `welcome_voice` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `voice_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voice_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `voice_type` tinyint(1) NOT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `tts_text` text DEFAULT NULL,
  `tts_profile_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `voice_mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voice_mails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `voice_path` varchar(191) DEFAULT NULL,
  `transcript` text DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `caller_id` varchar(191) NOT NULL,
  `call_id` char(36) NOT NULL,
  `voice_record_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `voice_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `voice_records` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `voice_id` int(11) NOT NULL,
  `is_transcript` tinyint(1) NOT NULL DEFAULT 0,
  `text` text DEFAULT NULL,
  `play_beep` tinyint(1) NOT NULL DEFAULT 0,
  `is_send_email` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `is_create_ticket` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

/*M!999999\- enable the sandbox mode */ 
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2023_03_21_104401_create_voice_files_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2023_03_23_093935_create_plans_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2023_06_13_064707_create_extensions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2023_06_13_065743_create_sip_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2023_06_13_065756_create_ivrs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2023_06_13_070224_create_ivr_actions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2023_06_13_070602_create_extension_groups_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2023_06_13_094008_create_organizations_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2023_07_23_115041_create_apis_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2023_07_24_111950_create_trunks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2023_07_25_051301_create_outbound_routes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2023_07_25_052704_create_inbound_routes_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2023_07_25_112432_create_funcs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2023_07_27_111438_create_applications_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2023_08_01_114202_create_custom_funcs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2023_08_16_064612_create_hotdesks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2023_08_20_050311_create_calls_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2023_08_20_050341_create_call_legs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2023_09_14_065450_create_tts_profiles_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2023_10_01_053536_create_call_queues_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2023_10_01_061725_create_ring_groups_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2023_10_02_055934_create_call_queue_extensions_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2023_10_11_043005_create_settings_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2023_10_18_054628_create_call_records_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2023_10_18_102005_create_queues_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2023_10_26_055153_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2023_10_26_113717_create_queue_calls_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2023_10_26_114548_create_ring_group_calls_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2023_10_26_115218_create_sip_channels_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2023_10_30_060917_create_voice_mails_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2023_11_09_114236_add_agent_function_id_and_agent_destination_id_to_call_queues',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2023_11_09_114919_alter_queue_callback_and_description_in_call_queues',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2023_11_12_050459_update_organizations_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2023_11_09_114236_add_agent_function_id_and_agent_destination_id_to_call_queues_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2023_11_09_114919_alter_queue_callback_and_description_in_call_queues_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2023_11_14_053003_add_max_digit_max_retry_end_key_function_id_and_destination_id_to_ivrs_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2023_11_14_101832_add_dynamic_queue_and_static_queue_to_extensions',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2023_07_23_115041_create_api_keys_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2023_11_16_103634_create_api_logs_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2023_11_16_103634_create_api_access_logs_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2023_03_21_104356_create_contact_groups_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2023_03_21_104357_create_contacts_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2023_03_27_061010_create_campaigns_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2023_11_20_054841_create_campaign_calls_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2023_11_23_104801_update_settings_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2023_11_28_102717_rename_others_to_config_column_in_tts_profiles_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2023_11_28_115729_update_extension_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2023_11_29_061418_add_type_to_tts_profiles_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2023_11_29_065339_update_outbound_route_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2023_12_05_105447_create_announcements_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2023_12_06_043308_update_tts_profiles_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2023_12_14_054617_add_ua_to_sip_channels_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2023_12_17_115138_update_sip_users_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2023_12_17_120117_update_outbound_routes_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2023_12_17_065446_add_caller_id_to_voice_mails_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2023_12_20_064153_add_name_to_inbound_routes_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2023_12_24_065211_create_pin_lists_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2023_12_27_115115_add_type_and_modify_others_fields_to_outbound_routes_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2023_12_28_045135_create_sms_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2024_01_01_043446_create_sms_histories_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2023_12_28_095911_create_campaign_sms_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2024_01_02_111632_add_contact_to_campaign_sms_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2024_01_03_130211_update_sip_users_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2024_01_10_122448_create_time_groups_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2024_01_10_122932_create_time_conditions_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2024_01_13_121712_create_call_histories_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2024_01_28_061057_create_surveys_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2024_01_28_061131_create_survey_results_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2024_01_30_162053_update_campaign_calls_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2024_02_12_100326_create_leads_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2024_02_13_054746_create_ip_black_lists_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2024_02_20_111734_create_sms_profiles_table',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2024_02_25_145709_create_notifications_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2024_02_25_160858_create_mail_profiles_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2024_03_16_160824_update_ivrs_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2024_03_18_093048_create_virtual_agents_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2024_03_18_095622_create_flows_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2024_03_18_095637_create_flow_actions_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2024_03_20_090914_create_tts_histories_table',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2024_05_02_091357_create_mail_histories_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2024_05_05_113559_add_email_max_retry_and_phone_fields_into_surveys_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2024_05_29_095835_create_dialer_campaigns_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2024_06_04_100306_update_contacts_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2024_06_04_112714_create_scripts_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2024_06_26_142214_create_permission_tables',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2024_06_24_054016_create_custom_forms_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2024_07_29_150400_create_dialer_campaign_calls_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2024_08_06_204733_add_notes_field_to_contacts_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2024_08_18_115139_add_allow_ip_field_into_sip_users_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2024_08_28_115139_update_campaign',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2024_09_03_204912_update_dialer_campaign',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2024_09_02_175532_update_dialer_campaign_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2024_09_11_132914_create_call_parkings_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2024_09_17_132914_update_dialer_campaign_calls_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2024_09_25_154742_create_call_parking_logs_table',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2024_09_26_132850_create_voice_records_table',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (118,'2024_09_30_154753_update_voice_mails_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (119,'2024_10_02_112427_add_record_field_into_call_parkings_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (120,'2024_10_02_121707_create_tickets_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (121,'2024_10_02_123143_create_ticket_follow_ups_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (122,'2024_10_02_132850_alter_call_queue_extensions_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (123,'2024_10_02_132850_alter_queue_calls_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (124,'2024_10_07_164028_add_record_field_in_tickets_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (125,'2024_10_08_174534_add_login_code_in_call_queues_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (126,'2024_10_23_175851_update_call_queue_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (127,'2024_10_24_203109_update_call_queue_extensions_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (128,'2024_10_29_193330_update_survey_results_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (129,'2024_11_03_174004_update_caller_id_in_voice_mails_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (130,'2025_01_13_164434_add_duration_into_queue_calls_table',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (132,'2025_01_19_121050_create_ai_bots_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (133,'2025_01_19_162506_update_sip_user_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (134,'2025_02_04_174552_create_ai_conversations_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (135,'2025_02_05_163616_create_ai_assistant_calls_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (136,'2025_02_13_170029_update_language_and_model_fields_in_tts_profiles_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (137,'2025_02_25_124810_add_call_transfer_tone_field_into_ai_bots_table',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (138,'2025_02_27_123515_add_max_extension_field_into_organizations_table',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (139,'2025_03_06_132245_add_fields_into_ai_bots_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (140,'2025_03_09_131611_add_field_response_timeout_into_tts_histroies_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (141,'2025_03_11_121854_update_status_field_in_leads_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (142,'2025_03_09_131611_add_field_response_time_into_tts_histroies_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (143,'2025_04_13_124030_add_intent_analyzer_field_into_ivrs_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (144,'2025_04_21_165602_add_intent_analyzer_field_into_surveys_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (145,'2025_01_14_153703_add_call_limit_and_expire_date_into_organizations_table',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (146,'2025_04_24_183853_add_is_primary_field_into_organizations_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (147,'2025_04_28_123859_update_organization_tables',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (148,'2025_06_15_174854_alter__survey__timeout',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (151,'2025_08_13_090353_create_streams_table',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (152,'2025_08_14_115626_create_stream_histories_table',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (153,'2025_09_07_160914_alter_password_field_in_sip_users_table',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (154,'2025_09_08_130930_add_do_not_disturb_and_forwarding_fields_into_extensions_table',61);
