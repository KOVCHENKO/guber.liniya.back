<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\OrganizationRepository;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    protected $organizationRepository;

    /**
     * OrganizationController constructor.
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function getAll()
    {
        return response($this->organizationRepository->getAll(), 200);
    }

    public function create(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function delete($id)
    {

    }

}