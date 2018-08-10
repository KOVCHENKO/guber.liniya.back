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

}