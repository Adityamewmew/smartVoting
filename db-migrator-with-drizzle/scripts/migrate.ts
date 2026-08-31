import 'dotenv/config';
import { migrate } from 'drizzle-orm/mysql2/migrator';
import db from '../src/db';
import mysql from 'mysql2/promise';

async function main() {
  console.log('Running drizzle migrations...');
  
  // Directly execute DDL if column doesn't exist
  const connection = await mysql.createConnection(process.env.DATABASE_URL || 'mysql://root:123123@127.0.0.1:3306/smartvote');
  
  try {
    const [columns] = await connection.query("SHOW COLUMNS FROM `users` LIKE 'google_id'") as any[];
    if (columns.length === 0) {
      console.log('Adding google_id column to users table...');
      await connection.query("ALTER TABLE `users` ADD COLUMN `google_id` varchar(255) NULL AFTER `password`");
      await connection.query("CREATE UNIQUE INDEX `users_google_id_unique` ON `users` (`google_id`)");
      console.log('Added google_id successfully.');
    } else {
      console.log('google_id already exists.');
    }

    const [avatarCols] = await connection.query("SHOW COLUMNS FROM `users` LIKE 'avatar'") as any[];
    if (avatarCols.length === 0) {
      console.log('Adding avatar column to users table...');
      await connection.query("ALTER TABLE `users` ADD COLUMN `avatar` text NULL AFTER `google_id`");
      console.log('Added avatar successfully.');
    } else {
      console.log('avatar already exists.');
    }
  } finally {
    await connection.end();
  }

  console.log('Migrations complete!');
}

main().catch(console.error);
