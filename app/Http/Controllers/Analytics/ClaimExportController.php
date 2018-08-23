<?php

namespace App\Http\Controllers\Analytics;


use App\Http\Controllers\Controller;
use App\src\Exports\Claim\ClaimExportBetweenDates;
use App\src\Exports\Claim\ClaimExportByExecutor;
use App\src\Exports\Claim\ClaimExportByProblemTypes;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClaimExportController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * Экспорт между определенными датами
     */
    public function exportBetweenDates()
    {
        return Excel::download(new ClaimExportBetweenDates(), 'claims_between_dates.xls');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * Экспорт по массиву проблем
     */
    public function exportByProblems(Request $request)
    {
        return Excel::download(new ClaimExportByProblemTypes($request->all()), 'claims_by_problems.xls');
    }

    /**
     * @param $executorId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * Экспорт заявок по исполнителю
     */
    public function exportByExecutor($executorId)
    {
        return Excel::download(new ClaimExportByExecutor(), 'claims_by_executor.xls');
    }

}