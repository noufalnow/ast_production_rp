DROP TABLE IF EXISTS "mis_tenants";
DROP SEQUENCE IF EXISTS mis_tenants_tnt_id_seq;
CREATE SEQUENCE mis_tenants_tnt_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

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
    "deleted" smallint DEFAULT '0' NOT NULL,
    "tnt_doc_id" bigint NOT NULL,
    CONSTRAINT "mis_tenants_pkey" PRIMARY KEY ("tnt_id")
) WITH (oids = false);




INSERT INTO mis_tenants (tnt_doc_id, tnt_full_name, tnt_phone, tnt_tele, tnt_id_no, tnt_expat, tnt_crno, tnt_agr_type, u_created, u_modified, t_created, t_modified, t_deleted, deleted )
SELECT "doc_id",
       "agr_tenant",
       "agr_mobile",
       "agr_tele",
       "agr_idno",
       "agr_expat",
       "agr_crno",
       "agr_comp",
       "u_created",
       "u_modified",
       "t_created",
       "t_modified",
       "t_deleted",
       "deleted"
FROM "mis_documents"
WHERE "doc_type" = '201'
  AND "doc_ref_type" = '3'
  AND "deleted" = '0';


  ALTER TABLE "mis_documents"
ADD "agr_tnt_id" bigint NULL;
COMMENT ON TABLE "mis_documents" IS '';



UPDATE mis_documents
SET agr_tnt_id =
  (SELECT tnt_id
   FROM mis_tenants
   WHERE tnt_doc_id = mis_documents.doc_id)
WHERE "doc_type" = '201'
  AND "doc_ref_type" = '3'


SELECT agr_tenant ,tnt_full_name
FROM mis_documents
LEFT JOIN mis_tenants AS tenants ON tenants.tnt_id = agr_tnt_id
WHERE "doc_type" = '201' AND "doc_ref_type" = '3'
;




DELETE FROM
    mis_tenants a
        USING mis_tenants b
WHERE
    a.tnt_id < b.tnt_id
    AND a.tnt_full_name = b.tnt_full_name;



UPDATE mis_documents
SET agr_tnt_id =
  (SELECT tnt_id
   FROM mis_tenants
   WHERE tnt_full_name = mis_documents.agr_tenant)
WHERE "doc_type" = '201'
  AND "doc_ref_type" = '3'