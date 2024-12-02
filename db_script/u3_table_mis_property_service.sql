-- Adminer 4.8.1 PostgreSQL 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1) dump

DROP TABLE IF EXISTS "mis_property_service";
DROP SEQUENCE IF EXISTS mis_property_service_psvs_id_seq;
CREATE SEQUENCE mis_property_service_psvs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1;

CREATE TABLE "public"."mis_property_service" (
    "psvs_id" bigint DEFAULT nextval('mis_property_service_psvs_id_seq') NOT NULL,
    "psvs_prop_id" bigint,
    "psvs_type" smallint DEFAULT '1',
    "psvs_complaint_no" character varying(15),
    "psvs_date" date NOT NULL,
    "psvs_srv_date" date NOT NULL,
    "psvs_emp" bigint,
    "psvs_time_in" time without time zone,
    "psvs_time_out" time without time zone,
    "psvs_service_json" json,
    "psvs_parts_json" json,
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
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_property_service_pkey" PRIMARY KEY ("psvs_id")
) WITH (oids = false);


ALTER TABLE ONLY "public"."mis_property_service" ADD CONSTRAINT "mis_property_service_psvs_prop_id_fkey" FOREIGN KEY (psvs_prop_id) REFERENCES mis_property(prop_id) NOT DEFERRABLE;
ALTER TABLE ONLY "public"."mis_property_service" ADD CONSTRAINT "mis_property_service_u_created_fkey" FOREIGN KEY (u_created) REFERENCES core_users(user_id) NOT DEFERRABLE;

-- 2024-12-02 16:08:08.294526+05:30
