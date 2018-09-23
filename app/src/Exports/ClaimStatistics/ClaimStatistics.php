<?php

namespace App\src\Exports\ClaimStatistics;


use App\src\Services\Util\TranslationService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClaimStatistics implements FromCollection, WithHeadings
{
    protected $query;

    /**
     * ClaimStatistics constructor.
     * @param $query
     */
    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Дата/Время',
            'ФИО',
            'Адрес',
            'Проблема',
            'Организация',
            'Статус обработки'
        ];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $claims = $this->query->get();

        return $claims->map(function ($claim) {

            if ($claim->responsibleOrganization->isEmpty()) {
                $claim->responsibleOrganization = array([
                    'name' => 'Отсутствует'
                ]);
            }

            return [
                'created_at' => $claim->created_at,
                'lastname' => $claim->lastname.' '.$claim->firstname.' '.$claim->middlename,
                'address' => $claim->address->district.' '.$claim->address->location,
                'problem' => $claim->problem->name,
                'organization' => $claim->responsibleOrganization[0]['name'],
                'status' => TranslationService::translateClaimStatus($claim->status)
            ];
        });

    }


}