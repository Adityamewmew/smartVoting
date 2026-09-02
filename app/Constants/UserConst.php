<?php

namespace App\Constants;

class UserConst
{
    const PLATFORM_SUPERADMIN = 0; // Superadmin Platform (Kelola Semua Institusi)

    const SUPERADMIN = 1; // Admin Institusi / Sekolah

    const OPERATOR = 2; // Operator Bilik Suara

    const DEFAULT_PASSWORD = '$2y$12$2pV4WiD9nLczb381xpk20uGq4NnaVhUocp5aciksw5BhcgxkiKDh2';

    public static function getAccessTypes(): array
    {
        return [
            self::PLATFORM_SUPERADMIN => 'Superadmin Platform',
            self::SUPERADMIN => 'Administrator',
            self::OPERATOR => 'Operator',
        ];
    }

    public static function getAppAccessTypes(): array
    {
        return [
            self::SUPERADMIN => 'Administrator',
            self::OPERATOR => 'Operator',
        ];
    }
}
