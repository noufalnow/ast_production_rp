ALTER TABLE "mis_expense"
ADD "exp_vat_amt" numeric(13,3) NULL,
ADD "exp_vat_option" smallint NULL DEFAULT '0';


ALTER TABLE "mis_vendor"
ADD "ven_vat_no" text NULL;

ALTER TABLE "mis_vendor"
ADD "ven_type" smallint NOT NULL DEFAULT '1';

===========================

ALTER TABLE "mis_documents"
ADD "doc_dyn_no" bigint NULL;


update mis_documents
set doc_dyn_no = doc_type ;


ALTER TABLE "mis_documents"
ADD "doc_dyn_label" text NULL;

UPDATE "mis_documents" SET "doc_dyn_label" = 'CR CERTIFICATE' WHERE doc_ref_type = 1  AND doc_type = 1 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'CR ID' WHERE doc_ref_type = 1  AND doc_type = 2 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'SIGNATORY' WHERE doc_ref_type = 1  AND doc_type = 3 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'ID CARD 1' WHERE doc_ref_type = 1  AND doc_type = 4 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'ID CARD 2' WHERE doc_ref_type = 1  AND doc_type = 5 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'PINK CERTIFICATE 1' WHERE doc_ref_type = 1  AND doc_type = 61 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'PINK CERTIFICATE 2' WHERE doc_ref_type = 1  AND doc_type = 62;
UPDATE "mis_documents" SET "doc_dyn_label" = 'PINK CERTIFICATE 3' WHERE doc_ref_type = 1  AND doc_type = 63 ;
UPDATE "mis_documents" SET "doc_dyn_label" = 'PINK CERTIFICATE 4' WHERE doc_ref_type = 1  AND doc_type = 64;
UPDATE "mis_documents" SET "doc_dyn_label" = 'PINK CERTIFICATE 5' WHERE doc_ref_type = 1  AND doc_type = 65;

ALTER TABLE "mis_documents"
ADD "doc_dyn_ver" smallint NULL;


#################################


ALTER TABLE "mis_expense"
ADD "exp_novat_amt" numeric(13,3) NULL;
COMMENT ON TABLE "mis_expense" IS '';

###################################


ALTER TABLE "mis_building"
ADD "bld_comp" smallint NULL;
COMMENT ON TABLE "mis_building" IS '';

ALTER TABLE "mis_building"
ADD FOREIGN KEY ("bld_comp") REFERENCES "core_company" ("comp_id") ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE "mis_vehicle"
ADD "vhl_company_old" bigint NULL;
COMMENT ON TABLE "mis_vehicle" IS '';

update mis_vehicle set vhl_company_old = vhl_company;

UPDATE mis_building
SET bld_comp = CASE
    WHEN bld_id = 1 THEN 2
    WHEN bld_id = 3 THEN 9
    WHEN bld_id = 25 THEN 10
    WHEN bld_id = 10 THEN 10
    WHEN bld_id = 2 THEN 10
    WHEN bld_id = 5 THEN 7
    WHEN bld_id = 19 THEN 3
    WHEN bld_id = 6 THEN 7
    WHEN bld_id = 7 THEN 4
    WHEN bld_id = 9 THEN 2
    WHEN bld_id = 4 THEN 11
    WHEN bld_id = 11 THEN 4
    WHEN bld_id = 8 THEN 1
    WHEN bld_id = 27 THEN 1
    WHEN bld_id = 26 THEN 1
    ELSE bld_comp
END
WHERE bld_id IN (1, 3, 25, 10, 2, 5, 19, 6, 7, 9, 4, 11, 8, 27, 26);


UPDATE mis_vehicle
SET vhl_company = CASE
    WHEN vhl_id = 58 THEN 2
    WHEN vhl_id = 74 THEN 3
    WHEN vhl_id = 72 THEN 4
    WHEN vhl_id = 55 THEN 7
    WHEN vhl_id = 70 THEN 7
    WHEN vhl_id = 46 THEN 7
    WHEN vhl_id = 47 THEN 3
    WHEN vhl_id = 40 THEN 10
    WHEN vhl_id = 61 THEN 4
    WHEN vhl_id = 67 THEN 9
    WHEN vhl_id = 43 THEN 7
    WHEN vhl_id = 75 THEN 4
    WHEN vhl_id = 52 THEN 7
    WHEN vhl_id = 56 THEN 7
    WHEN vhl_id = 49 THEN 7
    WHEN vhl_id = 71 THEN 7
    WHEN vhl_id = 68 THEN 10
    WHEN vhl_id = 48 THEN 7
    WHEN vhl_id = 81 THEN 7
    WHEN vhl_id = 78 THEN 10
    WHEN vhl_id = 73 THEN 2
    WHEN vhl_id = 76 THEN 7
    WHEN vhl_id = 60 THEN 4
    WHEN vhl_id = 89 THEN 7
    WHEN vhl_id = 85 THEN 7
    WHEN vhl_id = 62 THEN 9
    WHEN vhl_id = 83 THEN 7
    WHEN vhl_id = 77 THEN 10
    WHEN vhl_id = 64 THEN 11
    WHEN vhl_id = 79 THEN 7
    WHEN vhl_id = 69 THEN 7
    WHEN vhl_id = 87 THEN 7
    WHEN vhl_id = 51 THEN 4
    WHEN vhl_id = 50 THEN 7
    WHEN vhl_id = 86 THEN 7
    WHEN vhl_id = 39 THEN 7
    WHEN vhl_id = 82 THEN 10
    WHEN vhl_id = 65 THEN 2
    WHEN vhl_id = 41 THEN 3
    WHEN vhl_id = 63 THEN 1
    WHEN vhl_id = 88 THEN 7
    WHEN vhl_id = 66 THEN 8
    WHEN vhl_id = 59 THEN 3
    WHEN vhl_id = 80 THEN 4
    WHEN vhl_id = 57 THEN 9
    WHEN vhl_id = 44 THEN 7
    WHEN vhl_id = 54 THEN 9
    WHEN vhl_id = 53 THEN 3
    WHEN vhl_id = 45 THEN 1
    WHEN vhl_id = 38 THEN 7
    WHEN vhl_id = 84 THEN 1
    WHEN vhl_id = 42 THEN 2
    ELSE vhl_company
