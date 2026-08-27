CREATE TABLE IF NOT EXISTS `institutions` (
	`id` bigint unsigned AUTO_INCREMENT NOT NULL,
	`name` varchar(255) NOT NULL,
	`type` enum('school','university','organization') NOT NULL DEFAULT 'school',
	`status` enum('active','inactive') NOT NULL DEFAULT 'active',
	`created_at` timestamp DEFAULT (now()),
	`updated_at` timestamp ON UPDATE CURRENT_TIMESTAMP,
	`deleted_at` timestamp,
	CONSTRAINT `institutions_id` PRIMARY KEY(`id`)
);

INSERT INTO `institutions` (`id`, `name`, `type`, `status`, `created_at`, `updated_at`)
VALUES (1, 'SMK Negeri 1 Demo', 'school', 'active', NOW(), NOW())
ON DUPLICATE KEY UPDATE `id`=`id`;

ALTER TABLE `users` ADD COLUMN `institution_id` bigint unsigned;
ALTER TABLE `elections` ADD COLUMN `institution_id` bigint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `candidates` ADD COLUMN `institution_id` bigint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `voting_sessions` ADD COLUMN `institution_id` bigint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `votes` ADD COLUMN `institution_id` bigint unsigned NOT NULL DEFAULT 1;

UPDATE `users` SET `institution_id` = 1 WHERE `institution_id` IS NULL;
UPDATE `elections` SET `institution_id` = 1 WHERE `institution_id` = 0;
UPDATE `candidates` SET `institution_id` = 1 WHERE `institution_id` = 0;
UPDATE `voting_sessions` SET `institution_id` = 1 WHERE `institution_id` = 0;
UPDATE `votes` SET `institution_id` = 1 WHERE `institution_id` = 0;

CREATE INDEX `users_institution_id_index` ON `users` (`institution_id`);
CREATE INDEX `elections_institution_id_index` ON `elections` (`institution_id`);
CREATE INDEX `candidates_institution_id_index` ON `candidates` (`institution_id`);
CREATE INDEX `voting_sessions_institution_id_index` ON `voting_sessions` (`institution_id`);
CREATE INDEX `votes_institution_id_index` ON `votes` (`institution_id`);
