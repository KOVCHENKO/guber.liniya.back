<?php

namespace App\src\Exports\Call;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallsForMonth implements FromCollection, WithHeadings
{

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Всего звонков',
            'Новые заявки',
            'Повторные заявки'
        ];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $start = Carbon::now()->startOfMonth();
        $finish = Carbon::now()->endOfMonth();

        $calls = DB::table('calls')
            ->whereBetween('created_at', [$start, $finish])
            ->count();

        $claims = DB::select(
            "SELECT count(*) as 'p_count' FROM `claims`
                  WHERE call_id in (select id from calls where created_at 
                  between '".$start."' AND '".$finish."') AND pid IS NULL;");

        $childClaims = DB::select(
            "SELECT count(*) as 'c_count' FROM `claims`
                  WHERE call_id in (select id from calls where created_at 
                   between '".$start."' AND '".$finish."') AND pid IS NOT NULL;");

        return collect([
            [
                'calls' => $calls,
                'p_count' => $claims[0]->p_count,
                'c_count' => $childClaims[0]->c_count,
            ],
        ]);
    }


}