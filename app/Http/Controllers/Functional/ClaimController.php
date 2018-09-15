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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        return response($this->claimService->search($request->page, $request->search, $request->dispatchStatus), 200);
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

    /**
     * Получить заявки со статусом выполнено
     */
    public function getExecutedClaims()
    {
        return response($this->claimService->getExecutedClaims(), 200);
    }

    /**
     * @param $id - ид заявки
     * @param $idOldOrganization - ид старой организации
     * @param $idNewOrganization - ид новой организации
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Изменение организации, выполняющей заявку
     */
    public function changeOrganization($id, $idOldOrganization, $idNewOrganization)
    {
        $this->claimService->changeOrganization($id, $idOldOrganization, $idNewOrganization);
        return response('success', 200);
    }

    /**
     * Изменение статуса проверки (закрытия заявки) - роль коммуникатора
     * @param $claimId - ид заявки
     * @param $closeStatus - статус закрытия (NOT_CALLED, NOT_EXECUTED, EXECUTED_PARTIALLY, EXECUTED_TOTALLY)
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function changeCloseStatus($claimId, $closeStatus)
    {
        return response($this->claimService->changeCloseStatus($claimId, $closeStatus), 200);
    }

    /**
     * @param $organizationId
     * @param $claimId
     * Переназначить другой организации заявку, от выполнения которой отказалась первая организация
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function reassignRejectedClaim($organizationId, $claimId)
    {
        return response($this->claimService->reassignRejectedClaim($organizationId, $claimId));
    }


}