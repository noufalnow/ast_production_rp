-- Adminer 4.8.1 PostgreSQL 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1) dump

\connect "db_csol_ast";

DROP TABLE IF EXISTS "cnfg_acl_actions";
DROP SEQUENCE IF EXISTS cnfg_acl_actions_action_id_seq;
CREATE SEQUENCE cnfg_acl_actions_action_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 199 CACHE 1;

CREATE TABLE "public"."cnfg_acl_actions" (
    "action_id" integer DEFAULT nextval('cnfg_acl_actions_action_id_seq') NOT NULL,
    "action_controller_id" bigint NOT NULL,
    "action_name" text NOT NULL,
    "action_label" text NOT NULL,
    "action_desc" text NOT NULL,
    "action_resource" smallint,
    "action_type" smallint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamp(0),
    "t_modified" timestamp(0),
    "t_deleted" timestamp(0),
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "cnfg_acl_actions_pkey" PRIMARY KEY ("action_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "cnfg_acl_actions_access";
DROP SEQUENCE IF EXISTS cnfg_acl_actions_access_aacc_id_seq;
CREATE SEQUENCE cnfg_acl_actions_access_aacc_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 6579 CACHE 1;

CREATE TABLE "public"."cnfg_acl_actions_access" (
    "aacc_id" integer DEFAULT nextval('cnfg_acl_actions_access_aacc_id_seq') NOT NULL,
    "aacc_action_id" bigint NOT NULL,
    "aacc_role_id" bigint NOT NULL,
    "aacc_role_type" smallint NOT NULL,
    "aacc_access_status" smallint DEFAULT '(1)' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamp(0),
    "t_modified" timestamp(0),
    "t_deleted" timestamp(0),
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "cnfg_acl_actions_access_pkey" PRIMARY KEY ("aacc_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "cnfg_acl_controllers";
DROP SEQUENCE IF EXISTS cnfg_acl_controllers_controller_id_seq;
CREATE SEQUENCE cnfg_acl_controllers_controller_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 33 CACHE 1;

CREATE TABLE "public"."cnfg_acl_controllers" (
    "controller_id" integer DEFAULT nextval('cnfg_acl_controllers_controller_id_seq') NOT NULL,
    "controller_module_id" bigint NOT NULL,
    "controller_name" text NOT NULL,
    "controller_label" text NOT NULL,
    "controller_desc" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamp(0),
    "t_modified" timestamp(0),
    "t_deleted" timestamp(0),
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "cnfg_acl_controllers_pkey" PRIMARY KEY ("controller_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "cnfg_acl_controllers_access";
DROP SEQUENCE IF EXISTS cnfg_acl_controllers_access_cacc_id_seq;
CREATE SEQUENCE cnfg_acl_controllers_access_cacc_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 1201 CACHE 1;

CREATE TABLE "public"."cnfg_acl_controllers_access" (
    "cacc_id" integer DEFAULT nextval('cnfg_acl_controllers_access_cacc_id_seq') NOT NULL,
    "cacc_controller_id" bigint NOT NULL,
    "cacc_role_id" bigint NOT NULL,
    "cacc_role_type" smallint NOT NULL,
    "cacc_create_status" smallint DEFAULT '(1)' NOT NULL,
    "cacc_update_status" smallint DEFAULT '(1)' NOT NULL,
    "cacc_view_status" smallint DEFAULT '(1)' NOT NULL,
    "cacc_delete_status" smallint DEFAULT '(1)' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamp(0),
    "t_modified" timestamp(0),
    "t_deleted" timestamp(0),
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "cnfg_acl_controllers_access_pkey" PRIMARY KEY ("cacc_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "cnfg_acl_modules";
DROP SEQUENCE IF EXISTS cnfg_acl_modules_module_id_seq;
CREATE SEQUENCE cnfg_acl_modules_module_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 12 CACHE 1;

CREATE TABLE "public"."cnfg_acl_modules" (
    "module_id" integer DEFAULT nextval('cnfg_acl_modules_module_id_seq') NOT NULL,
    "module_name" text NOT NULL,
    "module_label" text NOT NULL,
    "module_desc" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamp(0),
    "t_modified" timestamp(0),
    "t_deleted" timestamp(0),
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "cnfg_acl_modules_pkey" PRIMARY KEY ("module_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "cnfg_acl_modules_access";
DROP SEQUENCE IF EXISTS cnfg_acl_modules_access_macc_id_seq;
CREATE SEQUENCE cnfg_acl_modules_access_macc_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 521 CACHE 1;

CREATE TABLE "public"."cnfg_acl_modules_access" (
    "macc_id" integer DEFAULT nextval('cnfg_acl_modules_access_macc_id_seq') NOT NULL,
    "macc_module_id" bigint NOT NULL,
    "macc_role_id" bigint NOT NULL,
    "macc_role_type" smallint NOT NULL,
    "macc_create_status" smallint DEFAULT '(1)' NOT NULL,
    "macc_update_status" smallint DEFAULT '(1)' NOT NULL,
    "macc_view_status" smallint DEFAULT '(1)' NOT NULL,
    "macc_delete_status" smallint DEFAULT '(1)' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamp(0),
    "t_modified" timestamp(0),
    "t_deleted" timestamp(0),
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "cnfg_acl_modules_access_pkey" PRIMARY KEY ("macc_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_bank_account";
DROP SEQUENCE IF EXISTS core_bank_account_acnt_id_seq;
CREATE SEQUENCE core_bank_account_acnt_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 4 CACHE 1;

CREATE TABLE "public"."core_bank_account" (
    "acnt_id" bigint DEFAULT nextval('core_bank_account_acnt_id_seq') NOT NULL,
    "acnt_company" smallint,
    "acnt_type" smallint DEFAULT '1' NOT NULL,
    "acnt_bank" smallint NOT NULL,
    "acnt_branch" text NOT NULL,
    "acnt_name" text NOT NULL,
    "acnt_number" text NOT NULL,
    "acnt_remarks" text NOT NULL,
    "acnt_disp_name" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "core_bank_account_pkey" PRIMARY KEY ("acnt_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_category";
DROP SEQUENCE IF EXISTS core_category_cat_id_seq;
CREATE SEQUENCE core_category_cat_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 407 CACHE 1;

CREATE TABLE "public"."core_category" (
    "cat_id" bigint DEFAULT nextval('core_category_cat_id_seq') NOT NULL,
    "cat_type" smallint NOT NULL,
    "cat_name" text NOT NULL,
    "cat_remarks" text,
    "cat_parent" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "core_category_pkey" PRIMARY KEY ("cat_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_comp_department";
DROP SEQUENCE IF EXISTS core_comp_department_cmpdept_id_seq;
CREATE SEQUENCE core_comp_department_cmpdept_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 28 CACHE 1;

CREATE TABLE "public"."core_comp_department" (
    "cmpdept_id" bigint DEFAULT nextval('core_comp_department_cmpdept_id_seq') NOT NULL,
    "cmpdept_dept_id" bigint NOT NULL,
    "cmpdept_comp_id" bigint NOT NULL,
    "cmpdept_contact_person" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "core_comp_department_pkey" PRIMARY KEY ("cmpdept_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_company";
DROP SEQUENCE IF EXISTS core_company_comp_id_seq;
CREATE SEQUENCE core_company_comp_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 4 CACHE 1;

CREATE TABLE "public"."core_company" (
    "comp_id" bigint DEFAULT nextval('core_company_comp_id_seq') NOT NULL,
    "comp_name" text NOT NULL,
    "comp_disp_name" text NOT NULL,
    "comp_reg_no" text,
    "comp_contact_person" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0',
    CONSTRAINT "core_company_pkey" PRIMARY KEY ("comp_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_department";
DROP SEQUENCE IF EXISTS core_department_dept_id_seq;
CREATE SEQUENCE core_department_dept_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 6 CACHE 1;

CREATE TABLE "public"."core_department" (
    "dept_id" bigint DEFAULT nextval('core_department_dept_id_seq') NOT NULL,
    "dept_name" text NOT NULL,
    "dept_contact_person" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "core_department_pkey" PRIMARY KEY ("dept_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_designation";
DROP SEQUENCE IF EXISTS core_designation_desig_id_seq;
CREATE SEQUENCE core_designation_desig_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 20 CACHE 1;

CREATE TABLE "public"."core_designation" (
    "desig_id" bigint DEFAULT nextval('core_designation_desig_id_seq') NOT NULL,
    "desig_name" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "core_designation_pkey" PRIMARY KEY ("desig_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_files";
DROP SEQUENCE IF EXISTS core_files_file_id_seq;
CREATE SEQUENCE core_files_file_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 32400 CACHE 1;

CREATE TABLE "public"."core_files" (
    "file_id" bigint DEFAULT nextval('core_files_file_id_seq') NOT NULL,
    "file_type" smallint NOT NULL,
    "file_ref_id" bigint NOT NULL,
    "file_actual_name" text NOT NULL,
    "file_exten" text,
    "file_size" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "core_files_pkey" PRIMARY KEY ("file_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_login_log";
DROP SEQUENCE IF EXISTS core_login_log_log_id_seq;
CREATE SEQUENCE core_login_log_log_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 11968 CACHE 1;

CREATE TABLE "public"."core_login_log" (
    "log_id" bigint DEFAULT nextval('core_login_log_log_id_seq') NOT NULL,
    "log_remote_addr" text,
    "log_http_user_agent" text,
    "log_remote_port" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0',
    CONSTRAINT "core_login_log_pkey" PRIMARY KEY ("log_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "core_updates";
DROP SEQUENCE IF EXISTS core_updates_upd_id_seq;
CREATE SEQUENCE core_updates_upd_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 60 CACHE 1;

CREATE TABLE "public"."core_updates" (
    "upd_id" bigint DEFAULT nextval('core_updates_upd_id_seq') NOT NULL,
    "upd_type" smallint NOT NULL,
    "upd_type_refid" bigint NOT NULL,
    "upd_reported" bigint NOT NULL,
    "upd_dttime" timestamp,
    "upd_enddttime" timestamp,
    "upd_priority" smallint NOT NULL,
    "upd_note" text NOT NULL,
    "upd_title" text NOT NULL,
    "upd_deleted" smallint DEFAULT '0' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "upd_status" smallint DEFAULT '1',
    "upd_close_date" timestamp,
    "upd_close_by" bigint,
    "upd_close_note" text,
    "upd_assign" bigint,
    "upd_remainder" smallint,
    CONSTRAINT "core_updates_pkey" PRIMARY KEY ("upd_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."core_updates"."upd_status" IS '1=>Open,100=>Closed';


DROP TABLE IF EXISTS "core_users";
DROP SEQUENCE IF EXISTS core_users_user_id_seq;
CREATE SEQUENCE core_users_user_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 55 CACHE 1;

CREATE TABLE "public"."core_users" (
    "user_id" bigint DEFAULT nextval('core_users_user_id_seq') NOT NULL,
    "user_fname" text,
    "user_lname" text,
    "user_uname" text,
    "user_status" smallint DEFAULT '1',
    "user_password" text,
    "user_desig" bigint,
    "user_dept" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0',
    "user_emp_id" bigint,
    "user_email" character varying(100),
    CONSTRAINT "core_users_pkey" PRIMARY KEY ("user_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_bill";
DROP SEQUENCE IF EXISTS mis_bill_bill_id_seq;
CREATE SEQUENCE mis_bill_bill_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 690 CACHE 1;

CREATE TABLE "public"."mis_bill" (
    "bill_id" bigint DEFAULT nextval('mis_bill_bill_id_seq') NOT NULL,
    "bill_company" bigint,
    "bill_refno" text,
    "bill_customer_id" bigint NOT NULL,
    "bill_mode" smallint NOT NULL,
    "bill_remarks" text,
    "bill_total" numeric(13,3) NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "bill_date" date,
    "bill_month" date,
    "bill_rev_date" date,
    "bill_file_no" text,
    "bill_app_status" smallint,
    "bill_app_date" timestamp,
    "bill_app_note" text,
    "bill_pstatus" bigint,
    "bill_credit_amt" numeric(13,3),
    "bill_oribill_amt" numeric(13,3),
    "bill_book_no" bigint,
    "bill_cancellation_status" smallint DEFAULT '0' NOT NULL,
    "bill_eedit_note" text,
    "bill_wo" bigint,
    "bill_wo_note" text,
    "bill_location" text,
    CONSTRAINT "mis_bill_pkey" PRIMARY KEY ("bill_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_bill"."bill_pstatus" IS '1=>Received, 2=>Credit,3=>Return,4=>Cancelled';


DROP TABLE IF EXISTS "mis_bill_det";
DROP SEQUENCE IF EXISTS mis_bill_det_bdet_id_seq;
CREATE SEQUENCE mis_bill_det_bdet_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 2907 CACHE 1;

CREATE TABLE "public"."mis_bill_det" (
    "bdet_id" bigint DEFAULT nextval('mis_bill_det_bdet_id_seq') NOT NULL,
    "bdet_bill_id" bigint,
    "bdet_item" bigint NOT NULL,
    "bdet_qty" numeric(9,3) NOT NULL,
    "bdet_amt" numeric(13,3) NOT NULL,
    "bdet_remarks" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "bdet_update_sts" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_bill_det_pkey" PRIMARY KEY ("bdet_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_building";
DROP SEQUENCE IF EXISTS mis_building_bld_id_seq;
CREATE SEQUENCE mis_building_bld_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 27 CACHE 1;

CREATE TABLE "public"."mis_building" (
    "bld_id" bigint DEFAULT nextval('mis_building_bld_id_seq') NOT NULL,
    "bld_name" text NOT NULL,
    "bld_no" text NOT NULL,
    "bld_area" text NOT NULL,
    "bld_block_no" text NOT NULL,
    "bld_plot_no" text,
    "bld_way" text NOT NULL,
    "bld_street" text NOT NULL,
    "bld_block" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "bld_comp" smallint,
    CONSTRAINT "mis_building_pkey" PRIMARY KEY ("bld_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_call_log";
DROP SEQUENCE IF EXISTS mis_call_log_clog_id_seq;
CREATE SEQUENCE mis_call_log_clog_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 3 CACHE 1;

CREATE TABLE "public"."mis_call_log" (
    "clog_id" bigint DEFAULT nextval('mis_call_log_clog_id_seq') NOT NULL,
    "clog_type" smallint DEFAULT '(1)',
    "clog_phone_no" character varying(15),
    "clog_name" character varying(50),
    "clog_email" character varying(50),
    "clog_date" date NOT NULL,
    "clog_time" time without time zone,
    "clog_emp" bigint,
    "clog_log" text,
    "clog_fup_json" text,
    "clog_sts_for" smallint DEFAULT '(1)',
    "clog_sts" smallint DEFAULT '(1)',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    "clog_sts_cur" smallint,
    CONSTRAINT "mis_call_log_pkey" PRIMARY KEY ("clog_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_call_log_follow";
DROP SEQUENCE IF EXISTS mis_call_log_follow_cflo_id_seq;
CREATE SEQUENCE mis_call_log_follow_cflo_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."mis_call_log_follow" (
    "cflo_id" bigint DEFAULT nextval('mis_call_log_follow_cflo_id_seq') NOT NULL,
    "cflo_clog_id" bigint,
    "cflo_log" text,
    "cflo_date" date NOT NULL,
    "cflo_time" time without time zone,
    "cflo_emp" bigint,
    "cflo_prv_sts" smallint DEFAULT '(1)',
    "cflo_sts" smallint DEFAULT '(1)',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "mis_call_log_follow_pkey" PRIMARY KEY ("cflo_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_cash_book";
DROP SEQUENCE IF EXISTS mis_cash_book_cb_id_seq;
CREATE SEQUENCE mis_cash_book_cb_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1131 CACHE 1;

CREATE TABLE "public"."mis_cash_book" (
    "cb_id" bigint DEFAULT nextval('mis_cash_book_cb_id_seq') NOT NULL,
    "cb_type" smallint NOT NULL,
    "cb_type_ref" bigint NOT NULL,
    "cb_exp_id" bigint,
    "cb_debit" numeric(13,3),
    "cb_credit" numeric(13,3),
    "cb_date" date NOT NULL,
    "cb_debit_note" text,
    "cb_status" smallint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "cb_src" smallint,
    "cb_src_inc" bigint,
    "cb_src_det" text,
    "cb_exp_type" smallint DEFAULT '1',
    CONSTRAINT "mis_cash_book_pkey" PRIMARY KEY ("cb_id")
) WITH (oids = false);

COMMENT ON TABLE "public"."mis_cash_book" IS 'Cash book register';

COMMENT ON COLUMN "public"."mis_cash_book"."cb_type" IS '1=>Company, 2=>Employee';

COMMENT ON COLUMN "public"."mis_cash_book"."cb_type_ref" IS 'if company=> comp_id, if employee=>emp_id';


DROP TABLE IF EXISTS "mis_cash_demand";
DROP SEQUENCE IF EXISTS mis_cash_demand_cdmd_id_seq;
CREATE SEQUENCE mis_cash_demand_cdmd_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 7179 CACHE 1;

CREATE TABLE "public"."mis_cash_demand" (
    "cdmd_id" bigint DEFAULT nextval('mis_cash_demand_cdmd_id_seq') NOT NULL,
    "cdmd_type" smallint DEFAULT '1' NOT NULL,
    "cdmd_ref_id" bigint NOT NULL,
    "cdmd_oth_id" bigint,
    "cdmd_narration" text NOT NULL,
    "cdmd_note" text NOT NULL,
    "cdmd_mode" smallint NOT NULL,
    "cdmd_total" numeric(13,3) NOT NULL,
    "cdmd_date" date NOT NULL,
    "cdmd_month" date NOT NULL,
    "cdmd_app_status" smallint,
    "cdmd_app_date" timestamp,
    "cdmd_app_note" text,
    "cdmd_pstatus" smallint DEFAULT '2' NOT NULL,
    "cdmd_credit_amt" numeric(13,3),
    "cdmd_orig_amt" numeric(13,3),
    "cdmd_cancellation_status" smallint DEFAULT '0' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_cash_demand_pkey" PRIMARY KEY ("cdmd_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_cash_flow";
DROP SEQUENCE IF EXISTS mis_cash_flow_cf_id_seq;
CREATE SEQUENCE mis_cash_flow_cf_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 10667 CACHE 1;

CREATE TABLE "public"."mis_cash_flow" (
    "cf_id" bigint DEFAULT nextval('mis_cash_flow_cf_id_seq') NOT NULL,
    "cf_cb_id" bigint NOT NULL,
    "cf_assigned" bigint,
    "cf_dttime" timestamp,
    "cf_amount" numeric(13,3) NOT NULL,
    "cf_note" text NOT NULL,
    "cf_note_ar" text,
    "cf_status" smallint DEFAULT '1' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "cf_approve" smallint DEFAULT '1',
    "cf_approve_time" timestamp,
    "cf_approve_by" bigint,
    CONSTRAINT "mis_cash_flow_pkey" PRIMARY KEY ("cf_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_collection";
DROP SEQUENCE IF EXISTS mis_collection_coll_id_seq;
CREATE SEQUENCE mis_collection_coll_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 7470 CACHE 1;

CREATE TABLE "public"."mis_collection" (
    "coll_id" bigint DEFAULT nextval('mis_collection_coll_id_seq') NOT NULL,
    "coll_cust" bigint NOT NULL,
    "coll_amount" numeric(13,3) NOT NULL,
    "coll_coll_mode" smallint DEFAULT '1' NOT NULL,
    "coll_chqno" text,
    "coll_remarks" text,
    "coll_paydate" date NOT NULL,
    "coll_refno" text,
    "coll_status" smallint DEFAULT '1' NOT NULL,
    "coll_file_no" text,
    "coll_app_date" date,
    "coll_app_by" bigint,
    "coll_app_note" text,
    "coll_app_status" smallint DEFAULT '0',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "coll_src_type" bigint DEFAULT '1' NOT NULL,
    CONSTRAINT "mis_collection_pkey" PRIMARY KEY ("coll_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_collection_det";
DROP SEQUENCE IF EXISTS mis_collection_det_cdet_id_seq;
CREATE SEQUENCE mis_collection_det_cdet_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 8610 CACHE 1;

CREATE TABLE "public"."mis_collection_det" (
    "cdet_id" bigint DEFAULT nextval('mis_collection_det_cdet_id_seq') NOT NULL,
    "cdet_coll_id" bigint,
    "cdet_bill_id" bigint,
    "cdet_amt_topay" numeric(13,3) NOT NULL,
    "cdet_amt_paid" numeric(13,3) NOT NULL,
    "cdet_amt_dis" numeric(13,3),
    "cdet_amt_bal" numeric(13,3),
    "cdet_status" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "cdet_src_type" bigint DEFAULT '1' NOT NULL,
    CONSTRAINT "mis_collection_det_pkey" PRIMARY KEY ("cdet_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_contacts";
DROP SEQUENCE IF EXISTS mis_contacts_con_id_seq;
CREATE SEQUENCE mis_contacts_con_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 194 CACHE 1;

CREATE TABLE "public"."mis_contacts" (
    "con_id" bigint DEFAULT nextval('mis_contacts_con_id_seq') NOT NULL,
    "con_type" smallint NOT NULL,
    "con_ref_type" smallint NOT NULL,
    "con_ref_id" bigint NOT NULL,
    "con_name" text,
    "con_house" text,
    "con_street1" text,
    "con_street2" text,
    "con_place" text,
    "con_locality" text,
    "con_region" text,
    "con_country" smallint,
    "con_zip_code" text,
    "con_phone" text,
    "con_mobile" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_contacts_pkey" PRIMARY KEY ("con_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_customer";
DROP SEQUENCE IF EXISTS mis_customer_cust_id_seq;
CREATE SEQUENCE mis_customer_cust_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 39 CACHE 1;

CREATE TABLE "public"."mis_customer" (
    "cust_id" bigint DEFAULT nextval('mis_customer_cust_id_seq') NOT NULL,
    "cust_code" text,
    "cust_name" text,
    "cust_remarks" text,
    "cust_status" bigint DEFAULT '1' NOT NULL,
    "cust_pay_mode" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_customer_pkey" PRIMARY KEY ("cust_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_documents";
DROP SEQUENCE IF EXISTS mis_documents_doc_id_seq;
CREATE SEQUENCE mis_documents_doc_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 3999 CACHE 1;

CREATE TABLE "public"."mis_documents" (
    "doc_id" bigint DEFAULT nextval('mis_documents_doc_id_seq') NOT NULL,
    "doc_type" smallint NOT NULL,
    "doc_ref_type" smallint NOT NULL,
    "doc_ref_id" bigint NOT NULL,
    "doc_no" text,
    "doc_desc" text,
    "doc_remarks" text,
    "doc_issue_auth" text,
    "doc_apply_date" date,
    "doc_issue_date" date,
    "doc_expiry_date" date,
    "doc_alert_days" smallint DEFAULT '(0)',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "agr_tenant" text,
    "agr_mobile" text,
    "agr_tele" text,
    "agr_idno" text,
    "agr_rent" numeric(9,2) DEFAULT '(0)',
    "agr_tax" numeric(9,2) DEFAULT '(0)',
    "agr_expat" smallint,
    "agr_crno" text,
    "agr_paydet" text,
    "agr_comp" smallint,
    "agr_amount" numeric(13,3),
    "agr_tnt_id" bigint,
    "doc_remainder" smallint,
    "doc_dyn_no" bigint,
    "doc_dyn_label" text,
    "doc_dyn_ver" smallint,
    CONSTRAINT "mis_documents_pkey" PRIMARY KEY ("doc_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_emp_contract";
DROP SEQUENCE IF EXISTS mis_emp_contract_emc_id_seq;
CREATE SEQUENCE mis_emp_contract_emc_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 115 CACHE 1;

CREATE TABLE "public"."mis_emp_contract" (
    "emc_id" bigint DEFAULT nextval('mis_emp_contract_emc_id_seq') NOT NULL,
    "emc_emp_id" bigint NOT NULL,
    "emc_vhl_id" bigint NOT NULL,
    "emc_status" smallint DEFAULT '1' NOT NULL,
    "emc_cust_id" bigint NOT NULL,
    "emc_project" text,
    "emc_location" text NOT NULL,
    "emc_note" text,
    "emc_date_start" date NOT NULL,
    "emc_date_end" date,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "emc_note2" text,
    CONSTRAINT "mis_emp_contract_pkey" PRIMARY KEY ("emc_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_employee";
DROP SEQUENCE IF EXISTS mis_employee_emp_id_seq;
CREATE SEQUENCE mis_employee_emp_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 175 CACHE 1;

CREATE TABLE "public"."mis_employee" (
    "emp_id" bigint DEFAULT nextval('mis_employee_emp_id_seq') NOT NULL,
    "emp_no" bigint NOT NULL,
    "emp_fileno" text,
    "emp_mobileno" text,
    "emp_fname" text,
    "emp_mname" text,
    "emp_lname" text,
    "emp_nationality" smallint,
    "emp_dob" date,
    "emp_doj" date,
    "emp_comp_dept" smallint NOT NULL,
    "emp_desig" smallint NOT NULL,
    "emp_status" smallint DEFAULT '1',
    "emp_bank" text,
    "emp_branch" text,
    "emp_accountno" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "emp_nativeno" text,
    "emp_bank_id" smallint,
    "emp_reg_mulkia" text,
    "emp_reg_chassis" text,
    "emp_reg_refno" text,
    "emp_reg_remarks" text,
    CONSTRAINT "mis_employee_pkey" PRIMARY KEY ("emp_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_employee"."emp_bank_id" IS '1=>Bank Muscat, 2=>Bank Dhofar';


DROP TABLE IF EXISTS "mis_employee_pay";
DROP SEQUENCE IF EXISTS mis_employee_pay_pay_id_seq;
CREATE SEQUENCE mis_employee_pay_pay_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 285 CACHE 1;

CREATE TABLE "public"."mis_employee_pay" (
    "pay_id" bigint DEFAULT nextval('mis_employee_pay_pay_id_seq') NOT NULL,
    "pay_emp_id" bigint,
    "pay_type" smallint DEFAULT '0' NOT NULL,
    "pay_basic" numeric(13,3) DEFAULT '0',
    "pay_da" numeric(13,3) DEFAULT '0',
    "pay_hra" numeric(13,3) DEFAULT '0',
    "pay_ta" numeric(13,3) DEFAULT '0',
    "pay_allw1" numeric(13,3) DEFAULT '0',
    "pay_allw2" numeric(13,3) DEFAULT '0',
    "pay_allw3" numeric(13,3) DEFAULT '0',
    "pay_total" numeric(13,3) DEFAULT '0',
    "pay_wef" date,
    "pay_dor" date,
    "pay_remarks" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_employee_pay_pkey" PRIMARY KEY ("pay_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_employee_status";
DROP SEQUENCE IF EXISTS mis_employee_status_sts_id_seq;
CREATE SEQUENCE mis_employee_status_sts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 458 CACHE 1;

CREATE TABLE "public"."mis_employee_status" (
    "sts_id" bigint DEFAULT nextval('mis_employee_status_sts_id_seq') NOT NULL,
    "sts_type" smallint NOT NULL,
    "sts_emp_id" bigint NOT NULL,
    "sts_remarks" text,
    "sts_apply_date" date,
    "sts_approval_date" date,
    "sts_start_date" date,
    "sts_end_date" date,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_employee_status_pkey" PRIMARY KEY ("sts_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_expense";
DROP SEQUENCE IF EXISTS mis_expense_exp_id_seq;
CREATE SEQUENCE mis_expense_exp_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 17885 CACHE 1;

CREATE TABLE "public"."mis_expense" (
    "exp_id" bigint DEFAULT nextval('mis_expense_exp_id_seq') NOT NULL,
    "exp_vendor" bigint NOT NULL,
    "exp_company" bigint NOT NULL,
    "exp_mainh" smallint NOT NULL,
    "exp_mainh_ref" bigint,
    "exp_pcat" bigint NOT NULL,
    "exp_scat" bigint NOT NULL,
    "exp_ccat" bigint NOT NULL,
    "exp_details" text NOT NULL,
    "exp_amount" numeric(13,3) NOT NULL,
    "exp_pay_mode" smallint NOT NULL,
    "exp_remarks" text,
    "exp_paydate" date,
    "exp_refno" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "exp_file_no" text,
    "exp_app_status" smallint,
    "exp_app_date" timestamptz,
    "exp_app_note" text,
    "exp_billdt" date,
    "exp_paydays" smallint,
    "exp_pstatus" bigint,
    "exp_credit_amt" numeric(13,3),
    "exp_oribill_amt" numeric(13,3),
    "exp_cash_flow" bigint,
    "exp_update_status" smallint,
    "exp_export" bigint,
    "exp_vat_amt" numeric(13,3),
    "exp_vat_option" smallint DEFAULT '(0)',
    "exp_novat_amt" numeric(13,3),
    CONSTRAINT "mis_expense_pkey" PRIMARY KEY ("exp_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_expense"."exp_pstatus" IS '1=>Paid, 2=>Credit,3=>Return,4=>Cancelled';

COMMENT ON COLUMN "public"."mis_expense"."exp_credit_amt" IS 'Credit amount, will reduce on payment approval';


DROP TABLE IF EXISTS "mis_expense_href";
DROP SEQUENCE IF EXISTS mis_expense_href_eref_id_seq;
CREATE SEQUENCE mis_expense_href_eref_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 53150 CACHE 1;

CREATE TABLE "public"."mis_expense_href" (
    "eref_id" bigint DEFAULT nextval('mis_expense_href_eref_id_seq') NOT NULL,
    "eref_exp_id" bigint NOT NULL,
    "eref_main_head" smallint NOT NULL,
    "eref_main_head_ref" bigint NOT NULL,
    "eref_amount" numeric(13,3) NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "eref_status" smallint DEFAULT '1' NOT NULL,
    CONSTRAINT "mis_expense_href_pkey" PRIMARY KEY ("eref_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_expense_update";
DROP SEQUENCE IF EXISTS mis_expense_update_eup_id_seq;
CREATE SEQUENCE mis_expense_update_eup_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 102 CACHE 1;

CREATE TABLE "public"."mis_expense_update" (
    "eup_id" bigint DEFAULT nextval('mis_expense_update_eup_id_seq') NOT NULL,
    "eup_exp_id" bigint,
    "eup_type" smallint NOT NULL,
    "eup_date" date NOT NULL,
    "eup_exp_topay" numeric(13,3) NOT NULL,
    "eup_exp_adjust" numeric(13,3),
    "eup_exp_credit" numeric(13,3),
    "eup_app_status" smallint,
    "eup_app_date" date,
    "eup_app_by" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_expense_update_pkey" PRIMARY KEY ("eup_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_item";
DROP SEQUENCE IF EXISTS mis_item_item_id_seq;
CREATE SEQUENCE mis_item_item_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 119 CACHE 1;

CREATE TABLE "public"."mis_item" (
    "item_id" bigint DEFAULT nextval('mis_item_item_id_seq') NOT NULL,
    "item_code" text,
    "item_name" text,
    "item_remarks" text,
    "item_status" bigint DEFAULT '(1)' NOT NULL,
    "item_price" numeric(13,3),
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "item_unit" text,
    "item_type" smallint DEFAULT '1',
    "item_vehicle" bigint,
    CONSTRAINT "mis_item_pkey" PRIMARY KEY ("item_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_notification";
DROP SEQUENCE IF EXISTS mis_notification_notif_id_seq;
CREATE SEQUENCE mis_notification_notif_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 10 CACHE 1;

CREATE TABLE "public"."mis_notification" (
    "notif_id" bigint DEFAULT nextval('mis_notification_notif_id_seq') NOT NULL,
    "notif_month" date NOT NULL,
    "notif_email" text,
    "notif_content" text,
    "notif_status" smallint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '(0)',
    CONSTRAINT "mis_notification_pkey" PRIMARY KEY ("notif_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_payment";
DROP SEQUENCE IF EXISTS mis_payment_pay_id_seq;
CREATE SEQUENCE mis_payment_pay_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 749 CACHE 1;

CREATE TABLE "public"."mis_payment" (
    "pay_id" bigint DEFAULT nextval('mis_payment_pay_id_seq') NOT NULL,
    "pay_vendor" bigint NOT NULL,
    "pay_amount" numeric(13,3) NOT NULL,
    "pay_pay_mode" smallint DEFAULT '1' NOT NULL,
    "pay_chqno" text,
    "pay_remarks" text,
    "pay_paydate" date NOT NULL,
    "pay_refno" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "pay_file_no" text,
    "pay_app_date" date,
    "pay_app_by" bigint,
    "pay_app_note" text,
    "pay_app_status" smallint DEFAULT '0',
    "pay_pay_status" smallint,
    "pay_pay_note" text,
    "pay_pay_date" date,
    "pay_pay_app_date" timestamp,
    "pay_cash_flow" bigint,
    CONSTRAINT "mis_payment_pkey" PRIMARY KEY ("pay_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_payment"."pay_pay_status" IS '1=>Paid, 2=>Approved/Closed';


DROP TABLE IF EXISTS "mis_payment_det";
DROP SEQUENCE IF EXISTS mis_payment_det_pdet_id_seq;
CREATE SEQUENCE mis_payment_det_pdet_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 3537 CACHE 1;

CREATE TABLE "public"."mis_payment_det" (
    "pdet_id" bigint DEFAULT nextval('mis_payment_det_pdet_id_seq') NOT NULL,
    "pdet_pay_id" bigint,
    "pdet_exp_id" bigint,
    "pdet_amt_topay" numeric(13,3) NOT NULL,
    "pdet_amt_paid" numeric(13,3) NOT NULL,
    "pdet_amt_dis" numeric(13,3),
    "pdet_amt_bal" numeric(13,3),
    "pdet_status" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_payment_det_pkey" PRIMARY KEY ("pdet_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_property";
DROP SEQUENCE IF EXISTS mis_property_prop_id_seq;
CREATE SEQUENCE mis_property_prop_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 348 CACHE 1;

CREATE TABLE "public"."mis_property" (
    "prop_id" bigint DEFAULT nextval('mis_property_prop_id_seq') NOT NULL,
    "prop_no" text NOT NULL,
    "prop_name" text NOT NULL,
    "prop_fileno" text NOT NULL,
    "prop_building" smallint NOT NULL,
    "prop_responsible" bigint,
    "prop_remarks" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "prop_cat" smallint,
    "prop_type" smallint,
    "prop_level" smallint,
    "prop_elec_meter" text,
    "prop_water" text,
    "prop_building_type" smallint,
    "prop_status" bigint DEFAULT '1',
    "prop_elec_account" text,
    "prop_elec_recharge" text,
    "prop_account" bigint,
    CONSTRAINT "mis_property_pkey" PRIMARY KEY ("prop_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_property"."prop_status" IS '1=>Ready to Occupy, 2=>Under Agreement,3=>Under Maintenance';


DROP TABLE IF EXISTS "mis_property_building";
DROP SEQUENCE IF EXISTS mis_property_building_bld_id_seq;
CREATE SEQUENCE mis_property_building_bld_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 4 CACHE 1;

CREATE TABLE "public"."mis_property_building" (
    "bld_id" bigint DEFAULT nextval('mis_property_building_bld_id_seq') NOT NULL,
    "bld_name" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_property_building_pkey" PRIMARY KEY ("bld_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_property_payoption";
DROP SEQUENCE IF EXISTS mis_property_payoption_popt_id_seq;
CREATE SEQUENCE mis_property_payoption_popt_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 9767 CACHE 1;

CREATE TABLE "public"."mis_property_payoption" (
    "popt_id" bigint DEFAULT nextval('mis_property_payoption_popt_id_seq') NOT NULL,
    "popt_prop_id" bigint,
    "popt_doc_id" bigint,
    "popt_type" smallint NOT NULL,
    "popt_date" date NOT NULL,
    "popt_amount" numeric(13,3),
    "popt_bank" smallint,
    "popt_chqno" text,
    "popt_status" smallint DEFAULT '1',
    "popt_status_date" date,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_property_payoption_pkey" PRIMARY KEY ("popt_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_property_service";
DROP SEQUENCE IF EXISTS mis_property_service_psvs_id_seq;
CREATE SEQUENCE mis_property_service_psvs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 11 CACHE 1;

CREATE TABLE "public"."mis_property_service" (
    "psvs_id" bigint DEFAULT nextval('mis_property_service_psvs_id_seq') NOT NULL,
    "psvs_prop_id" bigint,
    "psvs_type" smallint DEFAULT '(1)',
    "psvs_complaint_no" character varying(15),
    "psvs_date" date NOT NULL,
    "psvs_srv_date" date NOT NULL,
    "psvs_emp" bigint,
    "psvs_time_in" time without time zone,
    "psvs_time_out" time without time zone,
    "psvs_service_json" text,
    "psvs_parts_json" text,
    "psvs_signed" character varying(50),
    "psvs_amt_mat" numeric(9,3),
    "psvs_amt_lab" numeric(9,3),
    "psvs_amt_tot" numeric(9,3),
    "psvs_signed_phone" character varying(20),
    "psvs_feedback" smallint,
    "psvs_remarks" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    CONSTRAINT "mis_property_service_pkey" PRIMARY KEY ("psvs_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_property_status";
DROP SEQUENCE IF EXISTS mis_property_status_psts_id_seq;
CREATE SEQUENCE mis_property_status_psts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 438 CACHE 1;

CREATE TABLE "public"."mis_property_status" (
    "psts_id" bigint DEFAULT nextval('mis_property_status_psts_id_seq') NOT NULL,
    "psts_type" smallint DEFAULT '1',
    "psts_prop_id" bigint,
    "psts_remarks" text NOT NULL,
    "psts_status_date" date NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "psts_attach_prop" bigint,
    CONSTRAINT "mis_property_status_pkey" PRIMARY KEY ("psts_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_salary";
DROP SEQUENCE IF EXISTS mis_salary_sal_id_seq;
CREATE SEQUENCE mis_salary_sal_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 72 CACHE 1;

CREATE TABLE "public"."mis_salary" (
    "sal_id" bigint DEFAULT nextval('mis_salary_sal_id_seq') NOT NULL,
    "sal_period" smallint NOT NULL,
    "sal_total" numeric(13,3),
    "sal_addition" numeric(13,3),
    "sal_deduction" numeric(13,3),
    "sal_net" numeric(13,3),
    "sal_remarks" text,
    "sal_paydate" date,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "sal_status" smallint DEFAULT '1',
    "sal_empcount" smallint DEFAULT '0',
    CONSTRAINT "mis_salary_pkey" PRIMARY KEY ("sal_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_salary"."sal_status" IS '1=>Under Process,2=>Closed';


DROP TABLE IF EXISTS "mis_salary_det";
DROP SEQUENCE IF EXISTS mis_salary_det_sdet_id_seq;
CREATE SEQUENCE mis_salary_det_sdet_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 5556 CACHE 1;

CREATE TABLE "public"."mis_salary_det" (
    "sdet_id" bigint DEFAULT nextval('mis_salary_det_sdet_id_seq') NOT NULL,
    "sdet_sal_id" bigint,
    "sdet_emp_id" bigint,
    "sdet_group" smallint NOT NULL,
    "sdet_amt_total" numeric(13,3),
    "sdet_amt_deduct" numeric(13,3),
    "sdet_amt_addition" numeric(13,3),
    "sdet_amt_net" numeric(13,3),
    "sdet_remarks" text,
    "sdet_status" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "sdet_category" smallint,
    CONSTRAINT "mis_salary_det_pkey" PRIMARY KEY ("sdet_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_salary_det"."sdet_category" IS '1=>Labours, 2=>Drivers';


DROP TABLE IF EXISTS "mis_tenants";
DROP SEQUENCE IF EXISTS mis_tenants_tnt_id_seq;
CREATE SEQUENCE mis_tenants_tnt_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1844 CACHE 1;

CREATE TABLE "public"."mis_tenants" (
    "tnt_id" bigint DEFAULT nextval('mis_tenants_tnt_id_seq') NOT NULL,
    "tnt_full_name" text NOT NULL,
    "tnt_comp_name" text,
    "tnt_phone" text,
    "tnt_tele" text,
    "tnt_id_no" text NOT NULL,
    "tnt_crno" text NOT NULL,
    "tnt_expat" smallint,
    "tnt_agr_type" smallint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    "tnt_doc_id" bigint,
    CONSTRAINT "mis_tenants_pkey" PRIMARY KEY ("tnt_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tenants_temp";
DROP SEQUENCE IF EXISTS mis_tenants_temp_tnt_id_temp_seq;
CREATE SEQUENCE mis_tenants_temp_tnt_id_temp_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."mis_tenants_temp" (
    "tnt_id_temp" bigint DEFAULT nextval('mis_tenants_temp_tnt_id_temp_seq') NOT NULL,
    "tnt_full_name" text,
    "tnt_comp_name" text,
    "tnt_phone" text,
    "tnt_tele" text,
    "tnt_id_no" text,
    "tnt_crno" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0',
    "tnt_expat" smallint,
    "tnt_agr_type" smallint,
    CONSTRAINT "mis_tenants_temp_pkey" PRIMARY KEY ("tnt_id_temp")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tenants_x";
DROP SEQUENCE IF EXISTS mis_tenants_x_tnt_id_temp_seq;
CREATE SEQUENCE mis_tenants_x_tnt_id_temp_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."mis_tenants_x" (
    "tnt_id_temp" bigint DEFAULT nextval('mis_tenants_x_tnt_id_temp_seq') NOT NULL,
    "tnt_full_name" text,
    "tnt_comp_name" text,
    "tnt_phone" text,
    "tnt_tele" text,
    "tnt_id_no" text,
    "tnt_crno" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0',
    "tnt_expat" smallint,
    "tnt_agr_type" smallint,
    CONSTRAINT "mis_tenants_x_pkey" PRIMARY KEY ("tnt_id_temp")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tickets";
DROP SEQUENCE IF EXISTS mis_tickets_tkt_id_seq;
CREATE SEQUENCE mis_tickets_tkt_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 4 CACHE 1;

CREATE TABLE "public"."mis_tickets" (
    "tkt_id" bigint DEFAULT nextval('mis_tickets_tkt_id_seq') NOT NULL,
    "tkt_company" bigint NOT NULL,
    "tkt_reported" text NOT NULL,
    "tkt_cat" smallint NOT NULL,
    "tkt_mob1" text NOT NULL,
    "tkt_mob2" text,
    "tkt_vtime_srt" time without time zone,
    "tkt_vtime_end" time without time zone,
    "tkt_assign" bigint,
    "tkt_mainhead" smallint NOT NULL,
    "tkt_dttime_strt" timestamp,
    "tkt_dttime_end" timestamp,
    "tkt_priority" smallint NOT NULL,
    "tkt_budjet" numeric(13,3),
    "tkt_status" smallint,
    "tkt_details" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_tickets_pkey" PRIMARY KEY ("tkt_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tickets_actions";
DROP SEQUENCE IF EXISTS mis_tickets_actions_act_id_seq;
CREATE SEQUENCE mis_tickets_actions_act_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 16 CACHE 1;

CREATE TABLE "public"."mis_tickets_actions" (
    "act_id" bigint DEFAULT nextval('mis_tickets_actions_act_id_seq') NOT NULL,
    "act_ticket_id" smallint NOT NULL,
    "act_by" bigint NOT NULL,
    "act_remarks" text NOT NULL,
    "act_steps" bigint,
    "act_status" smallint NOT NULL,
    "act_dttime" timestamp,
    "act_escalate" bigint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_tickets_actions_pkey" PRIMARY KEY ("act_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tickets_cat";
DROP SEQUENCE IF EXISTS mis_tickets_cat_tcat_id_seq;
CREATE SEQUENCE mis_tickets_cat_tcat_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 3 CACHE 1;

CREATE TABLE "public"."mis_tickets_cat" (
    "tcat_id" bigint DEFAULT nextval('mis_tickets_cat_tcat_id_seq') NOT NULL,
    "tcat_type" smallint DEFAULT '1',
    "tcat_name" text NOT NULL,
    "tcat_remarks" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_tickets_cat_pkey" PRIMARY KEY ("tcat_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tickets_href";
DROP SEQUENCE IF EXISTS mis_tickets_href_tref_id_seq;
CREATE SEQUENCE mis_tickets_href_tref_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 13 CACHE 1;

CREATE TABLE "public"."mis_tickets_href" (
    "tref_id" bigint DEFAULT nextval('mis_tickets_href_tref_id_seq') NOT NULL,
    "tref_tkt_id" bigint NOT NULL,
    "tref_main_head" smallint NOT NULL,
    "tref_main_head_ref" bigint NOT NULL,
    "tref_note" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_tickets_href_pkey" PRIMARY KEY ("tref_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_tickets_steps";
DROP SEQUENCE IF EXISTS mis_tickets_steps_stp_id_seq;
CREATE SEQUENCE mis_tickets_steps_stp_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 54 CACHE 1;

CREATE TABLE "public"."mis_tickets_steps" (
    "stp_id" bigint DEFAULT nextval('mis_tickets_steps_stp_id_seq') NOT NULL,
    "stp_ticket_id" smallint NOT NULL,
    "stp_by" bigint NOT NULL,
    "stp_steps" text NOT NULL,
    "stp_dttime" timestamp,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_tickets_steps_pkey" PRIMARY KEY ("stp_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_vehicle";
DROP SEQUENCE IF EXISTS mis_vehicle_vhl_id_seq;
CREATE SEQUENCE mis_vehicle_vhl_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 91 CACHE 1;

CREATE TABLE "public"."mis_vehicle" (
    "vhl_id" bigint DEFAULT nextval('mis_vehicle_vhl_id_seq') NOT NULL,
    "vhl_no" text NOT NULL,
    "vhl_fileno" text NOT NULL,
    "vhl_type" smallint NOT NULL,
    "vhl_model" bigint NOT NULL,
    "vhl_company" smallint NOT NULL,
    "vhl_responsible" bigint,
    "vhl_remarks" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "vhl_comm_status" smallint DEFAULT '1' NOT NULL,
    "vhl_rate_hour" numeric(13,3),
    "vhl_rate_day" numeric(13,3),
    "vhl_rate_month" numeric(13,3),
    "vhl_man" smallint,
    "vhl_employed" bigint,
    "vhl_vendor" bigint,
    "vhl_site" text,
    "vhl_company_old" bigint,
    "vhl_status" smallint DEFAULT '(1)',
    CONSTRAINT "mis_vehicle_pkey" PRIMARY KEY ("vhl_id")
) WITH (oids = false);

COMMENT ON COLUMN "public"."mis_vehicle"."vhl_comm_status" IS '1=>Non commercial, 2=>Commercial';


DROP TABLE IF EXISTS "mis_vehicle_man";
DROP SEQUENCE IF EXISTS mis_vehicle_man_vman_id_seq;
CREATE SEQUENCE mis_vehicle_man_vman_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 12 CACHE 1;

CREATE TABLE "public"."mis_vehicle_man" (
    "vman_id" bigint DEFAULT nextval('mis_vehicle_man_vman_id_seq') NOT NULL,
    "vman_name" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '(0)' NOT NULL,
    "vman_code" character varying(10),
    CONSTRAINT "mis_vehicle_vman_pkey" PRIMARY KEY ("vman_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_vehicle_type";
DROP SEQUENCE IF EXISTS mis_vehicle_type_type_id_seq;
CREATE SEQUENCE mis_vehicle_type_type_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 25 CACHE 1;

CREATE TABLE "public"."mis_vehicle_type" (
    "type_id" bigint DEFAULT nextval('mis_vehicle_type_type_id_seq') NOT NULL,
    "type_name" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "type_code" character varying(10),
    CONSTRAINT "mis_vehicle_type_pkey" PRIMARY KEY ("type_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_vendor";
DROP SEQUENCE IF EXISTS mis_vendor_ven_id_seq;
CREATE SEQUENCE mis_vendor_ven_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1057 CACHE 1;

CREATE TABLE "public"."mis_vendor" (
    "ven_id" bigint DEFAULT nextval('mis_vendor_ven_id_seq') NOT NULL,
    "ven_code" text,
    "ven_name" text NOT NULL,
    "ven_remarks" text,
    "ven_status" smallint DEFAULT '1' NOT NULL,
    "ven_pay_mode" smallint,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "ven_disp_name" text,
    "ven_vat_no" text,
    "ven_type" smallint DEFAULT '(1)' NOT NULL,
    CONSTRAINT "mis_vendor_pkey" PRIMARY KEY ("ven_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_vhl_service";
DROP SEQUENCE IF EXISTS mis_vhl_service_srv_id_seq;
CREATE SEQUENCE mis_vhl_service_srv_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 20 CACHE 1;

CREATE TABLE "public"."mis_vhl_service" (
    "srv_id" bigint DEFAULT nextval('mis_vhl_service_srv_id_seq') NOT NULL,
    "srv_vhl_id" bigint NOT NULL,
    "srv_date_start" date NOT NULL,
    "srv_location" text NOT NULL,
    "srv_reading" text NOT NULL,
    "srv_note" text,
    "srv_type" smallint DEFAULT '1' NOT NULL,
    "srv_nxt_type" smallint DEFAULT '1' NOT NULL,
    "srv_done_by" bigint,
    "srv_reading_next" text NOT NULL,
    "srv_date_next" date NOT NULL,
    "srv_wash" smallint DEFAULT '1' NOT NULL,
    "srv_greese" smallint DEFAULT '1' NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "srv_labour" numeric(9,3),
    CONSTRAINT "mis_vhl_service_pkey" PRIMARY KEY ("srv_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "mis_vhl_srv_det";
DROP SEQUENCE IF EXISTS mis_vhl_srv_det_sdt_id_seq;
CREATE SEQUENCE mis_vhl_srv_det_sdt_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 114 CACHE 1;

CREATE TABLE "public"."mis_vhl_srv_det" (
    "sdt_id" bigint DEFAULT nextval('mis_vhl_srv_det_sdt_id_seq') NOT NULL,
    "sdt_srv_id" bigint NOT NULL,
    "sdt_item" bigint NOT NULL,
    "sdt_qty" text NOT NULL,
    "sdt_done_by" bigint NOT NULL,
    "sdt_note" text,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "sdt_unit" character varying(20),
    "sdt_price" numeric(9,3),
    "sdt_billid" bigint,
    CONSTRAINT "mis_vhl_srv_det_pkey" PRIMARY KEY ("sdt_id")
) WITH (oids = false);


DROP TABLE IF EXISTS "temp_files";
DROP SEQUENCE IF EXISTS temp_files_id_seq1;
CREATE SEQUENCE temp_files_id_seq1 INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."temp_files" (
    "temp_old" text,
    "temp_new" text,
    "id" bigint DEFAULT nextval('temp_files_id_seq1') NOT NULL,
    "type" smallint,
    CONSTRAINT "temp_files_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "users";
DROP SEQUENCE IF EXISTS users_id_seq;
CREATE SEQUENCE users_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 CACHE 1;

CREATE TABLE "public"."users" (
    "id" integer DEFAULT nextval('users_id_seq') NOT NULL,
    "name" text NOT NULL,
    "email" text NOT NULL,
    CONSTRAINT "users_pkey" PRIMARY KEY ("id")
) WITH (oids = false);


ALTER TABLE ONLY "public"."core_bank_account" ADD CONSTRAINT "core_bank_account_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_category" ADD CONSTRAINT "core_category_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_comp_department" ADD CONSTRAINT "core_comp_department_cmpdept_comp_id_fkey" FOREIGN KEY (cmpdept_comp_id) REFERENCES core_company(comp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."core_comp_department" ADD CONSTRAINT "core_comp_department_cmpdept_dept_id_fkey" FOREIGN KEY (cmpdept_dept_id) REFERENCES core_department(dept_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."core_comp_department" ADD CONSTRAINT "core_comp_department_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_company" ADD CONSTRAINT "core_company_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_department" ADD CONSTRAINT "core_department_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_designation" ADD CONSTRAINT "core_designation_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_files" ADD CONSTRAINT "core_files_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_login_log" ADD CONSTRAINT "core_login_log_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."core_updates" ADD CONSTRAINT "core_updates_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_bill" ADD CONSTRAINT "mis_bill_bill_company_fkey" FOREIGN KEY (bill_company) REFERENCES core_company(comp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_bill" ADD CONSTRAINT "mis_bill_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_bill_det" ADD CONSTRAINT "mis_bill_det_bdet_bill_id_fkey" FOREIGN KEY (bdet_bill_id) REFERENCES mis_bill(bill_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_bill_det" ADD CONSTRAINT "mis_bill_det_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_building" ADD CONSTRAINT "mis_building_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_call_log" ADD CONSTRAINT "mis_call_log_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_call_log_follow" ADD CONSTRAINT "mis_call_log_follow_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_cash_book" ADD CONSTRAINT "mis_cash_book_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_cash_demand" ADD CONSTRAINT "mis_cash_demand_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_cash_flow" ADD CONSTRAINT "mis_cash_flow_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_collection" ADD CONSTRAINT "mis_collection_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_collection_det" ADD CONSTRAINT "mis_collection_det_cdet_coll_id_fkey" FOREIGN KEY (cdet_coll_id) REFERENCES mis_collection(coll_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_collection_det" ADD CONSTRAINT "mis_collection_det_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_contacts" ADD CONSTRAINT "mis_contacts_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_customer" ADD CONSTRAINT "mis_customer_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_documents" ADD CONSTRAINT "mis_documents_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_emp_contract" ADD CONSTRAINT "mis_emp_contract_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_employee_pay" ADD CONSTRAINT "mis_employee_pay_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_employee_status" ADD CONSTRAINT "mis_employee_status_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_expense" ADD CONSTRAINT "mis_expense_exp_ccat_fkey" FOREIGN KEY (exp_ccat) REFERENCES core_category(cat_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense" ADD CONSTRAINT "mis_expense_exp_company_fkey" FOREIGN KEY (exp_company) REFERENCES core_company(comp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense" ADD CONSTRAINT "mis_expense_exp_pcat_fkey" FOREIGN KEY (exp_pcat) REFERENCES core_category(cat_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense" ADD CONSTRAINT "mis_expense_exp_scat_fkey" FOREIGN KEY (exp_scat) REFERENCES core_category(cat_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense" ADD CONSTRAINT "mis_expense_exp_vendor_fkey" FOREIGN KEY (exp_vendor) REFERENCES mis_vendor(ven_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense" ADD CONSTRAINT "mis_expense_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_expense_href" ADD CONSTRAINT "mis_expense_href_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_expense_update" ADD CONSTRAINT "mis_expense_update_eup_app_by_fkey" FOREIGN KEY (eup_app_by) REFERENCES core_users(user_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense_update" ADD CONSTRAINT "mis_expense_update_eup_exp_id_fkey" FOREIGN KEY (eup_exp_id) REFERENCES mis_expense(exp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_expense_update" ADD CONSTRAINT "mis_expense_update_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_item" ADD CONSTRAINT "mis_item_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_payment" ADD CONSTRAINT "mis_payment_pay_vendor_fkey" FOREIGN KEY (pay_vendor) REFERENCES mis_vendor(ven_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_payment" ADD CONSTRAINT "mis_payment_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_payment_det" ADD CONSTRAINT "mis_payment_det_pdet_exp_id_fkey" FOREIGN KEY (pdet_exp_id) REFERENCES mis_expense(exp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_payment_det" ADD CONSTRAINT "mis_payment_det_pdet_pay_id_fkey" FOREIGN KEY (pdet_pay_id) REFERENCES mis_payment(pay_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_payment_det" ADD CONSTRAINT "mis_payment_det_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_property" ADD CONSTRAINT "mis_property_prop_building_fkey" FOREIGN KEY (prop_building) REFERENCES mis_building(bld_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_property" ADD CONSTRAINT "mis_property_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_property_building" ADD CONSTRAINT "mis_property_building_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_property_payoption" ADD CONSTRAINT "mis_property_payoption_popt_doc_id_fkey" FOREIGN KEY (popt_doc_id) REFERENCES mis_documents(doc_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_property_payoption" ADD CONSTRAINT "mis_property_payoption_popt_prop_id_fkey" FOREIGN KEY (popt_prop_id) REFERENCES mis_property(prop_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_property_payoption" ADD CONSTRAINT "mis_property_payoption_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_property_service" ADD CONSTRAINT "mis_property_service_psvs_prop_id_fkey" FOREIGN KEY (psvs_prop_id) REFERENCES mis_property(prop_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_property_service" ADD CONSTRAINT "mis_property_service_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_property_status" ADD CONSTRAINT "mis_property_status_psts_prop_id_fkey" FOREIGN KEY (psts_prop_id) REFERENCES mis_property(prop_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_property_status" ADD CONSTRAINT "mis_property_status_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_salary" ADD CONSTRAINT "mis_salary_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_salary_det" ADD CONSTRAINT "mis_salary_det_sdet_emp_id_fkey" FOREIGN KEY (sdet_emp_id) REFERENCES mis_employee(emp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_salary_det" ADD CONSTRAINT "mis_salary_det_sdet_sal_id_fkey" FOREIGN KEY (sdet_sal_id) REFERENCES mis_salary(sal_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_salary_det" ADD CONSTRAINT "mis_salary_det_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_tickets" ADD CONSTRAINT "mis_tickets_tkt_company_fkey" FOREIGN KEY (tkt_company) REFERENCES core_company(comp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_tickets" ADD CONSTRAINT "mis_tickets_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_tickets_actions" ADD CONSTRAINT "mis_tickets_actions_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_tickets_cat" ADD CONSTRAINT "mis_tickets_cat_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_tickets_href" ADD CONSTRAINT "mis_tickets_href_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_tickets_steps" ADD CONSTRAINT "mis_tickets_steps_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_vehicle" ADD CONSTRAINT "mis_vehicle_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_vehicle" ADD CONSTRAINT "mis_vehicle_vhl_company_fkey" FOREIGN KEY (vhl_company) REFERENCES core_company(comp_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_vehicle" ADD CONSTRAINT "mis_vehicle_vhl_type_fkey" FOREIGN KEY (vhl_type) REFERENCES mis_vehicle_type(type_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_vehicle_type" ADD CONSTRAINT "mis_vehicle_type_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_vendor" ADD CONSTRAINT "mis_vendor_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_vhl_service" ADD CONSTRAINT "mis_vhl_service_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_vhl_srv_det" ADD CONSTRAINT "mis_vhl_srv_det_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

-- 2024-12-13 10:23:38.634619+05:30
