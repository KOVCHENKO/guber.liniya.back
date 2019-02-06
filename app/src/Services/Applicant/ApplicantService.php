<?php

namespace App\src\Services\Applicant;


use App\src\Repositories\AddressRepository;
use App\src\Repositories\ApplicantRepository;
use App\src\Services\Util\Pagination;
use Illuminate\Http\Request;

class ApplicantService
{
    protected $applicantRepository;
    protected $addressRepository;
    protected $paginator;

    /**
     * ApplicantService constructor.
     * @param ApplicantRepository $applicantRepository
     * @param AddressRepository $addressRepository
     * @param Pagination $pagination
     */
    public function __construct(ApplicantRepository $applicantRepository,
                                AddressRepository $addressRepository,
                                Pagination $pagination)
    {
        $this->applicantRepository = $applicantRepository;
        $this->addressRepository = $addressRepository;
        $this->paginator = $pagination;
    }

    /**
     * Получить всех заявителей
     * @param $page - необходимая страница
     * @param $request - search: строка запроса
     * @return array - возвращает массив заявителей
     */
    public function getAll($page, $request)
    {
        if($request->search == '') {
            $applicants = $this->applicantRepository->getAll(
                $this->paginator->itemsPerPage,
                $this->paginator->getSkippedItems($page)
            );
        } else {
            $applicants = $this->applicantRepository->search(
                $this->paginator->itemsPerPage,
                $this->paginator->getSkippedItems($page),
                $request->search
            );
        }

        $pages = $this->applicantRepository->getPagesCount();

        return [
            'applicants' => $applicants,
            'pages' => $this->paginator->getPagesQuantity($pages)
        ];
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
