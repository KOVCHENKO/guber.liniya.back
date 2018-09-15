<?php

namespace App\Http\Controllers\Util;


use App\Http\Controllers\Controller;
use App\src\Services\Util\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{

    protected $uploadService;

    /**
     * UploadController constructor.
     * @param $uploadService
     */
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }


    /**
     * @param $claimId
     * @param Request $request
     * Загрузить одиночный файл
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function uploadSingleFile($claimId, Request $request)
    {
        return response($this->uploadService->uploadSingleFile($claimId, $request['file']), 200);
    }

    public function downloadFile()
    {
        $path = "liniya.localhost/public/storage/files/1AImLfCOWtrUcGHRRpy8QXcIjhxPnddPVmm8VFNl.txt";

        Storage::download($path);
//        return response()->download($path);
    }
}