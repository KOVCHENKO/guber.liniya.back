<?php

namespace App\src\Services\Organization;

use App\src\Repositories\OrganizationRepository;
use App\src\Services\Claim\DispatchStatus\DispatchStatusProcessing;

class OrganizationService
{
    protected $organizationRepository;
    protected $dispatchStatusProcessing;

    public function __construct(OrganizationRepository $organizationRepository, 
        DispatchStatusProcessing $dispatchStatusProcessing)
    {
        $this->organizationRepository = $organizationRepository;
        $this->dispatchStatusProcessing = $dispatchStatusProcessing;
    }

    public function getClaimsToOrganization($id, $dispatchStatusFilter, $search)
    {
        // Фильтр dispatch_status
        $allDispatchStatus = $this->dispatchStatusProcessing->resolveDispatchStatus('all');
        $dispatchStatusFilter = $this->dispatchStatusProcessing->establishDispatchStatusFilter($allDispatchStatus, $dispatchStatusFilter);
        
        $organizationIdArray = $this->organizationRepository->getChildrenOrganization($id);

        return $this->organizationRepository->getClaimsToOrganizations($organizationIdArray, $dispatchStatusFilter, $search);
        // return $this->organizationRepository->getClaimsToOrganization($id, $dispatchStatusFilter, $search);
    }

}