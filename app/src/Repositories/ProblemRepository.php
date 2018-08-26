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

    public function delete($id)
    {
        return $this->problem->destroy($id);
    }

    public function getById($id)
    {
        return $this->problem->find($id);
    }

    public function update($problemData, $problemId)
    {
        $problemForEdit = $this->problem->find($problemId);
        $problemForEdit->name = $problemData['name'];
        $problemForEdit->description = $problemData['description'];
        $problemForEdit->save();

        return $problemForEdit;
    }

}