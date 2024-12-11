-- Adminer 4.8.1 PostgreSQL 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1) dump

DROP TABLE IF EXISTS "mis_call_log";
DROP SEQUENCE IF EXISTS mis_call_log_clog_id_seq;
CREATE SEQUENCE mis_call_log_clog_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."mis_call_log" (
    "clog_id" bigint DEFAULT nextval('mis_call_log_clog_id_seq') NOT NULL,
    "clog_type" smallint DEFAULT '1',
    "clog_phone_no" character varying(15),
    "clog_name" character varying(50),
    "clog_email" character varying(50),
    "clog_date" date NOT NULL,
    "clog_time" time without time zone,
    "clog_emp" bigint,
    "clog_log" text,
    "clog_fup_json" text,
    "clog_sts_for" smallint DEFAULT '1',
    "clog_sts" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
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
    "cflo_prv_sts" smallint DEFAULT '1',
    "cflo_sts" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_call_log_follow_pkey" PRIMARY KEY ("cflo_id")
) WITH (oids = false);


ALTER TABLE ONLY "public"."mis_call_log" ADD CONSTRAINT "mis_call_log_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

ALTER TABLE ONLY "public"."mis_call_log_follow" ADD CONSTRAINT "mis_call_log_follow_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

-- 2024-12-11 17:04:13.271763+05:30