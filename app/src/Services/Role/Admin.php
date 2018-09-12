<?php

namespace App\src\Services\Role;


use App\src\Services\Role\Entities\Cabinet;

class Admin implements RoleTypeInterface
{
    public $type = 'admin';

    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Организация', 'all_organizations', 'all_organizations'),
            new Cabinet(2, 'Проблемы', 'all_problems', 'all_problems'),
        ];
    }
}