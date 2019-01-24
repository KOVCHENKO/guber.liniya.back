<?php

namespace App\src\Services\Applicant;


use App\src\Repositories\ApplicantRepository;

class ApplicantService
{
    protected $applicantRepository;

    /**
     * ApplicantService constructor.
     * @param ApplicantRepository $applicantRepository
     */
    public function __construct(ApplicantRepository $applicantRepository)
    {
        $this->applicantRepository = $applicantRepository;
    }

    /**
     * Получить всех заявителей
     */
    public function getAll()
    {
        return $this->applicantRepository->getAll();
    }
}
