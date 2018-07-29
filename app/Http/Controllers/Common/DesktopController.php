<?php

namespace App\Http\Controllers\Common;


use App\Http\Controllers\Controller;
use App\src\Services\Common\CabinetsCollector;

class DesktopController extends Controller
{
    protected $cabinetsCollector;

    /**
     * DesktopController constructor.
     * @param CabinetsCollector $cabinetsCollector
     */
    public function __construct(CabinetsCollector $cabinetsCollector)
    {
        $this->cabinetsCollector = $cabinetsCollector;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getCabinets($userId)
    {
        return $this->cabinetsCollector->getCabinets($userId);
    }
}