<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Editor implements RoleTypeInterface
{
    public $type = 'editor';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Заявки', 'dispatcher_applications', '/dispatcher_applications/prepared'),
        ];
    }
}