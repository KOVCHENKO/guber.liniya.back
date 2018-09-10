<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Services\Claim\ClaimService;
use Illuminate\Http\Request;
use App\src\Repositories\ClaimRepository;

class ClaimController extends Controller
{
    protected $claimService;
    protected $claimRepository;

    /**
     * ClaimController constructor.
     * @param ClaimService $claimService
     */
    public function __construct(ClaimService $claimService, ClaimRepository $claimRepository)
    {
        $this->claimService = $claimService;
        $this->claimRepository = $claimRepository;
    }

    /**
     * @param $page - страница
     * @param $dispatchStatus - все, отредактированные, для отправки
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить все заявки
     */
    public function getAll($page, $dispatchStatus)
    {
        return response($this->claimService->getAll($page, $dispatchStatus), 200);
    }

    /**
     * @param Request $request
     * Создание заявления/жалобы
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        return response($this->claimService->createBasedOnCall($request->all()), 200);
    }

    public function update(Request $request, $dispatchStatusToUpdate)
    {
        return response($this->claimService->update($request->all(), $dispatchStatusToUpdate), 200);
    }

    /**
     * @param $page - страница
     * @param $search - строка поиска
     * @param $dispatchStatus - все, отредактированные, для отправки
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function search($page, $search, $dispatchStatus)
    {
        return response($this->claimService->search($page, $search, $dispatchStatus), 200);
    }

    /**
     * @param $id - айди заявки
     * @param $status - статус заявки
     * Изменение статуса заявки (created/assigned/executed/rejected) 
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateStatus($id, $status) 
    {
        return response($this->claimRepository->updateStatus($id, $status), 200);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить предыдущие заявки по номеру телефона
     */
    public function getPreviousByPhone(Request $request)
    {
        return response($this->claimService->getPreviousByPhone($request->phone), 200);
    }

    //TODO: return value
    public function changeOrganization($id, $idOldOrganization, $idNewOrganization)
    {
        $this->claimService->changeOrganization($id, $idOldOrganization, $idNewOrganization);
        return response('success', 200);
    } 

}