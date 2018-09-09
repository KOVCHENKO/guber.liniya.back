<?php

namespace App\src\Services\Claim\PIDStatus;


use App\src\Repositories\ClaimRepository;

class PIDResolver
{
    protected $childClaim;
    protected $newClaim;

    protected $claimRepository;

    /**
     * PIDResolver constructor.
     * @param ChildClaim $childClaim
     * @param NewClaim $newClaim
     * @param ClaimRepository $claimRepository
     */
    public function __construct(ChildClaim $childClaim, NewClaim $newClaim,
                                ClaimRepository $claimRepository)
    {
        $this->newClaim = $newClaim;
        $this->childClaim = $childClaim;

        $this->claimRepository = $claimRepository;
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



    /**
     * Получить все родительские заявки
     * @param $claim
     * @return mixed
     */
    public function getParentClaims($claim)
    {
        $parents = array();

        $this->getParentClaim($claim['pid'], $parents);
        $claim['parents'] = $parents;

        return $claim;
    }

    /**
     * @param $claimPid
     * @param $parents
     * Зациклить получение родительских заявок
     */
    private function getParentClaim($claimPid, &$parents)
    {
        if ($claimPid) {
            $parentClaim = $this->claimRepository->getParentClaim($claimPid);
            array_push($parents, $parentClaim);

            $this->getParentClaim($parentClaim->pid, $parents);
        }
    }
}