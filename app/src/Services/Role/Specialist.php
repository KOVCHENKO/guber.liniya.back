<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Specialist implements RoleTypeInterface
{
    public $type = 'specialist';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Заявки', 'applications', 'specialist_applications'),
            new Cabinet(2, 'Организации', 'organizations', 'specialist_organizations')
        ];
    }
}