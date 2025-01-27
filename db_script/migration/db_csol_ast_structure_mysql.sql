-- Adminer 4.8.1 PostgreSQL 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1 dump

-- \connect removed (PostgreSQL specific `db_csol_ast`;

DROP TABLE IF EXISTS `cnfg_acl_actions`;

CREATE TABLE `cnfg_acl_actions` (
    `action_id` bigint AUTO_INCREMENT,
    `action_controller_id` bigint NOT NULL,
    `action_name` text NOT NULL,
    `action_label` text NOT NULL,
    `action_desc` text NOT NULL,
    `action_resource` smallint,
    `action_type` smallint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` DATETIME,
    `t_modified` DATETIME,
    `t_deleted` DATETIME,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `cnfg_acl_actions_pkey` PRIMARY KEY (`action_id`))
 ;


DROP TABLE IF EXISTS `cnfg_acl_actions_access`;

CREATE TABLE `cnfg_acl_actions_access` (
    `aacc_id` bigint AUTO_INCREMENT,
    `aacc_action_id` bigint NOT NULL,
    `aacc_role_id` bigint NOT NULL,
    `aacc_role_type` smallint NOT NULL,
    `aacc_access_status` smallint DEFAULT 1 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` DATETIME,
    `t_modified` DATETIME,
    `t_deleted` DATETIME,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `cnfg_acl_actions_access_pkey` PRIMARY KEY (`aacc_id`))
 ;


DROP TABLE IF EXISTS `cnfg_acl_controllers`;

CREATE TABLE `cnfg_acl_controllers` (
    `controller_id` bigint AUTO_INCREMENT,
    `controller_module_id` bigint NOT NULL,
    `controller_name` text NOT NULL,
    `controller_label` text NOT NULL,
    `controller_desc` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` DATETIME,
    `t_modified` DATETIME,
    `t_deleted` DATETIME,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `cnfg_acl_controllers_pkey` PRIMARY KEY (`controller_id`))
 ;


DROP TABLE IF EXISTS `cnfg_acl_controllers_access`;

CREATE TABLE `cnfg_acl_controllers_access` (
    `cacc_id` bigint AUTO_INCREMENT,
    `cacc_controller_id` bigint NOT NULL,
    `cacc_role_id` bigint NOT NULL,
    `cacc_role_type` smallint NOT NULL,
    `cacc_create_status` smallint DEFAULT 1 NOT NULL,
    `cacc_update_status` smallint DEFAULT 1 NOT NULL,
    `cacc_view_status` smallint DEFAULT 1 NOT NULL,
    `cacc_delete_status` smallint DEFAULT 1 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` DATETIME,
    `t_modified` DATETIME,
    `t_deleted` DATETIME,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `cnfg_acl_controllers_access_pkey` PRIMARY KEY (`cacc_id`))
 ;


DROP TABLE IF EXISTS `cnfg_acl_modules`;

CREATE TABLE `cnfg_acl_modules` (
    `module_id` bigint AUTO_INCREMENT,
    `module_name` text NOT NULL,
    `module_label` text NOT NULL,
    `module_desc` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` DATETIME,
    `t_modified` DATETIME,
    `t_deleted` DATETIME,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `cnfg_acl_modules_pkey` PRIMARY KEY (`module_id`))
 ;


DROP TABLE IF EXISTS `cnfg_acl_modules_access`;

CREATE TABLE `cnfg_acl_modules_access` (
    `macc_id` bigint AUTO_INCREMENT,
    `macc_module_id` bigint NOT NULL,
    `macc_role_id` bigint NOT NULL,
    `macc_role_type` smallint NOT NULL,
    `macc_create_status` smallint DEFAULT 1 NOT NULL,
    `macc_update_status` smallint DEFAULT 1 NOT NULL,
    `macc_view_status` smallint DEFAULT 1 NOT NULL,
    `macc_delete_status` smallint DEFAULT 1 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` DATETIME,
    `t_modified` DATETIME,
    `t_deleted` DATETIME,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `cnfg_acl_modules_access_pkey` PRIMARY KEY (`macc_id`))
 ;


DROP TABLE IF EXISTS `core_bank_account`;

CREATE TABLE `core_bank_account` (
    `acnt_id` bigint AUTO_INCREMENT,
    `acnt_company` smallint,
    `acnt_type` smallint DEFAULT 1 NOT NULL,
    `acnt_bank` smallint NOT NULL,
    `acnt_branch` text NOT NULL,
    `acnt_name` text NOT NULL,
    `acnt_number` text NOT NULL,
    `acnt_remarks` text NOT NULL,
    `acnt_disp_name` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `core_bank_account_pkey` PRIMARY KEY (`acnt_id`))
 ;


DROP TABLE IF EXISTS `core_category`;

CREATE TABLE `core_category` (
    `cat_id` bigint AUTO_INCREMENT,
    `cat_type` smallint NOT NULL,
    `cat_name` text NOT NULL,
    `cat_remarks` text,
    `cat_parent` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `core_category_pkey` PRIMARY KEY (`cat_id`))
 ;


DROP TABLE IF EXISTS `core_comp_department`;

CREATE TABLE `core_comp_department` (
    `cmpdept_id` bigint AUTO_INCREMENT,
    `cmpdept_dept_id` bigint NOT NULL,
    `cmpdept_comp_id` bigint NOT NULL,
    `cmpdept_contact_person` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `core_comp_department_pkey` PRIMARY KEY (`cmpdept_id`))
 ;


DROP TABLE IF EXISTS `core_company`;

CREATE TABLE `core_company` (
    `comp_id` bigint AUTO_INCREMENT,
    `comp_name` text NOT NULL,
    `comp_disp_name` text NOT NULL,
    `comp_reg_no` text,
    `comp_contact_person` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0,
    CONSTRAINT `core_company_pkey` PRIMARY KEY (`comp_id`))
 ;


DROP TABLE IF EXISTS `core_department`;

CREATE TABLE `core_department` (
    `dept_id` bigint AUTO_INCREMENT,
    `dept_name` text NOT NULL,
    `dept_contact_person` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `core_department_pkey` PRIMARY KEY (`dept_id`))
 ;


DROP TABLE IF EXISTS `core_designation`;

CREATE TABLE `core_designation` (
    `desig_id` bigint AUTO_INCREMENT,
    `desig_name` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `core_designation_pkey` PRIMARY KEY (`desig_id`))
 ;


DROP TABLE IF EXISTS `core_files`;

CREATE TABLE `core_files` (
    `file_id` bigint AUTO_INCREMENT,
    `file_type` smallint NOT NULL,
    `file_ref_id` bigint NOT NULL,
    `file_actual_name` text NOT NULL,
    `file_exten` text,
    `file_size` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `core_files_pkey` PRIMARY KEY (`file_id`))
 ;


DROP TABLE IF EXISTS `core_login_log`;

CREATE TABLE `core_login_log` (
    `log_id` bigint AUTO_INCREMENT,
    `log_remote_addr` text,
    `log_http_user_agent` text,
    `log_remote_port` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0,
    CONSTRAINT `core_login_log_pkey` PRIMARY KEY (`log_id`))
 ;


DROP TABLE IF EXISTS `core_updates`;

CREATE TABLE `core_updates` (
    `upd_id` bigint AUTO_INCREMENT,
    `upd_type` smallint NOT NULL,
    `upd_type_refid` bigint NOT NULL,
    `upd_reported` bigint NOT NULL,
    `upd_dttime` timestamp,
    `upd_enddttime` timestamp,
    `upd_priority` smallint NOT NULL,
    `upd_note` text NOT NULL,
    `upd_title` text NOT NULL,
    `upd_deleted` smallint DEFAULT 0 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `upd_status` smallint DEFAULT 1,
    `upd_close_date` timestamp,
    `upd_close_by` bigint,
    `upd_close_note` text,
    `upd_assign` bigint,
    `upd_remainder` smallint,
    CONSTRAINT `core_updates_pkey` PRIMARY KEY (`upd_id`))
 ;



DROP TABLE IF EXISTS `core_users`;

CREATE TABLE `core_users` (
    `user_id` bigint AUTO_INCREMENT,
    `user_fname` text,
    `user_lname` text,
    `user_uname` text,
    `user_status` smallint DEFAULT 1,
    `user_password` text,
    `user_desig` bigint,
    `user_dept` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0,
    `user_emp_id` bigint,
    `user_email` VARCHAR(100),
    CONSTRAINT `core_users_pkey` PRIMARY KEY (`user_id`))
 ;


DROP TABLE IF EXISTS `mis_bill`;

CREATE TABLE `mis_bill` (
    `bill_id` bigint AUTO_INCREMENT,
    `bill_company` bigint,
    `bill_refno` text,
    `bill_customer_id` bigint NOT NULL,
    `bill_mode` smallint NOT NULL,
    `bill_remarks` text,
    `bill_total` DECIMAL(13,3) NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `bill_date` date,
    `bill_month` date,
    `bill_rev_date` date,
    `bill_file_no` text,
    `bill_app_status` smallint,
    `bill_app_date` timestamp,
    `bill_app_note` text,
    `bill_pstatus` bigint,
    `bill_credit_amt` DECIMAL(13,3),
    `bill_oribill_amt` DECIMAL(13,3),
    `bill_book_no` bigint,
    `bill_cancellation_status` smallint DEFAULT 0 NOT NULL,
    `bill_eedit_note` text,
    `bill_wo` bigint,
    `bill_wo_note` text,
    `bill_location` text,
    CONSTRAINT `mis_bill_pkey` PRIMARY KEY (`bill_id`))
 ;



DROP TABLE IF EXISTS `mis_bill_det`;

CREATE TABLE `mis_bill_det` (
    `bdet_id` bigint AUTO_INCREMENT,
    `bdet_bill_id` bigint,
    `bdet_item` bigint NOT NULL,
    `bdet_qty` DECIMAL(9,3) NOT NULL,
    `bdet_amt` DECIMAL(13,3) NOT NULL,
    `bdet_remarks` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `bdet_update_sts` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_bill_det_pkey` PRIMARY KEY (`bdet_id`))
 ;


DROP TABLE IF EXISTS `mis_building`;

CREATE TABLE `mis_building` (
    `bld_id` bigint AUTO_INCREMENT,
    `bld_name` text NOT NULL,
    `bld_no` text NOT NULL,
    `bld_area` text NOT NULL,
    `bld_block_no` text NOT NULL,
    `bld_plot_no` text,
    `bld_way` text NOT NULL,
    `bld_street` text NOT NULL,
    `bld_block` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `bld_comp` smallint,
    CONSTRAINT `mis_building_pkey` PRIMARY KEY (`bld_id`))
 ;


DROP TABLE IF EXISTS `mis_call_log`;

CREATE TABLE `mis_call_log` (
    `clog_id` bigint AUTO_INCREMENT,
    `clog_type` smallint DEFAULT 1,
    `clog_phone_no` VARCHAR(15),
    `clog_name` VARCHAR(50),
    `clog_email` VARCHAR(50),
    `clog_date` date NOT NULL,
    `clog_time` TIME,
    `clog_emp` bigint,
    `clog_log` text,
    `clog_fup_json` text,
    `clog_sts_for` smallint DEFAULT 1,
    `clog_sts` smallint DEFAULT 1,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `clog_sts_cur` smallint,
    CONSTRAINT `mis_call_log_pkey` PRIMARY KEY (`clog_id`))
 ;


DROP TABLE IF EXISTS `mis_call_log_follow`;

CREATE TABLE `mis_call_log_follow` (
    `cflo_id` bigint AUTO_INCREMENT,
    `cflo_clog_id` bigint,
    `cflo_log` text,
    `cflo_date` date NOT NULL,
    `cflo_time` TIME,
    `cflo_emp` bigint,
    `cflo_prv_sts` smallint DEFAULT 1,
    `cflo_sts` smallint DEFAULT 1,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_call_log_follow_pkey` PRIMARY KEY (`cflo_id`))
 ;


DROP TABLE IF EXISTS `mis_cash_book`;

CREATE TABLE `mis_cash_book` (
    `cb_id` bigint AUTO_INCREMENT,
    `cb_type` smallint NOT NULL,
    `cb_type_ref` bigint NOT NULL,
    `cb_exp_id` bigint,
    `cb_debit` DECIMAL(13,3),
    `cb_credit` DECIMAL(13,3),
    `cb_date` date NOT NULL,
    `cb_debit_note` text,
    `cb_status` smallint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `cb_src` smallint,
    `cb_src_inc` bigint,
    `cb_src_det` text,
    `cb_exp_type` smallint DEFAULT 1,
    CONSTRAINT `mis_cash_book_pkey` PRIMARY KEY (`cb_id`))
 ;





DROP TABLE IF EXISTS `mis_cash_demand`;

CREATE TABLE `mis_cash_demand` (
    `cdmd_id` bigint AUTO_INCREMENT,
    `cdmd_type` smallint DEFAULT 1 NOT NULL,
    `cdmd_ref_id` bigint NOT NULL,
    `cdmd_oth_id` bigint,
    `cdmd_narration` text NOT NULL,
    `cdmd_note` text NOT NULL,
    `cdmd_mode` smallint NOT NULL,
    `cdmd_total` DECIMAL(13,3) NOT NULL,
    `cdmd_date` date NOT NULL,
    `cdmd_month` date NOT NULL,
    `cdmd_app_status` smallint,
    `cdmd_app_date` timestamp,
    `cdmd_app_note` text,
    `cdmd_pstatus` smallint DEFAULT 2 NOT NULL,
    `cdmd_credit_amt` DECIMAL(13,3),
    `cdmd_orig_amt` DECIMAL(13,3),
    `cdmd_cancellation_status` smallint DEFAULT 0 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_cash_demand_pkey` PRIMARY KEY (`cdmd_id`))
 ;


DROP TABLE IF EXISTS `mis_cash_flow`;

CREATE TABLE `mis_cash_flow` (
    `cf_id` bigint AUTO_INCREMENT,
    `cf_cb_id` bigint NOT NULL,
    `cf_assigned` bigint,
    `cf_dttime` timestamp,
    `cf_amount` DECIMAL(13,3) NOT NULL,
    `cf_note` text NOT NULL,
    `cf_note_ar` text,
    `cf_status` smallint DEFAULT 1 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `cf_approve` smallint DEFAULT 1,
    `cf_approve_time` timestamp,
    `cf_approve_by` bigint,
    CONSTRAINT `mis_cash_flow_pkey` PRIMARY KEY (`cf_id`))
 ;


DROP TABLE IF EXISTS `mis_collection`;

CREATE TABLE `mis_collection` (
    `coll_id` bigint AUTO_INCREMENT,
    `coll_cust` bigint NOT NULL,
    `coll_amount` DECIMAL(13,3) NOT NULL,
    `coll_coll_mode` smallint DEFAULT 1 NOT NULL,
    `coll_chqno` text,
    `coll_remarks` text,
    `coll_paydate` date NOT NULL,
    `coll_refno` text,
    `coll_status` smallint DEFAULT 1 NOT NULL,
    `coll_file_no` text,
    `coll_app_date` date,
    `coll_app_by` bigint,
    `coll_app_note` text,
    `coll_app_status` smallint DEFAULT 0,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `coll_src_type` bigint DEFAULT 1 NOT NULL,
    CONSTRAINT `mis_collection_pkey` PRIMARY KEY (`coll_id`))
 ;


DROP TABLE IF EXISTS `mis_collection_det`;

CREATE TABLE `mis_collection_det` (
    `cdet_id` bigint AUTO_INCREMENT,
    `cdet_coll_id` bigint,
    `cdet_bill_id` bigint,
    `cdet_amt_topay` DECIMAL(13,3) NOT NULL,
    `cdet_amt_paid` DECIMAL(13,3) NOT NULL,
    `cdet_amt_dis` DECIMAL(13,3),
    `cdet_amt_bal` DECIMAL(13,3),
    `cdet_status` smallint DEFAULT 1,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `cdet_src_type` bigint DEFAULT 1 NOT NULL,
    CONSTRAINT `mis_collection_det_pkey` PRIMARY KEY (`cdet_id`))
 ;


DROP TABLE IF EXISTS `mis_contacts`;

CREATE TABLE `mis_contacts` (
    `con_id` bigint AUTO_INCREMENT,
    `con_type` smallint NOT NULL,
    `con_ref_type` smallint NOT NULL,
    `con_ref_id` bigint NOT NULL,
    `con_name` text,
    `con_house` text,
    `con_street1` text,
    `con_street2` text,
    `con_place` text,
    `con_locality` text,
    `con_region` text,
    `con_country` smallint,
    `con_zip_code` text,
    `con_phone` text,
    `con_mobile` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_contacts_pkey` PRIMARY KEY (`con_id`))
 ;


DROP TABLE IF EXISTS `mis_customer`;

CREATE TABLE `mis_customer` (
    `cust_id` bigint AUTO_INCREMENT,
    `cust_code` text,
    `cust_name` text,
    `cust_remarks` text,
    `cust_status` bigint DEFAULT 1 NOT NULL,
    `cust_pay_mode` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_customer_pkey` PRIMARY KEY (`cust_id`))
 ;


DROP TABLE IF EXISTS `mis_documents`;

CREATE TABLE `mis_documents` (
    `doc_id` bigint AUTO_INCREMENT,
    `doc_type` smallint NOT NULL,
    `doc_ref_type` smallint NOT NULL,
    `doc_ref_id` bigint NOT NULL,
    `doc_no` text,
    `doc_desc` text,
    `doc_remarks` text,
    `doc_issue_auth` text,
    `doc_apply_date` date,
    `doc_issue_date` date,
    `doc_expiry_date` date,
    `doc_alert_days` smallint DEFAULT 0,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `agr_tenant` text,
    `agr_mobile` text,
    `agr_tele` text,
    `agr_idno` text,
    `agr_rent` DECIMAL(9,2) DEFAULT 0,
    `agr_tax` DECIMAL(9,2) DEFAULT 0,
    `agr_expat` smallint,
    `agr_crno` text,
    `agr_paydet` text,
    `agr_comp` smallint,
    `agr_amount` DECIMAL(13,3),
    `agr_tnt_id` bigint,
    `doc_remainder` smallint,
    `doc_dyn_no` bigint,
    `doc_dyn_label` text,
    `doc_dyn_ver` smallint,
    CONSTRAINT `mis_documents_pkey` PRIMARY KEY (`doc_id`))
 ;


DROP TABLE IF EXISTS `mis_emp_contract`;

CREATE TABLE `mis_emp_contract` (
    `emc_id` bigint AUTO_INCREMENT,
    `emc_emp_id` bigint NOT NULL,
    `emc_vhl_id` bigint NOT NULL,
    `emc_status` smallint DEFAULT 1 NOT NULL,
    `emc_cust_id` bigint NOT NULL,
    `emc_project` text,
    `emc_location` text NOT NULL,
    `emc_note` text,
    `emc_date_start` date NOT NULL,
    `emc_date_end` date,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `emc_note2` text,
    CONSTRAINT `mis_emp_contract_pkey` PRIMARY KEY (`emc_id`))
 ;


DROP TABLE IF EXISTS `mis_employee`;

CREATE TABLE `mis_employee` (
    `emp_id` bigint AUTO_INCREMENT,
    `emp_no` bigint NOT NULL,
    `emp_fileno` text,
    `emp_mobileno` text,
    `emp_fname` text,
    `emp_mname` text,
    `emp_lname` text,
    `emp_nationality` smallint,
    `emp_dob` date,
    `emp_doj` date,
    `emp_comp_dept` smallint NOT NULL,
    `emp_desig` smallint NOT NULL,
    `emp_status` smallint DEFAULT 1,
    `emp_bank` text,
    `emp_branch` text,
    `emp_accountno` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `emp_nativeno` text,
    `emp_bank_id` smallint,
    `emp_reg_mulkia` text,
    `emp_reg_chassis` text,
    `emp_reg_refno` text,
    `emp_reg_remarks` text,
    CONSTRAINT `mis_employee_pkey` PRIMARY KEY (`emp_id`))
 ;



DROP TABLE IF EXISTS `mis_employee_pay`;

CREATE TABLE `mis_employee_pay` (
    `pay_id` bigint AUTO_INCREMENT,
    `pay_emp_id` bigint,
    `pay_type` smallint DEFAULT 0 NOT NULL,
    `pay_basic` DECIMAL(13,3) DEFAULT 0,
    `pay_da` DECIMAL(13,3) DEFAULT 0,
    `pay_hra` DECIMAL(13,3) DEFAULT 0,
    `pay_ta` DECIMAL(13,3) DEFAULT 0,
    `pay_allw1` DECIMAL(13,3) DEFAULT 0,
    `pay_allw2` DECIMAL(13,3) DEFAULT 0,
    `pay_allw3` DECIMAL(13,3) DEFAULT 0,
    `pay_total` DECIMAL(13,3) DEFAULT 0,
    `pay_wef` date,
    `pay_dor` date,
    `pay_remarks` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_employee_pay_pkey` PRIMARY KEY (`pay_id`))
 ;


DROP TABLE IF EXISTS `mis_employee_status`;

CREATE TABLE `mis_employee_status` (
    `sts_id` bigint AUTO_INCREMENT,
    `sts_type` smallint NOT NULL,
    `sts_emp_id` bigint NOT NULL,
    `sts_remarks` text,
    `sts_apply_date` date,
    `sts_approval_date` date,
    `sts_start_date` date,
    `sts_end_date` date,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_employee_status_pkey` PRIMARY KEY (`sts_id`))
 ;


DROP TABLE IF EXISTS `mis_expense`;

CREATE TABLE `mis_expense` (
    `exp_id` bigint AUTO_INCREMENT,
    `exp_vendor` bigint NOT NULL,
    `exp_company` bigint NOT NULL,
    `exp_mainh` smallint NOT NULL,
    `exp_mainh_ref` bigint,
    `exp_pcat` bigint NOT NULL,
    `exp_scat` bigint NOT NULL,
    `exp_ccat` bigint NOT NULL,
    `exp_details` text NOT NULL,
    `exp_amount` DECIMAL(13,3) NOT NULL,
    `exp_pay_mode` smallint NOT NULL,
    `exp_remarks` text,
    `exp_paydate` date,
    `exp_refno` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `exp_file_no` text,
    `exp_app_status` smallint,
    `exp_app_date` datetime NULL,
    `exp_app_note` text,
    `exp_billdt` date,
    `exp_paydays` smallint,
    `exp_pstatus` bigint,
    `exp_credit_amt` DECIMAL(13,3),
    `exp_oribill_amt` DECIMAL(13,3),
    `exp_cash_flow` bigint,
    `exp_update_status` smallint,
    `exp_export` bigint,
    `exp_vat_amt` DECIMAL(13,3),
    `exp_vat_option` smallint DEFAULT 0,
    `exp_novat_amt` DECIMAL(13,3),
    CONSTRAINT `mis_expense_pkey` PRIMARY KEY (`exp_id`))
 ;




DROP TABLE IF EXISTS `mis_expense_href`;

CREATE TABLE `mis_expense_href` (
    `eref_id` bigint AUTO_INCREMENT,
    `eref_exp_id` bigint NOT NULL,
    `eref_main_head` smallint NOT NULL,
    `eref_main_head_ref` bigint NOT NULL,
    `eref_amount` DECIMAL(13,3) NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `eref_status` smallint DEFAULT 1 NOT NULL,
    CONSTRAINT `mis_expense_href_pkey` PRIMARY KEY (`eref_id`))
 ;


DROP TABLE IF EXISTS `mis_expense_update`;

CREATE TABLE `mis_expense_update` (
    `eup_id` bigint AUTO_INCREMENT,
    `eup_exp_id` bigint,
    `eup_type` smallint NOT NULL,
    `eup_date` date NOT NULL,
    `eup_exp_topay` DECIMAL(13,3) NOT NULL,
    `eup_exp_adjust` DECIMAL(13,3),
    `eup_exp_credit` DECIMAL(13,3),
    `eup_app_status` smallint,
    `eup_app_date` date,
    `eup_app_by` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_expense_update_pkey` PRIMARY KEY (`eup_id`))
 ;


DROP TABLE IF EXISTS `mis_item`;

CREATE TABLE `mis_item` (
    `item_id` bigint AUTO_INCREMENT,
    `item_code` text,
    `item_name` text,
    `item_remarks` text,
    `item_status` bigint DEFAULT 1 NOT NULL,
    `item_price` DECIMAL(13,3),
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `item_unit` text,
    `item_type` smallint DEFAULT 1,
    `item_vehicle` bigint,
    CONSTRAINT `mis_item_pkey` PRIMARY KEY (`item_id`))
 ;


DROP TABLE IF EXISTS `mis_notification`;

CREATE TABLE `mis_notification` (
    `notif_id` bigint AUTO_INCREMENT,
    `notif_month` date NOT NULL,
    `notif_email` text,
    `notif_content` text,
    `notif_status` smallint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0,
    CONSTRAINT `mis_notification_pkey` PRIMARY KEY (`notif_id`))
 ;


DROP TABLE IF EXISTS `mis_payment`;

CREATE TABLE `mis_payment` (
    `pay_id` bigint AUTO_INCREMENT,
    `pay_vendor` bigint NOT NULL,
    `pay_amount` DECIMAL(13,3) NOT NULL,
    `pay_pay_mode` smallint DEFAULT 1 NOT NULL,
    `pay_chqno` text,
    `pay_remarks` text,
    `pay_paydate` date NOT NULL,
    `pay_refno` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `pay_file_no` text,
    `pay_app_date` date,
    `pay_app_by` bigint,
    `pay_app_note` text,
    `pay_app_status` smallint DEFAULT 0,
    `pay_pay_status` smallint,
    `pay_pay_note` text,
    `pay_pay_date` date,
    `pay_pay_app_date` timestamp,
    `pay_cash_flow` bigint,
    CONSTRAINT `mis_payment_pkey` PRIMARY KEY (`pay_id`))
 ;



DROP TABLE IF EXISTS `mis_payment_det`;

CREATE TABLE `mis_payment_det` (
    `pdet_id` bigint AUTO_INCREMENT,
    `pdet_pay_id` bigint,
    `pdet_exp_id` bigint,
    `pdet_amt_topay` DECIMAL(13,3) NOT NULL,
    `pdet_amt_paid` DECIMAL(13,3) NOT NULL,
    `pdet_amt_dis` DECIMAL(13,3),
    `pdet_amt_bal` DECIMAL(13,3),
    `pdet_status` smallint DEFAULT 1,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_payment_det_pkey` PRIMARY KEY (`pdet_id`))
 ;


DROP TABLE IF EXISTS `mis_property`;

CREATE TABLE `mis_property` (
    `prop_id` bigint AUTO_INCREMENT,
    `prop_no` text NOT NULL,
    `prop_name` text NOT NULL,
    `prop_fileno` text NOT NULL,
    `prop_building` smallint NOT NULL,
    `prop_responsible` bigint,
    `prop_remarks` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `prop_cat` smallint,
    `prop_type` smallint,
    `prop_level` smallint,
    `prop_elec_meter` text,
    `prop_water` text,
    `prop_building_type` smallint,
    `prop_status` bigint DEFAULT 1,
    `prop_elec_account` text,
    `prop_elec_recharge` text,
    `prop_account` bigint,
    CONSTRAINT `mis_property_pkey` PRIMARY KEY (`prop_id`))
 ;



DROP TABLE IF EXISTS `mis_property_building`;

CREATE TABLE `mis_property_building` (
    `bld_id` bigint AUTO_INCREMENT,
    `bld_name` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_property_building_pkey` PRIMARY KEY (`bld_id`))
 ;


DROP TABLE IF EXISTS `mis_property_payoption`;

CREATE TABLE `mis_property_payoption` (
    `popt_id` bigint AUTO_INCREMENT,
    `popt_prop_id` bigint,
    `popt_doc_id` bigint,
    `popt_type` smallint NOT NULL,
    `popt_date` date NOT NULL,
    `popt_amount` DECIMAL(13,3),
    `popt_bank` smallint,
    `popt_chqno` text,
    `popt_status` smallint DEFAULT 1,
    `popt_status_date` date,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_property_payoption_pkey` PRIMARY KEY (`popt_id`))
 ;


DROP TABLE IF EXISTS `mis_property_service`;

CREATE TABLE `mis_property_service` (
    `psvs_id` bigint AUTO_INCREMENT,
    `psvs_prop_id` bigint,
    `psvs_type` smallint DEFAULT 1,
    `psvs_complaint_no` VARCHAR(15),
    `psvs_date` date NOT NULL,
    `psvs_srv_date` date NOT NULL,
    `psvs_emp` bigint,
    `psvs_time_in` TIME,
    `psvs_time_out` TIME,
    `psvs_service_json` text,
    `psvs_parts_json` text,
    `psvs_signed` VARCHAR(50),
    `psvs_amt_mat` DECIMAL(9,3),
    `psvs_amt_lab` DECIMAL(9,3),
    `psvs_amt_tot` DECIMAL(9,3),
    `psvs_signed_phone` VARCHAR(20),
    `psvs_feedback` smallint,
    `psvs_remarks` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_property_service_pkey` PRIMARY KEY (`psvs_id`))
 ;


DROP TABLE IF EXISTS `mis_property_status`;

CREATE TABLE `mis_property_status` (
    `psts_id` bigint AUTO_INCREMENT,
    `psts_type` smallint DEFAULT 1,
    `psts_prop_id` bigint,
    `psts_remarks` text NOT NULL,
    `psts_status_date` date NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `psts_attach_prop` bigint,
    CONSTRAINT `mis_property_status_pkey` PRIMARY KEY (`psts_id`))
 ;


DROP TABLE IF EXISTS `mis_salary`;

CREATE TABLE `mis_salary` (
    `sal_id` bigint AUTO_INCREMENT,
    `sal_period` smallint NOT NULL,
    `sal_total` DECIMAL(13,3),
    `sal_addition` DECIMAL(13,3),
    `sal_deduction` DECIMAL(13,3),
    `sal_net` DECIMAL(13,3),
    `sal_remarks` text,
    `sal_paydate` date,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `sal_status` smallint DEFAULT 1,
    `sal_empcount` smallint DEFAULT 0,
    CONSTRAINT `mis_salary_pkey` PRIMARY KEY (`sal_id`))
 ;



DROP TABLE IF EXISTS `mis_salary_det`;

CREATE TABLE `mis_salary_det` (
    `sdet_id` bigint AUTO_INCREMENT,
    `sdet_sal_id` bigint,
    `sdet_emp_id` bigint,
    `sdet_group` smallint NOT NULL,
    `sdet_amt_total` DECIMAL(13,3),
    `sdet_amt_deduct` DECIMAL(13,3),
    `sdet_amt_addition` DECIMAL(13,3),
    `sdet_amt_net` DECIMAL(13,3),
    `sdet_remarks` text,
    `sdet_status` smallint DEFAULT 1,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `sdet_category` smallint,
    CONSTRAINT `mis_salary_det_pkey` PRIMARY KEY (`sdet_id`))
 ;



DROP TABLE IF EXISTS `mis_tenants`;

CREATE TABLE `mis_tenants` (
    `tnt_id` bigint AUTO_INCREMENT,
    `tnt_full_name` text NOT NULL,
    `tnt_comp_name` text,
    `tnt_phone` text,
    `tnt_tele` text,
    `tnt_id_no` text NOT NULL,
    `tnt_crno` text NOT NULL,
    `tnt_expat` smallint,
    `tnt_agr_type` smallint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `tnt_doc_id` bigint,
    CONSTRAINT `mis_tenants_pkey` PRIMARY KEY (`tnt_id`))
 ;


DROP TABLE IF EXISTS `mis_tenants_temp`;

CREATE TABLE `mis_tenants_temp` (
    `tnt_id_temp` bigint AUTO_INCREMENT,
    `tnt_full_name` text,
    `tnt_comp_name` text,
    `tnt_phone` text,
    `tnt_tele` text,
    `tnt_id_no` text,
    `tnt_crno` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0,
    `tnt_expat` smallint,
    `tnt_agr_type` smallint,
    CONSTRAINT `mis_tenants_temp_pkey` PRIMARY KEY (`tnt_id_temp`))
 ;


DROP TABLE IF EXISTS `mis_tenants_x`;

CREATE TABLE `mis_tenants_x` (
    `tnt_id_temp` bigint AUTO_INCREMENT,
    `tnt_full_name` text,
    `tnt_comp_name` text,
    `tnt_phone` text,
    `tnt_tele` text,
    `tnt_id_no` text,
    `tnt_crno` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0,
    `tnt_expat` smallint,
    `tnt_agr_type` smallint,
    CONSTRAINT `mis_tenants_x_pkey` PRIMARY KEY (`tnt_id_temp`))
 ;


DROP TABLE IF EXISTS `mis_tickets`;

CREATE TABLE `mis_tickets` (
    `tkt_id` bigint AUTO_INCREMENT,
    `tkt_company` bigint NOT NULL,
    `tkt_reported` text NOT NULL,
    `tkt_cat` smallint NOT NULL,
    `tkt_mob1` text NOT NULL,
    `tkt_mob2` text,
    `tkt_vtime_srt` TIME,
    `tkt_vtime_end` TIME,
    `tkt_assign` bigint,
    `tkt_mainhead` smallint NOT NULL,
    `tkt_dttime_strt` timestamp,
    `tkt_dttime_end` timestamp,
    `tkt_priority` smallint NOT NULL,
    `tkt_budjet` DECIMAL(13,3),
    `tkt_status` smallint,
    `tkt_details` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_tickets_pkey` PRIMARY KEY (`tkt_id`))
 ;


DROP TABLE IF EXISTS `mis_tickets_actions`;

CREATE TABLE `mis_tickets_actions` (
    `act_id` bigint AUTO_INCREMENT,
    `act_ticket_id` smallint NOT NULL,
    `act_by` bigint NOT NULL,
    `act_remarks` text NOT NULL,
    `act_steps` bigint,
    `act_status` smallint NOT NULL,
    `act_dttime` timestamp,
    `act_escalate` bigint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_tickets_actions_pkey` PRIMARY KEY (`act_id`))
 ;


DROP TABLE IF EXISTS `mis_tickets_cat`;

CREATE TABLE `mis_tickets_cat` (
    `tcat_id` bigint AUTO_INCREMENT,
    `tcat_type` smallint DEFAULT 1,
    `tcat_name` text NOT NULL,
    `tcat_remarks` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_tickets_cat_pkey` PRIMARY KEY (`tcat_id`))
 ;


DROP TABLE IF EXISTS `mis_tickets_href`;

CREATE TABLE `mis_tickets_href` (
    `tref_id` bigint AUTO_INCREMENT,
    `tref_tkt_id` bigint NOT NULL,
    `tref_main_head` smallint NOT NULL,
    `tref_main_head_ref` bigint NOT NULL,
    `tref_note` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_tickets_href_pkey` PRIMARY KEY (`tref_id`))
 ;


DROP TABLE IF EXISTS `mis_tickets_steps`;

CREATE TABLE `mis_tickets_steps` (
    `stp_id` bigint AUTO_INCREMENT,
    `stp_ticket_id` smallint NOT NULL,
    `stp_by` bigint NOT NULL,
    `stp_steps` text NOT NULL,
    `stp_dttime` timestamp,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    CONSTRAINT `mis_tickets_steps_pkey` PRIMARY KEY (`stp_id`))
 ;


DROP TABLE IF EXISTS `mis_vehicle`;

CREATE TABLE `mis_vehicle` (
    `vhl_id` bigint AUTO_INCREMENT,
    `vhl_no` text NOT NULL,
    `vhl_fileno` text NOT NULL,
    `vhl_type` smallint NOT NULL,
    `vhl_model` bigint NOT NULL,
    `vhl_company` smallint NOT NULL,
    `vhl_responsible` bigint,
    `vhl_remarks` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `vhl_comm_status` smallint DEFAULT 1 NOT NULL,
    `vhl_rate_hour` DECIMAL(13,3),
    `vhl_rate_day` DECIMAL(13,3),
    `vhl_rate_month` DECIMAL(13,3),
    `vhl_man` smallint,
    `vhl_employed` bigint,
    `vhl_vendor` bigint,
    `vhl_site` text,
    `vhl_company_old` bigint,
    `vhl_status` smallint DEFAULT 1,
    CONSTRAINT `mis_vehicle_pkey` PRIMARY KEY (`vhl_id`))
 ;



DROP TABLE IF EXISTS `mis_vehicle_man`;

CREATE TABLE `mis_vehicle_man` (
    `vman_id` bigint AUTO_INCREMENT,
    `vman_name` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `vman_code` VARCHAR(10),
    CONSTRAINT `mis_vehicle_vman_pkey` PRIMARY KEY (`vman_id`))
 ;


DROP TABLE IF EXISTS `mis_vehicle_type`;

CREATE TABLE `mis_vehicle_type` (
    `type_id` bigint AUTO_INCREMENT,
    `type_name` text NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `type_code` VARCHAR(10),
    CONSTRAINT `mis_vehicle_type_pkey` PRIMARY KEY (`type_id`))
 ;


DROP TABLE IF EXISTS `mis_vendor`;

CREATE TABLE `mis_vendor` (
    `ven_id` bigint AUTO_INCREMENT,
    `ven_code` text,
    `ven_name` text NOT NULL,
    `ven_remarks` text,
    `ven_status` smallint DEFAULT 1 NOT NULL,
    `ven_pay_mode` smallint,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `ven_disp_name` text,
    `ven_vat_no` text,
    `ven_type` smallint DEFAULT 1 NOT NULL,
    CONSTRAINT `mis_vendor_pkey` PRIMARY KEY (`ven_id`))
 ;


DROP TABLE IF EXISTS `mis_vhl_service`;

CREATE TABLE `mis_vhl_service` (
    `srv_id` bigint AUTO_INCREMENT,
    `srv_vhl_id` bigint NOT NULL,
    `srv_date_start` date NOT NULL,
    `srv_location` text NOT NULL,
    `srv_reading` text NOT NULL,
    `srv_note` text,
    `srv_type` smallint DEFAULT 1 NOT NULL,
    `srv_nxt_type` smallint DEFAULT 1 NOT NULL,
    `srv_done_by` bigint,
    `srv_reading_next` text NOT NULL,
    `srv_date_next` date NOT NULL,
    `srv_wash` smallint DEFAULT 1 NOT NULL,
    `srv_greese` smallint DEFAULT 1 NOT NULL,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `srv_labour` DECIMAL(9,3),
    CONSTRAINT `mis_vhl_service_pkey` PRIMARY KEY (`srv_id`))
 ;


DROP TABLE IF EXISTS `mis_vhl_srv_det`;

CREATE TABLE `mis_vhl_srv_det` (
    `sdt_id` bigint AUTO_INCREMENT,
    `sdt_srv_id` bigint NOT NULL,
    `sdt_item` bigint NOT NULL,
    `sdt_qty` text NOT NULL,
    `sdt_done_by` bigint NOT NULL,
    `sdt_note` text,
    `u_created` bigint,
    `u_modified` bigint,
    `u_deleted` bigint,
    `t_created` datetime NULL,
    `t_modified` datetime NULL,
    `t_deleted` datetime NULL,
    `deleted` smallint DEFAULT 0 NOT NULL,
    `sdt_unit` VARCHAR(20),
    `sdt_price` DECIMAL(9,3),
    `sdt_billid` bigint,
    CONSTRAINT `mis_vhl_srv_det_pkey` PRIMARY KEY (`sdt_id`))
 ;


DROP TABLE IF EXISTS `temp_files`;

CREATE TABLE `temp_files` (
    `temp_old` text,
    `temp_new` text,
    `id` bigint AUTO_INCREMENT,
    `type` smallint,
    CONSTRAINT `temp_files_pkey` PRIMARY KEY (`id`))
 ;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
    `id` bigint AUTO_INCREMENT,
    `name` text NOT NULL,
    `email` text NOT NULL,
    CONSTRAINT `users_pkey` PRIMARY KEY (`id`))
 ;

