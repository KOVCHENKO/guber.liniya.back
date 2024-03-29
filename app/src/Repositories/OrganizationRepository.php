<?php

namespace App\src\Repositories;


use App\src\Models\Organization;
use App\src\Models\Claim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class OrganizationRepository
{
    protected $organization;
    protected $claim;

    /**
     * OrganizationRepository constructor.
     * @param Organization $organization
     */
    public function __construct(Organization $organization, ClaimsByOrganizationRepository $claim)
    {
        $this->organization = $organization;
        $this->claim = $claim;
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
                $query->where('claims.firstname', 'like', '%'.$search.'%')
                    ->orWhere('claims.lastname', 'like', '%'.$search.'%')
                    ->orWhere('claims.middlename', 'like', '%'.$search.'%')
                    ->orWhere('claims.phone', 'like', '%'.$search.'%');
            });
        }

        return $query->orderBy('name')->get();
    }

    /**
     * @param $organizationId
     * @return array
     * Получить дочерние заявки организаций
     */
    public function getChildrenOrganization($organizationId) 
    {
        $organizationsId = DB::select(
            "SELECT GROUP_CONCAT(Level SEPARATOR ',') as idOrg FROM 
                    ( SELECT @Ids := ( SELECT GROUP_CONCAT(`ID` SEPARATOR ',') FROM
                     `organizations` WHERE FIND_IN_SET(`pid`, @Ids) ) Level FROM 
                     `organizations` JOIN (SELECT @Ids := ?) temp1 ) temp2", [$organizationId]);
        $arrayOrganizationsId = explode(',', $organizationsId[0]->idOrg);
        // $arrayOrganizationsId[] = $organizationId;
        return $arrayOrganizationsId;        
    }

    public function getClaimsToOrganization2($id, $data, $take, $page)
    {
        // Получить все заявки
        $this->claim->getAll($id);
        
        // Фильтры
        if (!empty($data['status'])) {
            $this->claim->byStatus($data['status']);
        }
        if (!empty($data['initials'])) {
            $this->claim->byInitials($data['initials']);
        }
        if (!empty($data['phone'])) {
            $this->claim->byPhone($data['phone']);
        }
        if (!empty($data['address'])) {
            $this->claim->byAddress($data['address']);
        }
        if (!empty($data['minDate']) || !empty($data['maxDate'])) {
            // TODO: 
            $minDate = !empty($data['minDate']) ? $data['minDate'] : null;
            $maxDate = !empty($data['maxDate']) ? $data['maxDate'] : null;
            $this->claim->byDate($minDate, $maxDate);
        }

        $this->claim->render();
        
        // Сортировка
        if (!empty($data['field']) && !empty($data['direction'])) {
            $this->claim->bySort($data['field'], $data['direction']);
        }

        return [ 
            'count' => $this->claim->countPage($take),
            'claims' => $this->claim->forPage($page, $take)
        ];

    }

    public function getClaimsToOrganizations($take, $page, $organizationIdArray, $dispatchStatusFilter, $search, $sortByData) 
    {

        $claims = collect();

        $query = $this->organization->whereIn('id', $organizationIdArray); // query - returns Builder

        $query->each(function ($item) use ($claims, $dispatchStatusFilter, $search) {

            $query = $item->claims()        // Берет заявки всех организаций
                ->join('claims_organizations as co', 'claims.id', '=', 'co.claim_id')
                ->with('address')
                ->with('responsibleOrganization')
                ->whereNotIn('status', ['rejected'])
                ->whereIn('status', $dispatchStatusFilter)
                ->where('co.visibility', '=', 'show');


            if ($search != '') {
                $query->where(function ($query) use ($search) {
                    $query->where('claims.firstname', 'like', '%'.$search.'%')
                        ->orWhere('claims.lastname', 'like', '%'.$search.'%')
                        ->orWhere('claims.middlename', 'like', '%'.$search.'%')
                        ->orWhere('claims.phone', 'like', '%'.$search.'%');
                });
            }

            $claims[] = $query
                ->orderBy('claims.name')
                ->get();

        });
        $claims = $claims->collapse();
        $claims = ($sortByData == 'desc') ?  $claims->sortByDesc('created_at') :  $claims->sortBy('created_at');
        $count = $claims->count();
        $claims = $claims->forPage($page, $take)->toArray();
        return [ 
            'count' => (int)ceil($count / $take),
            'claims' => array_values($claims)
        ];
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