<?php

namespace App\src\Exports\ClaimRegister;


use App\src\Models\Claim;
use App\src\Services\Util\TranslationService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClaimsForPreviousDay implements FromCollection, WithHeadings
{


    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Дата/Время',
            'ФИО',
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
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        $claims = Claim::with('problem')
            ->with('responsibleOrganization')
            ->where('created_at', 'like', '%'.$yesterday.'%')
            ->get();

        return $claims->map(function ($claim) {

            if ($claim->responsibleOrganization->isEmpty()) {
                $claim->responsibleOrganization = array([
                    'name' => 'Отсутствует'
                ]);
            }

            return [
                'created_at' => $claim->created_at,
                'lastname' => $claim->lastname.' '.$claim->firstname.' '.$claim->middlename,
                'problem' => $claim->problem->name,
                'organization' => $claim->responsibleOrganization[0]['name'],
                'status' => TranslationService::translateClaimStatus($claim->status)
            ];
        });
    }


}