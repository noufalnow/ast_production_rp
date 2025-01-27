Clean Database

############# PGSql


DROP TABLE "employees";
DROP TABLE "mis_property_building";
DROP TABLE "mis_tenants_temp";
DROP TABLE "mis_tenants_x";
DROP TABLE "temp_files";
DROP TABLE "users";



TRUNCATE "mis_notification";

############# MySql 

SET foreign_key_checks = 0;
DROP TABLE `employees`, `mis_property_building`, `mis_tenants_temp`, `mis_tenants_x`, `temp_files`, `users`;


