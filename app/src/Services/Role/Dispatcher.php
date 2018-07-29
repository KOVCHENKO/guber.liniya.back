<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Dispatcher implements RoleTypeInterface
{
    public $type = 'dispatcher';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Заявки', '/public/images/cabinets/40.png', 'dispatcher_applications')
        ];
    }
}