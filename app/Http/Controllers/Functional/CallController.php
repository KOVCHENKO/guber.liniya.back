<?php

namespace App\Http\Controllers\Functional;


use App\src\Services\Call\CallService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    /**
     * @param Request $request
     * Получить звонок с облачной АТС для внесения в локальную БД
     */
    public function receive(Request $request)
    {
        Log::channel('daily')->info(serialize($request->all()));
        $this->callService->receive($request->all());
    }

    /**
     * Получтиь все звонки из БД
     * @param $page
     * @return \App\src\Models\Call[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($page)
    {
        return $this->callService->all($page);
    }

    public function markCallAsFaulty($callId)
    {
        return $this->callService->markCallAsFaulty($callId);
    }
}