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

    /**
     * @return ProblemType[]|\Illuminate\Database\Eloquent\Collection
     * Все типы проблем
     */
    public function getAll()
    {
        return $this->problem->all();
    }

    /**
     * @param $problemType
     * @return mixed
     * Создать тип проблемы
     */
    public function create($problemType)
    {
        return $this->problem->create($problemType);
    }

    /**
     * @param $id - id типа проблемы
     * @return mixed
     * Получить тип проблемы по id
     */
    public function getById($id)
    {
        return $this->problem->find($id);
    }


}