<?php

namespace App\Http\Controllers\Functional;


use App\src\Services\CallService;
use Illuminate\Http\Request;

class CallController
{
    protected $callService;

    /**
     * CallController constructor.
     * @param $callService
     */
    public function __construct(CallService $callService)
    {
        $this->callService = $callService;
    }


    public function getCall(Request $request)
    {
        $this->callService->getCall($request->all());
    }
}