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
     * @param $data
     * 1. Создать заявку
     * 2. Распределить по организациям
     * @return mixed
     */
    public function create($data)
    {
        $newClaim = $this->saveClaim($data);
        $this->distributeByOrganizations($newClaim, $data);

        return $newClaim;
    }

    /**
     * @param $data
     * @return Claim - новая заявка
     */
    private function saveClaim($data): Claim
    {
        $address = $this->addressRepository->create([
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        return $this->claimRepository->create([
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'middlename' => $data['middleName'],
            'name' => $data['name'],
            'description' => $data['description'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address_id' => $address['id'],
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