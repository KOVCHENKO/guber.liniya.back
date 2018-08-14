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
        return $this->claim->all();
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