<?php

namespace App\src\Exports\ClaimRegister;


use App\src\Models\Claim;
use App\src\Services\Util\TranslationService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClaimsForRange implements FromCollection, WithHeadings
{

    protected $from;
    protected $to;

    /**
     * CallsForRange constructor.
     * @param $from
     * @param $to
     */
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

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

        $start = Carbon::parse($this->from)->format('Y-m-d');
        $finish = Carbon::parse($this->to)->addDay()->format('Y-m-d');

        $claims = Claim::with('problem')
            ->with('responsibleOrganization')
            ->whereBetween('created_at', [$start, $finish])
            ->orderBy('created_at', 'desc')
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
