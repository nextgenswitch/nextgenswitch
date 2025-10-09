DROP TABLE IF EXISTS "ai_assistant_calls";
CREATE TABLE "ai_assistant_calls" (
  "id" INTEGER  NOT NULL,
  "call_id" char(36) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "caller_id" varchar(191) NOT NULL,
  "ai_assistant_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "ai_bots";
  ;
  CREATE TABLE "ai_bots" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "voice_id" INTEGER NOT NULL,
  "llm_provider_id" INTEGER NOT NULL,
  "api_key" varchar(191) NOT NULL,
  "api_endpoint" varchar(191) DEFAULT NULL,
  "model" varchar(191) DEFAULT NULL,
  "resource" longtext NOT NULL,
  "max_interactions" INTEGER NOT NULL,
  "max_silince" INTEGER NOT NULL,
  "waiting_tone" INTEGER DEFAULT NULL,
  "inaudible_voice" INTEGER DEFAULT NULL,
  "listening_tone" INTEGER DEFAULT NULL,
  "internal_directory" INTEGER NOT NULL,
  "email" varchar(191) DEFAULT NULL,
  "create_support_ticket" INTEGER NOT NULL DEFAULT 0,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "call_transfer_tone" INTEGER DEFAULT NULL,
  "tts_profile_id" INTEGER DEFAULT NULL,
  "stt_profile_id" INTEGER DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "ai_conversations";
  ;
  CREATE TABLE "ai_conversations" (
  "id" INTEGER  NOT NULL,
  "call_id" char(36) NOT NULL,
  "message" text NOT NULL,
  "ai_msg" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "announcements";
  ;
  CREATE TABLE "announcements" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "voice_id" INTEGER NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "api_access_logs";
  ;
  CREATE TABLE "api_access_logs" (
  "id" INTEGER  NOT NULL,
  "api_key_id" INTEGER NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "ip_address" varchar(191) NOT NULL,
  "url" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "api_keys";
  ;
  CREATE TABLE "api_keys" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "title" varchar(191) NOT NULL,
  "key" varchar(191) NOT NULL,
  "secret" varchar(191) NOT NULL,
  "status" INTEGER NOT NULL DEFAULT 1,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "api_logs";
  ;
  CREATE TABLE "api_logs" (
  "id" INTEGER  NOT NULL,
  "api_key_id" INTEGER NOT NULL,
  "ip_address" varchar(191) NOT NULL,
  "url" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "applications";
  ;
  CREATE TABLE "applications" (
  "id" INTEGER  NOT NULL,
  "name" varchar(191) NOT NULL,
  "code" INTEGER NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "call_histories";
  ;
  CREATE TABLE "call_histories" (
  "call_id" char(36) NOT NULL,
  "bridge_call_id" char(36) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "duration" INTEGER DEFAULT NULL,
  "record_file" varchar(191) DEFAULT NULL,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL);
  DROP TABLE IF EXISTS "call_parking_logs";
  ;
  CREATE TABLE "call_parking_logs" (
  "call_id" char(36) NOT NULL,
  "call_parking_id" INTEGER NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "parking_no" INTEGER NOT NULL,
  "from" varchar(191) NOT NULL,
  "to" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL);
  DROP TABLE IF EXISTS "call_parkings";
  ;
  CREATE TABLE "call_parkings" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "extension_no" INTEGER NOT NULL,
  "no_of_slot" INTEGER NOT NULL,
  "music_on_hold" INTEGER NOT NULL,
  "timeout" INTEGER NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "record" INTEGER NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "call_queue_extensions";
  ;
  CREATE TABLE "call_queue_extensions" (
  "id" INTEGER  NOT NULL,
  "call_queue_id" INTEGER NOT NULL,
  "extension_id" INTEGER NOT NULL,
  "priority" INTEGER NOT NULL,
  "member_type" INTEGER NOT NULL,
  "allow_diversion" INTEGER NOT NULL DEFAULT 0,
  "last_ans" TEXT NULL DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "last_dial" TEXT NULL DEFAULT NULL,
  "dynamic_queue" INTEGER DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "call_queues";
  ;
  CREATE TABLE "call_queues" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "extension_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "description" varchar(191) DEFAULT NULL,
  "strategy" INTEGER NOT NULL,
  "cid_name_prefix" varchar(191) DEFAULT NULL,
  "join_announcement" INTEGER DEFAULT NULL,
  "agent_announcemnet" INTEGER DEFAULT NULL,
  "service_level" varchar(191) DEFAULT NULL,
  "join_empty" INTEGER NOT NULL DEFAULT 1,
  "leave_when_empty" INTEGER NOT NULL DEFAULT 0,
  "timeout_priority" varchar(191) DEFAULT NULL,
  "queue_timeout" INTEGER NOT NULL DEFAULT 30,
  "member_timeout" INTEGER NOT NULL DEFAULT 15,
  "retry" INTEGER NOT NULL DEFAULT 5,
  "wrap_up_time" INTEGER NOT NULL DEFAULT 0,
  "queue_callback" INTEGER DEFAULT NULL,
  "music_on_hold" INTEGER DEFAULT NULL,
  "ring_busy_agent" INTEGER NOT NULL DEFAULT 0,
  "record" INTEGER NOT NULL DEFAULT 0,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "agent_function_id" INTEGER DEFAULT NULL,
  "agent_destination_id" INTEGER DEFAULT NULL,
  "join_extension_id" INTEGER DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "call_records";
  ;
  CREATE TABLE "call_records" (
  "call_id" char(36) NOT NULL,
  "dial_call_id" char(36) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "record_path" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL);
  DROP TABLE IF EXISTS "calls";
  ;
  CREATE TABLE "calls" (
  "id" char(36) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "parent_call_id" char(36) DEFAULT NULL,
  "caller_id" varchar(191) NOT NULL,
  "sip_user_id" INTEGER NOT NULL,
  "channel" varchar(191) DEFAULT NULL,
  "destination" varchar(191) NOT NULL,
  "status" INTEGER NOT NULL,
  "connect_time" TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "ringing_time" TEXT NULL DEFAULT NULL,
  "establish_time" TEXT NULL DEFAULT NULL,
  "disconnect_time" TEXT NULL DEFAULT NULL,
  "disconnecct_code" INTEGER DEFAULT NULL,
  "duration" INTEGER NOT NULL,
  "record_file" varchar(191) DEFAULT NULL,
  "user_agent" varchar(191) DEFAULT NULL,
  "uas" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "campaign_calls";
  ;
  CREATE TABLE "campaign_calls" (
  "id" char(36) NOT NULL,
  "campaign_id" INTEGER NOT NULL,
  "call_id" char(36) DEFAULT NULL,
  "tel" varchar(191) NOT NULL,
  "retry" INTEGER NOT NULL,
  "status" INTEGER DEFAULT NULL,
  "sms_history_id" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "duration" INTEGER DEFAULT 0,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "campaign_sms";
  ;
  CREATE TABLE "campaign_sms" (
  "id" INTEGER  NOT NULL,
  "campaign_id" INTEGER NOT NULL,
  "sms_history_id" char(36) NOT NULL,
  "retry" varchar(191) NOT NULL DEFAULT '1',
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "contact" varchar(191) NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "campaigns";
  ;
  CREATE TABLE "campaigns" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "from" varchar(191) NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "tts" text DEFAULT NULL,
  "tts_lang" varchar(10) DEFAULT NULL,
  "on_queue" INTEGER DEFAULT NULL,
  "provider_id" INTEGER DEFAULT NULL,
  "contact_groups" varchar(100) NOT NULL,
  "status" INTEGER NOT NULL,
  "max_retry" INTEGER NOT NULL,
  "call_limit" INTEGER NOT NULL,
  "timezone" varchar(100) NOT NULL,
  "start_at" TEXT NOT NULL,
  "end_at" TEXT NOT NULL,
  "schedule_days" varchar(100) NOT NULL,
  "total_sent" INTEGER DEFAULT NULL,
  "total_successfull" INTEGER DEFAULT NULL,
  "total_failed" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "contact_groups";
  ;
  CREATE TABLE "contact_groups" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(100) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "contacts";
  ;
  CREATE TABLE "contacts" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(100) NOT NULL,
  "cc" varchar(100) DEFAULT NULL,
  "tel_no" varchar(100) NOT NULL,
  "contact_groups" varchar(100) DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "first_name" varchar(191) NOT NULL DEFAULT 'unnamed',
  "last_name" varchar(191) DEFAULT NULL,
  "gender" varchar(191) DEFAULT NULL,
  "email" varchar(191) DEFAULT NULL,
  "address" text DEFAULT NULL,
  "city" varchar(191) DEFAULT NULL,
  "state" varchar(191) DEFAULT NULL,
  "post_code" varchar(191) DEFAULT NULL,
  "country" varchar(191) DEFAULT NULL,
  "notes" text DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "custom_forms";
  ;
  CREATE TABLE "custom_forms" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "fields" text NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "custom_funcs";
  ;
  CREATE TABLE "custom_funcs" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "func_lang" INTEGER NOT NULL,
  "func_body" text NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "dialer_campaign_calls";
  ;
  CREATE TABLE "dialer_campaign_calls" (
  "id" char(36) NOT NULL,
  "dialer_campaign_id" INTEGER NOT NULL,
  "call_id" char(36) DEFAULT NULL,
  "tel" varchar(191) NOT NULL,
  "retry" INTEGER NOT NULL DEFAULT 1,
  "status" INTEGER DEFAULT NULL,
  "duration" INTEGER NOT NULL DEFAULT 0,
  "form_data" text DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "record_file" varchar(191) DEFAULT NULL);
  DROP TABLE IF EXISTS "dialer_campaigns";
  ;
  CREATE TABLE "dialer_campaigns" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "description" varchar(191) DEFAULT NULL,
  "contact_groups" varchar(100) NOT NULL,
  "agents" varchar(191) DEFAULT NULL,
  "timezone" varchar(100) NOT NULL,
  "end_date" TEXT DEFAULT NULL,
  "start_at" TEXT NOT NULL,
  "end_at" TEXT NOT NULL,
  "schedule_days" varchar(100) NOT NULL,
  "script_id" INTEGER NOT NULL,
  "form_id" INTEGER NOT NULL,
  "status" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "max_retry" INTEGER DEFAULT 0,
  "call_interval" INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "extension_groups";
  ;
  CREATE TABLE "extension_groups" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "extension_id" text NOT NULL,
  "algorithm" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "extensions";
  ;
  CREATE TABLE "extensions" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "code" INTEGER NOT NULL,
  "extension_type" INTEGER NOT NULL DEFAULT 1,
  "function_id" INTEGER NOT NULL DEFAULT 1,
  "destination_id" INTEGER NOT NULL,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "forwarding_number" varchar(255) DEFAULT NULL,
  "dynamic_queue" INTEGER DEFAULT NULL,
  "do_not_disturb" INTEGER NOT NULL DEFAULT 0,
  "forwarding" INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "failed_jobs";
  ;
  CREATE TABLE "failed_jobs" (
  "id" INTEGER  NOT NULL,
  "uuid" varchar(191) NOT NULL,
  "connection" text NOT NULL,
  "queue" text NOT NULL,
  "payload" longtext NOT NULL,
  "exception" longtext NOT NULL,
  "failed_at" TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "flow_actions";
  ;
  CREATE TABLE "flow_actions" (
  "id" INTEGER  NOT NULL,
  "title" varchar(255) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "action_type" INTEGER NOT NULL,
  "action_value" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "flows";
  ;
  CREATE TABLE "flows" (
  "id" INTEGER  NOT NULL,
  "title" varchar(255) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "voice_file" varchar(191) DEFAULT NULL,
  "match_type" INTEGER NOT NULL,
  "match_value" varchar(191) NOT NULL,
  "match_action_id" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "funcs";
  ;
  CREATE TABLE "funcs" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "func_type" INTEGER NOT NULL,
  "func" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "hotdesks";
  ;
  CREATE TABLE "hotdesks" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "sip_user_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "inbound_routes";
  ;
  CREATE TABLE "inbound_routes" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "did_pattern" varchar(191) NOT NULL,
  "cid_pattern" varchar(191) DEFAULT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "name" varchar(191) NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "ip_black_lists";
  ;
  CREATE TABLE "ip_black_lists" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "title" varchar(191) DEFAULT NULL,
  "ip" varchar(191) NOT NULL,
  "subnet" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "ivr_actions";
  ;
  CREATE TABLE "ivr_actions" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "ivr_id" INTEGER NOT NULL,
  "digit" INTEGER NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "voice" varchar(191) DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "ivrs";
  ;
  CREATE TABLE "ivrs" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "welcome_voice" INTEGER DEFAULT NULL,
  "instruction_voice" INTEGER NOT NULL,
  "invalid_voice" INTEGER NOT NULL,
  "timeout_voice" INTEGER NOT NULL,
  "invalid_retry_voice" INTEGER DEFAULT NULL,
  "timeout_retry_voice" INTEGER DEFAULT NULL,
  "timeout" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "max_digit" INTEGER DEFAULT NULL,
  "max_retry" INTEGER DEFAULT NULL,
  "end_key" char(1) DEFAULT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "mode" INTEGER NOT NULL DEFAULT 0,
  "intent_analyzer" text DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "jobs";
  ;
  CREATE TABLE "jobs" (
  "id" INTEGER  NOT NULL,
  "queue" varchar(191) NOT NULL,
  "payload" longtext NOT NULL,
  "attempts" INTEGER  NOT NULL,
  "reserved_at" INTEGER  DEFAULT NULL,
  "available_at" INTEGER  NOT NULL,
  "created_at" INTEGER  NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "leads";
  ;
  CREATE TABLE "leads" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "designation" varchar(191) DEFAULT NULL,
  "phone" varchar(191) NOT NULL,
  "email" varchar(191) DEFAULT NULL,
  "website" varchar(191) DEFAULT NULL,
  "company" varchar(191) DEFAULT NULL,
  "address" text DEFAULT NULL,
  "source" varchar(191) DEFAULT NULL,
  "notes" text DEFAULT NULL,
  "status" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "mail_histories";
  ;
  CREATE TABLE "mail_histories" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "mail_profiles";
  ;
  CREATE TABLE "mail_profiles" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "provider" varchar(191) NOT NULL,
  "options" text NOT NULL,
  "default" INTEGER NOT NULL DEFAULT 0,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "migrations";
  ;
  CREATE TABLE "migrations" (
  "id" INTEGER  NOT NULL,
  "migration" varchar(191) NOT NULL,
  "batch" INTEGER NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "model_has_permissions";
  ;
  CREATE TABLE "model_has_permissions" (
  "permission_id" INTEGER  NOT NULL,
  "model_type" varchar(191) NOT NULL,
  "model_id" INTEGER  NOT NULL,
  "organization_id" INTEGER  NOT NULL,
  PRIMARY KEY ("organization_id","permission_id","model_id","model_type"),
  CONSTRAINT "model_has_permissions_permission_id_foreign" FOREIGN KEY ("permission_id") REFERENCES "permissions" ("id") ON DELETE CASCADE);
  DROP TABLE IF EXISTS "model_has_roles";
  ;
  CREATE TABLE "model_has_roles" (
  "role_id" INTEGER  NOT NULL,
  "model_type" varchar(191) NOT NULL,
  "model_id" INTEGER  NOT NULL,
  "organization_id" INTEGER  NOT NULL,
  PRIMARY KEY ("organization_id","role_id","model_id","model_type"),
  CONSTRAINT "model_has_roles_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles" ("id") ON DELETE CASCADE);
  DROP TABLE IF EXISTS "notifications";
  ;
  CREATE TABLE "notifications" (
  "id" char(36) NOT NULL,
  "type" varchar(191) NOT NULL,
  "notifiable_type" varchar(191) NOT NULL,
  "notifiable_id" INTEGER  NOT NULL,
  "data" text NOT NULL,
  "read_at" TEXT NULL DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "organizations";
  ;
  CREATE TABLE "organizations" (
  "id" INTEGER  NOT NULL,
  "plan_id" INTEGER DEFAULT NULL,
  "name" varchar(191) NOT NULL,
  "domain" varchar(191) NOT NULL,
  "contact_no" varchar(191) DEFAULT NULL,
  "email" varchar(191) NOT NULL,
  "address" varchar(191) DEFAULT NULL,
  "credit" REAL NOT NULL DEFAULT 0.00,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "is_default" INTEGER DEFAULT NULL,
  "max_extension" INTEGER NOT NULL DEFAULT 0,
  "call_limit" INTEGER NOT NULL DEFAULT 0,
  "expire_date" TEXT DEFAULT NULL,
  "is_primary" INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "outbound_routes";
  ;
  CREATE TABLE "outbound_routes" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "trunk_id" varchar(191) NOT NULL,
  "name" varchar(191) NOT NULL,
  "pattern" text NOT NULL,
  "priority" INTEGER DEFAULT NULL,
  "is_active" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "pin_list_id" INTEGER DEFAULT NULL,
  "function_id" INTEGER DEFAULT NULL,
  "destination_id" INTEGER DEFAULT NULL,
  "record" INTEGER DEFAULT NULL,
  "outbound_cid" varchar(191) DEFAULT NULL,
  "type" INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "password_reset_tokens";
  ;
  CREATE TABLE "password_reset_tokens" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "email" varchar(191) NOT NULL,
  "token" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "permissions";
  ;
  CREATE TABLE "permissions" (
  "id" INTEGER  NOT NULL,
  "name" varchar(191) NOT NULL,
  "guard_name" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "personal_access_tokens";
  ;
  CREATE TABLE "personal_access_tokens" (
  "id" INTEGER  NOT NULL,
  "tokenable_type" varchar(191) NOT NULL,
  "tokenable_id" INTEGER  NOT NULL,
  "name" varchar(191) NOT NULL,
  "token" varchar(64) NOT NULL,
  "abilities" text DEFAULT NULL,
  "last_used_at" TEXT NULL DEFAULT NULL,
  "expires_at" TEXT NULL DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "pin_lists";
  ;
  CREATE TABLE "pin_lists" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "pin_list" text NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "plans";
  ;
  CREATE TABLE "plans" (
  "id" INTEGER  NOT NULL,
  "name" varchar(191) NOT NULL,
  "duration" INTEGER NOT NULL,
  "price" REAL NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "queue_calls";
  ;
  CREATE TABLE "queue_calls" (
  "call_id" char(36) NOT NULL,
  "parent_call_id" char(36) DEFAULT NULL,
  "organization_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "extension_id" INTEGER NOT NULL,
  "call_queue_id" INTEGER NOT NULL,
  "status" INTEGER NOT NULL,
  "duration" INTEGER DEFAULT NULL);
  DROP TABLE IF EXISTS "queues";
  ;
  CREATE TABLE "queues" (
  "call_id" char(36) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "sip_user_id" INTEGER DEFAULT NULL,
  "bridge_call_id" char(36) DEFAULT NULL,
  "call_queue_id" INTEGER DEFAULT NULL,
  "queue_name" varchar(191) NOT NULL,
  "duration" INTEGER DEFAULT NULL,
  "waiting_duration" INTEGER DEFAULT NULL,
  "recieved_by" INTEGER DEFAULT NULL,
  "record_file" varchar(191) DEFAULT NULL,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL);
  DROP TABLE IF EXISTS "ring_group_calls";
  ;
  CREATE TABLE "ring_group_calls" (
  "ring_group_id" INTEGER NOT NULL,
  "call_id" char(36) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL);
  DROP TABLE IF EXISTS "ring_groups";
  ;
  CREATE TABLE "ring_groups" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "extension_id" INTEGER NOT NULL,
  "description" varchar(191) NOT NULL,
  "ring_strategy" INTEGER NOT NULL,
  "ring_time" INTEGER NOT NULL,
  "answer_channel" INTEGER NOT NULL DEFAULT 0,
  "skip_busy_extension" INTEGER NOT NULL DEFAULT 0,
  "allow_diversions" INTEGER NOT NULL DEFAULT 0,
  "ringback_tone" INTEGER NOT NULL DEFAULT 0,
  "extensions" text NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "role_has_permissions";
  ;
  CREATE TABLE "role_has_permissions" (
  "permission_id" INTEGER  NOT NULL,
  "role_id" INTEGER  NOT NULL,
  PRIMARY KEY ("permission_id","role_id"),
  CONSTRAINT "role_has_permissions_permission_id_foreign" FOREIGN KEY ("permission_id") REFERENCES "permissions" ("id") ON DELETE CASCADE,
  CONSTRAINT "role_has_permissions_role_id_foreign" FOREIGN KEY ("role_id") REFERENCES "roles" ("id") ON DELETE CASCADE);
  DROP TABLE IF EXISTS "roles";
  ;
  CREATE TABLE "roles" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER  DEFAULT NULL,
  "name" varchar(191) NOT NULL,
  "guard_name" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "scripts";
  ;
  CREATE TABLE "scripts" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "content" mediumtext NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "settings";
  ;
  CREATE TABLE "settings" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "key" varchar(191) NOT NULL,
  "value" text NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "group" varchar(191) DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "sip_channels";
  ;
  CREATE TABLE "sip_channels" (
  "sip_user_id" INTEGER NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "location" varchar(191) NOT NULL,
  "expire" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "ua" varchar(255) DEFAULT NULL);
  DROP TABLE IF EXISTS "sip_users";
  ;
  CREATE TABLE "sip_users" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "username" varchar(191) NOT NULL,
  "password" varchar(32) DEFAULT NULL,
  "host" varchar(191) DEFAULT NULL,
  "port" INTEGER DEFAULT NULL,
  "transport" INTEGER DEFAULT NULL,
  "peer" INTEGER NOT NULL DEFAULT 1,
  "record" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "user_type" INTEGER DEFAULT NULL,
  "call_limit" INTEGER DEFAULT 0,
  "status" INTEGER DEFAULT 1,
  "allow_ip" text DEFAULT NULL,
  "overwrite_cid" INTEGER DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "sms";
  ;
  CREATE TABLE "sms" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "title" varchar(191) NOT NULL,
  "content" text NOT NULL,
  "sms_count" INTEGER NOT NULL DEFAULT 1,
  "status" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "sms_histories";
  ;
  CREATE TABLE "sms_histories" (
  "id" char(36) NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "trxid" varchar(191) DEFAULT NULL,
  "from" varchar(191) NOT NULL,
  "to" varchar(191) NOT NULL,
  "body" text NOT NULL,
  "sms_count" INTEGER NOT NULL,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "sms_profiles";
  ;
  CREATE TABLE "sms_profiles" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "provider" varchar(191) NOT NULL,
  "options" text NOT NULL,
  "default" INTEGER NOT NULL DEFAULT 0,
  "status" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "stream_histories";
  ;
  CREATE TABLE "stream_histories" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "call_id" char(36) NOT NULL,
  "stream_id" varchar(191) NOT NULL,
  "caller_id" varchar(191) NOT NULL,
  "duration" INTEGER NOT NULL DEFAULT 0,
  "record_file" varchar(191) DEFAULT NULL,
  "transcript" varchar(191) DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "streams";
  ;
  CREATE TABLE "streams" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "ws_url" varchar(191) NOT NULL,
  "prompt" text DEFAULT NULL,
  "greetings" text DEFAULT NULL,
  "extra_parameters" text DEFAULT NULL,
  "forwarding_number" varchar(191) DEFAULT NULL,
  "max_call_duration" INTEGER NOT NULL DEFAULT 0,
  "record" INTEGER NOT NULL DEFAULT 0,
  "email" varchar(191) DEFAULT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "survey_results";
  ;
  CREATE TABLE "survey_results" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "call_id" char(36) NOT NULL,
  "survey_id" INTEGER NOT NULL,
  "caller_id" varchar(191) NOT NULL,
  "pressed_key" INTEGER DEFAULT NULL,
  "record_file" varchar(191) DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "surveys";
  ;
  CREATE TABLE "surveys" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "voice_id" INTEGER NOT NULL,
  "type" INTEGER NOT NULL DEFAULT 0,
  "keys" text DEFAULT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "max_retry" INTEGER NOT NULL,
  "email" varchar(191) DEFAULT NULL,
  "phone" varchar(191) DEFAULT NULL,
  "intent_analyzer" text DEFAULT NULL,
  "timeout" varchar(191) DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "ticket_follow_ups";
  ;
  CREATE TABLE "ticket_follow_ups" (
  "id" INTEGER  NOT NULL,
  "ticket_id" INTEGER NOT NULL,
  "user_id" INTEGER NOT NULL,
  "comment" text NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "tickets";
  ;
  CREATE TABLE "tickets" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "ticket_id" INTEGER NOT NULL,
  "user_id" INTEGER DEFAULT NULL,
  "name" varchar(191) DEFAULT NULL,
  "phone" varchar(191) NOT NULL,
  "subject" varchar(191) NOT NULL,
  "description" text NOT NULL,
  "status" INTEGER NOT NULL DEFAULT 1,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "record" varchar(191) DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "time_conditions";
  ;
  CREATE TABLE "time_conditions" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "time_group_id" INTEGER NOT NULL,
  "matched_function_id" INTEGER NOT NULL,
  "matched_destination_id" INTEGER NOT NULL,
  "function_id" INTEGER NOT NULL,
  "destination_id" INTEGER NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "time_groups";
  ;
  CREATE TABLE "time_groups" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "time_zone" varchar(191) NOT NULL,
  "schedules" text DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "trunks";
  ;
  CREATE TABLE "trunks" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "sip_user_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "tts_histories";
  ;
  CREATE TABLE "tts_histories" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "tts_profile_id" INTEGER NOT NULL,
  "type" INTEGER NOT NULL,
  "input" text NOT NULL,
  "output" text DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "response_timeout" REAL NOT NULL,
  "response_time" REAL NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "tts_profiles";
  ;
  CREATE TABLE "tts_profiles" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER DEFAULT NULL,
  "name" varchar(191) NOT NULL,
  "provider" varchar(191) NOT NULL,
  "language" varchar(191) DEFAULT NULL,
  "model" varchar(191) DEFAULT NULL,
  "config" text DEFAULT NULL,
  "is_default" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "type" INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "users";
  ;
  CREATE TABLE "users" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "email" varchar(191) NOT NULL,
  "password" varchar(191) NOT NULL,
  "role" varchar(191) NOT NULL DEFAULT 'user',
  "email_verified_at" TEXT NULL DEFAULT NULL,
  "status" INTEGER NOT NULL DEFAULT 0,
  "remember_token" varchar(100) DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "virtual_agents";
  ;
  CREATE TABLE "virtual_agents" (
  "id" INTEGER  NOT NULL,
  "name" varchar(191) NOT NULL,
  "welcome_voice" varchar(191) NOT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "voice_files";
  ;
  CREATE TABLE "voice_files" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "voice_type" INTEGER NOT NULL,
  "file_name" varchar(191) DEFAULT NULL,
  "tts_text" text DEFAULT NULL,
  "tts_profile_id" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "voice_mails";
  ;
  CREATE TABLE "voice_mails" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "voice_path" varchar(191) DEFAULT NULL,
  "transcript" text DEFAULT NULL,
  "read" INTEGER NOT NULL DEFAULT 0,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  "caller_id" varchar(191) NOT NULL,
  "call_id" char(36) NOT NULL,
  "voice_record_id" INTEGER NOT NULL,
  PRIMARY KEY ("id"));
  DROP TABLE IF EXISTS "voice_records";
  ;
  CREATE TABLE "voice_records" (
  "id" INTEGER  NOT NULL,
  "organization_id" INTEGER NOT NULL,
  "name" varchar(191) NOT NULL,
  "voice_id" INTEGER NOT NULL,
  "is_transcript" INTEGER NOT NULL DEFAULT 0,
  "text" text DEFAULT NULL,
  "play_beep" INTEGER NOT NULL DEFAULT 0,
  "is_send_email" INTEGER NOT NULL DEFAULT 0,
  "email" varchar(191) DEFAULT NULL,
  "phone" varchar(100) DEFAULT NULL,
  "is_create_ticket" INTEGER DEFAULT NULL,
  "created_at" TEXT NULL DEFAULT NULL,
  "updated_at" TEXT NULL DEFAULT NULL,
  PRIMARY KEY ("id"));
  ;
  ;
  INSERT INTO "migrations" ("id", "migration", "batch") VALUES (1,'2014_10_12_000000_create_users_table',1
);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (5,'2023_03_21_104401_create_voice_files_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (6,'2023_03_23_093935_create_plans_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (8,'2023_06_13_064707_create_extensions_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (9,'2023_06_13_065743_create_sip_users_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (10,'2023_06_13_065756_create_ivrs_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (11,'2023_06_13_070224_create_ivr_actions_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (12,'2023_06_13_070602_create_extension_groups_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (13,'2023_06_13_094008_create_organizations_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (14,'2023_07_23_115041_create_apis_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (15,'2023_07_24_111950_create_trunks_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (16,'2023_07_25_051301_create_outbound_routes_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (17,'2023_07_25_052704_create_inbound_routes_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (18,'2023_07_25_112432_create_funcs_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (19,'2023_07_27_111438_create_applications_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (20,'2023_08_01_114202_create_custom_funcs_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (21,'2023_08_16_064612_create_hotdesks_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (22,'2023_08_20_050311_create_calls_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (23,'2023_08_20_050341_create_call_legs_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (24,'2023_09_14_065450_create_tts_profiles_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (25,'2023_10_01_053536_create_call_queues_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (26,'2023_10_01_061725_create_ring_groups_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (27,'2023_10_02_055934_create_call_queue_extensions_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (28,'2023_10_11_043005_create_settings_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (29,'2023_10_18_054628_create_call_records_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (30,'2023_10_18_102005_create_queues_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (31,'2023_10_26_055153_create_jobs_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (32,'2023_10_26_113717_create_queue_calls_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (33,'2023_10_26_114548_create_ring_group_calls_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (34,'2023_10_26_115218_create_sip_channels_table',1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (35,'2023_10_30_060917_create_voice_mails_table',2);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (36,'2023_11_09_114236_add_agent_function_id_and_agent_destination_id_to_call_queues',3);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (37,'2023_11_09_114919_alter_queue_callback_and_description_in_call_queues',3);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (38,'2023_11_12_050459_update_organizations_table',4);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (39,'2023_11_09_114236_add_agent_function_id_and_agent_destination_id_to_call_queues_table',5);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (40,'2023_11_09_114919_alter_queue_callback_and_description_in_call_queues_table',5);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (41,'2023_11_14_053003_add_max_digit_max_retry_end_key_function_id_and_destination_id_to_ivrs_table',5);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (42,'2023_11_14_101832_add_dynamic_queue_and_static_queue_to_extensions',5);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (43,'2023_07_23_115041_create_api_keys_table',6);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (44,'2023_11_16_103634_create_api_logs_table',6);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (45,'2023_11_16_103634_create_api_access_logs_table',7);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (46,'2023_03_21_104356_create_contact_groups_table',8);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (47,'2023_03_21_104357_create_contacts_table',8);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (48,'2023_03_27_061010_create_campaigns_table',8);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (49,'2023_11_20_054841_create_campaign_calls_table',9);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (50,'2023_11_23_104801_update_settings_table',10);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (52,'2023_11_28_102717_rename_others_to_config_column_in_tts_profiles_table',12);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (53,'2023_11_28_115729_update_extension_table',13);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (54,'2023_11_29_061418_add_type_to_tts_profiles_table',14);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (55,'2023_11_29_065339_update_outbound_route_table',14);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (56,'2023_12_05_105447_create_announcements_table',15);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (57,'2023_12_06_043308_update_tts_profiles_table',16);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (58,'2023_12_14_054617_add_ua_to_sip_channels_table',17);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (59,'2023_12_17_115138_update_sip_users_table',17);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (63,'2023_12_17_120117_update_outbound_routes_table',18);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (64,'2023_12_17_065446_add_caller_id_to_voice_mails_table',19);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (65,'2023_12_20_064153_add_name_to_inbound_routes_table',19);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (66,'2023_12_24_065211_create_pin_lists_table',19);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (67,'2023_12_27_115115_add_type_and_modify_others_fields_to_outbound_routes_table',20);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (70,'2023_12_28_045135_create_sms_table',21);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (72,'2024_01_01_043446_create_sms_histories_table',22);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (75,'2023_12_28_095911_create_campaign_sms_table',23);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (76,'2024_01_02_111632_add_contact_to_campaign_sms_table',23);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (77,'2024_01_03_130211_update_sip_users_table',24);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (78,'2024_01_10_122448_create_time_groups_table',25);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (79,'2024_01_10_122932_create_time_conditions_table',25);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (81,'2024_01_13_121712_create_call_histories_table',26);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (85,'2024_01_28_061057_create_surveys_table',27);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (86,'2024_01_28_061131_create_survey_results_table',27);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (87,'2024_01_30_162053_update_campaign_calls_table',28);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (88,'2024_02_12_100326_create_leads_table',29);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (90,'2024_02_13_054746_create_ip_black_lists_table',30);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (91,'2024_02_20_111734_create_sms_profiles_table',31);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (92,'2024_02_25_145709_create_notifications_table',32);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (93,'2024_02_25_160858_create_mail_profiles_table',33);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (94,'2024_03_16_160824_update_ivrs_table',33);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (95,'2024_03_18_093048_create_virtual_agents_table',34);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (96,'2024_03_18_095622_create_flows_table',35);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (97,'2024_03_18_095637_create_flow_actions_table',35);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (98,'2024_03_20_090914_create_tts_histories_table',36);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (99,'2024_05_02_091357_create_mail_histories_table',37);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (100,'2024_05_05_113559_add_email_max_retry_and_phone_fields_into_surveys_table',37);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (101,'2024_05_29_095835_create_dialer_campaigns_table',37);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (102,'2024_06_04_100306_update_contacts_table',37);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (103,'2024_06_04_112714_create_scripts_table',37);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (104,'2024_06_26_142214_create_permission_tables',38);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (105,'2024_06_24_054016_create_custom_forms_table',39);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (106,'2024_07_29_150400_create_dialer_campaign_calls_table',40);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (107,'2024_08_06_204733_add_notes_field_to_contacts_table',40);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (108,'2024_08_18_115139_add_allow_ip_field_into_sip_users_table',40);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (110,'2024_08_28_115139_update_campaign',41);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (111,'2024_09_03_204912_update_dialer_campaign',42);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (112,'2024_09_02_175532_update_dialer_campaign_table',43);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (113,'2024_09_11_132914_create_call_parkings_table',44);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (114,'2024_09_17_132914_update_dialer_campaign_calls_table',45);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (116,'2024_09_25_154742_create_call_parking_logs_table',46);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (117,'2024_09_26_132850_create_voice_records_table',47);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (118,'2024_09_30_154753_update_voice_mails_table',48);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (119,'2024_10_02_112427_add_record_field_into_call_parkings_table',48);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (120,'2024_10_02_121707_create_tickets_table',48);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (121,'2024_10_02_123143_create_ticket_follow_ups_table',48);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (122,'2024_10_02_132850_alter_call_queue_extensions_table',48);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (123,'2024_10_02_132850_alter_queue_calls_table',49);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (124,'2024_10_07_164028_add_record_field_in_tickets_table',49);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (125,'2024_10_08_174534_add_login_code_in_call_queues_table',49);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (126,'2024_10_23_175851_update_call_queue_table',50);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (127,'2024_10_24_203109_update_call_queue_extensions_table',50);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (128,'2024_10_29_193330_update_survey_results_table',50);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (129,'2024_11_03_174004_update_caller_id_in_voice_mails_table',50);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (130,'2025_01_13_164434_add_duration_into_queue_calls_table',51);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (132,'2025_01_19_121050_create_ai_bots_table',52);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (133,'2025_01_19_162506_update_sip_user_table',52);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (134,'2025_02_04_174552_create_ai_conversations_table',52);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (135,'2025_02_05_163616_create_ai_assistant_calls_table',52);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (136,'2025_02_13_170029_update_language_and_model_fields_in_tts_profiles_table',52);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (137,'2025_02_25_124810_add_call_transfer_tone_field_into_ai_bots_table',53);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (138,'2025_02_27_123515_add_max_extension_field_into_organizations_table',54);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (139,'2025_03_06_132245_add_fields_into_ai_bots_table',55);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (140,'2025_03_09_131611_add_field_response_timeout_into_tts_histroies_table',55);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (141,'2025_03_11_121854_update_status_field_in_leads_table',55);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (142,'2025_03_09_131611_add_field_response_time_into_tts_histroies_table',56);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (143,'2025_04_13_124030_add_intent_analyzer_field_into_ivrs_table',56);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (144,'2025_04_21_165602_add_intent_analyzer_field_into_surveys_table',56);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (145,'2025_01_14_153703_add_call_limit_and_expire_date_into_organizations_table',57);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (146,'2025_04_24_183853_add_is_primary_field_into_organizations_table',58);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (147,'2025_04_28_123859_update_organization_tables',58);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (148,'2025_06_15_174854_alter__survey__timeout',59);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (151,'2025_08_13_090353_create_streams_table',60);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (152,'2025_08_14_115626_create_stream_histories_table',60);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (153,'2025_09_07_160914_alter_password_field_in_sip_users_table',61);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (154,'2025_09_08_130930_add_do_not_disturb_and_forwarding_fields_into_extensions_table',61);

-- Indexes extracted from MySQL KEY clauses
CREATE UNIQUE INDEX "idx_ai_assistant_calls_failed_jobs_uuid_unique" ON "ai_assistant_calls" ("uuid");
CREATE INDEX "idx_ai_assistant_calls_jobs_queue_index" ON "ai_assistant_calls" ("queue");
CREATE INDEX "idx_ai_assistant_calls_model_has_permissions_model_id_model_type_index" ON "ai_assistant_calls" ("model_id", "model_type");
CREATE INDEX "idx_ai_assistant_calls_model_has_permissions_permission_id_foreign" ON "ai_assistant_calls" ("permission_id");
CREATE INDEX "idx_ai_assistant_calls_model_has_permissions_team_foreign_key_index" ON "ai_assistant_calls" ("organization_id");
CREATE INDEX "idx_ai_assistant_calls_model_has_roles_model_id_model_type_index" ON "ai_assistant_calls" ("model_id", "model_type");
CREATE INDEX "idx_ai_assistant_calls_model_has_roles_role_id_foreign" ON "ai_assistant_calls" ("role_id");
CREATE INDEX "idx_ai_assistant_calls_model_has_roles_team_foreign_key_index" ON "ai_assistant_calls" ("organization_id");
CREATE INDEX "idx_ai_assistant_calls_notifications_notifiable_type_notifiable_id_index" ON "ai_assistant_calls" ("notifiable_type", "notifiable_id");
CREATE UNIQUE INDEX "idx_ai_assistant_calls_permissions_name_guard_name_unique" ON "ai_assistant_calls" ("name", "guard_name");
CREATE UNIQUE INDEX "idx_ai_assistant_calls_personal_access_tokens_token_unique" ON "ai_assistant_calls" ("token");
CREATE INDEX "idx_ai_assistant_calls_personal_access_tokens_tokenable_type_tokenable_id_index" ON "ai_assistant_calls" ("tokenable_type", "tokenable_id");
CREATE INDEX "idx_ai_assistant_calls_role_has_permissions_role_id_foreign" ON "ai_assistant_calls" ("role_id");
CREATE UNIQUE INDEX "idx_ai_assistant_calls_roles_organization_id_name_guard_name_unique" ON "ai_assistant_calls" ("organization_id", "name", "guard_name");
CREATE INDEX "idx_ai_assistant_calls_roles_team_foreign_key_index" ON "ai_assistant_calls" ("organization_id");
