<?php

namespace App\src\Repositories;


use App\src\Models\Organization;
use Illuminate\Support\Facades\DB;

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

    /**
     * @return Organization[]|\Illuminate\Database\Eloquent\Collection
     * ВСе организации
     */
    public function getAll()
    {
       return $this->organization->all();
    }

    /**
     * @param $organization
     * @return mixed
     * Создать организацию
     */
    public function create($organization): Organization
    {
        return $this->organization->create($organization);
    }

    /**
     * @param $organization
     * @param $id
     * @return mixed
     * Обновить организацию
     */
    public function update($organization, int $id): Organization
    {
        $organizationToUpdate = $this->organization->find($id);
        $organizationToUpdate->name = $organization['name'];
        $organizationToUpdate->description = $organization['description'];
        $organizationToUpdate->save();

        return $organizationToUpdate;
    }

    /**
     * @param $id
     * @return int
     * Удалить организацию
     */
    public function delete($id)
    {
        return $this->organization->destroy($id);
    }

    /**
     * @param int $id
     * @return Organization
     * Получить организацию по id
     */
    public function getById(int $id): Organization
    {
        return $this->organization->find($id);
    }

    /**
     * @param $organizationId
     * @return \Illuminate\Support\Collection
     * Получить все ид проблем, которые решает данная организация
     */
    public function getOrganizationProblemsIds($organizationId)
    {
        return DB::table('problems_organizations')
            ->select('problem_id')
            ->where('organization_id', '=', $organizationId)
            ->get();
    }

    /**
     * @param $problemId
     * @param $organizationId
     * Прикрепить проблему к организации
     * @return void
     */
    public function attachProblem($problemId, $organizationId)
    {
        $organization = $this->organization->getById($organizationId);
        $organization->problems()->attach($problemId);
    }

    /**
     * @param $problemId
     * @param $organizationId
     * @return mixed
     * Отвязать проблему от организации
     */
    public function detachProblem($problemId, $organizationId)
    {
        $organization = $this->organization->getById($organizationId);
        $organization->problems()->detach($problemId);
    }

    /**
     * @param $problemId
     * @return \Illuminate\Support\Collection
     * Получить организаци, которые могут выполнить данную проблему
     */
    public function getOrganizationsByProblem($problemId)
    {
        return DB::table('organizations')
            ->join('problems_organizations',
                'problems_organizations.organization_id', '=', 'organizations.id')
            ->where('problems_organizations.problem_id', '=', $problemId)
            ->get();
    }
}