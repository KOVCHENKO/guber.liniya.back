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

    public function getClaimsToOrganization($id, $statusFilter, $search)
    {
        // Фильтр dispatch_status
        $allStatus = $this->resolveStatus();
        $statusFilter = $this->dispatchStatusProcessing->establishDispatchStatusFilter($allStatus, $statusFilter);
        
        $organizationIdArray = $this->organizationRepository->getChildrenOrganization($id);

        return $this->organizationRepository->getClaimsToOrganizations($organizationIdArray, $statusFilter, $search);
    }

    public function resolveStatus()
    {
        return ['created', 'assigned', 'executed', 'rejected'];
    }

}