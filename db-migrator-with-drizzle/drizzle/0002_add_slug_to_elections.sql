ALTER TABLE `elections` ADD `slug` varchar(255);
CREATE UNIQUE INDEX `elections_slug_unique` ON `elections` (`slug`);