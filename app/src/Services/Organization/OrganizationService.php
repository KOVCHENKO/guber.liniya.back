<?php

namespace App\src\Services\Organization;

use App\src\Repositories\OrganizationRepository;
use App\src\Services\Claim\DispatchStatus\DispatchStatusProcessing;

class OrganizationService
{
    protected $organizationRepository;
    protected $dispatchStatusProcessing;
    protected $claimsPerPage = 10;

    public function __construct(OrganizationRepository $organizationRepository, 
        DispatchStatusProcessing $dispatchStatusProcessing)
    {
        $this->organizationRepository = $organizationRepository;
        $this->dispatchStatusProcessing = $dispatchStatusProcessing;
    }

    public function getClaimsToOrganization($id, $statusFilter, $search, $page, $sortByData)
    {  
        // Фильтр dispatch_status
        $allStatus = $this->resolveStatus();
        $statusFilter = $this->dispatchStatusProcessing->establishDispatchStatusFilter($allStatus, $statusFilter);
        
        $organizationIdArray = $this->organizationRepository->getChildrenOrganization($id);

        return $this->organizationRepository->getClaimsToOrganizations(
            $this->claimsPerPage,
            $page,
            $organizationIdArray,
            $statusFilter,
            $search,
            $sortByData
        );
    }

    public function resolveStatus()
    {
        return ['created', 'assigned', 'executed', 'rejected'];
    }

}