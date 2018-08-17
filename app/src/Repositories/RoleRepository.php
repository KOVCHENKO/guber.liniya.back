<?php

namespace App\src\Repositories;


use App\src\Models\Role;

class RoleRepository
{
    protected $role;

    /**
     * RoleRepository constructor.
     * @param $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @return Role
     * Получить роль специалиста
     */
    public function getSpecialistRole(): Role
    {
        return $this->role
            ->where('name', '=', 'specialist')
            ->first();
    }

    /**
     * @param $roleId
     * @return Role
     * Получить роль по ид
     */
    public function getById($roleId): Role
    {
        return $this->role->find($roleId);
    }


}