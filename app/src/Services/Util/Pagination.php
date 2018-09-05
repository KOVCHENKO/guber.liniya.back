<?php

namespace App\src\Services\Util;


class Pagination
{
    public $itemsPerPage = 10;

    public function getSkippedItems($page)
    {
        if (!isset($page)) {
            $page = 1;
        }

        return ($page != 1) ? ($page - 1) * $this->itemsPerPage : 0;
    }
}