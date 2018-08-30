<?php

namespace App\src\Services;


use App\src\Services\Claim\ClaimService;
use Illuminate\Support\Facades\Log;

class CallService
{
    protected $claimService;

    /**
     * CallService constructor.
     * @param ClaimService $claimService
     */
    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }

    public function getAllCalls()
    {
        // Получить все звонки
    }

    public function getCall($call)
    {
        if ($call['cmd'] == 'history') {
            $this->makeClaim($call);

            Log::channel('daily')->info(serialize($call));
        }


    }

    private function makeClaim($call)
    {
        $this->claimService->createBasedOnCall($call);
    }


}