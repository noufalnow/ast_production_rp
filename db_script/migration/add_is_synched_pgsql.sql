ALTER TABLE "cnfg_acl_actions" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "cnfg_acl_actions_access" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "cnfg_acl_controllers" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "cnfg_acl_controllers_access" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "cnfg_acl_modules" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "cnfg_acl_modules_access" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_action_log" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_bank_account" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_category" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_comp_department" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_company" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_department" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_designation" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_files" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_login_log" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_updates" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "core_users" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_bill" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_bill_det" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_building" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_call_log" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_call_log_follow" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_cash_book" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_cash_demand" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_cash_flow" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_collection" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_collection_det" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_collection_revenue" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_contacts" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_customer" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_documents" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_emp_contract" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_employee" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_employee_pay" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_employee_status" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_expense" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_expense_href" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_expense_update" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_item" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_legal_case" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_legal_case_follow" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_notification" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_payment" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_payment_det" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_property" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_property_payoption" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_property_service" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_property_status" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_salary" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_salary_det" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_tenants" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_tickets" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_tickets_actions" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_tickets_cat" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_tickets_href" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_tickets_steps" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_vehicle" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_vehicle_man" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_vehicle_type" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_vendor" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_vhl_service" ADD COLUMN "is_synched" SMALLINT;
ALTER TABLE "mis_vhl_srv_det" ADD COLUMN "is_synched" SMALLINT;

; %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


UPDATE "cnfg_acl_actions" SET "is_synched" = 1;
UPDATE "cnfg_acl_actions_access" SET "is_synched" = 1;
UPDATE "cnfg_acl_controllers" SET "is_synched" = 1;
UPDATE "cnfg_acl_controllers_access" SET "is_synched" = 1;
UPDATE "cnfg_acl_modules" SET "is_synched" = 1;
UPDATE "cnfg_acl_modules_access" SET "is_synched" = 1;
UPDATE "core_action_log" SET "is_synched" = 1;
UPDATE "core_bank_account" SET "is_synched" = 1;
UPDATE "core_category" SET "is_synched" = 1;
UPDATE "core_comp_department" SET "is_synched" = 1;
UPDATE "core_company" SET "is_synched" = 1;
UPDATE "core_department" SET "is_synched" = 1;
UPDATE "core_designation" SET "is_synched" = 1;
UPDATE "core_files" SET "is_synched" = 1;
UPDATE "core_login_log" SET "is_synched" = 1;
UPDATE "core_updates" SET "is_synched" = 1;
UPDATE "core_users" SET "is_synched" = 1;
UPDATE "mis_bill" SET "is_synched" = 1;
UPDATE "mis_bill_det" SET "is_synched" = 1;
UPDATE "mis_building" SET "is_synched" = 1;
UPDATE "mis_call_log" SET "is_synched" = 1;
UPDATE "mis_call_log_follow" SET "is_synched" = 1;
UPDATE "mis_cash_book" SET "is_synched" = 1;
UPDATE "mis_cash_demand" SET "is_synched" = 1;
UPDATE "mis_cash_flow" SET "is_synched" = 1;
UPDATE "mis_collection" SET "is_synched" = 1;
UPDATE "mis_collection_det" SET "is_synched" = 1;
UPDATE "mis_collection_revenue" SET "is_synched" = 1;
UPDATE "mis_contacts" SET "is_synched" = 1;
UPDATE "mis_customer" SET "is_synched" = 1;
UPDATE "mis_documents" SET "is_synched" = 1;
UPDATE "mis_emp_contract" SET "is_synched" = 1;
UPDATE "mis_employee" SET "is_synched" = 1;
UPDATE "mis_employee_pay" SET "is_synched" = 1;
UPDATE "mis_employee_status" SET "is_synched" = 1;
UPDATE "mis_expense" SET "is_synched" = 1;
UPDATE "mis_expense_href" SET "is_synched" = 1;
UPDATE "mis_expense_update" SET "is_synched" = 1;
UPDATE "mis_item" SET "is_synched" = 1;
UPDATE "mis_legal_case" SET "is_synched" = 1;
UPDATE "mis_legal_case_follow" SET "is_synched" = 1;
UPDATE "mis_notification" SET "is_synched" = 1;
UPDATE "mis_payment" SET "is_synched" = 1;
UPDATE "mis_payment_det" SET "is_synched" = 1;
UPDATE "mis_property" SET "is_synched" = 1;
UPDATE "mis_property_payoption" SET "is_synched" = 1;
UPDATE "mis_property_service" SET "is_synched" = 1;
UPDATE "mis_property_status" SET "is_synched" = 1;
UPDATE "mis_salary" SET "is_synched" = 1;
UPDATE "mis_salary_det" SET "is_synched" = 1;
UPDATE "mis_tenants" SET "is_synched" = 1;
UPDATE "mis_tickets" SET "is_synched" = 1;
UPDATE "mis_tickets_actions" SET "is_synched" = 1;
UPDATE "mis_tickets_cat" SET "is_synched" = 1;
UPDATE "mis_tickets_href" SET "is_synched" = 1;
UPDATE "mis_tickets_steps" SET "is_synched" = 1;
UPDATE "mis_vehicle" SET "is_synched" = 1;
UPDATE "mis_vehicle_man" SET "is_synched" = 1;
UPDATE "mis_vehicle_type" SET "is_synched" = 1;
UPDATE "mis_vendor" SET "is_synched" = 1;
UPDATE "mis_vhl_service" SET "is_synched" = 1;
UPDATE "mis_vhl_srv_det" SET "is_synched" = 1;


