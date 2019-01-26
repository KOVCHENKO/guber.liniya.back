<?php

namespace App\src\Services\Applicant;


use App\src\Repositories\AddressRepository;
use App\src\Repositories\ApplicantRepository;

class ApplicantService
{
    protected $applicantRepository;
    protected $addressRepository;

    /**
     * ApplicantService constructor.
     * @param ApplicantRepository $applicantRepository
     * @param AddressRepository $addressRepository
     */
    public function __construct(ApplicantRepository $applicantRepository,
                                AddressRepository $addressRepository)
    {
        $this->applicantRepository = $applicantRepository;
        $this->addressRepository = $addressRepository;
    }

    /**
     * Получить всех заявителей
     */
    public function getAll()
    {
        return $this->applicantRepository->getAll();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * Создать заявителя
     * @return \Illuminate\Http\Request
     */
    public function create(\Illuminate\Http\Request $request)
    {
        return $request;

        // 1. Создать адрес
        $address = $this->addressRepository->create([
            'district' => $request['address']['district'],
            'location' => $request['address']['location']
        ]);

        // 2. Создать заявителя
        $this->applicantRepository->create($request);
    }
}
