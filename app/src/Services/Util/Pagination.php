<?php

namespace App\src\Services\Util;


class Pagination
{
    public $itemsPerPage = 10;

    /**
     * @param $page
     * @return float|int
     * Получить offset
     */
    public function getSkippedItems($page)
    {
        if (!isset($page)) {
            $page = 1;
        }

        return ($page != 1) ? ($page - 1) * $this->itemsPerPage : 0;
    }


    /**
     * @param $pagesCount
     * @return float
     * Получить кол-во страниц
     */
    public function getPagesQuantity($pagesCount)
    {
        return ceil($pagesCount / $this->itemsPerPage);
    }

}
