<?php

namespace App\src\Services\Common;


use App\src\Repositories\UserRepository;
use App\src\Services\Role\RoleResolver;
use App\src\Services\Role\RoleTypeInterface;

class CabinetsCollector
{
    protected $userRepository;
    protected $roleResolver;

    /**
     * CabinetsCollector constructor.
     * @param UserRepository $userRepository
     * @param RoleResolver $roleResolver
     */
    public function __construct(UserRepository $userRepository, RoleResolver $roleResolver)
    {
        $this->userRepository = $userRepository;
        $this->roleResolver = $roleResolver;
    }

    /**
     * @param $userId
     * @return mixed
     * Сначала resolve роль пользователя, затем получить роли
     */
    public function getCabinets($userId)
    {
        $roleType = $this->resolveUserRole($userId);
        return $roleType->getCabinets();
    }

    /**
     * @param $userId
     * @return \Exception|mixed
     * Resolve роль - customer, specialist, admin...
     */
    private function resolveUserRole(int $userId): RoleTypeInterface
    {
        $userRole = $this->userRepository->getUserRole($userId);
        return $this->roleResolver->resolveRole($userRole->name);

    }
}