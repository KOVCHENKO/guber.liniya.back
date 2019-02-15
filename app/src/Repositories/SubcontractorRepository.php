<?php

namespace App\src\Repositories;

use App\src\Models\Subcontractor;

class SubcontractorRepository
{

    protected $subcontractor;

    /**
     * SubcontractorRepository constructor.
     * @param $subcontractor
     */
    public function __construct(Subcontractor $subcontractor)
    {
        $this->subcontractor = $subcontractor;
    }

    public function getClaimsSubcontractors($organizationId)
    {

        $query = $this->subcontractor->where('organization_id', $organizationId)->get();
    
        $query->each(function ($item) {
            $item->claim = $item->claim()->with('responsibleOrganization')->get();
            return $item;
        });

        return $query;
    }

    public function updateSubcontractor($id)
    {
        $subcontractor = $this->subcontractor->find($id);

        $subcontractor->status = 'closed';
        $subcontractor->save();

        return $subcontractor;
    }

}
