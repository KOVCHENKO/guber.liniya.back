<?php

namespace App\src\Repositories;


use App\src\Models\Claim;
use Carbon\Carbon;

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
        $claimToUpdate = $this->claim->find($claim['id']);

        $claimToUpdate->id = $claim['id'];
        $claimToUpdate->firstname = $claim['firstname'];
        $claimToUpdate->lastname = $claim['lastname'];
        $claimToUpdate->middlename = $claim['middlename'];
        $claimToUpdate->name = $claim['name'];
        $claimToUpdate->description = $claim['description'];
        $claimToUpdate->phone = $claim['phone'];
        $claimToUpdate->email = $claim['email'];
        $claimToUpdate->status = 'created';
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

}