<?php

namespace App\Usecase;

use Illuminate\Support\Facades\Auth;

abstract class Usecase
{
    public string $className;

    /**
     * Get the current active tenant (institution) ID.
     */
    protected function tenantId(?int $explicitTenantId = null): int
    {
        if ($explicitTenantId !== null) {
            return $explicitTenantId;
        }

        if (app()->bound('current_tenant')) {
            $tenant = app('current_tenant');

            return is_object($tenant) ? $tenant->id : (int) $tenant;
        }

        $user = Auth::user();
        if ($user && ! empty($user->institution_id)) {
            return (int) $user->institution_id;
        }

        return 1;
    }
}
