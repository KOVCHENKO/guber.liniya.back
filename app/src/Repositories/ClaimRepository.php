<?php

namespace App\src\Repositories;


use App\src\Models\Claim;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ClaimRepository
{
    protected $claim;

    /**
     * ClaimRepository constructor.
     * @param $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function findClaim($id): Claim
    {
        return $this->claim->find($id);
    }

    /**
     * @param $take - кол-во получаемых элементов
     * @param $skip - оффсет, пропустить элементы
     * @param $dispatchStatus - prepared, edited, dispatched, null
     * @param $status - created, assigned, executed, rejected
     * @param $closeStatus - not_executed, executed_partially, executed_totally
     * @return Claim[]|\Illuminate\Database\Eloquent\Collection
     * Получить все заявки
     */
    public function getAll($take, $skip, $dispatchStatus, $status, $closeStatus, $sortBy, $sortDirection)
    {
        return $this->claim
            ->with('problem')
            ->with('address')
            ->with('comments')
            ->with('files')
            ->with('responsibleOrganization')
            ->take($take)
            ->skip($skip)
            ->whereIn('close_status', $closeStatus)
            ->whereIn('dispatch_status', $dispatchStatus)
            ->whereIn('status', $status)
            ->orderBy($sortBy, $sortDirection)
            ->get();
    }

    /**
     * @param $claim
     * @return mixed
     * Создать заявление/жалобу
     */
    public function create($claim)
    {
        return $this->claim->create($claim);
    }

    public function update($claim): Claim
    {
        $claimToUpdate = $this->findClaim($claim['id']);

        $claimToUpdate->id = $claim['id'];
        $claimToUpdate->firstname = $claim['firstname'];
        $claimToUpdate->lastname = $claim['lastname'];
        $claimToUpdate->middlename = $claim['middlename'];
        $claimToUpdate->name = $claim['name'];
        $claimToUpdate->description = $claim['description'];
        $claimToUpdate->phone = $claim['phone'];
        $claimToUpdate->email = $claim['email'];
        $claimToUpdate->status = 'created';
        $claimToUpdate->dispatch_status = $claim['dispatch_status'];
        $claimToUpdate->save();

        return $claimToUpdate;
    }

    /**
     * @param Claim $claim
     * @param $organizationId - id организации
     * @return void
     */
    public function assignClaimToResponsibleOrganization(Claim $claim, $organizationId, $visibility)
    {
        $claim->organizations()->attach($organizationId, [
            'visibility' => $visibility,
            'created_at' => Carbon::now()
        ]);
    }

    public function reassignClaimToResponsibleOrganization(Claim $claim, $organizationId)
    {
        $claim->organizations()->attach($organizationId, ['visibility' => 'show']);
    }

    /**
     * @param Claim $claim
     * @param $organizationId - id организации
     * @return void
     */
    public function detachClaimToResponsibleOrganization(Claim $claim, $organizationId)
    {
        $claim->organizations()->detach($organizationId);
    }

    /**
     * @param $resolvedDispatchStatus
     * @param $resolvedCloseStatus
     * @param $resolvedStatus
     * @param $search
     * @return mixed
     * Получить кол-во страниц
     */
    public function getPagesCount($resolvedDispatchStatus, $resolvedCloseStatus, $resolvedStatus)
    {
        return $this->claim
            ->whereIn('dispatch_status', $resolvedDispatchStatus)
            ->whereIn('close_status', $resolvedCloseStatus)
            ->whereIn('status', $resolvedStatus)
            ->count();
    }

    /**
     * @param $resolvedDispatchStatus
     * @param $resolvedCloseStatus
     * @param $resolvedStatus
     * @return mixed
     * Получить кол-во страниц для поиска
     */
    public function getPagesCountForSearch($resolvedDispatchStatus, $resolvedCloseStatus, $resolvedStatus, $search)
    {
        return $this->claim
            ->whereIn('dispatch_status', $resolvedDispatchStatus)
            ->whereIn('close_status', $resolvedCloseStatus)
            ->whereIn('status', $resolvedStatus)
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', '%'.$search.'%')
                    ->orWhere('lastname', 'like', '%'.$search.'%')
                    ->orWhere('middlename', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            })
            ->count();
    }


    public function search($take, $skip, $search, $resolvedDispatchStatus, $status, $closeStatus,  $sortBy, $sortDirection)
    {
        return $this->claim
            ->with('problem')
            ->with('address')
            ->with('comments')
            ->with('files')
            ->with('responsibleOrganization')
            ->take($take)
            ->skip($skip)
            ->whereIn('dispatch_status', $resolvedDispatchStatus)
            ->whereIn('status', $status)
            ->whereIn('close_status', $closeStatus)
            ->where(function ($query) use ($search) {
                $query->where('firstname', 'like', '%'.$search.'%')
                    ->orWhere('lastname', 'like', '%'.$search.'%')
                    ->orWhere('middlename', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            })
            ->orderBy($sortBy, $sortDirection)
            ->get();
    }

    public function updateStatus($id, $status): Claim
    {
        $claim = $this->findClaim($id);
        $claim->status = $status;
        $claim->save();
        return $claim;
    }

    /**
     * @param $phone
     * Получить все предыдущие, созданные заявки с определенным номером телефона
     * Поулчить только отправленные (так как эти необходимы для поиска по номеру телефона
     * @return  - возвращает список заявок с одинаковым номером телефона
     */
    public function getByPhone($phone)
    {
        return $this->claim
            ->where('phone', $phone)
            ->where('dispatch_status', 'dispatched')
            ->with('problem')
            ->with('address')
            ->with('comments')
            ->with('files')
            ->get();
    }

    /**
     * @param $pid
     * Получтить организацию, которая решает данную заявку
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
     */
    public function getOrganizationWhichResolvesClaim(int $claimId)
    {
        return DB::table('claims_organizations')
            ->where('claim_id', $claimId)
            ->first();
    }

    /**
     * @param $pid
     * @return mixed
     * Получить родительскую заявку
     */
    public function getParentClaim($pid)
    {
        return $this->claim->find($pid);
    }

    /**
     * @param $claimId
     * @return Claim
     * Получить заявку по ид
     */
    public function getById($claimId): Claim
    {
        return $this->claim->find($claimId);
    }

    /**
     * @param Claim $claim
     * @param $closeStatus
     * @return Claim
     * Изменить статус закрытия заявки (коммуникатором)
     */
    public function changeCloseCStatus(Claim $claim, $closeStatus): Claim
    {
        $claim->close_status = $closeStatus;
        $claim->save();

        return $claim;
    }

    /**
     * @param Claim $claim
     * @param $status
     * @return Claim
     * Изменить статус выполнения заявки
     */
    public function changeStatus(Claim $claim, $status): Claim
    {
        $claim->status = $status;
        $claim->save();

        return $claim;
    }



}
