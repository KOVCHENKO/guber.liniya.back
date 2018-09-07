<?php

namespace App\src\Services\Claim\PIDStatus;


interface PIDClaimInterface
{
    public function createBasedOnCall($claim);
}