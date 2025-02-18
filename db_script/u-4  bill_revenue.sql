DROP TABLE IF EXISTS "mis_bill_revenue";
DROP SEQUENCE IF EXISTS mis_bill_revenue_brev_id_seq;
CREATE SEQUENCE mis_bill_revenue_brev_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE "public"."mis_bill_revenue" (
    "brev_id" bigint DEFAULT nextval('mis_bill_revenue_brev_id_seq') NOT NULL,
    "brev_type" smallint NOT NULL,
    "brev_group_id" smallint NOT NULL,
    "brev_bill_id" bigint NOT NULL,
    "brev_vhl_id" bigint NOT NULL,
    "brev_remarks" text,
    "brev_revenue" numeric(9,3),
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT 0 NOT NULL,
    "is_synched" smallint DEFAULT 0,
    CONSTRAINT "mis_bill_revenue_pkey" PRIMARY KEY ("brev_id")
) WITH (oids = false);


DELIMITER ;;

CREATE TRIGGER "is_synched_trigger" BEFORE INSERT OR UPDATE ON "public"."mis_bill_revenue" FOR EACH ROW EXECUTE FUNCTION set_is_synched_flag();;

DELIMITER ;
