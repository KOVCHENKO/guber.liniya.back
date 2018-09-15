<?php

namespace App\src\Repositories;


use App\src\Models\File;
use Illuminate\Support\Facades\DB;

class FileRepository
{
    protected $file;

    /**
     * FileRepository constructor.
     * @param $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }


    public function saveFileInfoInDB($claimId, $path): File
    {
        $newFile = new $this->file;
        $newFile->claim_id = $claimId;
        $newFile->path = $path;

        $newFile->save();

        return $newFile;
    }

}