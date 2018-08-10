<?php

namespace App\src\Repositories;


use App\src\Models\Problem;

class ProblemRepository
{
    protected $problem;

    /**
     * ProblemRepository constructor.
     */
    public function __construct(Problem $problem)
    {
        $this->problem = $problem;
    }

    public function create($problem)
    {
        return $this->problem->create($problem);
    }
}