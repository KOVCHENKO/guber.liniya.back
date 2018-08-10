<?php

namespace App\src\Services\Organization;


use App\src\Repositories\OrganizationRepository;

class OrganizationProblemsControl
{
    protected $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function bindProblemTypeToOrganization($organizationId, $problemId, $status)
    {
        switch ($status) {
            case 'true':
                return $this->organizationRepository->attachProblem($problemId, $organizationId);
                break;
            case 'false':
                return $this->organizationRepository->detachProblem($problemId, $organizationId);
                break;
        }

        return new \Exception('wrong status has been given');
    }



}