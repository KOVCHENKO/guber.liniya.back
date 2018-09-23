<?php

namespace App\Http\Controllers\Analytics;


use App\src\Models\Claim;
use App\src\Services\Analytics\CallsReportService;
use App\src\Services\Analytics\ClaimsRegisterService;
use App\src\Services\Analytics\ClaimsStatisticsService;
use Carbon\Carbon;

class AnalyticsController
{

    protected $callsReportService;
    protected $claimsRegisterService;
    protected $claimsStatisticsService;

    /**
     * AnalyticsController constructor.
     * @param CallsReportService $callsReportService
     * @param ClaimsRegisterService $claimsRegisterService
     * @param ClaimsStatisticsService $claimsStatisticsService
     */
    public function __construct(
        CallsReportService $callsReportService,
        ClaimsRegisterService $claimsRegisterService,
        ClaimsStatisticsService $claimsStatisticsService
    )
    {
        $this->callsReportService = $callsReportService;
        $this->claimsRegisterService = $claimsRegisterService;
        $this->claimsStatisticsService = $claimsStatisticsService;
    }


    /**
     * @param $reportOption
     * @param $from
     * @param $to
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function callsReport($reportOption, $from, $to)
    {
        return $this->callsReportService->makeReport($reportOption, $from, $to);
    }

    /**
     * @param $reportOption
     * @param $from
     * @param $to
     * @return mixed
     */
    public function claimsRegisterReport($reportOption, $from, $to)
    {
        return $this->claimsRegisterService->makeReport($reportOption, $from, $to);
    }

    /**
     * @param $reportPeriodOption
     * @param $from
     * @param $to
     * @param $chosenDistrict
     * @param $chosenOrganization
     * @param $chosenProblem
     * @param $statusFilter
     * @return Claim[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function claimsStatisticsReport($reportPeriodOption, $from, $to, $chosenDistrict,
                                           $chosenOrganization, $chosenProblem, $statusFilter )
    {
        return $this->claimsStatisticsService->claimsStatisticsReport(
            $reportPeriodOption, $from, $to, $chosenDistrict,
            $chosenOrganization, $chosenProblem, $statusFilter
        );
    }

}