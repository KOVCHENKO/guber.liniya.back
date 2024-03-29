<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Dispatcher implements RoleTypeInterface
{
    public $type = 'dispatcher';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Звонки', 'calls', '/calls'),
            new Cabinet(2, 'Заявки', 'dispatcher_applications', '/dispatcher_applications'),
            new Cabinet(3, 'Аналитика', 'analytics', '/analytics'),
        ];
    }
}
