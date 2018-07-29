<?php

namespace App\src\Services\Role;


use Exception;
use Illuminate\Support\Collection;

class RoleResolver
{
    public $rolesCollection;

    public function __construct(
        Collection $rolesCollection,

        Admin $admin,
        Specialist $specialist,
        Analyst $analyst,
        Dispatcher $dispatcher
    )
    {
        $rolesCollection = collect([$admin, $specialist, $analyst, $dispatcher]);
        $this->rolesCollection = $rolesCollection;
    }

    /**
     * @param $type - тип роли (admin, specialist, analyst, dispatcher...)
     * @return Exception|mixed
     */
    public function resolveRole($type)
    {
        foreach ($this->rolesCollection as $singleRole)
        {
            if($singleRole->type == $type)
            {
                return $singleRole;
            }
        }

        return new Exception('There is no such role');
    }
}