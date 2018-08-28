<?php

namespace App\src\Services\Claim;


use App\src\Models\Claim;
use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;
use App\src\Repositories\OrganizationRepository;
use Illuminate\Support\Collection;

class ClaimService
{
    protected $claimRepository;
    protected $addressRepository;
    protected $organizationRepository;

    /**
     * ClaimService constructor.
     * @param ClaimRepository $claimRepository
     * @param AddressRepository $addressRepository
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(
        ClaimRepository $claimRepository,
        AddressRepository $addressRepository,
        OrganizationRepository $organizationRepository)
    {
        $this->claimRepository = $claimRepository;
        $this->addressRepository = $addressRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @param $data - array
     * Создает заявку на основе звонка
     */
    public function createBasedOnCall($data)
    {
        $data['address']['district'] = 'не заполнено';
        $data['address']['location'] = 'не заполнено';

        $address = $this->addressRepository->create([
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        $this->claimRepository->create([
            'firstname' => 'не заполнено',
            'lastname' => 'не заполнено',
            'middlename' => 'не заполнено',
            'name' => $data['callid'],
            'description' => 'не заполнено',
            'link' => $data['link'],
            'ats_status' => $data['status'],
            'phone' => $data['phone'],
            'email' => 'не заполнено',
            'address_id' => $address['id'],
            'status' => 'created'
        ]);

    }

    /**
     * @param $data
     * 1. Создать заявку (на основе уже существующей в БД из АТС Мегафон
     * 2. Распределить по организациям
     * @return mixed
     */
    public function createViaUpdating($data)
    {
        $newClaim = $this->saveClaim($data);
        $this->distributeByOrganizations($newClaim, $data);

        return $newClaim;
    }

    /**
     * @param $data
     * @return Claim - сохранение заявки на основе АТС Мегафон
     */
    private function saveClaim($data): Claim
    {
        $this->addressRepository->update([
            'address_id' => $data['address']['id'],
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        return $this->claimRepository->update([
            'id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'middlename' => $data['middleName'],
            'name' => $data['name'],
            'description' => $data['description'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'status' => 'created'
        ]);
    }

    /**
     * @param Claim $newClaim
     * @param $data
     * @return mixed
     * 1. Получить все ответственные организации
     * 2. Назначить выполнение заявки данным организациям
     */
    private function distributeByOrganizations(Claim $newClaim, $data)
    {
        $responsibleOrganizations =  $this->getOrganizationsRelatedToProblem($data['problem']['id']);

        $responsibleOrganizations->map(function ($organization) use ($newClaim) {
            $this->claimRepository->assignClaimToResponsibleOrganization($newClaim, $organization->id);
        });

        return true;
    }

    /**
     * @param $problemId
     * Получить организации, которые могут решить данный вид проблемы
     * @return \Illuminate\Support\Collection - организации по ид проблемы
     */
    private function getOrganizationsRelatedToProblem($problemId): Collection
    {
        return $this->organizationRepository->getOrganizationsByProblem($problemId);
    }


}