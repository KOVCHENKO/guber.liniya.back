<?php

namespace App\src\Repositories;


use App\src\Models\Organization;

class OrganizationRepository
{
    protected $organization;

    /**
     * OrganizationRepository constructor.
     * @param Organization $organization
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getAll()
    {
       return $this->organization->all();
    }

    public function create($data)
    {

    }

    public function update($data)
    {

    }

    public function delete($id)
    {

    }
}