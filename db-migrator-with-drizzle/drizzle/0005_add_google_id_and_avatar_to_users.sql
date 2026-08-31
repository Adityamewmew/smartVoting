ALTER TABLE `users` ADD COLUMN `google_id` varchar(255) NULL AFTER `password`;
ALTER TABLE `users` ADD COLUMN `avatar` text NULL AFTER `google_id`;
CREATE UNIQUE INDEX `users_google_id_unique` ON `users` (`google_id`);
