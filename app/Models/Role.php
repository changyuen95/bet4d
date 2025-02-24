<?php

namespace App\Models;
use Spatie\Permission\Models\Role as Base;

class Role extends Base
{
    protected $table = 'roles';

    const NORMAL_USER = 'normal_user';
    const MEMBER = 'member';
    const OPERATOR = 'operator';
    const HQ = 'hq';
    const SUPER_ADMIN = 'super_admin';
    protected $appends = [
        "editable","deletable"
    ];

    const ADMIN_GUARD = "admin-api";

    const DEFAULT_ROLES = [
        self::NORMAL_USER,
        self::MEMBER,
        self::OPERATOR,
        self::SUPER_ADMIN,
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
