<?php

namespace App\src\Repositories;


use App\src\Models\Claim;

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
     * @return Claim[]|\Illuminate\Database\Eloquent\Collection
     * Получить все заявки
     */
    public function getAll()
    {
        return $this->claim
            ->with('problem')
            ->with('address')
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
    public function assignClaimToResponsibleOrganization(Claim $claim, $organizationId)
    {
        $claim->organizations()->attach($organizationId);
    }
}