<?php

namespace App\src\Repositories;


use App\src\Models\Claim;
use App\src\Models\Organization;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClaimRepository
{
    protected $claim;

    /**
     * ClaimRepository constructor.
     * @param $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function findClaim($id): Claim
    {
        return $this->claim->find($id);
    }

    /**
     * @param $take - кол-во получаемых элементов
     * @param $skip - оффсет, пропустить элементы
     * @param $dispatchStatus - prepared, edited, dispatched, null
     * @return Claim[]|\Illuminate\Database\Eloquent\Collection
     * Получить все заявки
     */
    public function getAll($take, $skip, $dispatchStatus)
    {
        return $this->claim
            ->with('problem')
            ->with('address')
            ->take($take)
            ->skip($skip)
            ->whereIn('dispatch_status', $dispatchStatus)
            ->get();
    }

    /**
     * @param $claim
     * @return mixed
     * Создать заявление/жалобу
     */
    public function create($claim)
    {
        return $this->claim->create($claim);
    }

    public function update($claim): Claim
    {
        $claimToUpdate = $this->findClaim($claim['id']);

        $claimToUpdate->id = $claim['id'];
        $claimToUpdate->firstname = $claim['firstname'];
        $claimToUpdate->lastname = $claim['lastname'];
        $claimToUpdate->middlename = $claim['middlename'];
        $claimToUpdate->name = $claim['name'];
        $claimToUpdate->description = $claim['description'];
        $claimToUpdate->phone = $claim['phone'];
        $claimToUpdate->email = $claim['email'];
        $claimToUpdate->status = 'created';
        $claimToUpdate->dispatch_status = $claim['dispatch_status'];
        $claimToUpdate->save();

        return $claimToUpdate;
    }

    /**
     * @param Claim $claim
     * @param $organizationId - id организации
     * @return void
     */
    public function assignClaimToResponsibleOrganization(Claim $claim, $organizationId, $visibility)
    {
        $claim->organizations()->attach($organizationId, [
            'visibility' => $visibility,
            'created_at' => Carbon::now()
        ]);
    }

    public function reassignClaimToResponsibleOrganization(Claim $claim, $organizationId)
    {	
        $claim->organizations()->attach($organizationId);
    }

    /**
     * @param Claim $claim
     * @param $organizationId - id организации
     * @return void
     */
    public function detachClaimToResponsibleOrganization(Claim $claim, $organizationId)
    {
        $claim->organizations()->detach($organizationId);
    }

    public function getPagesCount($resolvedDispatchStatus)
    {
        return $this->claim
            ->whereIn('dispatch_status', $resolvedDispatchStatus)
            ->count();
    }

    public function search($take, $skip, $search, $resolvedDispatchStatus)
    {
        return $this->claim
            ->with('problem')
            ->with('address')
            ->take($take)
            ->skip($skip)
            ->where('description', 'like', '%'.$search.'%')
            ->whereIn('dispatch_status', $resolvedDispatchStatus)
            ->get();
    }

    public function updateStatus($id, $status): Claim
    {
        $claim = $this->findClaim($id);
        $claim->status = $status;
        $claim->save();
        return $claim;
    }
    
    /**
     * @param $phone
     * Получить все предыдущие, созданные заявки с определенным номером телефона
     * @return  - возвращает список заявок с одинаковым номером телефона
     */
    public function getByPhone($phone)
    {
        return $this->claim
            ->where('phone', $phone)
            ->with('problem')
            ->with('address')
            ->get();
    }

    /**
     * @param $pid
     * Получтить организацию, которая решает данную заявку
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
     */
    public function getOrganizationWhichResolvesClaim(int $claimId)
    {
        return DB::table('claims_organizations')
            ->where('claim_id', $claimId)
            ->first();
    }

    /**
     * @param $pid
     * @return mixed
     * Получить родительскую заявку
     */
    public function getParentClaim($pid)
    {
        return $this->claim->find($pid);
    }

}