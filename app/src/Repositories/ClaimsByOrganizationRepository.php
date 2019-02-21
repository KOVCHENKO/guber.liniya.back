<?php

namespace App\src\Repositories;

use App\src\Models\Claim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ClaimsByOrganizationRepository
{

    protected $claims;
    protected $model;

    public function __construct(Claim $model)
    {
        $this->model = $model;
        $this->claims = collect();
    }

    // Получить все заявки по организации
    public function getAll($organizationId)
    {
        $this->claims = $this->model
            ->join('claims_organizations as co', 'claims.id', '=', 'co.claim_id')
            ->where('co.organization_id', $organizationId)
            ->where('co.visibility', 'show')
            ->with('applicant')
            ->with('address')
            ->whereNotIn('status', ['rejected']);
    }

    // Фильтр по статус
    public function byStatus($status)
    {   
        $this->claims->where('status', $status);
    }

    // Фильтр ФИО
    public function byInitials($search)
    {   
        $this->claims->whereHas('applicant', function ($query) use ($search) {
            $query->where('firstname', 'like', '%'.$search.'%')
                ->orWhere('lastname', 'like', '%'.$search.'%')
                ->orWhere('middlename', 'like', '%'.$search.'%');
        });
    }

    // Фильтр по телефону
    public function byPhone($search)
    {   
        $this->claims->whereHas('applicant', function ($query) use ($search) {
            $query->where('phone', 'like', '%'.$search.'%');
        });
    }

    // Фильтр по адресу
    public function byAddress($search)
    {   
        $this->claims->whereHas('address', function ($query) use ($search) {
            $query->where('district', 'like', '%'.$search.'%')
                ->orWhere('city', 'like', '%'.$search.'%')
                ->orWhere('street', 'like', '%'.$search.'%')
                ->orWhere('building', 'like', '%'.$search.'%');
        });
    }

    // Фильтр по дате
    public function byDate($minDate, $maxDate)
    {
        if(empty($minDate) && !empty($maxDate)) {
            $this->claims->where('claims.created_at', '<=', $maxDate);
        } 
        elseif(!empty($minDate) && empty($maxDate)) {
            $this->claims->where('claims.created_at', '>=', $minDate);
        }
        else {
            $this->claims->whereBetween('claims.created_at', [$minDate, $maxDate]);
        }
    }

    // Сортировка
    public function bySort($field, $direction = 'asc')
    {   
        $funcname = ($direction == 'asc') ?  'sortBy' : 'sortByDesc';

        if ($field == 'date') {

            $sorted = $this->claims->$funcname(function ($product, $key) {
                return $product->created_at;
            })->values();

            $this->claims = $sorted;
        }
        elseif ($field == 'initials') {

            $sorted = $this->claims->$funcname(function ($product, $key) {
                return $product->applicant['firstname'] . $product->applicant['middlename'] . 
                    $product->applicant['lastname'];
            })->values();

            $this->claims = $sorted;
        }
        elseif ($field == 'phone') {

            $sorted = $this->claims->$funcname(function ($product, $key) {
                return $product->applicant['phone'];
            })->values();

            $this->claims = $sorted;
        }
        elseif ($field == 'address') {

            $sorted = $this->claims->$funcname(function ($product, $key) {
                return $product->address['city'] . $product->address['district'] .
                    $product->address['street'] . $product->address['building'];
            })->values();

            $this->claims = $sorted;
        }
    }

    public function render()
    {
        $this->claims = $this->claims->get();
    }

    public function countPage($take)
    {        
        return (int)ceil($this->claims->count() / $take);
    }

    public function forPage($page, $take)
    {
        return $this->claims->forPage($page, $take);
    }

    public function get()
    {
        return $this->claims;
    }

}