ALTER TABLE `core_bank_account`
ADD CONSTRAINT `core_bank_account_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_category`
ADD CONSTRAINT `core_category_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_comp_department`
ADD CONSTRAINT `core_comp_department_cmpdept_comp_id_fkey` FOREIGN KEY (`cmpdept_comp_id`) REFERENCES `core_company`(`comp_id`);

ALTER TABLE `core_comp_department`
ADD CONSTRAINT `core_comp_department_cmpdept_dept_id_fkey` FOREIGN KEY (`cmpdept_dept_id`) REFERENCES `core_department`(`dept_id`);

ALTER TABLE `core_comp_department`
ADD CONSTRAINT `core_comp_department_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_company`
ADD CONSTRAINT `core_company_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_department`
ADD CONSTRAINT `core_department_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_designation`
ADD CONSTRAINT `core_designation_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_files`
ADD CONSTRAINT `core_files_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_login_log`
ADD CONSTRAINT `core_login_log_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `core_updates`
ADD CONSTRAINT `core_updates_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_bill`
ADD CONSTRAINT `mis_bill_bill_company_fkey` FOREIGN KEY (`bill_company`) REFERENCES `core_company`(`comp_id`);

ALTER TABLE `mis_bill`
ADD CONSTRAINT `mis_bill_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_bill_det`
ADD CONSTRAINT `mis_bill_det_bdet_bill_id_fkey` FOREIGN KEY (`bdet_bill_id`) REFERENCES `mis_bill`(`bill_id`);

ALTER TABLE `mis_bill_det`
ADD CONSTRAINT `mis_bill_det_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_building`
ADD CONSTRAINT `mis_building_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_call_log`
ADD CONSTRAINT `mis_call_log_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_call_log_follow`
ADD CONSTRAINT `mis_call_log_follow_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_cash_book`
ADD CONSTRAINT `mis_cash_book_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_cash_demand`
ADD CONSTRAINT `mis_cash_demand_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_cash_flow`
ADD CONSTRAINT `mis_cash_flow_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_collection`
ADD CONSTRAINT `mis_collection_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_collection_det`
ADD CONSTRAINT `mis_collection_det_cdet_coll_id_fkey` FOREIGN KEY (`cdet_coll_id`) REFERENCES `mis_collection`(`coll_id`);

ALTER TABLE `mis_collection_det`
ADD CONSTRAINT `mis_collection_det_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_contacts`
ADD CONSTRAINT `mis_contacts_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_customer`
ADD CONSTRAINT `mis_customer_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_documents`
ADD CONSTRAINT `mis_documents_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_emp_contract`
ADD CONSTRAINT `mis_emp_contract_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_employee_pay`
ADD CONSTRAINT `mis_employee_pay_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_employee_status`
ADD CONSTRAINT `mis_employee_status_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_expense`
ADD CONSTRAINT `mis_expense_exp_ccat_fkey` FOREIGN KEY (`exp_ccat`) REFERENCES `core_category`(`cat_id`);

ALTER TABLE `mis_expense`
ADD CONSTRAINT `mis_expense_exp_company_fkey` FOREIGN KEY (`exp_company`) REFERENCES `core_company`(`comp_id`);

ALTER TABLE `mis_expense`
ADD CONSTRAINT `mis_expense_exp_pcat_fkey` FOREIGN KEY (`exp_pcat`) REFERENCES `core_category`(`cat_id`);

ALTER TABLE `mis_expense`
ADD CONSTRAINT `mis_expense_exp_scat_fkey` FOREIGN KEY (`exp_scat`) REFERENCES `core_category`(`cat_id`);

ALTER TABLE `mis_expense`
ADD CONSTRAINT `mis_expense_exp_vendor_fkey` FOREIGN KEY (`exp_vendor`) REFERENCES `mis_vendor`(`ven_id`);

ALTER TABLE `mis_expense`
ADD CONSTRAINT `mis_expense_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_expense_href`
ADD CONSTRAINT `mis_expense_href_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_expense_update`
ADD CONSTRAINT `mis_expense_update_eup_app_by_fkey` FOREIGN KEY (`eup_app_by`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_expense_update`
ADD CONSTRAINT `mis_expense_update_eup_exp_id_fkey` FOREIGN KEY (`eup_exp_id`) REFERENCES `mis_expense`(`exp_id`);

ALTER TABLE `mis_expense_update`
ADD CONSTRAINT `mis_expense_update_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_item`
ADD CONSTRAINT `mis_item_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_payment`
ADD CONSTRAINT `mis_payment_pay_vendor_fkey` FOREIGN KEY (`pay_vendor`) REFERENCES `mis_vendor`(`ven_id`);

ALTER TABLE `mis_payment`
ADD CONSTRAINT `mis_payment_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);

ALTER TABLE `mis_payment_det`
ADD CONSTRAINT `mis_payment_det_pdet_exp_id_fkey` FOREIGN KEY (`pdet_exp_id`) REFERENCES `mis_expense`(`exp_id`);

ALTER TABLE `mis_payment_det`
ADD CONSTRAINT `mis_payment_det_pdet_pay_id_fkey` FOREIGN KEY (`pdet_pay_id`) REFERENCES `mis_payment`(`pay_id`);

ALTER TABLE `mis_payment_det`
ADD CONSTRAINT `mis_payment_det_u_created_fkey` FOREIGN KEY (`u_created`) REFERENCES `core_users`(`user_id`);


-- 2024-12-13 10:23:38.634619+05:30
