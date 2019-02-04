<?php

namespace App\src\Services\Applicant;


use App\src\Repositories\AddressRepository;
use App\src\Repositories\ApplicantRepository;
use Illuminate\Http\Request;

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
    public function create(Request $request)
    {
        // 1. Создать адрес
        $address = $this->addressRepository->create([
            'district' => $request['address']['city'],
            'city' => $request['address']['city'],
            'street' => $request['address']['street'],
            'building' => $request['address']['building']
        ]);

        // 2. Создать заявителя
        return $this->applicantRepository->create([
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'middlename' => $request['middlename'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'address_id' => $address->id
        ]);
    }
}
