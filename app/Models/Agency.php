<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;

class Agency extends Tenant implements TenantWithDatabase
{
    use HasDatabase;

    protected $table = 'tenants'; // central tenants table

    protected $fillable = [
        'id',
        'agency_name',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'agency_name',
            'data'
        ];
    }
}
