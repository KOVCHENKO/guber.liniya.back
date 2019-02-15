<?php

namespace App\src\Services\Role;


use App\src\Repositories\RoleRepository;
use App\src\Repositories\UserRepository;
use App\src\Services\Role\Entities\Cabinet;

class Specialist implements RoleTypeInterface
{
    public $type = 'specialist';
    protected $userRepository;
    protected $roleRepository;

    /**
     * Specialist constructor.
     * @param UserRepository $userRepository
     * @param RoleRepository $roleRepository
     */
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return array - Возврат - каибнеты специалиста
     */
    public function getCabinets()
    {
        return [
            new Cabinet(1, 'Заявки', 'specialist_applications', 'specialist_applications'),
            new Cabinet(2, 'Заявки организации', 'claims_of_children_organizations', 'claims_of_children_organizations'),
//            new Cabinet(3, 'Организации', 'specialist_organizations', 'specialist_organizations')
        ];
    }

    /**
     * @param $userData - данные специалиста (логин. пароль)
     * @param $organizationId - ид организации, к которой привязывать специалиста
     * 1. Получить роль специалиста
     * 2. Привязать роль к пользователю
     * 3. Создать специалиста
     * 4. Прикрепить специалиста к организации
     * @return mixed
     */
    public function add($userData, int $organizationId)
    {
        $specialistRole = $this->roleRepository->getSpecialistRole();

        $userData['role_id'] = $specialistRole->id;

        $newSpecialist = $this->userRepository->createSpecialist($userData);

        $this->userRepository->attachUserToOrganization($newSpecialist, $organizationId);

        return $newSpecialist;
    }

    /**
     * @param $organizationId
     * @return \Illuminate\Support\Collection - организации
     */
    public function getSpecialistsOfOrganization($organizationId)
    {
        return $this->userRepository->getSpecialistsOfOrganization($organizationId);
    }
}
