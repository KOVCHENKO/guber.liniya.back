<?php

namespace App\src\Services\Claim;


use App\src\Models\Claim;
use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;
use App\src\Repositories\OrganizationRepository;
use App\src\Services\Call\CallService;
use App\src\Services\Claim\PIDStatus\PIDResolver;
use Illuminate\Support\Collection;

class ClaimService
{
    protected $claimRepository;
    protected $addressRepository;
    protected $organizationRepository;
    protected $callRepository;
    protected $pidResolver;

    protected $claimsPerPage = 10;

    /**
     * ClaimService constructor.
     * @param ClaimRepository $claimRepository
     * @param AddressRepository $addressRepository
     * @param CallService $callService
     * @param PIDResolver $PIDResolver
     */
    public function __construct(
        ClaimRepository $claimRepository,
        AddressRepository $addressRepository,
        CallService $callService,
        PIDResolver $PIDResolver)
    {
        $this->claimRepository = $claimRepository;
        $this->addressRepository = $addressRepository;
        $this->callService = $callService;
        $this->pidResolver = $PIDResolver;
    }

    /**
     * @param $data - array
     * Создает заявку на основе звонка
     * @return mixed
     */
    public function createBasedOnCall($data)
    {
        $updatedCall = $this->callService->updateCall($data['call']);

        $pidStatusEntity = $this->pidResolver->resolvePidStatus($data['pid']);
        $pidStatusEntity->createBasedOnCall($data);

        return $updatedCall;

    }

    /**
     * @param $data
     * 1. Создать заявку (на основе уже существующей в БД из АТС Мегафон
     * 2. Распределить по организациям
     * @return mixed
     */
    public function update($data): Claim
    {
        $this->addressRepository->update([
            'address_id' => $data['address']['id'],
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        return $this->claimRepository->update([
            'id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'middlename' => $data['middleName'],
            'name' => $data['name'],
            'description' => $data['description'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'status' => 'created'
        ]);
    }

    /**
     * @param $page - получить согласно странице
     * Пока что по 10 записей на страницу (default)
     * @param $dispatchStatus - все, отредактированные, для отправки
     * @return Claim[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($page, $dispatchStatus)
    {
        $resolvedDispatchStatus = $this->resolveDispatchStatus($dispatchStatus);
        $claims = $this->claimRepository->getAll(
            $this->claimsPerPage,
            $this->getSkippedItems($page),
            $resolvedDispatchStatus
        );

        return [
            'claims' => $claims,
            'pages' => ceil($this->claimRepository->getPagesCount($resolvedDispatchStatus) / $this->claimsPerPage)
        ];
    }

    private function resolveDispatchStatus($dispatchStatus)
    {
        switch ($dispatchStatus) {
            case 'all':             // для диспетчера
                return ['raw', 'edited', 'dispatched', 'prepared'];
                break;
            case 'prepared':        // для редактора
                return ['prepared'];
                break;
            case 'edited':          // для супервизора-отправителя
                return ['edited', 'dispatched'];
                break;
        }
    }


    private function getSkippedItems($page)
    {
        if (!isset($page)) {
           $page = 1;
        }

        return ($page != 1) ? ($page - 1) * $this->claimsPerPage : 0;
    }

    /**
     * @param $page - страница
     * @param $search - поиск (строка)
     * @param $dispatchStatus - все, отредактированные, для отправки
     * @return array
     */
    public function search($page, $search, $dispatchStatus)
    {
        $resolvedDispatchStatus = $this->resolveDispatchStatus($dispatchStatus);

        return [
            'claims' => $this->claimRepository->search(
                $this->claimsPerPage,
                $this->getSkippedItems($page),
                $search,
                $resolvedDispatchStatus
            ),
            'pages' => ceil($this->claimRepository->getPagesCount($resolvedDispatchStatus) / $this->claimsPerPage)
        ];
    }

    /**
     * @param $phone
     * @return mixed
     * Получить все предыдущие заявки по номеру телефона
     */
    public function getPreviousByPhone($phone)
    {
        return $this->claimRepository->getByPhone($phone);
    }


}