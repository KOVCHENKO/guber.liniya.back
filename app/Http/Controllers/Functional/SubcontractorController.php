<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Services\SubcontractorService;
use Illuminate\Http\Request;

class SubcontractorController extends Controller
{
    protected $subcontractorService;

    /**
     * SubcontractorController constructor.
     * @param SubcontractorService $subcontractorService
     */
    public function __construct(SubcontractorService $subcontractorService)
    {
        $this->subcontractorService = $subcontractorService;
    }

    public function getClaimsSubcontractors($organizationId)
    {
        return response($this->subcontractorService->getClaimsSubcontractors($organizationId));
    }

    public function updateSubcontractor($id)
    {
        return response($this->subcontractorService->updateSubcontractor($id));
    }

}