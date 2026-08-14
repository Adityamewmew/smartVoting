<?php

namespace App\Constants;

class DatabaseConst
{
    const SQL_READ = 'mysql_read';

    public static function table(string $table): string
    {
        $prefix = self::DB_CORE();

        return $prefix ? $prefix.'.'.$table : $table;
    }

    public static function USER(): string
    {
        return self::table('users');
    }

    public static function SIDEBAR_MENU(): string
    {
        return self::table('sidebar_menus');
    }

    public static function ELECTIONS(): string
    {
        return self::table('elections');
    }

    public static function CANDIDATES(): string
    {
        return self::table('candidates');
    }

    public static function SIDEBAR_MENU_ACCESS(): string
    {
        return self::table('sidebar_menu_accesses');
    }

    public static function SIDEBAR_MENU_GROUP(): string
    {
        return self::table('sidebar_menu_groups');
    }

    public static function VOTING_SESSIONS(): string
    {
        return self::table('voting_sessions');
    }

    public static function VOTES(): string
    {
        return self::table('votes');
    }

    public static function DB_CORE(): string
    {
        if (config('database.default') === 'sqlite') {
            return '';
        }

        return config('database.connections.mysql.database', 'default');
    }
}
