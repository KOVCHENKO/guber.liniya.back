<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Supervisor implements RoleTypeInterface
{
    public $type = 'supervisor';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Звонки', 'calls', '/calls'),
            new Cabinet(2, 'Заявки', 'dispatcher_applications', '/dispatcher_applications/all'),
        ];
    }
}