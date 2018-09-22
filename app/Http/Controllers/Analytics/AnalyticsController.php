<?php

namespace App\Http\Controllers\Analytics;


use App\src\Services\Analytics\CallsReportService;
use App\src\Services\Analytics\ClaimsRegisterService;

class AnalyticsController
{

    protected $callsReportService;
    protected $claimsRegisterService;

    /**
     * AnalyticsController constructor.
     * @param CallsReportService $callsReportService
     * @param ClaimsRegisterService $claimsRegisterService
     */
    public function __construct(
        CallsReportService $callsReportService,
        ClaimsRegisterService $claimsRegisterService
    )
    {
        $this->callsReportService = $callsReportService;
        $this->claimsRegisterService = $claimsRegisterService;
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

}