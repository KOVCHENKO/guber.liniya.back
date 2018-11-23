<?php

namespace App\src\Services\Claim;


use App\src\Models\Claim;
use App\src\Repositories\AddressRepository;
use App\src\Repositories\ClaimRepository;
use App\src\Services\Call\CallService;
use App\src\Services\Claim\DispatchStatus\DispatchStatusProcessing;
use App\src\Services\Claim\PIDStatus\PIDResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ClaimService
{
    protected $claimRepository;
    protected $addressRepository;
    protected $callService;
    protected $pidResolver;
    protected $dispatchStatusProcessing;

    protected $claimsPerPage = 10;
    protected $daysForExpiration = 5;

    /**
     * ClaimService constructor.
     * @param ClaimRepository $claimRepository
     * @param AddressRepository $addressRepository
     * @param CallService $callService
     * @param PIDResolver $PIDResolver
     * @param DispatchStatusProcessing $dispatchStatusProcessing
     */
    public function __construct(
        ClaimRepository $claimRepository,
        AddressRepository $addressRepository,
        CallService $callService,
        PIDResolver $PIDResolver,
        DispatchStatusProcessing $dispatchStatusProcessing)
    {
        $this->claimRepository = $claimRepository;
        $this->addressRepository = $addressRepository;
        $this->callService = $callService;
        $this->pidResolver = $PIDResolver;
        $this->dispatchStatusProcessing = $dispatchStatusProcessing;
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
     * 1. Обновить адрес заявки
     * 2. При необходимости обнвоить статус видимости для организации
     * 3. Обновить саму заявку
     * @param $dispatchStatusToUpdate
     * @return mixed
     */
    public function update($data, $dispatchStatusToUpdate): Claim
    {
        $this->addressRepository->update([
            'address_id' => $data['address']['id'],
            'district' => $data['address']['district'],
            'location' => $data['address']['location']
        ]);

        $this->dispatchStatusProcessing->updateVisibilityOfClaimsForOrganizations($data['id'], $dispatchStatusToUpdate);

        return $this->claimRepository->update([
            'id' => $data['id'],
            'firstname' => $data['firstName'],
            'lastname' => $data['lastName'],
            'middlename' => $data['middleName'],
            'name' => $data['name'],
            'description' => $data['description'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'status' => 'created',
            'dispatch_status' => $dispatchStatusToUpdate
        ]);
    }

    /**
     * @param $page - получить согласно странице
     * Пока что по 10 записей на страницу (default)
     * @param $dispatchStatus - все, отредактированные, для отправки
     * @param $dispatchStatusFilter - фильтр статуса диспетчера и приемки заявки
     * @param $statusFilter - фильтр статуса обработки
     * @param $closeStatusFilter - фильтр статуса закрытия
     * @return Claim[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll($page, $dispatchStatus, $dispatchStatusFilter, $statusFilter, $closeStatusFilter, $sortBy, $sortDirection)
    {
        $resolvedDispatchStatus = $this->dispatchStatusProcessing->resolveDispatchStatus($dispatchStatus);

        // Использовать фильтр статуса диспетчера
        $resolvedDispatchStatus = $this->dispatchStatusProcessing->establishDispatchStatusFilter($resolvedDispatchStatus, $dispatchStatusFilter);

        // Фильтр по статусу обработки
        $resolvedStatusFilter = $this->dispatchStatusProcessing->establishStatusFilter($statusFilter);

        // Фильтр по статусу заврешения
        $resolveCloseStatusFilter = $this->dispatchStatusProcessing->establishCloseStatusFilter($closeStatusFilter);

        $claims = $this->claimRepository->getAll(
            $this->claimsPerPage,
            $this->getSkippedItems($page),
            $resolvedDispatchStatus,
            $resolvedStatusFilter,
            $resolveCloseStatusFilter,
            $sortBy,
            $sortDirection
        );

        // Получить родительские заявки
        $claims->map(function ($claim) {
            return $this->pidResolver->getParentClaims($claim);
        });

        // Выяснить, просрочена ли заявка
        $claims->map(function(&$claim) {
            $claim->expired = $this->checkIfClaimIsExpired($claim);
        });

        return [
            'claims' => $claims,
            'pages' => ceil(
                $this->claimRepository->getPagesCount($resolvedDispatchStatus, $resolveCloseStatusFilter, $resolvedStatusFilter)
                / $this->claimsPerPage)
        ];
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
     * @param $dispatchStatusFilter - фильтр статуса диспетчера
     * @return array
     */
    public function search($page, $search, $dispatchStatus, $dispatchStatusFilter, $statusFilter, $closeStatusFilter, $sortBy, $sortDirection)
    {
        $resolvedDispatchStatus = $this->dispatchStatusProcessing->resolveDispatchStatus($dispatchStatus);

        // Использовать фильтр статуса диспетчера
        $resolvedDispatchStatus = $this->dispatchStatusProcessing->establishDispatchStatusFilter($resolvedDispatchStatus, $dispatchStatusFilter);

        // Фильтр по статусу обработки
        $resolvedStatusFilter = $this->dispatchStatusProcessing->establishStatusFilter($statusFilter);

        // Фильтр по статусу заврешения
        $resolveCloseStatusFilter = $this->dispatchStatusProcessing->establishCloseStatusFilter($closeStatusFilter);

        $claims = $this->claimRepository->search(
            $this->claimsPerPage,
            $this->getSkippedItems($page),
            $search,
            $resolvedDispatchStatus,
            $resolvedStatusFilter,
            $resolveCloseStatusFilter,
            $sortBy,
            $sortDirection
        );

        // Получить родительские заявки
        $claims->map(function ($claim) {
            return $this->pidResolver->getParentClaims($claim);
        });

        // Выяснить, просрочена ли заявка
        $claims->map(function(&$claim) {
            $claim->expired = $this->checkIfClaimIsExpired($claim);
        });

        return [
            'claims' => $claims,
            'pages' => ceil(
                $this->claimRepository->getPagesCountForSearch($resolvedDispatchStatus, $resolveCloseStatusFilter, $resolvedStatusFilter, $search)
                / $this->claimsPerPage)
        ];
    }

    public function changeOrganization($id, $idOldOrganization, $idNewOrganization)
    {
        $claim = $this->claimRepository->findClaim($id);
        $this->claimRepository->changeStatus($claim, 'created');
        $this->claimRepository->detachClaimToResponsibleOrganization($claim, $idOldOrganization);
        $this->claimRepository->reassignClaimToResponsibleOrganization($claim, $idNewOrganization);
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


    /**
     * @param $claimId - id заявки
     * @param $closeStatus - статус закрытия заявки (NOT_CALLED, NOT_EXECUTED, EXECUTED_PARTIALLY, EXECUTED_TOTALLY)
     * @return
     */
    public function changeCloseStatus($claimId, $closeStatus): Claim
    {
        $claim = $this->claimRepository->getById($claimId);
        return $this->claimRepository->changeCloseCStatus($claim, $closeStatus);
    }

    /**
     * @param $claim
     * Проверить, истек ли срок выполнения заявки
     * @return bool
     */
    public function checkIfClaimIsExpired($claim)
    {
        $dateOfExpiration = Carbon::parse($claim->created_at)->addDays($this->daysForExpiration);

        if (Carbon::now()->gt($dateOfExpiration) &&
            ($claim->status == 'created' || $claim->status == 'assigned')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $organizationId
     * @param $claimId
     * @return Claim
     * Переназначить другой организации заявку, от выполнения которой отказалась первая организация
     */
    public function reassignRejectedClaim($organizationId, $claimId)
    {
        // Назначить новой организации
        $claim = $this->claimRepository->findClaim($claimId);
        $this->claimRepository->assignClaimToResponsibleOrganization($claim, $organizationId, 'show');

        // Изменить статус на вновь созданную
        $this->claimRepository->changeStatus($claim, 'created');

        return $claim;
    }

}
