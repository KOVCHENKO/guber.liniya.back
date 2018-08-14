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
    public function getAll()
    {
        return response($this->claimRepository->getAll(), 200);
    }

    /**
     * @param Request $request
     * Создание заявления/жалобы
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request) {
        return response($this->claimService->create($request->all()), 200);
    }



}