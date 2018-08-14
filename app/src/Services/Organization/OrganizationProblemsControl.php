<?php

namespace App\src\Services\Organization;


use App\src\Repositories\OrganizationRepository;
use Exception;

class OrganizationProblemsControl
{
    protected $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @param $organizationId
     * @param $problemId
     * @param $status
     * @return Exception|void
     */
    public function bindProblemTypeToOrganization($organizationId, $problemId, $status)
    {
        switch ($status) {
            case 'true':
                $this->organizationRepository->attachProblem($problemId, $organizationId);
                break;
            case 'false':
                $this->organizationRepository->detachProblem($problemId, $organizationId);
                break;
        }

        new Exception('wrong status has been given');
    }



}