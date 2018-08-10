<?php

namespace App\src\Services\Problems;


use App\src\Repositories\OrganizationRepository;
use App\src\Repositories\ProblemTypeRepository;

class ProblemTypeService
{
    protected $problemTypeRepository;
    protected $organizationRepository;

    /**
     * ProblemTypeService constructor.
     * @param ProblemTypeRepository $problemTypeRepository
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(
        ProblemTypeRepository $problemTypeRepository,
        OrganizationRepository $organizationRepository
    )
    {
        $this->problemTypeRepository = $problemTypeRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @param $organizationId
     * @return array:
     * - 1. Проблемы
     * - 2. Ид проблем, решаемых организацией
     */
    public function getAllWithProblems($organizationId)
    {
        return [
            'problems' => $this->problemTypeRepository->getAllWithProblems(),
            'checked_problems_ids' => $this->organizationRepository
                ->getOrganizationProblemsIds($organizationId)
                ->pluck('problem_id')
        ];
    }
}