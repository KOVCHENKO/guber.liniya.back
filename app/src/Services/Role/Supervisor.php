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
            new Cabinet(2, 'Заявки', 'supervisor_applications', '/supervisor_applications'),
            new Cabinet(3, 'Аналитика', 'analytics', '/analytics'),
        ];
    }
}
