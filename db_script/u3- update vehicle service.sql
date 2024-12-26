# catch the extra table added for revenue split up and this update to migration


ALTER TABLE "mis_vhl_service"
ADD "srv_working_time" numeric(5,2) NULL,
ADD "srv_reading_type" smallint NULL,
ADD "srv_reading_next_type" smallint NULL;
COMMENT ON TABLE "mis_vhl_service" IS '';
