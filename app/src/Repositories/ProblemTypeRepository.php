<?php

namespace App\src\Repositories;


use App\src\Models\ProblemType;

class ProblemTypeRepository
{
    protected $problem;

    /**
     * ProblemTypeRepository constructor.
     * @param $problem
     */
    public function __construct(ProblemType $problem)
    {
        $this->problem = $problem;
    }

    public function getAll()
    {
        return $this->problem->all();
    }


}