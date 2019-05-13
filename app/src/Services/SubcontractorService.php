<?php

namespace App\src\Services;

use App\src\Repositories\SubcontractorRepository;

class SubcontractorService
{
    protected $subcontractorRepository;

    /**
     * SubcontractorService constructor.
     * @param SubcontractorRepository $subcontractorRepository
     */
    public function __construct(SubcontractorRepository $subcontractorRepository)
    {
        $this->subcontractorRepository = $subcontractorRepository;
    }

    public function getClaimsSubcontractors($organizationId) 
    {
        return $this->subcontractorRepository->getClaimsSubcontractors($organizationId);
    }

    public function updateSubcontractor($id)
    {
        return $this->subcontractorRepository->updateSubcontractor($id);
    }

}
