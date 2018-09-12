<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Communicator implements RoleTypeInterface
{
    public $type = 'communicator';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Заявки', 'applications', 'communicator_applications')
        ];
    }
}