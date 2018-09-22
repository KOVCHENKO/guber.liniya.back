<?php

namespace App\src\Services\Analytics;


use App\src\Exports\Call\CallsForMonth;
use App\src\Exports\Call\CallsForPreviousDay;
use App\src\Exports\Call\CallsForRange;
use App\src\Exports\Call\CallsForYear;
use Maatwebsite\Excel\Facades\Excel;

class CallsReportService
{

    /**
     * @param $reportOption
     * @param $from
     * @param $to
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function makeReport($reportOption, $from, $to)
    {
        switch ($reportOption) {
            case 'day':
                return $this->callsForPreviousDay();
                break;
            case 'month':
                return $this->callsForThisMonth();
                break;
            case 'year':
                return $this->callsForYear();
                break;
            case 'range':
                return $this->callsForRange($from, $to);
                break;
        }
    }

    private function callsForPreviousDay()
    {
        return Excel::download(new CallsForPreviousDay(), 'calls_for_previous_day.xls');
    }

    private function callsForThisMonth()
    {
        return Excel::download(new CallsForMonth(), 'calls_for_month.xls');
    }

    private function callsForYear()
    {
        return Excel::download(new CallsForYear(), 'calls_for_year.xls');
    }

    private function callsForRange($from, $to)
    {
        return Excel::download(new CallsForRange($from, $to), 'calls_for_range.xls');
    }
}