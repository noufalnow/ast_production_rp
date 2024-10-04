ALTER TABLE "mis_expense"
ADD "exp_vat_amt" numeric(13,3) NULL,
ADD "exp_vat_option" smallint NULL DEFAULT '0';


ALTER TABLE "mis_vendor"
ADD "ven_vat_no" text NULL;

ALTER TABLE "mis_vendor"
ADD "ven_type" smallint NOT NULL DEFAULT '1';
