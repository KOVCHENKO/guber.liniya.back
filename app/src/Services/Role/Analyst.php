<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Analyst implements RoleTypeInterface
{
    public $type = 'analyst';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Аналитика', 'analytics', 'analytics')
        ];
    }
}