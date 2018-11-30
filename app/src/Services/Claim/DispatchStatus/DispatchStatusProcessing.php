<?php

namespace App\src\Services\Claim\DispatchStatus;


use App\src\Repositories\OrganizationRepository;
use Exception;

/**
 * Class DispatchStatusProcessing
 * @package App\src\Services\Claim\DispatchStatus
 * Фильтрация заявок по разным параметрам
 */
class DispatchStatusProcessing
{
    protected $organizationRepository;

    /**
     * DispatchStatusProcessing constructor.
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function resolveDispatchStatus($dispatchStatus)
    {
        switch ($dispatchStatus) {
            case 'all':             // для диспетчера
                return ['raw', 'edited', 'dispatched', 'prepared'];
                break;
            case 'prepared':        // для редактора
                return ['prepared', 'edited'];
                break;
            case 'edited':          // для супервизора-отправителя
                return ['edited', 'dispatched'];
                break;
            case 'dispatched':
                return ['dispatched'];
                break;
        }

        return new Exception('There is no such dispatch status');
    }

    /**
     * @param $claimId - ID заявки для обновления статуса видимости организации
     * @param $dispatchStatusToUpdate - prepared, edited, dispatched
     * При необходимости обнвоить статус видимости для организации
     * Если dispatched - то обновляем на видимый
     */
    public function updateVisibilityOfClaimsForOrganizations(int $claimId, $dispatchStatusToUpdate)
    {
        if ($dispatchStatusToUpdate == 'dispatched') {
            $this->organizationRepository->changeClaimVisibilityForOrganization($claimId);
        }
    }

    /**
     * @param $resolvedDispatchStatus
     * @param $dispatchStatusFilter
     * @return array
     * Если фильтр выставлен на 'all', то пустой фильтр
     */
    public function establishDispatchStatusFilter($resolvedDispatchStatus, $dispatchStatusFilter)
    {
        if ($dispatchStatusFilter == 'all' || $dispatchStatusFilter == null) {
            return $resolvedDispatchStatus;
        }
        return [$dispatchStatusFilter];
    }

    /**
     * @param $statusFilter
     * @return array
     * Фильтр на статус обработки - все или никакие фильтры
     * aer - собирательно: assigned, executed, rejected
     */
    public function establishStatusFilter($statusFilter)
    {
        if ($statusFilter == 'all' || $statusFilter == null) {
            return [NULL, 'created', 'assigned', 'executed', 'rejected'];
        } elseif ($statusFilter = 'aer') {
            return ['assigned', 'executed', 'rejected'];
        }

        return [$statusFilter];
    }

    /**
     * @param $closeStatusFilter
     * Фильтр по закрытию (завершению) заявки
     * @return mixed
     */
    public function establishCloseStatusFilter($closeStatusFilter)
    {
        if ($closeStatusFilter == 'all' || $closeStatusFilter == null) {
            return ['raw', 'not_executed', 'executed_partially', 'executed_totally'];
        }

        return [$closeStatusFilter];
    }

}
