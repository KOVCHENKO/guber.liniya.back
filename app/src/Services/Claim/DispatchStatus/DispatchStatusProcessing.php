<?php

namespace App\src\Services\Claim\DispatchStatus;


use App\src\Repositories\OrganizationRepository;
use Exception;

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
}