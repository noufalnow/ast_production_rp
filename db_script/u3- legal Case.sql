CREATE TABLE "public"."mis_legal_case" (
    "lcas_id" BIGSERIAL NOT NULL,
    "lcas_type" smallint DEFAULT '1',
    "lcas_party" character varying(250),
    "lcas_phone_no" character varying(15),
    "lcas_office" character varying(250),
    "lcas_lawer" character varying(250),
    "lcas_email" character varying(50),
    "lcas_date" date NOT NULL,
    "lcas_emp" bigint,
    "lcas_case" text,
    "lcas_sts" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_legal_case_pkey" PRIMARY KEY ("lcas_id")
) WITH (oids = false);

CREATE TABLE "public"."mis_legal_case_follow" (
    "lcflo_id" BIGSERIAL NOT NULL,
    "lcflo_lcas_id" bigint,
    "lcflo_update" text,
    "lcflo_date" date NOT NULL,
    "lcflo_emp" bigint,
    "lcflo_sts" smallint DEFAULT '1',
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "mis_legal_case_follow_pkey" PRIMARY KEY ("lcflo_id")
) WITH (oids = false);
