<?php

namespace App\src\Services\Claim;


use App\src\Models\Claim;
use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;
use App\src\Repositories\OrganizationRepository;
use App\src\Services\Call\CallService;
use Illuminate\Support\Collection;

class ClaimService
{
    protected $claimRepository;
    protected $addressRepository;
    protected $organizationRepository;
    protected $callRepository;

    protected $claimsPerPage = 10;

    /**
     * ClaimService constructor.
     * @param ClaimRepository $claimRepository
     * @param AddressRepository $addressRepository
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(
        ClaimRepository $claimRepository,
        AddressRepository $addressRepository,
        OrganizationRepository $organizationRepository,
        CallService $callService)
    {
        $this->claimRepository = $claimRepository;
        $this->addressRepository = $addressRepository;
        $this->organizationRepository = $organizationRepository;
        $this->callService = $callService;
    }

    /**
     * @param $data - array
     * Создает заявку на основе звонка
     * @return mixed
     */
    public function createBasedOnCall($data)
    {
        $this->callService->updateCall($data['call']);

        $address = $this->addressRepository->create([
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        $newClaim = $this->claimRepository->create([
            'firstname' =>  $data['firstName'],
            'lastname' =>  $data['lastName'],
            'middlename' =>  $data['middleName'],
            'name' => $data['call']['callId'],
            'description' =>  $data['description'],
            'link' => $data['link'],
            'ats_status' => $data['call']['atsStatus'],
            'phone' => $data['phone'],
            'email' =>  $data['email'],
            'address_id' => $address['id'],
            'call_id' => $data['call']['id'],
            'problem_id' => $data['problem']['id'],
            'status' => 'created'
        ]);

        $this->distributeByOrganizations($newClaim, $data);

        return $newClaim;
    }

    /**
     * @param $data
     * 1. Создать заявку (на основе уже существующей в БД из АТС Мегафон
     * 2. Распределить по организациям
     * @return mixed
     */
    public function updateAndDistributeByOrganizations($data)
    {
        $newClaim = $this->saveClaim($data);
        $this->distributeByOrganizations($newClaim, $data);

        return $newClaim;
    }

    /**
     * @param $data
     * @return Claim - сохранение заявки на основе АТС Мегафон
     */
    private function saveClaim($data): Claim
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
     * @param Claim $newClaim
     * @param $data
     * @return mixed
     * 1. Получить все ответственные организации
     * 2. Назначить выполнение заявки данным организациям
     */
    private function distributeByOrganizations(Claim $newClaim, $data)
    {
        $responsibleOrganizations =  $this->getOrganizationsRelatedToProblem($data['problem']['id']);

        $responsibleOrganizations->map(function ($organization) use ($newClaim) {
            $this->claimRepository->assignClaimToResponsibleOrganization($newClaim, $organization->id, 'hide');
        });

        return true;
    }

    /**
     * @param $problemId
     * Получить организации, которые могут решить данный вид проблемы
     * @return \Illuminate\Support\Collection - организации по ид проблемы
     */
    private function getOrganizationsRelatedToProblem($problemId): Collection
    {
        return $this->organizationRepository->getOrganizationsByProblem($problemId);
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


}