#== 20221229230807 ProcessCreate: migrating
START TRANSACTION;
CREATE TABLE `processes` (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `created` DATETIME NULL,
    `modified` DATETIME NULL,
    `start_date` DATETIME NULL,
    `duration` INT(11) NULL COMMENT 'in minutes',
    `sequence` INT(11) NULL COMMENT 'order inside job',
    `department_priority` INT(11) NULL COMMENT 'order of priority inside department',
    `complete` TINYINT(4) NULL DEFAULT 0,
    `name` CHAR(255) NULL,
    PRIMARY KEY (`id`)) ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
COMMIT;
INSERT INTO `phinxlog`
    (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`)
    VALUES ('20221229230807', 'ProcessCreate', '2022-12-30 02:53:34', '2022-12-30 02:53:34', 0);

#== 20221229230807 ProcessCreate: migrated 0.1263s

#== 20221229234538 JobCreate: migrating
START TRANSACTION;
CREATE TABLE `jobs` (
    `id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
    `created` DATETIME NULL,
    `modified` DATETIME NULL,
    `due_date` DATETIME NULL,
    `job_number` CHAR(255) NULL,
    PRIMARY KEY (`id`))
    ENGINE = InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
COMMIT;
INSERT INTO `phinxlog`
    (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`)
    VALUES ('20221229234538', 'JobCreate', '2022-12-30 02:53:34', '2022-12-30 02:53:34', 0);

#== 20221229234538 JobCreate: migrated 0.1071s

#== 20221229234800 LinkJobToProcess: migrating
START TRANSACTION;
ALTER TABLE `processes`
    ADD `job_id` INT(11) NULL;

ALTER TABLE `processes`
    ADD  FOREIGN KEY (`job_id`)
        REFERENCES `jobs` (`id`)
        ON DELETE SET NULL
        ON UPDATE NO ACTION;

#SQLSTATE[HY000]: General error: 1005
# Can't create table `gpschedule`.`processes`
# (errno: 150 "Foreign key constraint is incorrectly formed")

COMMIT;
