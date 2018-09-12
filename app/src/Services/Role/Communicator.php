<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Communicator implements RoleTypeInterface
{

    public function getCabinets()
    {
        new Cabinet(1, 'Заявки', 'communicator_applications', 'communicator_applications');
    }
}