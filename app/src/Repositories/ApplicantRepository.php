<?php

namespace App\src\Repositories;


use App\src\Models\Applicant;

class ApplicantRepository
{
    protected $applicant;

    /**
     * ApplicantRepository constructor.
     * @param Applicant $applicant
     */
    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * Получить всех заявителей
     */
    public function getAll()
    {
        return $this->applicant->get();
    }


    public function create($data)
    {
        return $this->applicant->create($data);
    }
}
