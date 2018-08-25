<?php

namespace App\src\Repositories;


use App\src\Models\ProblemType;

class ProblemTypeRepository
{
    protected $problemType;

    /**
     * ProblemTypeRepository constructor.
     * @param $problemType
     */
    public function __construct(ProblemType $problemType)
    {
        $this->problemType = $problemType;
    }

    /**
     * @return ProblemType[]|\Illuminate\Database\Eloquent\Collection
     * Все типы проблем
     */
    public function getAll()
    {
        return $this->problemType
            ->with('children')
            ->get();
    }

    /**
     * @param $problemType
     * @return mixed
     * Создать тип проблемы
     */
    public function create($problemType)
    {
        return $this->problemType->create($problemType);
    }

    /**
     * @param $id - id типа проблемы
     * @return mixed
     * Получить тип проблемы по id
     */
    public function getById($id)
    {
        return $this->problemType
            ->with('problems')
            ->where('id', $id)
            ->first();
    }

    public function getAllWithProblems()
    {
        return $this->problemType
            ->with('children')
            ->get();
    }

    public function update($problemTypeId, $problemType)
    {
        $problemTypeForEdit = $this->problemType->find($problemTypeId);
        $problemTypeForEdit->name = $problemType['name'];
        $problemTypeForEdit->description = $problemType['description'];
        $problemTypeForEdit->save();

        return $problemTypeForEdit;
    }

    public function delete($id)
    {
        return $this->problemType->destroy($id);
    }


}