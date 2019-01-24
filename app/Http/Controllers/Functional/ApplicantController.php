<?php

namespace App\Http\Controllers\Functional;


use App\src\Services\Applicant\ApplicantService;

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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * Получить всех заявителей
     */
    public function getAll()
    {
        try {
            return response($this->applicantService->getAll(), 200);
        } catch (\Exception $ex) {
            return response(['error' => $ex->getMessage()], 400);
        }
    }
}
