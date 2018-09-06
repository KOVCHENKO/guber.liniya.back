<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\OrganizationRepository;
use App\src\Repositories\ProblemRepository;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    protected $problemRepository;
    protected $organizationRepository;

    /**
     * ProblemController constructor.
     * @param ProblemRepository $problemRepository
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(ProblemRepository $problemRepository, OrganizationRepository $organizationRepository)
    {
        $this->problemRepository = $problemRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Создать проблему
     */
    public function create(Request $request)
    {
        return response($this->problemRepository->create($request->all()), 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Удалить проблему по id
     */
    public function delete($id)
    {
        return response($this->problemRepository->delete($id), 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить проблему по ид
     */
    public function getById($id)
    {
        return response($this->problemRepository->getById($id), 200);
    }

    /**
     * @param Request $request
     * @param $problemId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Обновить проблему
     */
    public function update(Request $request, $problemId)
    {
        return response($this->problemRepository->update($request->all(), $problemId), 200);
    }

    /**
     * @param int $problemId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * Получить все организации, привязанные к определенной проблеме
     */
    public function getOrganizationsOfProblem(int $problemId)
    {
        return response($this->organizationRepository->getOrganizationsByProblem($problemId), 200);
    }

}