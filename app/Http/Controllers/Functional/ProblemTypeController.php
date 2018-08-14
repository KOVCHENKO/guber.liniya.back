<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\ProblemTypeRepository;
use App\src\Services\Problems\ProblemTypeService;
use Illuminate\Http\Request;

class ProblemTypeController extends Controller
{
    protected $problemTypeRepository;
    protected $problemTypeService;

    /**
     * ProblemTypeController constructor.
     * @param ProblemTypeRepository $problemTypeRepository
     * @param ProblemTypeService $problemTypeService
     */
    public function __construct(
        ProblemTypeRepository $problemTypeRepository,
        ProblemTypeService $problemTypeService
    )
    {
        $this->problemTypeRepository = $problemTypeRepository;
        $this->problemTypeService = $problemTypeService;
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить все типы проблем
     */
    public function getAll()
    {
        return response($this->problemTypeRepository->getAll(), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Создать тип проблемы
     */
    public function create(Request $request)
    {
        return response($this->problemTypeRepository->create($request->all()), 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить тип проблемы по id
     */
    public function getById($id)
    {
        return response($this->problemTypeRepository->getById($id), 200);
    }


    public function getAllWithQuestions($organizationId)
    {
        return response($this->problemTypeService->getAllWithProblems($organizationId), 200);
    }
}