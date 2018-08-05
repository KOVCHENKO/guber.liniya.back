<?php

namespace App\Http\Controllers\Functional;


use App\Http\Controllers\Controller;
use App\src\Repositories\ProblemTypeRepository;
use Illuminate\Http\Request;

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

    public function create(Request $request)
    {
        return response($this->problemTypeRepository->create($request->all()), 200);
    }

    public function getById($id)
    {
        return response($this->problemTypeRepository->getById($id), 200);
    }
}