; %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


ALTER TABLE "cnfg_acl_actions" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "cnfg_acl_actions_access" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "cnfg_acl_controllers" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "cnfg_acl_controllers_access" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "cnfg_acl_modules" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "cnfg_acl_modules_access" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_action_log" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_bank_account" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_category" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_comp_department" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_company" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_department" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_designation" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_files" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_login_log" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_updates" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "core_users" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_bill" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_bill_det" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_building" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_call_log" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_call_log_follow" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_cash_book" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_cash_demand" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_cash_flow" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_collection" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_collection_det" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_collection_revenue" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_contacts" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_customer" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_documents" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_emp_contract" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_employee" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_employee_pay" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_employee_status" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_expense" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_expense_href" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_expense_update" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_item" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_legal_case" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_legal_case_follow" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_notification" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_payment" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_payment_det" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_property" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_property_payoption" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_property_service" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_property_status" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_salary" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_salary_det" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_tenants" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_tickets" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_tickets_actions" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_tickets_cat" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_tickets_href" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_tickets_steps" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_vehicle" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_vehicle_man" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_vehicle_type" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_vendor" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_vhl_service" ALTER COLUMN "is_synched" SET DEFAULT 0;
ALTER TABLE "mis_vhl_srv_det" ALTER COLUMN "is_synched" SET DEFAULT 0;





;%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


CREATE OR REPLACE FUNCTION set_is_synched_flag()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if the column "is_synched" exists in the table
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_name = TG_TABLE_NAME
          AND column_name = 'is_synched'
    ) THEN
        -- Check if the update query explicitly sets is_synched
        IF TG_OP = 'UPDATE' AND NOT (NEW.is_synched IS DISTINCT FROM OLD.is_synched) THEN
            -- If not explicitly set, reset is_synched to 0
            NEW.is_synched := 0;
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;





; %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


DO $$
DECLARE
    tbl RECORD;
BEGIN
    FOR tbl IN
        SELECT table_name
        FROM information_schema.columns
        WHERE column_name = 'is_synched'
    LOOP
        EXECUTE format(
            'CREATE TRIGGER is_synched_trigger BEFORE INSERT OR UPDATE ON %I FOR EACH ROW EXECUTE FUNCTION set_is_synched_flag();',
            tbl.table_name
        );
    END LOOP;
END;
$$;


;%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


