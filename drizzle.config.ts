import { defineConfig } from 'drizzle-kit';

export default defineConfig({
  out: './db-migrator-with-drizzle/drizzle',
  schema: './db-migrator-with-drizzle/src/db/schema.ts',
  dialect: 'mysql',
  dbCredentials: {
    host: process.env.DB_HOST || '127.0.0.1',
    port: Number(process.env.DB_PORT) || 3306,
    user: process.env.DB_USERNAME || 'root',
    password: process.env.DB_PASSWORD || '123123',
    database: process.env.DB_DATABASE || 'smartvote',
  },
  verbose: true,
});
