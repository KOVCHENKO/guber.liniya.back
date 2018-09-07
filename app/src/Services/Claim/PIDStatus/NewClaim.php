<?php

namespace App\src\Services\Claim\PIDStatus;


use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;
use App\src\Repositories\OrganizationRepository;
use Illuminate\Support\Collection;

class NewClaim implements PIDClaimInterface
{
    protected $claimRepository;
    protected $addressRepository;
    protected $organizationRepository;

    public function __construct(
        ClaimRepository $claimRepository,
        AddressRepository $addressRepository,
        OrganizationRepository $organizationRepository
    )
    {
        $this->claimRepository = $claimRepository;
        $this->addressRepository = $addressRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function createBasedOnCall($data)
    {
        $responsibleOrganizations =  $this->getOrganizationsRelatedToProblem($data['problem']['id']);

        $address = $this->addressRepository->create([
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        $responsibleOrganizations->map(function ($organization) use ($data, $address) {

            $newClaim = $this->claimRepository->create([
                'firstname' =>  $data['firstName'],
                'lastname' =>  $data['lastName'],
                'middlename' =>  $data['middleName'],
                'name' => $data['call']['callId'],
                'description' =>  $data['description'],
                'link' => $data['link'],
                'ats_status' => $data['call']['atsStatus'],
                'phone' => $data['phone'],
                'email' =>  $data['email'],
                'address_id' => $address['id'],
                'call_id' => $data['call']['id'],
                'problem_id' => $data['problem']['id'],
                'status' => 'created'
            ]);

            $this->claimRepository->assignClaimToResponsibleOrganization($newClaim, $organization->id, 'hide');
        });
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