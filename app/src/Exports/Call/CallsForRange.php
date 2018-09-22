<?php

namespace App\src\Exports\Call;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallsForRange implements FromCollection, WithHeadings
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
        $parsedFrom = Carbon::parse($this->from)->format('Y-m-d');
        $parsedTo = Carbon::parse($this->to)->format('Y-m-d');

        $calls = DB::table('calls')
            ->whereBetween('created_at', [$parsedFrom, $parsedTo])
            ->count();

        $claims = DB::select(
            "SELECT count(*) as 'p_count' FROM `claims`
                  WHERE call_id in (select id from calls where created_at 
                  between '".$parsedFrom."' AND '".$parsedTo."') AND pid IS NULL;");

        $childClaims = DB::select(
            "SELECT count(*) as 'c_count' FROM `claims`
                  WHERE call_id in (select id from calls where created_at 
                   between '".$parsedFrom."' AND '".$parsedTo."') AND pid IS NOT NULL;");

        return collect([
            [
                'calls' => $calls,
                'p_count' => $claims[0]->p_count,
                'c_count' => $childClaims[0]->c_count,
            ],
        ]);
    }


}