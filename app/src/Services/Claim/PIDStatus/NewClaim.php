<?php

namespace App\src\Services\Claim\PIDStatus;


use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;
use App\src\Repositories\OrganizationRepository;
use Illuminate\Support\Collection;

class NewClaim implements PIDClaimInterface
{
    protected $claimRepository;
    protected $organizationRepository;

    public function __construct(
        ClaimRepository $claimRepository,
        OrganizationRepository $organizationRepository
    )
    {
        $this->claimRepository = $claimRepository;
        $this->organizationRepository = $organizationRepository;
    }

    public function createBasedOnCall($data)
    {
        $responsibleOrganizations =  $this->getOrganizationsRelatedToProblem($data['problem']['id']);

        $responsibleOrganizations->map(function ($organization) use ($data) {

            $newClaim = $this->claimRepository->create([
                'name' => $data['call']['callId'],
                'description' =>  $data['description'],
                'link' => $data['call']['link'],
                'ats_status' => $data['call']['atsStatus'],
                'phone' => $data['phone'],
                'address_id' => $data['applicant']['address_id'],
                'call_id' => $data['call']['id'],
                'problem_id' => $data['problem']['id'],
                'level' => $data['level'],
                'status' => 'created',
                'dispatch_status' => $data['dispatchStatus'],
                'close_status' => 'raw'
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
