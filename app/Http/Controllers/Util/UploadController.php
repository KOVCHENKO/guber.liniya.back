<?php

namespace App\Http\Controllers\Util;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * @param Request $request
     * Загрузить одиночный файл
     */
    public function uploadSingleFile(Request $request)
    {
        $file = $request['file'];

        $path = $file->store('public/files');
        $path = 'public' . Storage::url($path);
        $result[] = [
            'path' => $path,
        ];

        // сохранить в БД
    }
}