END
WHERE vhl_id IN (58, 74, 72, 55, 70, 46, 47, 40, 61, 67, 43, 75, 52, 56, 49, 71, 68, 48, 81, 78, 73, 76, 60, 89, 85, 62, 83, 77, 64, 79, 69, 87, 51, 50, 86, 39, 82, 65, 41, 63, 88, 66, 59, 80, 57, 44, 54, 53, 45, 38, 84, 42);





update mis_expense
#set exp_novat_amt   = exp_amount;


###############################

-- Adminer 4.8.1 PostgreSQL 14.13 (Ubuntu 14.13-0ubuntu0.22.04.1) dump

DROP TABLE IF EXISTS "mis_vehicle_man";
DROP SEQUENCE IF EXISTS mis_vehicle_man_vman_id_seq;
CREATE SEQUENCE mis_vehicle_man_vman_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 8 CACHE 1;

CREATE TABLE "public"."mis_vehicle_man" (
    "vman_id" bigint DEFAULT nextval('mis_vehicle_man_vman_id_seq') NOT NULL,
    "vman_name" text NOT NULL,
    "u_created" bigint,
    "u_modified" bigint,
    "u_deleted" bigint,
    "t_created" timestamptz,
    "t_modified" timestamptz,
    "t_deleted" timestamptz,
    "deleted" smallint DEFAULT '0' NOT NULL,
    "vman_code" character varying(10),
    CONSTRAINT "mis_vehicle_vman_pkey" PRIMARY KEY ("vman_id")
) WITH (oids = false);

TRUNCATE "mis_vehicle_man";
INSERT INTO "mis_vehicle_man" ("vman_id", "vman_name", "u_created", "u_modified", "u_deleted", "t_created", "t_modified", "t_deleted", "deleted", "vman_code") VALUES
(1,	'MERCEDES',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL),
(3,	'KOMATSU',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL),
(4,	'SCANIA',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL),
(5,	'GORICA',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL),
(6,	'RENAULT',	NULL,	NULL,	NULL,	NULL,	NULL,	NULL,	0,	NULL),
(2,	'CJ',	NULL,	1,	NULL,	NULL,	'2024-10-10 10:16:54+05:30',	NULL,	0,	'CJ');

-- 2024-10-10 11:49:55.221631+05:30




##################################

ALTER TABLE "mis_vehicle"
ADD "vhl_status" smallint NULL DEFAULT '1';
COMMENT ON TABLE "mis_vehicle" IS '';


#############################

ALTER TABLE "mis_item"
ADD "item_vehicle" bigint NULL;
COMMENT ON TABLE "mis_item" IS '';

##############################


ALTER TABLE "mis_item"
ADD "item_vehicle" bigint NULL;
COMMENT ON TABLE "mis_item" IS '';




# UPDATE "mis_property_payoption"
SET popt_date = to_date('2024-10' || to_char(popt_date, '-DD'), 'YYYY-MM-DD')
WHERE to_char(popt_date, 'YYYY-MM') = '2024-09'
AND popt_id IN (9046, 9053, 9060, 9064, 9071, 9074, 9078, 9081, 9086, 9095, 9097, 9103, 9006, 8916, 9109, 9114, 9115, 9123, 9126, 9132, 9138, 9145, 9156, 9167);


#UPDATE "mis_cash_demand"
SET cdmd_date = to_date('2024-10' || to_char(cdmd_date, '-DD'), 'YYYY-MM-DD')
WHERE to_char(cdmd_date, 'YYYY-MM') = '2024-09'  AND 
"cdmd_id" IN (6963,6964,6965,6966,6967,6968,6968,6969,6970,6971,6972,6973,6974,6975,6976,6977,6978,6979,6980,6981,6982,6983,6984,6985,6987);



#UPDATE "mis_cash_demand"
SET cdmd_month = to_date('2024-10' || to_char(cdmd_month, '-DD'), 'YYYY-MM-DD')
WHERE to_char(cdmd_month, 'YYYY-MM') = '2024-09'  AND 
"cdmd_id" IN (6963,6964,6965,6966,6967,6968,6968,6969,6970,6971,6972,6973,6974,6975,6976,6977,6978,6979,6980,6981,6982,6983,6984,6985,6987);

#UPDATE "mis_cash_demand" SET "cdmd_date" = '2024-09-29', "cdmd_month" = '2024-09-29' WHERE "cdmd_id" = '6957' AND "cdmd_id" = '6957';


# UPDATE "mis_property_payoption" SET "popt_date" = '2024-09-29' WHERE "popt_id" = '9028' AND "popt_id" = '9028';



#####################


VAT ON INVOICE

ALTER TABLE "mis_bill"
ADD "bill_vat_option" smallint NOT NULL DEFAULT '0',
ADD "bill_vat_amt" numeric(13,3) NULL;

##################


ALTER TABLE "mis_bill"
ADD "bill_company_alias" smallint NOT NULL DEFAULT '0';
COMMENT ON COLUMN "mis_bill"."bill_company_alias" IS '1=> Bin Abullah Salem Trading';


