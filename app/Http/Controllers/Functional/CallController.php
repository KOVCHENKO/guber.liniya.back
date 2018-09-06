<?php

namespace App\Http\Controllers\Functional;


use App\src\Services\Call\CallService;
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

    /**
     * @param Request $request
     * Получить звонок с облачной АТС для внесения в локальную БД
     */
    public function receive(Request $request)
    {
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
}