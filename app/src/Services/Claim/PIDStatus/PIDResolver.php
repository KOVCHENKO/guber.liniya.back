<?php

namespace App\src\Services\Claim\PIDStatus;


class PIDResolver
{
    protected $childClaim;
    protected $newClaim;

    /**
     * PIDResolver constructor.
     * @param ChildClaim $childClaim
     * @param NewClaim $newClaim
     */
    public function __construct(ChildClaim $childClaim, NewClaim $newClaim)
    {
        $this->newClaim = $newClaim;
        $this->childClaim = $childClaim;
    }

    /**
     * @param $pid
     * @return ChildClaim|NewClaim
     * Возвращает класс - новая заявка или дочерняя заявка
     */
    public function resolvePidStatus($pid): PIDClaimInterface
    {
        if(!$pid) {
            return $this->newClaim;
        } else {
            return $this->childClaim;
        }
    }
}