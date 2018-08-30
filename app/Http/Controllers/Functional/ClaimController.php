<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\ClaimRepository;
use App\src\Services\Claim\ClaimService;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    protected $claimRepository;
    protected $claimService;

    /**
     * ClaimController constructor.
     * @param ClaimRepository $claimRepository
     * @param ClaimService $claimService
     */
    public function __construct(ClaimRepository $claimRepository, ClaimService $claimService)
    {
        $this->claimRepository = $claimRepository;
        $this->claimService = $claimService;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить все заявки
     */
    public function getAll($page)
    {
        return response($this->claimService->getAll($page), 200);
    }

    /**
     * @param Request $request
     * Создание заявления/жалобы
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request) {
        return response($this->claimService->createViaUpdating($request->all()), 200);
    }

    /**
     * @param $page - страница
     * @param $search - строка поиска
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function search($page, $search)
    {
        return response($this->claimService->search($page, $search), 200);
    }



}