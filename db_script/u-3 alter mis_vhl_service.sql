ALTER TABLE "mis_vhl_service"
ALTER "srv_type" TYPE smallint,
ALTER "srv_type" SET DEFAULT '1',
ALTER "srv_type" DROP NOT NULL,
ALTER "srv_nxt_type" TYPE smallint,
ALTER "srv_nxt_type" SET DEFAULT '1',
ALTER "srv_nxt_type" DROP NOT NULL,
ALTER "srv_reading_next" TYPE text,
ALTER "srv_reading_next" DROP DEFAULT,
ALTER "srv_reading_next" DROP NOT NULL,
ALTER "srv_date_next" TYPE date,
ALTER "srv_date_next" DROP DEFAULT,
ALTER "srv_date_next" DROP NOT NULL,
ALTER "srv_wash" TYPE smallint,
ALTER "srv_wash" SET DEFAULT '1',
ALTER "srv_wash" DROP NOT NULL,
ALTER "srv_greese" TYPE smallint,
ALTER "srv_greese" SET DEFAULT '1',
ALTER "srv_greese" DROP NOT NULL,
ADD "srv_category" smallint NOT NULL DEFAULT '1';
COMMENT ON COLUMN "mis_vhl_service"."srv_type" IS 'relavent only when srv_category = 1';
COMMENT ON COLUMN "mis_vhl_service"."srv_nxt_type" IS 'relavent only when srv_category = 1';
COMMENT ON COLUMN "mis_vhl_service"."srv_reading_next" IS '';
COMMENT ON COLUMN "mis_vhl_service"."srv_date_next" IS '';
COMMENT ON COLUMN "mis_vhl_service"."srv_wash" IS '';
COMMENT ON COLUMN "mis_vhl_service"."srv_greese" IS '';
COMMENT ON COLUMN "mis_vhl_service"."srv_category" IS '1=> Service, 2=> Accident';
COMMENT ON TABLE "mis_vhl_service" IS '';

##########################################
ALTER TABLE "mis_vhl_service"
ALTER "srv_reading" TYPE text,
ALTER "srv_reading" DROP DEFAULT,
ALTER "srv_reading" DROP NOT NULL;
COMMENT ON COLUMN "mis_vhl_service"."srv_reading" IS '';
COMMENT ON TABLE "mis_vhl_service" IS '';
