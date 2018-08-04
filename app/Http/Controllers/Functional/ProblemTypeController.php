<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\ProblemTypeRepository;

class ProblemTypeController extends Controller
{
    protected $problemTypeRepository;

    /**
     * ProblemTypeController constructor.
     * @param $problemTypeRepository
     */
    public function __construct(ProblemTypeRepository $problemTypeRepository)
    {
        $this->problemTypeRepository = $problemTypeRepository;
    }


    public function getAll()
    {
        return response($this->problemTypeRepository->getAll(), 200);
    }
}