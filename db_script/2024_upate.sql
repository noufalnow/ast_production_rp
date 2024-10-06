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
