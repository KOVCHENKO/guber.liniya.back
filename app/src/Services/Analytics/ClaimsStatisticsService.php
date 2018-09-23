<?php

namespace App\src\Services\Analytics;


use App\src\Exports\ClaimStatistics\ClaimStatistics;
use App\src\Models\Claim;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ClaimsStatisticsService
{

    /**
     * @param $reportPeriodOption - day, month, year, range
     * @param $from - date_from
     * @param $to - date_to
     * @param $chosenDistrict - address.district
     * @param $chosenOrganization - claims_organization.organization_id
     * @param $chosenProblem - claims.problem_id
     * @param $statusFilter - status: created, executed, assigned, rejected
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function claimsStatisticsReport(
        $reportPeriodOption, $from, $to, $chosenDistrict,
        $chosenOrganization, $chosenProblem, $statusFilter
    )
    {
        $query = Claim::with('problem')
            ->with('responsibleOrganization')
            ->with('address');

        switch ($reportPeriodOption) {
            case 'day':
                $yesterday = Carbon::now()->subDay()->format('Y-m-d');
                $query->where('created_at', 'like', '%'.$yesterday.'%');
                break;
            case 'month':
                $start = Carbon::now()->startOfMonth();
                $finish = Carbon::now()->endOfMonth();
                $query->whereBetween('created_at', [$start, $finish]);
                break;
            case 'year':
                $start = Carbon::now()->startOfYear();
                $finish = Carbon::now()->endOfYear();
                $query->whereBetween('created_at', [$start, $finish]);
                break;
            case 'range':
                $start = Carbon::parse($from)->format('Y-m-d');
                $finish = Carbon::parse($to)->format('Y-m-d');
                $query->whereBetween('created_at', [$start, $finish]);
                break;
        }


        if ($chosenProblem !== 'all') {
            $query->where('problem_id', '=', $chosenProblem);
        }

        if ($chosenOrganization !== 'all') {
            $query->whereHas('responsibleOrganization', function($q) use ($chosenOrganization) {
                $q->where('organization_id', $chosenOrganization);
            });
        }

        if ($chosenDistrict !== 'all') {
            $query->whereHas('address', function($q) use ($chosenDistrict) {
                $q->where('district', $chosenDistrict);
            });
        }

        if ($statusFilter !== 'all') {
            $query->where('status', '=', $statusFilter);
        }

        return Excel::download(new ClaimStatistics($query), 'claims_statistics.xls');

    }
}