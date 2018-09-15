<?php

namespace App\src\Services\Util;


use App\src\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    protected $fileRepository;

    /**
     * UploadService constructor.
     * @param $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }


    public function uploadSingleFile($claimId, $file)
    {

        $path = $file->store('public/files');
        $path = 'public' . Storage::url($path);
        $result[] = [
            'path' => $path,
        ];

        $this->fileRepository->saveFileInfoInDB($claimId, $path);
    }
}