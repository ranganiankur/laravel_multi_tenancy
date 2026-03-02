<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = null; // dynamically set thase

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Check tenant context
        if (tenant()) {
            $this->setConnection('tenant');
        } else {
            $this->setConnection('central');
        }
    }
}
