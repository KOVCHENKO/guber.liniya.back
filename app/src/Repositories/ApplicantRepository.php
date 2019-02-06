<?php

namespace App\src\Repositories;


use App\src\Models\Applicant;
use Illuminate\Support\Collection;

class ApplicantRepository
{
    protected $applicant;

    /**
     * ApplicantRepository constructor.
     * @param Applicant $applicant
     */
    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    /**
     * Получить всех заявителей
     * @param $take - кол-во пользователей (для пагинатора)
     * @param $skip - страница (пропущенные заявители)
     * @return Collection
     */
    public function getAll($take, $skip): Collection
    {
        return $this->applicant
            ->take($take)
            ->skip($skip)
            ->get();
    }

    /**
     * Создать заявителя
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->applicant->create($data);
    }

    /**
     * @param int $take - кол-во пользователей (для пагинатора)
     * @param int $skip - страница (пропущенные заявители)
     * @param string $search - строка поиска заявителя
     * @return Collection
     */
    public function search(int $take, int $skip, string $search): Collection
    {
        return $this->applicant
            ->take($take)
            ->skip($skip)
            ->where(function ($searchableApplicant) use ($search) {
                $searchableApplicant->where('firstname', 'like', '%'.$search.'%')
                    ->orWhere('lastname', 'like', '%'.$search.'%')
                    ->orWhere('middlename', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            })
            ->get();
    }

    /**
     * @return mixed
     * Получить кол-во страниц
     */
    public function getPagesCount()
    {
        return $this->applicant->count();
    }
}
