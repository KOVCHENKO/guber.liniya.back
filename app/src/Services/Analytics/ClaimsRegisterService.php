<?php

namespace App\src\Services\Analytics;



use App\src\Exports\ClaimRegister\ClaimsForMonth;
use App\src\Exports\ClaimRegister\ClaimsForPreviousDay;
use App\src\Exports\ClaimRegister\ClaimsForRange;
use App\src\Exports\ClaimRegister\ClaimsForYear;
use Maatwebsite\Excel\Facades\Excel;

class ClaimsRegisterService
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
                return $this->claimsForPreviousDay();
                break;
            case 'month':
                return $this->claimsForThisMonth();
                break;
            case 'year':
                return $this->claimsForYear();
                break;
            case 'range':
                return $this->claimsForRange($from, $to);
                break;
        }
    }

    private function claimsForPreviousDay()
    {
        return Excel::download(new ClaimsForPreviousDay(), 'claims_for_previous_day.xls');
    }

    private function claimsForThisMonth()
    {
        return Excel::download(new ClaimsForMonth(), 'claims_for_month.xls');
    }

    private function claimsForYear()
    {
        return Excel::download(new ClaimsForYear(), 'claims_for_year.xls');
    }

    private function claimsForRange($from, $to)
    {
        return Excel::download(new ClaimsForRange($from, $to), 'claims_for_range.xls');
    }
}