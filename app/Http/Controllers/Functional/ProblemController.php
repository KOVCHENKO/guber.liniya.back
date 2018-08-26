<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\ProblemRepository;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    protected $problemRepository;

    /**
     * ProblemController constructor.
     * @param ProblemRepository $problemRepository
     */
    public function __construct(ProblemRepository $problemRepository)
    {
        $this->problemRepository = $problemRepository;
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

    public function update(Request $request, $problemId)
    {
        return response($this->problemRepository->update($request->all(), $problemId), 200);
    }

}