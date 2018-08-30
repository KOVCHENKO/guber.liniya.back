<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Supervisor implements RoleTypeInterface
{
    public $type = 'supervisor';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Заявки', 'applications', '/dispatcher_applications/edited'),
        ];
    }
}