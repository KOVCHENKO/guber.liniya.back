<?php

namespace App\src\Repositories;


use App\src\Models\Organization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
        $organization = $this->getById($organizationId);
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
        $organization = $this->getById($organizationId);
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
            ->select('organizations.*')
            ->get();
    }

    /**
     * @param $organizationId
     * @return \Illuminate\Support\Collection
     * Получить заявки организации
     */
    public function getClaimsToOrganization($organizationId, $dispatchStatusFilter, $search)
    {
        $query = $this->getById($organizationId)->claims()
            ->with('address')
            ->whereNotIn('status', ['rejected'])
            ->whereIn('dispatch_status', $dispatchStatusFilter);

        if ($search != '') {
            $query->where(function ($query) use ($search) {
                $query->where('claims.created_at', 'like', '%'.$search.'%')
                    ->orWhere('claims.firstname', 'like', '%'.$search.'%')
                    ->orWhere('claims.lastname', 'like', '%'.$search.'%')
                    ->orWhere('claims.middlename', 'like', '%'.$search.'%')
                    ->orWhere('claims.phone', 'like', '%'.$search.'%');
            });
        }

        return $query->orderBy('name')->get();
    }

    public function getChildrenOrganization($organizationId) 
    {
        $organizationsId = DB::select("SELECT GROUP_CONCAT(Level SEPARATOR ',') as idOrg FROM ( SELECT @Ids := ( SELECT GROUP_CONCAT(`ID` SEPARATOR ',') FROM `organizations` WHERE FIND_IN_SET(`pid`, @Ids) ) Level FROM `organizations` JOIN (SELECT @Ids := ?) temp1 ) temp2", [$organizationId]);
        $arrayOrganizationsId = explode(',', $organizationsId[0]->idOrg);
        $arrayOrganizationsId[] = $organizationId;
        return $arrayOrganizationsId;        
    }

    public function getClaimsToOrganizations($organizationIdArray, $dispatchStatusFilter, $search) 
    {

        $claims = collect();

        $query = $this->organization->whereIn('id', $organizationIdArray);
        $query->each(function ($item, $key) use ($claims, $dispatchStatusFilter, $search) {

            $query = $item->claims()->with('address')
                ->whereNotIn('status', ['rejected'])
                ->whereIn('dispatch_status', $dispatchStatusFilter);

            if ($search != '') {
                $query->where(function ($query) use ($search) {
                    $query->where('claims.created_at', 'like', '%'.$search.'%')
                        ->orWhere('claims.firstname', 'like', '%'.$search.'%')
                        ->orWhere('claims.lastname', 'like', '%'.$search.'%')
                        ->orWhere('claims.middlename', 'like', '%'.$search.'%')
                        ->orWhere('claims.phone', 'like', '%'.$search.'%');
                });
            }

            $claims[] = $query->orderBy('name')->get();

        });

        return $claims->collapse();
    }

    /**
     * @param $organizationId
     * @return \Illuminate\Database\Eloquent\Collection
     * Получить дочерние организации
     */
    public function getChildOrganization($organizationId)
    {
        return $this->getById($organizationId)->children()->get();
    }

    /**
     * Сменить видимость
     * @param int $claimId
     */
    public function changeClaimVisibilityForOrganization(int $claimId) {
        DB::table('claims_organizations')
            ->where('claim_id', $claimId)
            ->update(['visibility' => 'show']);
    }
}