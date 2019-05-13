<?php

namespace App\Http\Controllers\Functional;


use App\src\Services\Applicant\ApplicantService;
use Illuminate\Http\Request;

class ApplicantController
{

    protected $applicantService;

    /**
     * ApplicantController constructor.
     * @param ApplicantService $applicantService
     */
    public function __construct(ApplicantService $applicantService)
    {
        $this->applicantService = $applicantService;
    }

    /**
     * @param Request $request - search: строка запроса
     * @param int $page - номер страницы
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Получить всех заявителей
     */
    public function getAll(Request $request, int $page)
    {
        try {
            return response($this->applicantService->getAll($page, $request), 200);
        } catch (\Exception $ex) {
            return response(['error' => $ex->getMessage()], 400);
        }
    }

    /**
     * @param Request $request
     * Создать заявителя
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            return response ($this->applicantService->create($request), 200);
        } catch (\Exception $ex) {
            return response(['error' => $ex->getMessage()], 400);
        }
    }
}
